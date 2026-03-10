<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\clientes; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    public function index() 
    {
        $pedidos = Pedido::with('cliente')->latest()->paginate(10);
        return view('pedidos.index', compact('pedidos'));
    }

    public function create() 
    {
        $clientes = clientes::all();
        return view('pedidos.create', compact('clientes'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'produto'    => 'required|string|max:255',
            'quantidade' => 'required|integer|min:1',
            'valor'      => 'required', 
        ]);

        try {
            // Limpeza robusta: remove tudo exceto números e a vírgula
            $valorLimpo = preg_replace('/[^0-9,]/', '', $request->valor);
            
            // Converte a vírgula para ponto (padrão SQL)
            $valorDecimal = (float) str_replace(',', '.', $valorLimpo);

            $pedido = new Pedido();
            $pedido->cliente_id = $request->cliente_id;
            $pedido->produto    = $request->produto;
            $pedido->quantidade = $request->quantidade;
            $pedido->valor      = $valorDecimal; 
            $pedido->save();

            return redirect()->route('pedidos.index')
                             ->with('success', 'Pedido registrado com sucesso!');

        } catch (\Exception $e) {
            Log::error("Erro no Pedido: " . $e->getMessage());
            return back()->withInput()->with('error', 'Erro ao processar valor.');
        }
    }

    public function destroy(Pedido $pedido) 
    {
        $pedido->delete();
        return redirect()->route('pedidos.index')->with('success', 'Pedido excluído.');
    }
}