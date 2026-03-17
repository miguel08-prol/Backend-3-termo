<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstoqueController extends Controller
{
    /**
     * Lista o histórico de movimentações com Eager Loading do produto.
     */
    public function index()
    {
        $movimentacoes = Estoque::with('produto')
            ->latest()
            ->paginate(12);

        return view('estoque.index', compact('movimentacoes'));
    }

    /**
     * Formulário de nova movimentação.
     */
    public function create()
    {
        $produtos = Produto::orderBy('nome', 'asc')->get();
        return view('estoque.create', compact('produtos'));
    }

    /**
     * Salva a movimentação com lógica de proteção.
     */
    public function store(Request $request)
    {
        // 1. Limpeza de dados (Sanitize)
        $this->sanitize($request);

        // 2. Validação
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:1',
            'tipo'       => 'required|in:Entrada,Saída',
            'motivo'     => 'nullable|string|max:255',
        ]);

        try {
            // Usamos Transaction para garantir que se algo falhar, nada é salvo.
            DB::transaction(function () use ($request) {
                Estoque::create($request->all());
            });

            return redirect()->route('estoque.index')
                ->with('success', 'Movimentação de estoque processada com sucesso!');

        } catch (\Exception $e) {
            Log::error("Erro no Estoque: " . $e->getMessage());
            return back()->withInput()->with('error', 'Falha ao processar movimentação.');
        }
    }

    /**
     * Formulário de edição de histórico.
     */
    public function edit($id)
    {
        $movimentacao = Estoque::findOrFail($id);
        $produtos = Produto::all();
        return view('estoque.edit', compact('movimentacao', 'produtos'));
    }

    /**
     * Atualiza um registo de estoque.
     */
    public function update(Request $request, $id)
    {
        $this->sanitize($request);
        
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:1',
            'tipo'       => 'required|in:Entrada,Saída',
            'motivo'     => 'nullable|string|max:255',
        ]);

        try {
            $movimentacao = Estoque::findOrFail($id);
            $movimentacao->update($request->all());

            return redirect()->route('estoque.index')->with('success', 'Registo atualizado!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar registo.');
        }
    }

    /**
     * Remove um registo do histórico.
     */
    public function destroy($id)
    {
        $movimentacao = Estoque::findOrFail($id);
        $movimentacao->delete();

        return redirect()->route('estoque.index')->with('success', 'Registo removido.');
    }

    /**
     * Lógica SÉNIOR: Garante que a quantidade seja sempre um número inteiro positivo.
     */
    private function sanitize(Request $request)
    {
        if ($request->has('quantidade')) {
            // Remove qualquer carácter que não seja número e aplica valor absoluto
            $qtd = preg_replace('/\D/', '', $request->quantidade);
            $request->merge(['quantidade' => (int)$qtd]);
        }
    }
}