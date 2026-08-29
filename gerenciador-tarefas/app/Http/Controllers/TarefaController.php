<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Models\Projeto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    public function index()
    {
        $tarefas = Tarefa::with(['projeto', 'categoria'])->get();
        return view('tarefas.index', compact('tarefas'));
    }

    public function create()
    {
        $projetos = Projeto::all();
        $categorias = Categoria::all();
        return view('tarefas.create', compact('projetos', 'categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|max:255',
            'descricao' => 'nullable',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
            'data_vencimento' => 'nullable|date',
            'status' => 'required|in:pendente,em_andamento,concluida,cancelada',
            'projeto_id' => 'required|exists:projetos,id',
            'categoria_id' => 'required|exists:categorias,id'
        ]);

        Tarefa::create($validated);
        return redirect()->route('tarefas.index')
                         ->with('success', 'Tarefa criada com sucesso!');
    }

    public function show(Tarefa $tarefa)
    {
        return view('tarefas.show', compact('tarefa'));
    }

    public function edit(Tarefa $tarefa)
    {
        $projetos = Projeto::all();
        $categorias = Categoria::all();
        return view('tarefas.edit', compact('tarefa', 'projetos', 'categorias'));
    }

    public function update(Request $request, Tarefa $tarefa)
    {
        $validated = $request->validate([
            'titulo' => 'required|max:255',
            'descricao' => 'nullable',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
            'data_vencimento' => 'nullable|date',
            'status' => 'required|in:pendente,em_andamento,concluida,cancelada',
            'projeto_id' => 'required|exists:projetos,id',
            'categoria_id' => 'required|exists:categorias,id'
        ]);

        $tarefa->update($validated);
        return redirect()->route('tarefas.index')
                         ->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Tarefa $tarefa)
    {
        $tarefa->delete();
        return redirect()->route('tarefas.index')
                         ->with('success', 'Tarefa excluída com sucesso!');
    }
}