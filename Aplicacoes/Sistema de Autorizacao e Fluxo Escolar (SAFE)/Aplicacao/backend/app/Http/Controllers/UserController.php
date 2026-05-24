<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Lista usuários filtrados por cargo.
     * Uso: /api/usuarios?role=tecnico ou /api/usuarios?role=professor
     */
    public function index(Request $request)
    {
        $role = $request->query('role', 'tecnico');
        return response()->json(User::where('role', $role)->get());
    }

    /**
     * Cadastra um novo usuário.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:tecnico,professor,admin',
            'telefone' => 'nullable|string|max:20',
            'departamento' => 'nullable|string|max:100'
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'telefone' => $validated['telefone'] ?? null,
            'departamento' => $validated['departamento'] ?? null,
        ]);

        return response()->json([
            'message' => 'Usuário cadastrado com sucesso!',
            'user'    => $user
        ], 201);
    }

    /**
     * Remove um usuário.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Usuário removido com sucesso.']);
    }

     /**
     * Buscar perfil do usuário logado
     */
    public function getProfile()
    {
        $user = Auth::user();
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'departamento' => $user->departamento ?? '',
            'telefone' => $user->telefone ?? '',
            'notificacoes_email' => $user->notificacoes_email ?? true,
            'notificacoes_push' => $user->notificacoes_push ?? false,
        ]);
    }

    /**
     * Atualizar perfil do usuário
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'departamento' => 'nullable|string|max:100',
            'telefone' => 'nullable|string|max:20'
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->departamento = $request->departamento;
        $user->telefone = $request->telefone;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Perfil atualizado com sucesso',
            'user' => $user
        ]);
    }

    /**
     * Alterar senha do usuário
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required|string',
            'nova_senha' => 'required|string|min:6',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->senha_atual, $user->password)) {
            throw ValidationException::withMessages([
                'senha_atual' => ['Senha atual incorreta'],
            ]);
        }

        $user->password = Hash::make($request->nova_senha);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Senha alterada com sucesso'
        ]);
    }

    /**
     * Atualizar preferências de notificação
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'email' => 'boolean',
            'push' => 'boolean',
        ]);

        $user = Auth::user();
        $user->notificacoes_email = $request->email ?? true;
        $user->notificacoes_push = $request->push ?? false;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Preferências salvas'
        ]);
    }

     public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'role'     => 'sometimes|in:tecnico,professor,admin',
            'telefone' => 'nullable|string|max:20',
            'departamento' => 'nullable|string|max:100',
            'password' => 'nullable|min:6',
    
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if (isset($validated['role'])) {
            $user->role = $validated['role'];
        }
        
        $user->telefone = $validated['telefone'] ?? $user->telefone;
        $user->departamento = $validated['departamento'] ?? $user->departamento;
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        return response()->json([
            'message' => 'Usuário atualizado com sucesso!',
            'user'    => $user
        ]);
    }

    // Adicione no UserController.php
public function getProfessores()
{
    $professores = User::where('role', 'professor')
        ->select('id', 'name', 'email', 'telefone', 'departamento')
        ->orderBy('name')
        ->get();
    
    return response()->json($professores);
}
}
