<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    // Listagem
    public function index()
    {
        $fornecedores = Fornecedor::paginate(10);
        return view('fornecedores.index', compact('fornecedores'));
    }

    // Abre o formulário de criação (create.blade.php)
    public function create()
    {
        return view('fornecedores.create');
    }

    // Salva o fornecedor no banco de dados
    public function store(Request $request)
    {
        $request->validate([
            'nome_fantasia' => 'required',
            'cnpj'          => 'required|unique:fornecedors,cnpj',
            'email'         => 'nullable|email',
        ]);

        Fornecedor::create($request->all());

        return redirect()->route('fornecedores.index')
                         ->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    // Deleta o fornecedor (acionado pelo seu modal de exclusão)
    public function destroy(Fornecedor $fornecedore) // O Laravel usa 'fornecedore' no plural automático do resource
    {
        $fornecedore->delete();
        return redirect()->route('fornecedores.index')
                         ->with('success', 'Fornecedor removido com sucesso!');
    }
}