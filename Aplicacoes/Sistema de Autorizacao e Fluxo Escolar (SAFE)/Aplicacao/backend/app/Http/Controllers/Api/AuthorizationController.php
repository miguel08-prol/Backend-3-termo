<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Models\User;
use App\Services\NotificationService;
use App\Services\FaltaCalculoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthorizationController extends Controller
{
    protected $notificationService;
    protected $faltaCalculoService;

    public function __construct(NotificationService $notificationService, FaltaCalculoService $faltaCalculoService)
    {
        $this->notificationService = $notificationService;
        $this->faltaCalculoService = $faltaCalculoService;
    }

    /**
     * Listar todas autorizações com filtros
     */
    public function index(Request $request)
    {
        $query = Authorization::with(['admin', 'professor']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->aluno) {
            $query->where('aluno_nome', 'like', "%{$request->aluno}%");
        }
        
        if ($request->turma) {
            $query->where('turma', 'like', "%{$request->turma}%");
        }
        
        if ($request->com_falta !== null) {
            $query->where('com_falta', $request->com_falta);
        }
        
        if ($request->data_inicio) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        
        if ($request->data_fim) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }
        
        $perPage = $request->per_page ?? 20;
        $authorizations = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return response()->json($authorizations);
    }

    /**
     * Dashboard Statistics for Admin
     */
    public function getDashboardStats(Request $request)
    {
        try {
            $total = Authorization::count();
            $pending = 0;
            $approved = Authorization::count();
            $completed = Authorization::where('status', 'completed')->count();
            $rejected = 0;
            $cancelled = Authorization::where('status', 'cancelled')->count();
            $comFalta = Authorization::where('com_falta', true)->count();
            
            $stats = [
                'total' => $total,
                'pending' => 0,
                'approved' => $approved,
                'completed' => $completed,
                'rejected' => 0,
                'cancelled' => $cancelled,
                'com_falta' => $comFalta,
                'taxa_aprovacao' => 100,
                'tempo_medio_resposta' => 0,
            ];
            
            $daily = Authorization::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->map(function ($item) {
                    return [
                        'day' => date('d/m', strtotime($item->date)),
                        'total' => $item->total,
                        'approved' => $item->total
                    ];
                });
            
            $hourly = [];
            for ($i = 7; $i <= 22; $i++) {
                $startTime = sprintf('%02d:00:00', $i);
                $endTime = sprintf('%02d:00:00', $i + 1);
                
                $count = Authorization::whereTime('horario_saida', '>=', $startTime)
                    ->whereTime('horario_saida', '<', $endTime)
                    ->count();
                
                $hourly[] = [
                    'label' => sprintf('%02d:00', $i),
                    'count' => $count
                ];
            }
            
            $topProfessors = Authorization::whereNotNull('professor_id')
                ->select('professor_id', DB::raw('COUNT(*) as total'))
                ->with('professor')
                ->groupBy('professor_id')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->professor ? $item->professor->name : 'Desconhecido',
                        'total' => $item->total
                    ];
                });
            
            return response()->json([
                'stats' => $stats,
                'daily' => $daily,
                'hourly' => $hourly,
                'top_professors' => $topProfessors
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro no dashboard stats: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao carregar estatísticas'], 500);
        }
    }

    /**
     * Criar nova autorização (já aprovada automaticamente)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'aluno_nome' => 'required|string|max:255',
                'turma' => 'required|string|max:100',
                'motivo_saida' => 'nullable|string',
                'horario_saida' => 'required|date_format:H:i',
                'aula_numero' => 'nullable|integer|min:1|max:5',
                'professor_id' => 'required|exists:users,id',
                'responsavel_contato' => 'nullable|email',
                'observacoes' => 'nullable|string',
                'tipo' => 'required|in:saida,entrada',
                'turno' => 'nullable|in:manha,tarde,noite',
                'com_falta' => 'nullable|boolean'
            ]);
            
            Log::info('Dados validados:', $validated);
            
            $validated['admin_id'] = Auth::id();
            $validated['status'] = 'approved_by_professor';
            $validated['autorizado_em'] = now();
            
            // Se o frontend enviou com_falta, usa ele, senão padrão true
            $validated['com_falta'] = $request->boolean('com_falta', true);
            
            $authorization = Authorization::create($validated);
            Log::info('Autorização criada com ID: ' . $authorization->id);
            
            // Buscar o aluno pelo nome (pode não existir na tabela users)
            $aluno = User::where('name', $authorization->aluno_nome)->first();
            
            // Determinar o turno
            $turno = $validated['turno'] ?? $this->faltaCalculoService->determinarTurno($authorization->horario_saida);
            
            // Calcular faltas SOMENTE se com_falta for true
            $aulasComFalta = [];
            
            if ($validated['com_falta'] && $aluno) {
                if ($authorization->tipo === 'saida') {
                    if (!empty($validated['aula_numero'])) {
                        $aulasComFalta = $this->faltaCalculoService->calcularFaltasPorAula(
                            $aluno,
                            $validated['aula_numero'],
                            $turno,
                            now(),
                            $authorization->id
                        );
                    } else {
                        $aulasComFalta = $this->faltaCalculoService->calcularFaltasSaidaAntecipada(
                            $aluno,
                            $authorization->horario_saida,
                            $turno,
                            now(),
                            $authorization->id
                        );
                    }
                } else {
                    if (!empty($validated['aula_numero'])) {
                        $aulasComFalta = $this->faltaCalculoService->calcularFaltasPorAulaChegada(
                            $aluno,
                            $validated['aula_numero'],
                            $turno,
                            now(),
                            $authorization->id
                        );
                    } else {
                        $aulasComFalta = $this->faltaCalculoService->calcularFaltasAtraso(
                            $aluno,
                            $authorization->horario_saida,
                            $turno,
                            now(),
                            $authorization->id
                        );
                    }
                }
                
                // Atualizar autorização com as faltas calculadas
                if (count($aulasComFalta) > 0) {
                    $authorization->update([
                        'observacoes' => ($authorization->observacoes ?? '') . " | Faltas nas aulas: " . implode(', ', $aulasComFalta)
                    ]);
                }
            } elseif ($validated['com_falta'] && !$aluno) {
                Log::warning("Aluno não encontrado para marcar falta: {$authorization->aluno_nome}");
            }
            
            // Salvar responsavel_contato em metadata se existir
            if (!empty($validated['responsavel_contato'])) {
                DB::table('authorization_metadata')->insert([
                    'authorization_id' => $authorization->id,
                    'key' => 'responsavel_contato',
                    'value' => $validated['responsavel_contato'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $this->notificationService->notifyResponsible($authorization, $validated['responsavel_contato']);
            }
            
            // Notificar professor
            $professor = User::find($validated['professor_id']);
            if ($professor) {
                $this->notificationService->notifyProfessorInformative($authorization, $professor);
            }
            
            return response()->json([
                'message' => 'Autorização criada com sucesso!',
                'authorization' => $authorization->load(['admin', 'professor']),
                'faltas_registradas' => $aulasComFalta
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar autorização: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erro ao criar autorização: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar uma autorização específica
     */
    public function show($id)
    {
        $authorization = Authorization::with(['admin', 'professor'])->findOrFail($id);
        return response()->json($authorization);
    }

    /**
     * Atualizar autorização
     */
public function update(Request $request, $id)
{
    try {
        $authorization = Authorization::findOrFail($id);
        
        $validated = $request->validate([
            'aluno_nome' => 'sometimes|string|max:255',
            'turma' => 'sometimes|string|max:100',
            'turno' => 'nullable|in:manha,tarde,noite',
            'motivo_saida' => 'nullable|string',
            'horario_saida' => 'sometimes|date_format:H:i',
            'aula_numero' => 'nullable|integer|min:1|max:5',
            'observacoes' => 'nullable|string',
            'com_falta' => 'nullable|boolean',
            'status' => 'nullable|in:approved_by_professor,completed,cancelled',
            'tipo' => 'nullable|in:saida,entrada' // Adicionar tipo
        ]);
        
        Log::info('Atualizando autorização ID: ' . $id . ' com dados: ', $validated);
        
        // Atualizar os campos
        $authorization->aluno_nome = $validated['aluno_nome'] ?? $authorization->aluno_nome;
        $authorization->turma = $validated['turma'] ?? $authorization->turma;
        $authorization->turno = $validated['turno'] ?? $authorization->turno;
        $authorization->motivo_saida = $validated['motivo_saida'] ?? $authorization->motivo_saida;
        $authorization->horario_saida = $validated['horario_saida'] ?? $authorization->horario_saida;
        $authorization->aula_numero = $validated['aula_numero'] ?? $authorization->aula_numero;
        $authorization->observacoes = $validated['observacoes'] ?? $authorization->observacoes;
        $authorization->tipo = $validated['tipo'] ?? $authorization->tipo;
        
        // Para com_falta e status, precisamos tratar o valor corretamente
        if ($request->has('com_falta')) {
            $authorization->com_falta = filter_var($validated['com_falta'], FILTER_VALIDATE_BOOLEAN);
        }
        
        if ($request->has('status')) {
            $authorization->status = $validated['status'];
        }
        
        $authorization->save();
        
        Log::info('Autorização atualizada com sucesso: ', $authorization->toArray());
        
        return response()->json([
            'message' => 'Autorização atualizada com sucesso',
            'authorization' => $authorization->load(['admin', 'professor'])
        ]);
        
    } catch (\Exception $e) {
        Log::error('Erro ao atualizar autorização: ' . $e->getMessage());
        return response()->json([
            'message' => 'Erro ao atualizar autorização: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Excluir autorização (cancelar)
     */
    public function destroy($id)
    {
        try {
            $authorization = Authorization::findOrFail($id);
            $authorization->update(['status' => 'cancelled']);
            $authorization->delete();
            
            return response()->json([
                'message' => 'Autorização cancelada com sucesso'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao excluir autorização: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erro ao excluir autorização: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar professores disponíveis
     */
    public function getProfessores()
    {
        $professores = User::where('role', 'professor')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
        
        return response()->json($professores);
    }
}