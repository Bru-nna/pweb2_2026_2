@extends('layouts.app')

@section('title', 'Lista de Tarefas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-tasks"></i> Tarefas</h1>
    <a href="{{ route('tarefas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nova Tarefa
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('tarefas.search') }}" method="POST" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-4">
                <label for="tipo" class="form-label mb-1">Buscar por</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="titulo">Título</option>
                    <option value="status">Status</option>
                    <option value="prioridade">Prioridade</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="valor" class="form-label mb-1">Termo</label>
                <input type="text" name="valor" id="valor" class="form-control" placeholder="Digite para buscar...">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</div>

@if($tarefas->isEmpty())
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Nenhuma tarefa cadastrada.
        <a href="{{ route('tarefas.create') }}" class="alert-link">Crie a primeira!</a>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-center">Concluir</th>
                    <th>Título</th>
                    <th>Projeto</th>
                    <th>Categoria</th>
                    <th>Prioridade</th>
                    <th>Vencimento</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tarefas as $tarefa)
                    <tr data-tarefa-row="{{ $tarefa->id }}" class="{{ $tarefa->status === 'concluida' ? 'table-success' : '' }}">
                        <td class="text-center">
                            <input type="checkbox"
                                   class="form-check-input tarefa-toggle-checkbox"
                                   data-tarefa-id="{{ $tarefa->id }}"
                                   data-toggle-url="{{ route('tarefas.toggle', $tarefa->id) }}"
                                   {{ $tarefa->status === 'concluida' ? 'checked' : '' }}>
                        </td>
                        <td>
                            <span data-tarefa-titulo="{{ $tarefa->id }}" class="{{ $tarefa->status === 'concluida' ? 'text-decoration-line-through text-muted' : '' }}">
                                {{ $tarefa->titulo }}
                            </span>
                        </td>
                        <td>{{ $tarefa->projeto->nome }}</td>
                        <td>
                            <span class="badge" style="background-color: {{ $tarefa->categoria->cor }};">
                                {{ $tarefa->categoria->nome }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $tarefa->prioridade == 'urgente' ? 'danger' : ($tarefa->prioridade == 'alta' ? 'warning' : ($tarefa->prioridade == 'media' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($tarefa->prioridade) }}
                            </span>
                        </td>
                        <td>
                            @if($tarefa->data_vencimento)
                                {{ date('d/m/Y', strtotime($tarefa->data_vencimento)) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $tarefa->status == 'concluida' ? 'success' : ($tarefa->status == 'cancelada' ? 'danger' : ($tarefa->status == 'em_andamento' ? 'warning' : 'secondary')) }}" data-tarefa-status="{{ $tarefa->id }}">
                                {{ ucfirst(str_replace('_', ' ', $tarefa->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('tarefas.edit', $tarefa->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('tarefas.destroy', $tarefa->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection