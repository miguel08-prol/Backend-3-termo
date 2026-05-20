<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Models\ProfessorValidation;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfessorAuthorizationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Listar autorizações pendentes para o professor logado
     */
    public function getPending()
    {
        $user = Auth::user();
        
        if ($user->role !== 'professor') {
            return response()->json(['message' => 'Acesso apenas para professores'], 403);
        }

        $authorizations = Authorization::with(['admin', 'validation'])
            ->where('professor_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($authorizations);
    }

    /**
     * Listar histórico de autorizações do professor (validadas)
     */
    public function getHistory()
    {
        $user = Auth::user();
        
        if ($user->role !== 'professor') {
            return response()->json(['message' => 'Acesso apenas para professores'], 403);
        }

        $authorizations = Authorization::with(['admin', 'validation', 'gatewayEntry'])
            ->where('professor_id', $user->id)
            ->whereIn('status', ['approved_by_professor', 'rejected', 'completed'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($authorizations);
    }

    /**
     * Buscar uma autorização específica para o professor
     */
    public function show($id)
    {
        $user = Auth::user();
        
        if ($user->role !== 'professor') {
            return response()->json(['message' => 'Acesso apenas para professores'], 403);
        }

        $authorization = Authorization::with(['admin', 'validation', 'gatewayEntry'])
            ->where('professor_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($authorization);
    }

    /**
     * Professor valida (aprova) a autorização
     */
    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->role !== 'professor') {
            return response()->json(['message' => 'Acesso apenas para professores'], 403);
        }

        $validated = $request->validate([
            'com_falta' => 'required|boolean',
            'observacao' => 'nullable|string|max:500'
        ]);

        $authorization = Authorization::where('professor_id', $user->id)
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        DB::transaction(function () use ($authorization, $user, $validated) {
            // Criar registro de validação do professor
            ProfessorValidation::create([
                'authorization_id' => $authorization->id,
                'professor_id' => $user->id,
                'status' => 'approved',
                'com_falta' => $validated['com_falta'],
                'observacao' => $validated['observacao'] ?? null,
                'validated_at' => now()
            ]);

            // Atualizar autorização
            $authorization->update([
                'status' => 'approved_by_professor',
                'com_falta' => $validated['com_falta'],
                'autorizado_em' => now(),
                'professor_id' => $user->id
            ]);
        });

        // Buscar portaria (usuário com role 'portaria' ou 'tecnico' para notificar)
        $portaria = \App\Models\User::where('role', 'tecnico')->first(); // Ou crie role 'portaria'
        
        if ($portaria) {
            $this->notificationService->notifyGateway($authorization, $portaria);
        }

        return response()->json([
            'success' => true,
            'message' => 'Autorização aprovada com sucesso! Portaria foi notificada.',
            'authorization' => $authorization->load(['validation'])
        ]);
    }

    /**
     * Professor rejeita a autorização
     */
    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->role !== 'professor') {
            return response()->json(['message' => 'Acesso apenas para professores'], 403);
        }

        $validated = $request->validate([
            'observacao' => 'required|string|max:500'
        ]);

        $authorization = Authorization::where('professor_id', $user->id)
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        DB::transaction(function () use ($authorization, $user, $validated) {
            // Criar registro de validação do professor
            ProfessorValidation::create([
                'authorization_id' => $authorization->id,
                'professor_id' => $user->id,
                'status' => 'rejected',
                'com_falta' => false,
                'observacao' => $validated['observacao'],
                'validated_at' => now()
            ]);

            // Atualizar autorização
            $authorization->update([
                'status' => 'rejected',
                'observacoes' => $validated['observacao']
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Autorização rejeitada.',
            'authorization' => $authorization->load(['validation'])
        ]);
    }

    /**
     * Estatísticas do professor (dashboard)
     */
    public function getStats()
    {
        $user = Auth::user();
        
        if ($user->role !== 'professor') {
            return response()->json(['message' => 'Acesso apenas para professores'], 403);
        }

        $stats = [
            'pendentes' => Authorization::where('professor_id', $user->id)->where('status', 'pending')->count(),
            'aprovadas_hoje' => Authorization::where('professor_id', $user->id)
                ->where('status', 'approved_by_professor')
                ->whereDate('autorizado_em', today())
                ->count(),
            'rejeitadas_hoje' => Authorization::where('professor_id', $user->id)
                ->where('status', 'rejected')
                ->whereDate('updated_at', today())
                ->count(),
            'total_mes' => Authorization::where('professor_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->count()
        ];

        return response()->json($stats);
    }

    public function getDailyStats()
{
    $user = Auth::user();
    
    $daily = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i);
        $daily[] = [
            'day' => $date->format('d/m'),
            'total' => Authorization::where('professor_id', $user->id)
                ->whereDate('created_at', $date)
                ->count()
        ];
    }
    
    return response()->json($daily);
}
}