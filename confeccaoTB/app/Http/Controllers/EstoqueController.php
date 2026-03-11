<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Produto; // Importante para listar os produtos no formulário
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    // 1. Listagem de Movimentações
    public function index()
    {
        // Carrega o estoque com os dados do produto (Eager Loading)
        $movimentacoes = Estoque::with('produto')->latest()->paginate(10);
        return view('estoque.index', compact('movimentacoes'));
    }

    // 2. Exibe o formulário de Cadastro (Entrada/Saída)
    public function create()
    {
        // Precisamos dos produtos para o usuário selecionar qual item está entrando/saindo
        $produtos = Produto::all();
        return view('estoque.create', compact('produtos'));
    }

    // 3. Salva a movimentação no banco
    public function store(Request $request)
    {
        // Validação rigorosa
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|numeric|min:1',
            'tipo'       => 'required|in:Entrada,Saída',
            'motivo'     => 'nullable|string|max:255',
        ]);

        // Cria o registro conforme o seu Model Estoque.php
        Estoque::create([
            'produto_id' => $request->produto_id,
            'quantidade' => $request->quantidade,
            'tipo'       => $request->tipo,
            'motivo'     => $request->motivo ?? 'Movimentação manual',
        ]);

        return redirect()->route('estoque.index')
            ->with('success', 'Movimentação de estoque registrada com sucesso!');
    }

    // 4. Deletar registro (se necessário)
    public function destroy($id)
    {
        $movimento = Estoque::findOrFail($id);
        $movimento->delete();

        return redirect()->route('estoque.index')
            ->with('success', 'Registro removido do histórico.');
    }

    // Adicione estes métodos ao seu EstoqueController

public function edit($id)
{
    $movimentacao = Estoque::findOrFail($id);
    $produtos = Produto::all(); // Necessário para o select de produtos
    
    return view('estoque.edit', compact('movimentacao', 'produtos'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'produto_id' => 'required|exists:produtos,id',
        'quantidade' => 'required|numeric|min:1',
        'tipo'       => 'required|in:Entrada,Saída',
        'motivo'     => 'nullable|string|max:255',
    ]);

    $movimentacao = Estoque::findOrFail($id);
    $movimentacao->update($request->all());

    return redirect()->route('estoque.index')
        ->with('success', 'Movimentação atualizada com sucesso!');
}
}