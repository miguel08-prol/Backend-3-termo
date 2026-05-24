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
     * Estatísticas do professor (dashboard)
     */
    public function getStats()
    {
        $user = Auth::user();
        
        if ($user->role !== 'professor') {
            return response()->json(['message' => 'Acesso apenas para professores'], 403);
        }

        $stats = [
            'total' => Authorization::where('professor_id', $user->id)->count(),
            'hoje' => Authorization::where('professor_id', $user->id)
                ->whereDate('created_at', today())
                ->count(),
            'mes' => Authorization::where('professor_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'com_falta' => Authorization::where('professor_id', $user->id)
                ->where('com_falta', true)
                ->count()
        ];

        return response()->json($stats);
    }

    /**
     * Dados para o gráfico dos últimos 7 dias
     */
    public function getDailyStats()
    {
        $user = Auth::user();
        
        $daily = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $total = Authorization::where('professor_id', $user->id)
                ->whereDate('created_at', $date)
                ->count();
            
            $daily[] = [
                'day' => $date->format('d/m'),
                'total' => $total
            ];
        }
        
        return response()->json($daily);
    }

    /**
     * Listar histórico de autorizações do professor com filtros
     */
    public function getHistory(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'professor') {
            return response()->json(['message' => 'Acesso apenas para professores'], 403);
        }

        $query = Authorization::with(['admin', 'professor', 'gatewayEntry'])
            ->where('professor_id', $user->id);
        
        // Filtro por status
        if ($request->status && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filtro por nome do aluno
        if ($request->aluno && $request->aluno !== '') {
            $query->where('aluno_nome', 'like', "%{$request->aluno}%");
        }
        
        // Filtro por turma
        if ($request->turma && $request->turma !== '') {
            $query->where('turma', 'like', "%{$request->turma}%");
        }
        
        // Filtro por período (data_inicio)
        if ($request->data_inicio && $request->data_inicio !== '') {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        
        // Filtro por período (data_fim)
        if ($request->data_fim && $request->data_fim !== '') {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }
        
        $perPage = $request->per_page ?? 20;
        $authorizations = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return response()->json($authorizations);
    }

    /**
     * Listar autorizações pendentes para o professor
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
     * Mostrar uma autorização específica
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
            ProfessorValidation::create([
                'authorization_id' => $authorization->id,
                'professor_id' => $user->id,
                'status' => 'approved',
                'com_falta' => $validated['com_falta'],
                'observacao' => $validated['observacao'] ?? null,
                'validated_at' => now()
            ]);

            $authorization->update([
                'status' => 'approved_by_professor',
                'com_falta' => $validated['com_falta'],
                'autorizado_em' => now()
            ]);
        });

        $portaria = \App\Models\User::where('role', 'tecnico')->first();
        
        if ($portaria) {
            $this->notificationService->notifyGateway($authorization, $portaria);
        }

        return response()->json([
            'success' => true,
            'message' => 'Autorização aprovada com sucesso!',
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
            ProfessorValidation::create([
                'authorization_id' => $authorization->id,
                'professor_id' => $user->id,
                'status' => 'rejected',
                'com_falta' => false,
                'observacao' => $validated['observacao'],
                'validated_at' => now()
            ]);

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
}