<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projeto;
use App\Models\Equipe;
use Illuminate\Support\Facades\Storage; // ADICIONADO PARA GERENCIAR IMAGENS

class ProjetoController extends Controller
{
    public function index()
    {
        $projetos = Projeto::with(['equipe', 'tarefas' => function ($query) {
            $query->with('categoria')->orderByRaw('data_vencimento IS NULL, data_vencimento ASC');
        }])->get();
        return view('projetos.index')->with(['projetos' => $projetos]);
    }

    function create()
    {
        $equipes = Equipe::orderBy('nome')->get();
        return view('projetos.create', compact('equipes'));
    }

    function validateForm(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'data_inicio' => 'required',
            'status' => 'required',
            'equipe_id' => 'nullable|exists:equipes,id',
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // ADICIONADO AQUI
        ], [
            'nome.required' => "O :attribute é obrigatorio",
            'data_inicio.required' => "O :attribute é obrigatorio",
            'status.required' => "O :attribute é obrigatorio",
            'equipe_id.exists' => "A equipe selecionada é inválida",
            'imagem.image' => "O arquivo enviado deve ser uma imagem válida.",
        ]);
    }

    function store(Request $request)
    {
        $this->validateForm($request);
        
        $dados = $request->all();

        // Lógica de Upload de Imagem
        if ($request->hasFile('imagem')) {
            $dados['imagem'] = $request->file('imagem')->store('projetos', 'public');
        }

        Projeto::create($dados);
        return redirect('projetos')->with("success", 'Registro Salvo com sucesso!');
    }

    function show($id)
    {
        $projeto = Projeto::find($id);
        $projeto->load(['equipe.integrantes', 'equipe.lider', 'tarefas' => function ($query) {
            $query->with('categoria')->orderByRaw('data_vencimento IS NULL, data_vencimento ASC');
        }]);
        return view('projetos.show', compact('projeto'));
    }

    function edit($id)
    {
        $projeto = Projeto::find($id);
        $equipes = Equipe::orderBy('nome')->get();
        return view('projetos.edit', compact('projeto', 'equipes'));
    }

    function update(Request $request, $id)
    {
        $this->validateForm($request);
        
        $projeto = Projeto::find($id);
        $dados = $request->all();

        // Lógica de Upload e Remoção de Imagem Antiga
        if ($request->hasFile('imagem')) {
            if ($projeto->imagem && Storage::disk('public')->exists($projeto->imagem)) {
                Storage::disk('public')->delete($projeto->imagem);
            }
            $dados['imagem'] = $request->file('imagem')->store('projetos', 'public');
        }

        $projeto->update($dados);
        return redirect('projetos')->with("success", 'Registro Atualizado com sucesso!');
    }

    function destroy($id)
    {
        $projeto = Projeto::find($id);
        
        // Deleta a imagem do storage ao excluir o projeto
        if ($projeto->imagem && Storage::disk('public')->exists($projeto->imagem)) {
            Storage::disk('public')->delete($projeto->imagem);
        }

        $projeto->delete();
        return redirect('projetos')->with("success", 'Registro removido com sucesso!');
    }

    public function search(Request $request)
    {
        $query = Projeto::with(['equipe', 'tarefas' => function ($q) {
            $q->with('categoria')->orderByRaw('data_vencimento IS NULL, data_vencimento ASC');
        }]);

        if (!empty($request->valor)) {
            $query->where($request->tipo, 'like', "%{$request->valor}%");
        }

        $projetos = $query->get();
        return view('projetos.index', compact('projetos'));
    }
}