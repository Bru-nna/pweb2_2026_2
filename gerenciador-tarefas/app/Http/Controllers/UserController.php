<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'telefone' => 'nullable|max:20',
            'cargo' => 'nullable|max:100',
            'status' => 'required|in:ativo,inativo',
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('imagem')) {
            $validated['imagem'] = $request->file('imagem')->store('users', 'public');
        }

        User::create($validated);

        return redirect()->route('users.index')
                         ->with('success', 'Usuário criado com sucesso!');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefone' => 'nullable|max:20',
            'cargo' => 'nullable|max:100',
            'status' => 'required|in:ativo,inativo',
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed'
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('imagem')) {
            // remove imagem antiga
            if ($user->imagem && Storage::disk('public')->exists($user->imagem)) {
                Storage::disk('public')->delete($user->imagem);
            }
            $validated['imagem'] = $request->file('imagem')->store('users', 'public');
        }

        $user->update($validated);

        return redirect()->route('users.index')
                         ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return redirect()->route('users.index')
                             ->with('error', 'Você não pode deletar seu próprio usuário!');
        }

        if ($user->imagem && Storage::disk('public')->exists($user->imagem)) {
            Storage::disk('public')->delete($user->imagem);
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'Usuário excluído com sucesso!');
    }

    public function search(Request $request)
    {
        $query = User::query();

        if ($request->filled('valor')) {
            $query->where($request->tipo, 'like', "%{$request->valor}%");
        }

        $users = $query->get();
        return view('users.index', compact('users'));
    }
}