<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::with(['tarefas' => function ($query) {
            $query->with('projeto')->orderByRaw('data_vencimento IS NULL, data_vencimento ASC');
        }])->get();
        return view('categorias.index')->with(['categorias' => $categorias]);
    }

    function create()
    {
        return view('categorias.create');
    }

    function validateForm(Request $request)
    {
        $request->validate([
            'nome' => 'required',
        ], [
            'nome.required' => "O :attribute é obrigatorio",
        ]);
    }

    function store(Request $request)
    {
        //dd($request->all());
        $this->validateForm($request);
        Categoria::create($request->all());
        return redirect('categorias')->with("success", 'Registro Salvo com sucesso!');
    }

    function edit($id)
    {
        $categoria = Categoria::find($id);
        return view('categorias.edit', compact('categoria'));
    }

    function update(Request $request, $id)
    {
        //dd($request->all());
        $this->validateForm($request);
        Categoria::find($id)->update($request->all());
        return redirect('categorias')->with("success", 'Registro Atualizado com sucesso!');
    }

    function destroy($id)
    {
        Categoria::destroy($id);
        return redirect('categorias')->with("success", 'Registro removido com sucesso!');
    }

        public function search(Request $request)
    {
        $query = Categoria::with(['tarefas' => function ($q) {
            $q->with('projeto')->orderByRaw('data_vencimento IS NULL, data_vencimento ASC');
        }]);

        if (!empty($request->valor)) {
            $query->where($request->tipo, 'like', "%{$request->valor}%");
        }

        $categorias = $query->get();
        return view('categorias.index', compact('categorias'));
    }
}