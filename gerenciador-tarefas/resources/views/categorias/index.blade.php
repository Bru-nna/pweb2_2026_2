@extends('layouts.app')

@section('title', 'Lista de Categorias')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-tags"></i> Categorias</h1>
    <a href="{{ route('categorias.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nova Categoria
    </a>
</div>

@if($categorias->isEmpty())
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Nenhuma categoria cadastrada.
        <a href="{{ route('categorias.create') }}" class="alert-link">Crie a primeira!</a>
    </div>
@else
    <div class="row">
        @foreach($categorias as $categoria)
            <div class="col-md-4 mb-3">
                <div class="card card-shadow">
                    <div class="card-body">
                        <h5 class="card-title">
                            <span class="badge" style="background-color: {{ $categoria->cor }}; color: white;">
                                {{ $categoria->nome }}
                            </span>
                        </h5>
                        <p class="card-text">{{ $categoria->descricao ?? 'Sem descrição' }}</p>
                        <div class="mt-2">
                            <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="d-inline">
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