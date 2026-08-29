<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TarefaController;

Route::get('/', function () {
    return redirect()->route('projetos.index');
});

Route::resource('projetos', ProjetoController::class);
Route::resource('categorias', CategoriaController::class);
Route::resource('tarefas', TarefaController::class);