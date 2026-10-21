@extends('layouts.app')

@section('title', 'Lista de Equipes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-diagram-3"></i> Equipes</h1>
    <a href="{{ route('equipes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nova Equipe
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- CARD DE BUSCA ADICIONADO AQUI -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('equipes.search') }}" method="POST" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-4">
                <label for="tipo" class="form-label mb-1">Buscar por</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="nome">Nome da Equipe</option>
                    <option value="lider">Nome do Líder</option>
                    <option value="integrante">Nome do Integrante</option>
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

<div class="row">
    @forelse($equipes as $equipe)
        <div class="col-md-4 mb-3">
            <div class="card" style="border-left: 6px solid {{ $equipe->cor }}">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ route('equipes.show', $equipe->id) }}" class="text-decoration-none">
                            {{ $equipe->nome }}
                        </a>
                    </h5>
                    <p class="mb-1"><strong>Líder:</strong> {{ $equipe->lider->name ?? '—' }}</p>
                    <p class="mb-1"><strong>Integrantes:</strong> {{ $equipe->integrantes->count() }}</p>

                    <hr>
                    <p class="mb-1"><strong>Projetos:</strong></p>
                    @if($equipe->projetos->count())
                        <ul class="mb-2">
                            @foreach($equipe->projetos as $projeto)
                                <li>{{ $projeto->nome }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted small">Nenhum projeto atribuído.</p>
                    @endif

                    <div class="d-flex gap-1 mt-2">
                        <a href="{{ route('equipes.show', $equipe->id) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('equipes.edit', $equipe->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('equipes.destroy', $equipe->id) }}" method="POST" onsubmit="return confirm('Excluir?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Nenhuma equipe cadastrada ou encontrada.
            </div>
        </div>
    @endforelse
</div>
@endsection