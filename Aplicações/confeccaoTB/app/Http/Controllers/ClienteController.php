<?php

namespace App\Http\Controllers;

use App\Models\clientes; // Certifique-se que o Model é Cliente (singular)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
{
    /**
     * Index com busca profissional e paginação.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $clientes = clientes::when($search, function($query, $search) {
            return $query->where('nome', 'LIKE', "%{$search}%")
                         ->orWhere('cpf', 'LIKE', "%{$search}%");
        })
        ->latest()
        ->paginate(12);

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store com Sanatização de Dados (Lógica Sênior).
     */
    public function store(Request $request)
    {
        // 1. Limpa as máscaras antes de validar (Padrão de Segurança)
        $this->sanitizeRequest($request);

        // 2. Validação rigorosa
        $request->validate([
            'nome'     => 'required|string|max:255',
            'cpf'      => 'required|string|size:11|unique:clientes,cpf', // CPF limpo tem 11
            'email'    => 'required|email|unique:clientes,email',
            'telefone' => 'nullable|string|min:10|max:11',
            'endereco' => 'nullable|string|max:255',
        ]);

        try {
            clientes::create($request->all());
            return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com maestria!');
        } catch (\Exception $e) {
            Log::error("Erro ao cadastrar cliente: " . $e->getMessage());
            return back()->withInput()->with('error', 'Erro interno ao salvar. Tente novamente.');
        }
    }

    public function edit($id)
    {
        $cliente = clientes::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update com tratamento de exceção do ID atual.
     */
    public function update(Request $request, $id)
    {
        $this->sanitizeRequest($request);
        $cliente = clientes::findOrFail($id);

        $request->validate([
            'nome'     => 'required|string|max:255',
            'cpf'      => 'required|string|size:11|unique:clientes,cpf,' . $id,
            'email'    => 'required|email|unique:clientes,email,' . $id,
            'telefone' => 'nullable|string|min:10|max:11',
            'endereco' => 'nullable|string|max:255',
        ]);

        try {
            $cliente->update($request->all());
            return redirect()->route('clientes.index')->with('success', 'Dados atualizados com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar cliente ID {$id}: " . $e->getMessage());
            return back()->withInput()->with('error', 'Não foi possível atualizar os dados.');
        }
    }

    public function destroy($id)
    {
        try {
            $cliente = clientes::findOrFail($id);
            $cliente->delete();
            return redirect()->route('clientes.index')->with('success', 'Cliente removido do sistema.');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')->with('error', 'Este cliente não pode ser removido.');
        }
    }

    /**
     * FUNÇÃO PRIVADA: Sanitize (O segredo do Sênior)
     * Remove pontos, traços e parênteses antes de qualquer lógica.
     */
    private function sanitizeRequest(Request $request)
    {
        $data = $request->all();

        if ($request->has('cpf')) {
            $data['cpf'] = preg_replace('/\D/', '', $request->cpf);
        }

        if ($request->has('telefone')) {
            $data['telefone'] = preg_replace('/\D/', '', $request->telefone);
        }

        $request->merge($data);
    }
}