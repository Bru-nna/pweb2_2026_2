@extends('layouts.app')

@section('title', 'Categorias')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-3 fw-bold mb-0"><i class="bi bi-tags text-primary"></i> Categorias</h1>
    <a href="{{ route('categorias.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg"></i> Nova Categoria
    </a>
</div>

@if($categorias->isEmpty())
    <div class="alert alert-dark border-secondary text-center py-5">
        <i class="bi bi-tags fs-1 text-muted d-block mb-3"></i>
        <h5 class="fw-normal">Nenhuma categoria cadastrada</h5>
        <a href="{{ route('categorias.create') }}" class="btn btn-outline-primary btn-sm mt-2">Criar primeira categoria</a>
    </div>
@else
    <div class="row">
        @foreach($categorias as $categoria)
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card card-shadow h-100 border-0">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="rounded-circle" style="width: 12px; height: 12px; background-color: {{ $categoria->cor }}; box-shadow: 0 0 6px {{ $categoria->cor }};"></span>
                            <h5 class="card-title fw-bold mb-0 text-light">{{ $categoria->nome }}</h5>
                        </div>
                        
                        <div class="mt-auto d-flex justify-content-end gap-1 border-top border-secondary pt-3">
                            <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-sm btn-outline-warning border-0" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="d-inline">
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