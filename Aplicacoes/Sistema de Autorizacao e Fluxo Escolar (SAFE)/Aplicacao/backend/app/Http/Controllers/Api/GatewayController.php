<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Models\GatewayEntry;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GatewayController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Listar autorizações aprovadas aguardando saída
     */
    public function getPendingExits()
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['tecnico', 'admin'])) {
            return response()->json(['message' => 'Acesso apenas para portaria'], 403);
        }

        $authorizations = Authorization::with(['admin', 'professor'])
            ->where('status', 'approved_by_professor')
            ->whereNull('portaria_id')
            ->orderBy('horario_saida', 'asc')
            ->get();

        return response()->json($authorizations);
    }

    /**
     * Get gateway statistics
     */
    public function getStats()
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['tecnico', 'admin'])) {
            return response()->json(['message' => 'Acesso apenas para portaria'], 403);
        }
        
        $stats = [
            'pendentes' => Authorization::where('status', 'approved_by_professor')
                ->whereNull('portaria_id')
                ->count(),
            'hoje' => GatewayEntry::whereDate('created_at', today())->count(),
            'total_mes' => GatewayEntry::whereMonth('created_at', now()->month)->count(),
            'com_falta' => Authorization::where('com_falta', true)
                ->where('status', 'completed')
                ->count()
        ];
        
        return response()->json($stats);
    }

    /**
     * Listar histórico de saídas realizadas
     */
    public function getHistory(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['tecnico', 'admin'])) {
            return response()->json(['message' => 'Acesso apenas para portaria'], 403);
        }

        $query = GatewayEntry::with(['authorization.admin', 'authorization.professor', 'portaria'])
            ->orderBy('created_at', 'desc');

        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('authorization', function($q) use ($search) {
                $q->where('aluno_nome', 'like', "%{$search}%")
                  ->orWhere('turma', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 20);
        $entries = $query->paginate($perPage);

        return response()->json($entries);
    }

    /**
     * Registrar saída do aluno (portaria) - CORRIGIDO
     */
    public function registerExit(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            if (!in_array($user->role, ['tecnico', 'admin'])) {
                return response()->json(['message' => 'Acesso apenas para portaria'], 403);
            }

            $authorization = Authorization::with(['professor'])
                ->where('id', $id)
                ->where('status', 'approved_by_professor')
                ->firstOrFail();

            $validated = $request->validate([
                'observacoes' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();
            
            try {
                // Registrar saída na portaria
                $gatewayEntry = GatewayEntry::create([
                    'authorization_id' => $authorization->id,
                    'portaria_id' => $user->id,
                    'horario_saida' => now(),
                    'tipo' => 'saida',
                    'observacoes' => $validated['observacoes'] ?? null
                ]);

                // Atualizar status da autorização
                $authorization->update([
                    'status' => 'completed',
                    'portaria_id' => $user->id
                ]);
                
                DB::commit();
                
                // Buscar contato do responsável na metadata
                $responsavel = DB::table('authorization_metadata')
                    ->where('authorization_id', $authorization->id)
                    ->where('key', 'responsavel_contato')
                    ->first();

                // Notificar responsável sobre a saída
                if ($responsavel && !empty($responsavel->value)) {
                    $this->notificationService->notifyResponsible($authorization, $responsavel->value);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Saída registrada com sucesso! Responsável foi notificado.',
                    'authorization' => $authorization,
                    'gateway_entry' => $gatewayEntry
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erro ao registrar saída: ' . $e->getMessage());
                return response()->json([
                    'message' => 'Erro ao registrar saída: ' . $e->getMessage()
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Erro geral ao registrar saída: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erro ao registrar saída: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar autorização por nome do aluno ou código
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['tecnico', 'admin'])) {
            return response()->json(['message' => 'Acesso apenas para portaria'], 403);
        }

        $validated = $request->validate([
            'search' => 'required|string|min:2'
        ]);

        $authorizations = Authorization::with(['admin', 'professor'])
            ->where('status', 'approved_by_professor')
            ->whereNull('portaria_id')
            ->where(function ($query) use ($validated) {
                $query->where('aluno_nome', 'like', "%{$validated['search']}%")
                    ->orWhere('turma', 'like', "%{$validated['search']}%");
            })
            ->orderBy('horario_saida', 'asc')
            ->get();

        return response()->json($authorizations);
    }
}