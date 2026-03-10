<?php

namespace App\Http\Controllers;

use App\Models\Clientes; // Mantenha 'Clientes' se este for o nome do seu arquivo em app/Models
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Listagem
 public function index(Request $request)
{
    $query = Clientes::query();

    // Filtro de Busca
    if ($request->has('search')) {
        $query->where('nome', 'LIKE', '%' . $request->search . '%')
              ->orWhere('cpf', 'LIKE', '%' . $request->search . '%');
    }

    // Ordem Alfabética (A-Z)
    $clientes = $query->orderBy('nome', 'asc')->paginate(9);

    return view('clientes.index', compact('clientes'));
}

    // Exibe o formulário de cadastro
    public function create() 
    {
        return view('clientes.create');
    }

    // Recebe os dados do formulário e salva no banco de dados
    public function store(Request $request) 
    {
        // 1. Validação (Corrigido de 'validade' para 'validate')
        // Importante: No 'unique', use o nome da TABELA no banco de dados (geralmente 'clientes')
        $request->validate([
            'nome'     => 'required|string|max:255',
            'cpf'      => 'required|string|unique:clientes', 
            'email'    => 'required|email|unique:clientes',
            'telefone' => 'required|string',
            'endereco' => 'required|string',
        ]);

        // 2. Salva o cliente (Corrigido para usar o Model 'Clientes' que você importou)
        Clientes::create($request->all());

        // 3. Redireciona (Corrigido 'sucess' para 'success')
        return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso!');
    }

    // Função para excluir (Útil para o botão de lixeira da sua tabela)
    public function destroy($id)
    {
        $cliente = Clientes::findOrFail($id);
        $cliente->delete();
        
        return redirect()->route('clientes.index')->with('success', 'Cliente removido com sucesso!');
    }
}