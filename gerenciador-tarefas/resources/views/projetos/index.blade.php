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
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card card-shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $projeto->nome }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($projeto->descricao, 100) }}
                        </p>
                        <div class="mb-2">
                            <span class="badge bg-{{ $projeto->status == 'concluido' ? 'success' : ($projeto->status == 'cancelado' ? 'danger' : 'primary') }}">
                                {{ ucfirst(str_replace('_', ' ', $projeto->status)) }}
                            </span>
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i> 
                                {{ date('d/m/Y', strtotime($projeto->data_inicio)) }}
                                @if($projeto->data_fim)
                                    - {{ date('d/m/Y', strtotime($projeto->data_fim)) }}
                                @endif
                            </small>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('projetos.show', $projeto->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('projetos.edit', $projeto->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('projetos.destroy', $projeto->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">
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