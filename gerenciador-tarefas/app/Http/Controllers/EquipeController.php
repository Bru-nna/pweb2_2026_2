<?php

namespace App\Http\Controllers;

use App\Models\Equipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipeController extends Controller
{
    // Listar equipes (com projetos de cada uma)
    public function index()
    {
        $equipes = Equipe::with(['lider', 'integrantes', 'projetos'])->get();
        return view('equipes.index', compact('equipes'));
    }

    // Formulário de criação
    public function create()
    {
        $usuarios = User::where('status', 'ativo')->orderBy('name')->get();
        return view('equipes.create', compact('usuarios'));
    }

    // Salvar nova equipe
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|max:150',
            'cor' => 'required|max:20',
            'lider_id' => 'required|exists:users,id',
            'integrantes' => 'required|array|min:1',
            'integrantes.*' => 'exists:users,id',
        ]);

        $equipe = Equipe::create([
            'nome' => $validated['nome'],
            'cor' => $validated['cor'],
            'lider_id' => $validated['lider_id'],
        ]);

        // Garante que o líder esteja entre os integrantes
        $integrantes = $validated['integrantes'];
        if (!in_array($validated['lider_id'], $integrantes)) {
            $integrantes[] = $validated['lider_id'];
        }
        $equipe->integrantes()->sync($integrantes);

        return redirect()->route('equipes.index')
                         ->with('success', 'Equipe criada com sucesso!');
    }

    // Detalhes da equipe (foto, email, líder, integrantes)
    public function show(Equipe $equipe)
    {
        $equipe->load(['lider', 'integrantes', 'projetos']);
        return view('equipes.show', compact('equipe'));
    }

    // Formulário de edição
    public function edit(Equipe $equipe)
    {
        $usuarios = User::where('status', 'ativo')->orderBy('name')->get();
        $equipe->load('integrantes');
        return view('equipes.edit', compact('equipe', 'usuarios'));
    }

    // Atualizar equipe
    public function update(Request $request, Equipe $equipe)
    {
        $validated = $request->validate([
            'nome' => 'required|max:150',
            'cor' => 'required|max:20',
            'lider_id' => 'required|exists:users,id',
            'integrantes' => 'required|array|min:1',
            'integrantes.*' => 'exists:users,id',
        ]);

        $equipe->update([
            'nome' => $validated['nome'],
            'cor' => $validated['cor'],
            'lider_id' => $validated['lider_id'],
        ]);

        $integrantes = $validated['integrantes'];
        if (!in_array($validated['lider_id'], $integrantes)) {
            $integrantes[] = $validated['lider_id'];
        }
        $equipe->integrantes()->sync($integrantes);

        return redirect()->route('equipes.index')
                         ->with('success', 'Equipe atualizada com sucesso!');
    }

    // Deletar equipe
    public function destroy(Equipe $equipe)
    {
        $equipe->delete();

        return redirect()->route('equipes.index')
                         ->with('success', 'Equipe excluída com sucesso!');
    }
}