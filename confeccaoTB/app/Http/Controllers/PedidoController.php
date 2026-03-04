<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\clientes; // Importe o model de clientes
use Illuminate\Http\Request;

class PedidoController extends Controller
{
 public function index() {
    $pedidos = Pedido::with('cliente')->paginate(10); // - Similar à listagem de clientes
    return view('pedidos.index', compact('pedidos'));
}
    public function create()
    {
        $clientes = clientes::all(); // Precisamos da lista de clientes para o <select>
        return view('pedidos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'produto'    => 'required',
            'valor'      => 'required|numeric',
        ]);

        Pedido::create($request->all());
        return redirect()->route('pedidos.index')->with('success', 'Pedido realizado!');
    }
}
