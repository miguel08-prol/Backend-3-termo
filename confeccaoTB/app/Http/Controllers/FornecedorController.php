<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FornecedorController extends Controller
{
    // 1. Listagem com busca opcional (Melhor UX)
    public function index(Request $request)
    {
        $query = Fornecedor::query();

        // Se houver busca por nome ou CNPJ
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nome_fantasia', 'LIKE', "%{$search}%")
                  ->orWhere('cnpj', 'LIKE', "%{$search}%");
        }

        $fornecedores = $query->latest()->paginate(10);
        
        return view('fornecedores.index', compact('fornecedores'));
    }

    // 2. Exibe o formulário de criação
    public function create()
    {
        return view('fornecedores.create');
    }

    // 3. Salva com validação de alto nível
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social'  => 'required|string|max:255',
            'cnpj'          => 'required|string|unique:fornecedors,cnpj|max:18',
            'email'         => 'nullable|email|max:255',
            'telefone'      => 'nullable|string|max:20',
        ], [
            'cnpj.unique' => 'Este CNPJ já está registrado em nossa base de parceiros.',
            'nome_fantasia.required' => 'O nome fantasia é essencial para a identificação.',
        ]);

        try {
            Fornecedor::create($validated);

            return redirect()->route('fornecedores.index')
                ->with('success', "Fornecedor {$request->nome_fantasia} registrado com sucesso!");
        } catch (\Exception $e) {
            Log::error("Erro ao salvar fornecedor: " . $e->getMessage());
            return back()->withInput()->with('error', 'Ocorreu um erro técnico ao salvar o fornecedor.');
        }
    }

    // 4. Edição (Seguindo o padrão do Route::resource)
    public function edit(Fornecedor $fornecedore) 
    {
        return view('fornecedores.edit', compact('fornecedore'));
    }

    // 5. Atualização
    public function update(Request $request, Fornecedor $fornecedore)
    {
        $validated = $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social'  => 'required|string|max:255',
            'cnpj'          => "required|string|max:18|unique:fornecedors,cnpj,{$fornecedore->id}",
            'email'         => 'nullable|email|max:255',
            'telefone'      => 'nullable|string|max:20',
        ]);

        $fornecedore->update($validated);

        return redirect()->route('fornecedores.index')
            ->with('success', 'Dados do fornecedor atualizados com sucesso.');
    }

    // 6. Exclusão (Aciona seu modal vermelho)
    public function destroy(Fornecedor $fornecedore)
    {
        $nome = $fornecedore->nome_fantasia;
        $fornecedore->delete();

        return redirect()->route('fornecedores.index')
            ->with('success', "O fornecedor {$nome} foi removido do catálogo.");
    }
}