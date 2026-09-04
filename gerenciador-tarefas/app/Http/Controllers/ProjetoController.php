<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projeto;
use App\Models\User;

class ProjetoController extends Controller
{
    public function index()
    {
        $projetos = Projeto::with(['responsavel', 'tarefas' => function ($query) {
            $query->with('categoria')->orderByRaw('data_vencimento IS NULL, data_vencimento ASC');
        }])->get();
        return view('projetos.index')->with(['projetos' => $projetos]);
    }

    function create()
    {
        $users = User::orderBy('name')->get();
        return view('projetos.create', compact('users'));
    }

    function validateForm(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'data_inicio' => 'required',
            'status' => 'required',
            'responsavel_id' => 'nullable|exists:users,id',
        ], [
            'nome.required' => "O :attribute é obrigatorio",
            'data_inicio.required' => "O :attribute é obrigatorio",
            'status.required' => "O :attribute é obrigatorio",
            'responsavel_id.exists' => "O responsável selecionado é inválido",
        ]);
    }

    function store(Request $request)
    {
        $this->validateForm($request);
        Projeto::create($request->all());
        return redirect('projetos')->with("success", 'Registro Salvo com sucesso!');
    }

    function show($id)
    {
        $projeto = Projeto::find($id);
        $projeto->load(['responsavel', 'tarefas' => function ($query) {
            $query->with('categoria')->orderByRaw('data_vencimento IS NULL, data_vencimento ASC');
        }]);
        return view('projetos.show', compact('projeto'));
    }

    function edit($id)
    {
        $projeto = Projeto::find($id);
        $users = User::orderBy('name')->get();
        return view('projetos.edit', compact('projeto', 'users'));
    }

    function update(Request $request, $id)
    {
        $this->validateForm($request);
        Projeto::find($id)->update($request->all());
        return redirect('projetos')->with("success", 'Registro Atualizado com sucesso!');
    }

    function destroy($id)
    {
        Projeto::destroy($id);
        return redirect('projetos')->with("success", 'Registro removido com sucesso!');
    }

    public function search(Request $request)
    {
        $query = Projeto::with(['responsavel', 'tarefas' => function ($q) {
            $q->with('categoria')->orderByRaw('data_vencimento IS NULL, data_vencimento ASC');
        }]);

        if (!empty($request->valor)) {
            $query->where($request->tipo, 'like', "%{$request->valor}%");
        }

        $projetos = $query->get();
        return view('projetos.index', compact('projetos'));
    }
}