<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presenca;
use App\Models\User;
use App\Services\FaltaCalculoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaltaController extends Controller
{
    protected $faltaService;

    public function __construct(FaltaCalculoService $faltaService)
    {
        $this->faltaService = $faltaService;
    }

    public function getFaltasByAluno($id, Request $request)
    {
        $faltas = Presenca::where('aluno_id', $id)
            ->when($request->mes, fn($q) => $q->whereMonth('data', $request->mes))
            ->when($request->ano, fn($q) => $q->whereYear('data', $request->ano))
            ->orderBy('data', 'desc')
            ->get();

        return response()->json($faltas);
    }

    public function getResumoFaltas($id)
    {
        $resumo = $this->faltaService->getResumoFaltas($id);
        return response()->json($resumo);
    }

    public function getRelatorioGeral(Request $request)
    {
        $query = User::where('role', 'aluno')
            ->with('turma');

        if ($request->turma_id) {
            $query->whereHas('turma', fn($q) => $q->where('id', $request->turma_id));
        }

        $alunos = $query->get();
        
        $totalFaltas = 0;
        foreach ($alunos as $aluno) {
            $faltas = Presenca::where('aluno_id', $aluno->id)
                ->where('status', 'falta')
                ->when($request->mes, fn($q) => $q->whereMonth('data', $request->mes))
                ->when($request->ano, fn($q) => $q->whereYear('data', $request->ano))
                ->count();
            
            $aluno->total_faltas = $faltas;
            $aluno->faltas_mes = $faltas;
            $totalFaltas += $faltas;
        }

        return response()->json([
            'alunos' => $alunos,
            'total_alunos' => $alunos->count(),
            'total_faltas' => $totalFaltas,
            'media_faltas' => $alunos->count() > 0 ? round($totalFaltas / $alunos->count(), 1) : 0
        ]);
    }

    public function marcarFalta(Request $request)
    {
        $request->validate([
            'aluno_id' => 'required|exists:users,id',
            'aula_numero' => 'required|integer|min:1|max:5',
            'data' => 'nullable|date'
        ]);

        $falta = $this->faltaService->marcarFalta(
            $request->aluno_id,
            $request->aula_numero,
            null,
            $request->data
        );

        return response()->json(['message' => 'Falta registrada com sucesso', 'falta' => $falta]);
    }

    public function justificarFalta($id, Request $request)
    {
        $request->validate([
            'justificativa' => 'required|string|min:3'
        ]);

        $presenca = Presenca::findOrFail($id);
        $presenca->update([
            'status' => 'justificado',
            'justificativa' => $request->justificativa
        ]);

        return response()->json(['message' => 'Falta justificada com sucesso']);
    }
}