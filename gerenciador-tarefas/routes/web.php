<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Rotas de autenticação (visitante)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Rotas protegidas (usuário precisa estar autenticado)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('projetos.index');
    });

    // Projetos
    Route::get('/projetos', [ProjetoController::class, 'index'])->name('projetos.index');
    Route::get('/projetos/create', [ProjetoController::class, 'create'])->name('projetos.create');
    Route::post('/projetos/store', [ProjetoController::class, 'store'])->name('projetos.store');
    Route::get('/projetos/edit/{id}', [ProjetoController::class, 'edit'])->name('projetos.edit');
    Route::put('/projetos/update/{id}', [ProjetoController::class, 'update'])->name('projetos.update');
    Route::delete('/projetos/{id}', [ProjetoController::class, 'destroy'])->name('projetos.destroy');
    Route::get('/projetos/{id}', [ProjetoController::class, 'show'])->name('projetos.show');

    // Categorias
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias/store', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/edit/{id}', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/update/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

    // Tarefas
    Route::get('/tarefas', [TarefaController::class, 'index'])->name('tarefas.index');
    Route::get('/tarefas/create', [TarefaController::class, 'create'])->name('tarefas.create');
    Route::post('/tarefas/store', [TarefaController::class, 'store'])->name('tarefas.store');
    Route::get('/tarefas/edit/{id}', [TarefaController::class, 'edit'])->name('tarefas.edit');
    Route::put('/tarefas/update/{id}', [TarefaController::class, 'update'])->name('tarefas.update');
    Route::delete('/tarefas/{id}', [TarefaController::class, 'destroy'])->name('tarefas.destroy');
    Route::post('/tarefas/search', [TarefaController::class, 'search'])->name('tarefas.search');
    Route::patch('/tarefas/{id}/toggle', [TarefaController::class, 'toggleStatus'])->name('tarefas.toggle');

    Route::resource('users', UserController::class);
});