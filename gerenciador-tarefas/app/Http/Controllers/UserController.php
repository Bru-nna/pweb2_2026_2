<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Listar usuários
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Formulário de criação
    public function create()
    {
        return view('users.create');
    }

    // Salvar novo usuário
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'telefone' => 'nullable|max:20',
            'cargo' => 'nullable|max:100',
            'status' => 'required|in:ativo,inativo'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
                         ->with('success', 'Usuário criado com sucesso!');
    }

    // Mostrar um usuário
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Formulário de edição
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Atualizar usuário
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefone' => 'nullable|max:20',
            'cargo' => 'nullable|max:100',
            'status' => 'required|in:ativo,inativo'
        ]);

        // Se a senha foi preenchida, atualiza
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed'
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.index')
                         ->with('success', 'Usuário atualizado com sucesso!');
    }

    // Deletar usuário
    public function destroy(User $user)
    {
        // Impede deletar o próprio usuário
        if ($user->id == auth()->id()) {
            return redirect()->route('users.index')
                             ->with('error', 'Você não pode deletar seu próprio usuário!');
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'Usuário excluído com sucesso!');
    }
}