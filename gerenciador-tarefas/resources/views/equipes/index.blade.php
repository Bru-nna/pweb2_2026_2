@extends('layouts.app')

@section('title', 'Equipes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-3 fw-bold mb-0"><i class="bi bi-diagram-3 text-primary"></i> Equipes</h1>
    <a href="{{ route('equipes.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg"></i> Nova Equipe
    </a>
</div>

<!-- Busca minimalista -->
<div class="card mb-4 border-0" style="background-color: transparent;">
    <div class="card-body p-0">
        <form action="{{ route('equipes.search') }}" method="POST" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-4">
                <select name="tipo" id="tipo" class="form-select form-select-sm">
                    <option value="nome">Buscar por Nome</option>
                    <option value="lider">Buscar por Líder</option>
                    <option value="integrante">Buscar por Integrante</option>
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

@if($equipes->isEmpty())
    <div class="alert alert-dark border-secondary text-center py-5">
        <i class="bi bi-people fs-1 text-muted d-block mb-3"></i>
        <h5 class="fw-normal">Nenhuma equipe encontrada</h5>
        <a href="{{ route('equipes.index') }}" class="text-decoration-none">Limpar busca</a>
    </div>
@else
    <div class="row">
        @foreach($equipes as $equipe)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card card-shadow h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-inline-block rounded-circle" style="width: 14px; height: 14px; background-color: {{ $equipe->cor }}; box-shadow: 0 0 8px {{ $equipe->cor }};"></span>
                                <h5 class="card-title fw-bold mb-0">{{ $equipe->nome }}</h5>
                            </div>
                        </div>

                        <p class="mb-1 small text-muted"><i class="bi bi-person-badge"></i> Líder: <span class="text-light">{{ $equipe->lider->name ?? 'Sem líder' }}</span></p>
                        <p class="mb-3 small text-muted"><i class="bi bi-people"></i> Integrantes: <span class="text-light">{{ $equipe->integrantes->count() }}</span></p>

                        <div class="border-top border-secondary pt-3 mb-3">
                            <small class="text-muted text-uppercase fw-bold d-block mb-2" style="font-size: 0.7rem;">Projetos Ativos</small>
                            @if($equipe->projetos->count())
                                <ul class="list-unstyled small mb-0" style="max-height: 80px; overflow-y: auto;">
                                    @foreach($equipe->projetos as $projeto)
                                        <li class="text-truncate text-muted"><i class="bi bi-folder2 text-primary"></i> {{ $projeto->nome }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <small class="text-muted fst-italic">Nenhum projeto.</small>
                            @endif
                        </div>

                        <!-- Botões de Ação Unificados -->
                        <div class="d-flex justify-content-end gap-1 border-top border-secondary pt-3 mt-auto">
                            <a href="{{ route('equipes.show', $equipe->id) }}" class="btn btn-sm btn-outline-info border-0" title="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('equipes.edit', $equipe->id) }}" class="btn btn-sm btn-outline-warning border-0" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('equipes.destroy', $equipe->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Tem certeza?')" title="Apagar">
                                    <i class="bi bi-trash"></i>
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