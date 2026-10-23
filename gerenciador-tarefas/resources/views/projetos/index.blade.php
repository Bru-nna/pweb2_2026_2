@extends('layouts.app')

@section('title', 'Projetos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-3 fw-bold mb-0"><i class="bi bi-folder text-primary"></i> Projetos</h1>
    <a href="{{ route('projetos.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg"></i> Novo Projeto
    </a>
</div>

<!-- Busca minimalista -->
<div class="card mb-4 border-0" style="background-color: transparent;">
    <div class="card-body p-0">
        <form action="{{ route('projetos.search') }}" method="POST" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-4">
                <select name="tipo" id="tipo" class="form-select form-select-sm">
                    <option value="nome">Buscar por Nome</option>
                    <option value="cliente">Buscar por Cliente</option>
                    <option value="status">Buscar por Status</option>
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

@if($projetos->isEmpty())
    <div class="alert alert-dark border-secondary text-center py-5">
        <i class="bi bi-folder-x fs-1 text-muted d-block mb-3"></i>
        <h5 class="fw-normal">Nenhum projeto encontrado</h5>
        <a href="{{ route('projetos.create') }}" class="btn btn-outline-primary btn-sm mt-2">Criar primeiro projeto</a>
    </div>
@else
    <div class="row">
        @foreach($projetos as $projeto)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card card-shadow h-100 border-0">
                    <!-- Imagem de Capa -->
                    @if($projeto->imagem)
                        <div style="height: 140px; overflow: hidden; border-radius: 8px 8px 0 0; position: relative;">
                            <img src="{{ asset('storage/' . $projeto->imagem) }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8;" alt="{{ $projeto->nome }}">
                            <div style="position: absolute; bottom: 0; width: 100%; height: 50%; background: linear-gradient(to top, #1e1e24, transparent);"></div>
                        </div>
                    @else
                        <div style="height: 60px; background: linear-gradient(45deg, #2d2d35, #1e1e24); border-radius: 8px 8px 0 0;"></div>
                    @endif

                    <div class="card-body pt-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0 text-truncate" title="{{ $projeto->nome }}">{{ $projeto->nome }}</h5>
                            <span class="badge rounded-pill bg-{{ $projeto->status == 'concluido' ? 'success' : ($projeto->status == 'cancelado' ? 'danger' : 'primary') }}" style="font-size: 0.7rem;">
                                {{ ucfirst(str_replace('_', ' ', $projeto->status)) }}
                            </span>
                        </div>
                        
                        <p class="card-text text-muted small mb-3" style="height: 40px; overflow: hidden;">
                            {{ Str::limit($projeto->descricao, 80) ?? 'Sem descrição.' }}
                        </p>

                        <div class="d-flex align-items-center gap-2 mb-2 small text-muted">
                            <i class="bi bi-people-fill"></i>
                            <span>{{ $projeto->equipe->nome ?? 'Sem equipe' }}</span>
                        </div>
                        
                        <div class="d-flex align-items-center gap-2 mb-3 small text-muted">
                            <i class="bi bi-calendar3"></i>
                            <span>{{ date('d/m/Y', strtotime($projeto->data_inicio)) }} @if($projeto->data_fim) até {{ date('d/m/Y', strtotime($projeto->data_fim)) }} @endif</span>
                        </div>

                        <!-- Botões de Ação Unificados -->
                        <div class="d-flex justify-content-end gap-1 border-top border-secondary pt-3 mt-auto">
                            <a href="{{ route('projetos.relatorio', $projeto->id) }}" target="_blank" class="btn btn-sm btn-outline-light border-0" title="Gerar relatório (PDF)">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                            <a href="{{ route('projetos.show', $projeto->id) }}" class="btn btn-sm btn-outline-info border-0" title="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('projetos.edit', $projeto->id) }}" class="btn btn-sm btn-outline-warning border-0" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('projetos.destroy', $projeto->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Tem certeza que deseja apagar?')" title="Apagar">
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