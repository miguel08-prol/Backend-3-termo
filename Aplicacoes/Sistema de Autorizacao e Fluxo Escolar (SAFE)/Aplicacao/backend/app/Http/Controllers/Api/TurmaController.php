<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TurmaController extends Controller
{
    /**
     * Listar todas as turmas
     */
    public function index(Request $request)
    {
        $query = Turma::with('professor');
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nome', 'like', "%{$request->search}%")
                  ->orWhere('codigo', 'like', "%{$request->search}%");
            });
        }
        
        $perPage = $request->per_page ?? 20;
        $turmas = $query->orderBy('nome')->paginate($perPage);
        
        return response()->json($turmas);
    }

    /**
     * Listar todas as turmas ativas (para selects)
     */
    public function list()
    {
        $turmas = Turma::with('professor')
            ->where('status', 'ativa')
            ->orderBy('nome')
            ->get();
        
        return response()->json($turmas);
    }

    /**
     * Criar nova turma
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'codigo' => 'required|string|unique:turmas,codigo',
            'ano' => 'nullable|string|max:10',
            'periodo' => 'nullable|string|max:20',
            'capacidade' => 'nullable|integer|min:1|max:100',
            'professor_id' => 'nullable|exists:users,id',
            'observacoes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $turma = Turma::create($request->all());

        return response()->json([
            'message' => 'Turma criada com sucesso!',
            'turma' => $turma->load('professor')
        ], 201);
    }

    /**
     * Mostrar uma turma
     */
    public function show($id)
    {
        $turma = Turma::with('professor')->findOrFail($id);
        return response()->json($turma);
    }

    /**
     * Atualizar turma
     */
    public function update(Request $request, $id)
    {
        $turma = Turma::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nome' => 'sometimes|string|max:255',
            'codigo' => 'sometimes|string|unique:turmas,codigo,' . $id,
            'ano' => 'nullable|string|max:10',
            'periodo' => 'nullable|string|max:20',
            'capacidade' => 'nullable|integer|min:1|max:100',
            'professor_id' => 'nullable|exists:users,id',
            'status' => 'sometimes|in:ativa,inativa',
            'observacoes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $turma->update($request->all());

        return response()->json([
            'message' => 'Turma atualizada com sucesso!',
            'turma' => $turma->load('professor')
        ]);
    }

    /**
     * Excluir turma
     */
    public function destroy($id)
    {
        $turma = Turma::findOrFail($id);
        $turma->delete();

        return response()->json([
            'message' => 'Turma excluída com sucesso!'
        ]);
    }
    
}