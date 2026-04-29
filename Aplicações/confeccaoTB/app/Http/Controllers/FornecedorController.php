<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FornecedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Fornecedor::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nome_fantasia', 'LIKE', "%{$search}%")
                  ->orWhere('cnpj', 'LIKE', "%{$search}%");
        }

        $fornecedores = $query->latest()->paginate(10);
        return view('fornecedores.index', compact('fornecedores'));
    }

    public function create()
    {
        return view('fornecedores.create');
    }

    public function store(Request $request)
    {
        // IMPORTANTE: Remove a máscara antes de validar
        $cnpjLimpo = preg_replace('/\D/', '', $request->cnpj);
        $telefoneLimpo = preg_replace('/\D/', '', $request->telefone);

        // Substitui os valores no request para a validação aceitar
        $request->merge([
            'cnpj' => $cnpjLimpo,
            'telefone' => $telefoneLimpo
        ]);

        $validated = $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social'  => 'required|string|max:255',
            'cnpj'          => 'required|string|size:14|unique:fornecedors,cnpj',
            'email'         => 'nullable|email|max:255',
            'telefone'      => 'nullable|string|min:10|max:11',
        ]);

        try {
            Fornecedor::create($validated);
            return redirect()->route('fornecedores.index')->with('success', 'Fornecedor cadastrado!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erro ao salvar fornecedor.');
        }
    }

    public function edit(Fornecedor $fornecedore)
    {
        return view('fornecedores.edit', compact('fornecedore'));
    }

    public function update(Request $request, Fornecedor $fornecedore)
    {
        // Limpeza idêntica ao store
        $cnpjLimpo = preg_replace('/\D/', '', $request->cnpj);
        $telefoneLimpo = preg_replace('/\D/', '', $request->telefone);

        $request->merge([
            'cnpj' => $cnpjLimpo,
            'telefone' => $telefoneLimpo
        ]);

        $validated = $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social'  => 'required|string|max:255',
            'cnpj'          => "required|string|size:14|unique:fornecedors,cnpj,{$fornecedore->id}",
            'email'         => 'nullable|email|max:255',
            'telefone'      => 'nullable|string|min:10|max:11',
        ]);

        $fornecedore->update($validated);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor atualizado!');
    }

    public function destroy(Fornecedor $fornecedore)
    {
        $fornecedore->delete();
        return redirect()->route('fornecedores.index')->with('success', 'Removido com sucesso!');
    }
}