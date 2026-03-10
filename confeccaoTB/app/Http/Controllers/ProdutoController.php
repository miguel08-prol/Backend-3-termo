<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::latest()->paginate(10);
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'      => 'required|string|max:255',
            'preco'     => 'required',
            'estoque'   => 'required|integer|min:0',
        ]);

        try {
            // Limpeza: remove tudo exceto números e a vírgula
            $precoLimpo = preg_replace('/[^0-9,]/', '', $request->preco);
            $precoDecimal = (float) str_replace(',', '.', $precoLimpo);

            Produto::create([
                'nome'      => $request->nome,
                'descricao' => $request->descricao,
                'preco'     => $precoDecimal,
                'estoque'   => $request->estoque,
            ]);

            return redirect()->route('produtos.index')
                ->with('success', 'Peça adicionada ao catálogo com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erro ao processar o preço.');
        }
    }

    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'nome'  => 'required|string|max:255',
            'preco' => 'required',
        ]);

        try {
            // Aplica a mesma limpeza na edição
            $precoLimpo = preg_replace('/[^0-9,]/', '', $request->preco);
            $precoDecimal = (float) str_replace(',', '.', $precoLimpo);

            $produto->update([
                'nome'      => $request->nome,
                'descricao' => $request->descricao,
                'preco'     => $precoDecimal,
                'estoque'   => $request->estoque,
            ]);

            return redirect()->route('produtos.index')
                ->with('success', 'Produto atualizado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar.');
        }
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();
        return redirect()->route('produtos.index')->with('success', 'Produto removido.');
    }
}