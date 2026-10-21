@extends('layouts.app')
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
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>
            <span style="display:inline-block;width:16px;height:16px;background:{{ $equipe->cor }};border-radius:4px;vertical-align:middle;"></span>
            {{ $equipe->nome }}
        </h2>
        <a href="{{ route('equipes.index') }}" class="btn btn-secondary">Voltar</a>
    </div>


    {{-- Líder --}}
    <div class="card mb-4">
        <div class="card-header">Líder da Equipe</div>
        <div class="card-body d-flex align-items-center gap-3">
            @if($equipe->lider && $equipe->lider->imagem)
                <img src="{{ asset('storage/' . $equipe->lider->imagem) }}" alt="Foto" width="80" height="80" style="object-fit:cover;border-radius:50%;">
            @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width:80px;height:80px;border-radius:50%;">
                    {{ strtoupper(substr($equipe->lider->name ?? '?', 0, 1)) }}
                </div>
            @endif
            <div>
                <p class="mb-0"><strong>Nome:</strong> {{ $equipe->lider->name ?? '—' }}</p>
                <p class="mb-0"><strong>ID:</strong> #{{ $equipe->lider->id ?? '—' }}</p>
                <p class="mb-0"><strong>E-mail:</strong> {{ $equipe->lider->email ?? '—' }}</p>
                <p class="mb-0"><strong>Cargo:</strong> {{ $equipe->lider->cargo ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Integrantes --}}
    <div class="card mb-4">
        <div class="card-header">Integrantes ({{ $equipe->integrantes->count() }})</div>
        <div class="card-body">
            <div class="row">
                @forelse($equipe->integrantes as $membro)
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            @if($membro->imagem)
                                <img src="{{ asset('storage/' . $membro->imagem) }}" alt="Foto" width="60" height="60" style="object-fit:cover;border-radius:50%;">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width:60px;height:60px;border-radius:50%;">
                                    {{ strtoupper(substr($membro->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="mb-0"><strong>{{ $membro->name }}</strong></p>
                                <p class="mb-0 small text-muted">{{ $membro->email }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Nenhum integrante cadastrado.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Projetos --}}
    <div class="card">
        <div class="card-header">Projetos da Equipe ({{ $equipe->projetos->count() }})</div>
        <div class="card-body">
            @if($equipe->projetos->count())
                <ul class="mb-0">
                    @foreach($equipe->projetos as $projeto)
                        <li>{{ $projeto->nome }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted mb-0">Nenhum projeto atribuído.</p>
            @endif
        </div>
    </div>
</div>
@endsection