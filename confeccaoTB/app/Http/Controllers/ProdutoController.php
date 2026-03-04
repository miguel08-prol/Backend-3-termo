<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    // Listagem de produtos
    public function index()
    {
        $produtos = Produto::paginate(10);
        return view('produtos.index', compact('produtos'));
    }

    // Abre o formulário de criação
    public function create()
    {
        return view('produtos.create');
    }

    // Salva o novo produto no banco
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'preco' => 'required|numeric',
            'estoque' => 'required|integer',
        ]);

        Produto::create($request->all());
        return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    // Abre o formulário de edição
    public function edit(Produto $produto)
    {
        return view('produtos.edit', compact('produto'));
    }

    // Atualiza os dados do produto
    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'nome' => 'required',
            'preco' => 'required|numeric',
            'estoque' => 'required|integer',
        ]);

        $produto->update($request->all());
        return redirect()->route('produtos.index')->with('success', 'Produto atualizado!');
    }

    // Remove o produto (chamado pelo botão do modal que fizemos)
    public function destroy(Produto $produto)
    {
        $produto->delete();
        return redirect()->route('produtos.index')->with('success', 'Produto removido!');
    }
}