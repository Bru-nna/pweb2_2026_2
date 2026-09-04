<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projeto;

class ProjetoController extends Controller
{
    public function index()
    {
        $projetos = Projeto::with(['tarefas' => function ($query) {
            $query->with('categoria')->orderByRaw('data_vencimento IS NULL, data_vencimento ASC');
        }])->get();
        return view('projetos.index')->with(['projetos' => $projetos]);
    }

    function create()
    {
        return view('projetos.create');
    }

    function validateForm(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'data_inicio' => 'required',
            'status' => 'required',
        ], [
            'nome.required' => "O :attribute é obrigatorio",
            'data_inicio.required' => "O :attribute é obrigatorio",
            'status.required' => "O :attribute é obrigatorio"
        ]);
    }

    function store(Request $request)
    {
        //dd($request->all());
        $this->validateForm($request);
        Projeto::create($request->all());
        return redirect('projetos')->with("success", 'Registro Salvo com sucesso!');
    }

    function show($id)
    {
        $projeto = Projeto::find($id);
        $projeto->load(['tarefas' => function ($query) {
            $query->with('categoria')->orderByRaw('data_vencimento IS NULL, data_vencimento ASC');
        }]);
        return view('projetos.show', compact('projeto'));
    }

    function edit($id)
    {
        $projeto = Projeto::find($id);
        return view('projetos.edit', compact('projeto'));
    }

    function update(Request $request, $id)
    {
        //dd($request->all());
        $this->validateForm($request);
        Projeto::find($id)->update($request->all());
        return redirect('projetos')->with("success", 'Registro Atualizado com sucesso!');
    }

    function destroy($id)
    {
        Projeto::destroy($id);
        return redirect('projetos')->with("success", 'Registro removido com sucesso!');
    }
}