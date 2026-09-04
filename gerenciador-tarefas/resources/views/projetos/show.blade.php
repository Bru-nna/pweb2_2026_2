@extends('layouts.app')

@section('title', $projeto->nome)

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-folder"></i> {{ $projeto->nome }}</h4>
    </div>
    <div class="card-body">
        <p><strong>Descrição:</strong> {{ $projeto->descricao ?? 'Sem descrição' }}</p>
        <p><strong>Data de Início:</strong> {{ date('d/m/Y', strtotime($projeto->data_inicio)) }}</p>
        @if($projeto->data_fim)
            <p><strong>Data de Término:</strong> {{ date('d/m/Y', strtotime($projeto->data_fim)) }}</p>
        @endif
        
        @if($projeto->responsaveis)
            <p><strong> Responsáveis:</strong> {{ $projeto->responsaveis }}</p>
        @endif

        @if($projeto->responsavel)
            <p><strong>Responsável:</strong> {{ $projeto->responsavel->name }} <small class="text-muted">({{ $projeto->responsavel->email }})</small></p>
        @endif

        @if($projeto->cliente)
            <p><strong> Cliente:</strong> {{ $projeto->cliente }}</p>
        @endif

        @if($projeto->orcamento)
            <p>
                <strong> Orçamento:</strong> 
                <span>R$ {{ number_format($projeto->orcamento, 2, ',', '.') }}</span>
            </p>
        @endif
        <p>
            <strong>Status:</strong>
            <span class="badge bg-{{ $projeto->status == 'concluido' ? 'success' : ($projeto->status == 'cancelado' ? 'danger' : 'primary') }}">
                {{ ucfirst(str_replace('_', ' ', $projeto->status)) }}
            </span>
        </p>
        <div class="mt-3">
            <a href="{{ route('projetos.edit', $projeto->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="{{ route('projetos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-list-task"></i> Tarefas deste projeto</h5>
        <a href="{{ route('tarefas.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Nova Tarefa
        </a>
    </div>
    <div class="card-body">
        @if($projeto->tarefas->isEmpty())
            <p class="text-muted mb-0">Nenhuma tarefa cadastrada para este projeto ainda.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">Concluir</th>
                            <th>Título</th>
                            <th>Categoria</th>
                            <th>Prioridade</th>
                            <th>Vencimento</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projeto->tarefas as $tarefa)
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
    </div>
</div>
@endsection