@extends('layouts.app')

@section('title', $projeto->nome)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-3 fw-bold mb-0"><i class="bi bi-folder text-primary"></i> {{ $projeto->nome }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('projetos.edit', $projeto->id) }}" class="btn btn-outline-warning btn-sm">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('projetos.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="row">
    <!-- COLUNA ESQUERDA: INFORMAÇÕES DO PROJETO -->
    <div class="col-lg-8 mb-4">
        <div class="card card-shadow border-0 h-100">
            @if($projeto->imagem)
                <img src="{{ asset('storage/' . $projeto->imagem) }}" class="img-fluid rounded-top" style="max-height: 250px; width: 100%; object-fit: cover; opacity: 0.85;" alt="{{ $projeto->nome }}">
            @endif

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="card-title fw-bold mb-0">Detalhes do Projeto</h5>
                    <span class="badge bg-{{ $projeto->status == 'concluido' ? 'success' : ($projeto->status == 'cancelado' ? 'danger' : 'primary') }}">
                        {{ ucfirst(str_replace('_', ' ', $projeto->status)) }}
                    </span>
                </div>

                <p class="text-muted">{{ $projeto->descricao ?? 'Sem descrição.' }}</p>
                
                <div class="row mt-4 mb-3">
                    <div class="col-sm-4 mb-3 mb-sm-0">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Data de Início</small>
                        <span class="text-light"><i class="bi bi-calendar3 text-muted me-1"></i> {{ date('d/m/Y', strtotime($projeto->data_inicio)) }}</span>
                    </div>
                    <div class="col-sm-4 mb-3 mb-sm-0">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Data de Término</small>
                        <span class="text-light"><i class="bi bi-calendar-check text-muted me-1"></i> {{ $projeto->data_fim ? date('d/m/Y', strtotime($projeto->data_fim)) : 'Não definido' }}</span>
                    </div>
                    <div class="col-sm-4">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Orçamento</small>
                        <span class="text-light"><i class="bi bi-cash-stack text-muted me-1"></i> R$ {{ number_format($projeto->orcamento, 2, ',', '.') }}</span>
                    </div>
                </div>

                @if($projeto->cliente)
                    <div class="mt-3">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Cliente</small>
                        <span class="text-light"><i class="bi bi-briefcase text-muted me-1"></i> {{ $projeto->cliente }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- COLUNA DIREITA: EQUIPE -->
    <div class="col-lg-4 mb-4">
        @if($projeto->equipe)
            <div class="card card-shadow border-0 h-100">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill text-muted"></i>
                    <span class="fw-bold text-light">Equipe Responsável</span>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 border-bottom border-secondary">
                        <h6 class="mb-1 text-light">{{ $projeto->equipe->nome }}</h6>
                        @if($projeto->equipe->lider)
                            <small class="text-muted d-block"><i class="bi bi-person-badge me-1"></i> Líder: {{ $projeto->equipe->lider->name }}</small>
                            <small class="text-muted d-block"><i class="bi bi-envelope me-1"></i> {{ $projeto->equipe->lider->email }}</small>
                        @endif
                    </div>
                    
                    <div class="p-3">
                        <small class="text-muted text-uppercase fw-bold d-block mb-3" style="font-size: 0.7rem;">Integrantes & Foco</small>
                        <ul class="list-unstyled mb-0">
                            @foreach($projeto->equipe->integrantes as $integrante)
                                <li class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($integrante->imagem)
                                            <img src="{{ asset('storage/' . $integrante->imagem) }}" width="32" height="32" class="rounded-circle" style="object-fit:cover; border: 1px solid #333;">
                                        @else
                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:32px; height:32px; font-size: 0.8rem;">
                                                {{ strtoupper(substr($integrante->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <span class="text-light d-block lh-1" style="font-size: 0.9rem;">{{ $integrante->name }}</span>
                                        </div>
                                    </div>
                                    <!-- Badge de foco mais sutil, sem fundo vermelho berrante -->
                                    <span class="badge bg-dark border border-secondary text-light" title="Tempo total de foco neste projeto">
                                        <i class="bi bi-clock text-danger"></i> {{ $integrante->tempoFocadoNoProjeto($projeto->id) }}m
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- TAREFAS DO PROJETO (Mantido o visual limpo de tabela) -->
<div class="card card-shadow border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fs-6"><i class="bi bi-list-task text-primary"></i> Tarefas do Projeto</h5>
        <a href="{{ route('tarefas.create') }}" class="btn btn-outline-primary btn-sm border-0">
            <i class="bi bi-plus-lg"></i> Nova
        </a>
    </div>
    <div class="card-body p-0">
        @if($projeto->tarefas->isEmpty())
            <div class="p-4 text-center text-muted">
                <p class="mb-0">Nenhuma tarefa cadastrada neste projeto ainda.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap" style="background-color: transparent;">
                    <thead class="border-bottom border-secondary">
                        <tr>
                            <th class="text-center py-3" style="width: 50px;">Feito</th>
                            <th class="py-3">Tarefa</th>
                            <th class="py-3">Categoria</th>
                            <th class="py-3">Prioridade</th>
                            <th class="py-3">Vencimento</th>
                            <th class="py-3">Status</th>
                            <th class="text-end py-3 pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projeto->tarefas as $tarefa)
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
                                    <span class="badge" style="background-color: {{ $tarefa->categoria->cor }}; color: #fff;">
                                        {{ $tarefa->categoria->nome }}
                                    </span>
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
        @endif
    </div>
</div>
@endsection