<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    public function index()
    {
        // Carrega as movimentações com os dados do produto (Eager Loading)
        $movimentacoes = Estoque::with('produto')->latest()->paginate(10);
        return view('estoque.index', compact('movimentacoes'));
    }

    public function create()
    {
        $produtos = Produto::all();
        return view('estoque.create', compact('produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer',
            'tipo'       => 'required',
        ]);

        Estoque::create($request->all());

        return redirect()->route('estoque.index')->with('success', 'Movimentação registrada!');
    }
}