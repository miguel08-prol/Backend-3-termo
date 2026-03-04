<?php

namespace App\Http\Controllers;

use App\Models\Clientes; // Verifique se o seu Model é 'Clientes' ou 'Cliente'
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Listagem
    public function index()
    {
        $clientes = Clientes::paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    // Abre o formulário de criação
    public function create()
    {
        return view('clientes.create');
    }

    // Salva o novo cliente no banco
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email',
            'cpf' => 'required',
        ]);

        Clientes::create($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente criado!');
    }

    // Deleta um cliente (Função para o botão de excluir da sua tabela)
    public function destroy(Clientes $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente removido!');
    }
}