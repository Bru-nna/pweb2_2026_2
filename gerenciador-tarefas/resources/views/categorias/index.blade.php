@extends('layouts.app')

@section('title', 'Lista de Categorias')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 p-4 rounded ">
    <h1><i class="bi bi-tags"></i> Categorias</h1>
    <a href="{{ route('categorias.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nova Categoria
    </a>
</div>

@if($categorias->isEmpty())
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Nenhuma categoria cadastrada.
        <a href="{{ route('categorias.create') }}" class="alert-link">Crie a primeira!</a>
    </div>
@else
    <div class="row">
        @foreach($categorias as $categoria)
            <div class="col-md-4 mb-3">
                <div class="card card-shadow">
                    <div class="card-body">
                        <h5 class="card-title">
                            <span class="shadow-sm badge" style="background-color: {{ $categoria->cor }}; color: white;">
                                {{ $categoria->nome }}
                            </span>
                        </h5>
                        <p class="card-text">{{ $categoria->descricao ?? 'Sem descrição' }}</p>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted fw-semibold"><i class="bi bi-list-task"></i> Tarefas</small>
                            <small class="text-muted">{{ $categoria->tarefas->count() }}</small>
                        </div>

                        @if($categoria->tarefas->isEmpty())
                            <small class="text-muted">Nenhuma tarefa ainda.</small>
                        @else
                            <ul class="list-unstyled small mb-0" style="max-height: 150px; overflow-y: auto;">
                                @foreach($categoria->tarefas as $tarefa)
                                    <li class="py-1 border-bottom" data-tarefa-row="{{ $tarefa->id }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="d-flex align-items-center text-truncate" style="max-width: 65%;">
                                                <input type="checkbox"
                                                       class="form-check-input me-2 tarefa-toggle-checkbox"
                                                       data-tarefa-id="{{ $tarefa->id }}"
                                                       data-toggle-url="{{ route('tarefas.toggle', $tarefa->id) }}"
                                                       {{ $tarefa->status === 'concluida' ? 'checked' : '' }}>
                                                <span data-tarefa-titulo="{{ $tarefa->id }}" class="{{ $tarefa->status === 'concluida' ? 'text-decoration-line-through text-muted' : '' }}">
                                                    {{ $tarefa->titulo }}
                                                </span>
                                            </span>
                                            <span class="badge bg-{{ $tarefa->status == 'concluida' ? 'success' : ($tarefa->status == 'cancelada' ? 'danger' : ($tarefa->status == 'em_andamento' ? 'warning' : 'secondary')) }}" data-tarefa-status="{{ $tarefa->id }}">
                                                {{ ucfirst(str_replace('_', ' ', $tarefa->status)) }}
                                            </span>
                                        </div>
                                        <small class="text-muted"><i class="bi bi-folder"></i> {{ $tarefa->projeto->nome }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="mt-2">
                            <a href="{{ route('categorias.edit', $categoria->id) }}" class="px-3 btn btn-sm btn-warning">
                                <i class="bi bi-pencil"> Editar</i>
                            </a>
                            <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger px-3" onclick="return confirm('Tem certeza?')">
                                    <i class="bi bi-trash"> Apagar</i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection