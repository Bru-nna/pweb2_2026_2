@extends('layouts.app')

@section('title', 'Lista de Projetos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-folder"></i> Projetos</h1>
    <a href="{{ route('projetos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Novo Projeto
    </a>
</div>

@if($projetos->isEmpty())
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Nenhum projeto cadastrado.
        <a href="{{ route('projetos.create') }}" class="alert-link">Crie o primeiro!</a>
    </div>
@else
    <div class="row">
        @foreach($projetos as $projeto)
            <div class="col-md-6 col-lg-6 mb-4">
                <div class="card card-shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $projeto->nome }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($projeto->descricao, 100) }}
                        </p>

                        @if($projeto->cliente)
                            <p class="mb-1">
                                <strong>Cliente:</strong> {{ $projeto->cliente }}
                            </p>
                        @endif

                        @if($projeto->orcamento)
                            <p class="mb-1">
                                <strong>Orçamento:</strong> 
                                R$ {{ number_format($projeto->orcamento, 2, ',', '.') }}
                            </p>
                        @endif

                        @if($projeto->responsaveis)
                            <p class="mb-1">
                                <strong>Responsáveis:</strong> 
                                <span class="text-muted">{{ $projeto->responsaveis }}</span>
                            </p>
                        @endif

                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i> 
                                {{ date('d/m/Y', strtotime($projeto->data_inicio)) }}
                                @if($projeto->data_fim)
                                    - {{ date('d/m/Y', strtotime($projeto->data_fim)) }}
                                @endif
                            </small>
                            <span class="badge ms-2 bg-{{ $projeto->status == 'concluido' ? 'success' : ($projeto->status == 'cancelado' ? 'danger' : 'primary') }}">
                                {{ ucfirst(str_replace('_', ' ', $projeto->status)) }}
                            </span>
                        </div>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted fw-semibold"><i class="bi bi-list-task"></i> Tarefas</small><br>
                            <small class="text-muted">{{ $projeto->tarefas->count() }}</small>
                        </div>

                        @if($projeto->tarefas->isEmpty())
                            <small class="text-muted">Nenhuma tarefa ainda.</small>
                        @else
                            <ul class="list-unstyled small mb-0" style="max-height: 150px; overflow-y: auto;">
                                @foreach($projeto->tarefas as $tarefa)
                                    <li class="d-flex justify-content-between align-items-center py-1 border-bottom" data-tarefa-row="{{ $tarefa->id }}">
                                        <span class="d-flex align-items-center text-truncate" style="max-width: 65%;">
                                            <input type="checkbox"
                                                class="form-check-input me-2 tarefa-toggle-checkbox"
                                                data-tarefa-id="{{ $tarefa->id }}"
                                                data-toggle-url="{{ route('tarefas.toggle', $tarefa->id) }}"
                                                {{ $tarefa->status === 'concluida' ? 'checked' : '' }}>
                                            <span class="d-inline-block rounded-circle me-1" style="width:8px; height:8px; background-color: {{ $tarefa->categoria->cor }};"></span>
                                            <span data-tarefa-titulo="{{ $tarefa->id }}" class="{{ $tarefa->status === 'concluida' ? 'text-decoration-line-through text-muted' : '' }}">
                                                {{ $tarefa->titulo }}
                                            </span>
                                        </span>
                                        <span class="badge bg-{{ $tarefa->status == 'concluida' ? 'success' : ($tarefa->status == 'cancelada' ? 'danger' : ($tarefa->status == 'em_andamento' ? 'warning' : 'secondary')) }}" data-tarefa-status="{{ $tarefa->id }}">
                                            {{ ucfirst(str_replace('_', ' ', $tarefa->status)) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="mt-3">
                            <a href="{{ route('projetos.show', $projeto->id) }}" class="px-3 btn btn-sm btn-info">
                                <i class="bi bi-eye"> Visualizar</i>
                            </a>
                            <a href="{{ route('projetos.edit', $projeto->id) }}" class="px-3 btn btn-sm btn-warning">
                                <i class="bi bi-pencil"> Editar</i>
                            </a>
                            <form action="{{ route('projetos.destroy', $projeto->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">
                                    <i class="px-2 bi bi-trash"> Apagar</i>
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