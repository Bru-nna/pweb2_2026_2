@extends('layouts.app')

@section('title', 'Editar Categoria')

@section('content')
<div class="card card-shadow border-0 mx-auto" style="max-width: 500px;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fs-6"><i class="bi bi-pencil text-primary"></i> Editar Categoria</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('categorias.update', $categoria->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nome" class="form-label text-muted small fw-bold">Nome da Categoria *</label>
                <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome', $categoria->nome) }}" required>
                @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="cor" class="form-label text-muted small fw-bold">Cor de Identificação *</label>
                <input type="color" class="form-control form-control-color w-100 @error('cor') is-invalid @enderror" id="cor" name="cor" value="{{ old('cor', $categoria->cor) }}" required>
                @error('cor') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between border-top border-secondary pt-3">
                <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg"></i> Atualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection