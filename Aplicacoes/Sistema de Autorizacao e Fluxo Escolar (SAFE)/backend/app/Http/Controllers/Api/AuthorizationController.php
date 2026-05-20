<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AuthorizationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get dashboard statistics for admin
     */
     public function getDashboardStats()
    {
        try {
            $user = Auth::user();
            
            // Log para debug
            \Log::info('Dashboard stats requested by user:', ['user_id' => $user->id, 'role' => $user->role]);
            
            if ($user->role !== 'admin') {
                return response()->json(['message' => 'Acesso negado - Apenas administradores'], 403);
            }
            
            $stats = [
                'total' => Authorization::count(),
                'pending' => Authorization::where('status', 'pending')->count(),
                'approved' => Authorization::where('status', 'approved_by_professor')->count(),
                'completed' => Authorization::where('status', 'completed')->count(),
                'rejected' => Authorization::where('status', 'rejected')->count(),
                'cancelled' => Authorization::where('status', 'cancelled')->count(),
                'com_falta' => Authorization::where('com_falta', true)->count(),
                'taxa_aprovacao' => $this->calculateApprovalRate(),
                'tempo_medio_resposta' => $this->calculateAverageResponseTime()
            ];
            
            $daily = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $daily[] = [
                    'day' => $date->format('d/m'),
                    'date' => $date->format('Y-m-d'),
                    'total' => Authorization::whereDate('created_at', $date)->count(),
                    'approved' => Authorization::whereDate('created_at', $date)
                        ->where('status', 'approved_by_professor')
                        ->count(),
                    'completed' => Authorization::whereDate('created_at', $date)
                        ->where('status', 'completed')
                        ->count()
                ];
            }
            
            $hourly = [];
            for ($i = 7; $i <= 22; $i++) {
                $hourly[] = [
                    'hour' => $i,
                    'label' => sprintf('%02d:00', $i),
                    'count' => Authorization::whereRaw('HOUR(horario_saida) = ?', [$i])->count()
                ];
            }
            
            $topProfessors = DB::table('authorizations')
                ->join('users', 'authorizations.professor_id', '=', 'users.id')
                ->where('authorizations.status', 'approved_by_professor')
                ->select('users.name', DB::raw('count(*) as total'))
                ->groupBy('users.id', 'users.name')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();
            
            return response()->json([
                'stats' => $stats,
                'daily' => $daily,
                'hourly' => $hourly,
                'top_professors' => $topProfessors,
                'updated_at' => now()->toIso8601String()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro no getDashboardStats: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erro ao carregar estatísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ... resto dos métodos (index, store, show, update, destroy, getProfessores)
    
    public function index(Request $request)
    {
        $query = Authorization::with(['admin', 'professor', 'validation', 'gatewayEntry']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('turma')) {
            $query->where('turma', 'like', "%{$request->turma}%");
        }
        
        if ($request->has('aluno')) {
            $query->where('aluno_nome', 'like', "%{$request->aluno}%");
        }
        
        $perPage = $request->get('per_page', 20);
        $authorizations = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return response()->json($authorizations);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'aluno_nome' => 'required|string|max:255',
                'turma' => 'required|string|max:50',
                'motivo_saida' => 'nullable|string|max:500',
                'horario_saida' => 'required|date_format:H:i',
                'aula_numero' => 'required|integer|between:1,5',
                'professor_id' => 'required|exists:users,id',
                'responsavel_contato' => 'required|email',
                'observacoes' => 'nullable|string'
            ]);

            $user = Auth::user();
            
            if ($user->role !== 'admin') {
                return response()->json(['message' => 'Apenas administradores podem criar autorizações'], 403);
            }

            $professor = User::find($validated['professor_id']);
            if (!$professor || $professor->role !== 'professor') {
                return response()->json(['message' => 'Professor inválido'], 422);
            }

            $authorization = DB::transaction(function () use ($validated, $user) {
                $auth = Authorization::create([
                    'aluno_nome' => $validated['aluno_nome'],
                    'turma' => $validated['turma'],
                    'motivo_saida' => $validated['motivo_saida'] ?? null,
                    'horario_saida' => $validated['horario_saida'],
                    'aula_numero' => $validated['aula_numero'],
                    'status' => 'pending',
                    'admin_id' => $user->id,
                    'professor_id' => $validated['professor_id'],
                    'observacoes' => $validated['observacoes'] ?? null
                ]);
                
                DB::table('authorization_metadata')->insert([
                    'authorization_id' => $auth->id,
                    'key' => 'responsavel_contato',
                    'value' => $validated['responsavel_contato'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                return $auth;
            });

            $professor = User::find($validated['professor_id']);
            $this->notificationService->notifyProfessor($authorization, $professor);

            return response()->json([
                'message' => 'Autorização criada com sucesso! Professor notificado.',
                'authorization' => $authorization->load(['admin', 'professor'])
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Erro ao criar autorização: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erro interno ao criar autorização: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $authorization = Authorization::with(['admin', 'professor', 'validation', 'gatewayEntry', 'logs'])->findOrFail($id);
        
        $responsavel = DB::table('authorization_metadata')
            ->where('authorization_id', $id)
            ->where('key', 'responsavel_contato')
            ->first();
        
        return response()->json([
            'authorization' => $authorization,
            'responsavel_contato' => $responsavel->value ?? null
        ]);
    }

    public function update(Request $request, $id)
    {
        $authorization = Authorization::findOrFail($id);
        
        $validated = $request->validate([
            'aluno_nome' => 'sometimes|string|max:255',
            'turma' => 'sometimes|string|max:50',
            'motivo_saida' => 'nullable|string|max:500',
            'horario_saida' => 'sometimes|date_format:H:i',
            'aula_numero' => 'sometimes|integer|between:1,5',
            'observacoes' => 'nullable|string'
        ]);
        
        $authorization->update($validated);
        
        return response()->json([
            'message' => 'Autorização atualizada com sucesso',
            'authorization' => $authorization
        ]);
    }

    public function destroy($id)
    {
        $authorization = Authorization::findOrFail($id);
        
        if (in_array($authorization->status, ['completed', 'approved_by_professor'])) {
            return response()->json(['message' => 'Não é possível cancelar autorização já validada ou concluída'], 422);
        }
        
        $authorization->update(['status' => 'cancelled']);
        
        return response()->json(['message' => 'Autorização cancelada com sucesso']);
    }

    public function getProfessores()
    {
        $professores = User::where('role', 'professor')
            ->select('id', 'name', 'email', 'telefone')
            ->get();
        
        return response()->json($professores);
    }
}