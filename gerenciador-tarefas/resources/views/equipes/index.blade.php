@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Equipes</h2>
        <a href="{{ route('equipes.create') }}" class="btn btn-primary">Nova Equipe</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
            <p class="text-muted">Nenhuma equipe cadastrada.</p>
        @endforelse
    </div>
</div>
@endsection