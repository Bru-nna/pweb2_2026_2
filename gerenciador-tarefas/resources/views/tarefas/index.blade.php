@extends('layouts.app')

@section('title', 'Tarefas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-3 fw-bold mb-0"><i class="bi bi-check2-square text-primary"></i> Minhas Tarefas</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('tarefas.grafico') }}" class="btn btn-outline-info btn-sm px-3">
            <i class="bi bi-pie-chart"></i> Gerar Gráfico
        </a>
        <a href="{{ route('tarefas.create') }}" class="btn btn-primary btn-sm px-3">
            <i class="bi bi-plus-lg"></i> Nova Tarefa
        </a>
    </div>
</div>

<!-- Busca minimalista -->
<div class="card mb-4 border-0" style="background-color: transparent;">
    <div class="card-body p-0">
        <form action="{{ route('tarefas.search') }}" method="POST" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-4">
                <select name="tipo" id="tipo" class="form-select form-select-sm">
                    <option value="titulo">Buscar por Título</option>
                    <option value="status">Buscar por Status</option>
                    <option value="prioridade">Buscar por Prioridade</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="valor" id="valor" class="form-control form-control-sm" placeholder="Digite o termo...">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</div>

@if($tarefas->isEmpty())
    <div class="alert alert-dark border-secondary text-center py-5">
        <i class="bi bi-check-circle fs-1 text-muted d-block mb-3"></i>
        <h5 class="fw-normal">Tudo limpo por aqui!</h5>
        <p class="text-muted small mb-3">Você não tem tarefas cadastradas ou nenhuma corresponde à busca.</p>
        <a href="{{ route('tarefas.create') }}" class="btn btn-outline-primary btn-sm">Criar nova tarefa</a>
    </div>
@else
    <div class="card border-0 card-shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap" style="background-color: transparent;">
                    <thead class="border-bottom border-secondary" style="border-bottom-width: 1px !important;">
                        <tr>
                            <th class="text-center py-3" style="width: 50px;">Feito</th>
                            <th class="py-3">Tarefa</th>
                            <th class="py-3">Projeto / Categoria</th>
                            <th class="py-3">Prioridade</th>
                            <th class="py-3">Vencimento</th>
                            <th class="py-3">Status</th>
                            <th class="text-end py-3 pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tarefas as $tarefa)
                            <tr data-tarefa-row="{{ $tarefa->id }}" class="{{ $tarefa->status === 'concluida' ? 'opacity-50' : '' }}">
                                
                                <td class="text-center">
                                    <input type="checkbox"
                                           class="form-check-input tarefa-toggle-checkbox fs-5 cursor-pointer"
                                           style="border-color: #555;"
                                           data-tarefa-id="{{ $tarefa->id }}"
                                           data-toggle-url="{{ route('tarefas.toggle', $tarefa->id) }}"
                                           {{ $tarefa->status === 'concluida' ? 'checked' : '' }}>
                                </td>
                                
                                <td>
                                    <span data-tarefa-titulo="{{ $tarefa->id }}" class="fw-medium {{ $tarefa->status === 'concluida' ? 'text-decoration-line-through text-muted' : 'text-light' }}">
                                        {{ $tarefa->titulo }}
                                    </span>
                                </td>
                                
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <small class="text-muted"><i class="bi bi-folder"></i> {{ $tarefa->projeto->nome }}</small>
                                        <span class="badge align-self-start" style="background-color: {{ $tarefa->categoria->cor }}; color: #fff;">
                                            {{ $tarefa->categoria->nome }}
                                        </span>
                                    </div>
                                </td>
                                
                                <td>
                                    <span class="badge bg-{{ $tarefa->prioridade == 'urgente' ? 'danger' : ($tarefa->prioridade == 'alta' ? 'warning' : ($tarefa->prioridade == 'media' ? 'info' : 'secondary')) }}">
                                        {{ ucfirst($tarefa->prioridade) }}
                                    </span>
                                </td>
                                
                                <td class="text-muted small">
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
                                
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-pomodoro-tarefa" 
                                                data-titulo="{{ $tarefa->titulo }}" 
                                                data-tarefa-id="{{ $tarefa->id }}"
                                                data-bs-toggle="modal" data-bs-target="#pomodoroModal"
                                                title="Focar nesta tarefa">
                                            <i class="bi bi-clock"></i>
                                        </button>
                                        
                                        <a href="{{ route('tarefas.edit', $tarefa->id) }}" class="btn btn-sm btn-outline-warning border-0" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('tarefas.destroy', $tarefa->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Tem certeza?')" title="Apagar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection