<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Listagem com Busca
    public function index(Request $request)
    {
        $query = clientes::query();

        if ($request->has('search')) {
            $query->where('nome', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('cpf', 'LIKE', '%' . $request->search . '%');
        }

        $clientes = $query->orderBy('nome', 'asc')->paginate(9);
        return view('clientes.index', compact('clientes'));
    }

    // Formulário de Criação
    public function create() 
    {
        return view('clientes.create');
    }

    // Salvar Novo Cliente
    public function store(Request $request) 
    {
        $request->validate([
            'nome'     => 'required|string|max:255',
            'cpf'      => 'required|string|unique:clientes,cpf', 
            'email'    => 'required|email|unique:clientes,email',
            'telefone' => 'required|string',
            'endereco' => 'required|string',
        ]);

        clientes::create($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso!');
    }

    // Abrir Formulário de Edição (Causa do erro anterior corrigida)
  public function edit($id)
{
    // O Laravel recebe o ID da URL (ex: /clientes/5/edit) e coloca na variável $id
    $cliente = \App\Models\clientes::findOrFail($id);
    
    // Retorna a view 'clientes.edit' (o arquivo edit.blade.php que você enviou)
    return view('clientes.edit', compact('cliente'));
}

    // Atualizar Dados
    public function update(Request $request, $id) 
    {
        $cliente = clientes::findOrFail($id);

        $request->validate([
            'nome'     => 'required|string|max:255',
            // O id no final ignora o registo atual na validação de "único"
            'cpf'      => 'required|string|unique:clientes,cpf,' . $id,
            'email'    => 'required|email|unique:clientes,email,' . $id,
            'telefone' => 'required|string',
            'endereco' => 'required|string',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    // Eliminar Cliente
    public function destroy($id)
    {
        $cliente = clientes::findOrFail($id);
        $cliente->delete();
        
        return redirect()->route('clientes.index')->with('success', 'Cliente removido com sucesso!');
    }
}