@extends('layouts.app')

@section('title', 'Editar Categoria')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-pencil"></i> Editar Categoria</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('categorias.update', $categoria->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="nome" class="form-label">Nome da Categoria *</label>
                <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                       id="nome" name="nome" value="{{ old('nome', $categoria->nome) }}" required>
                @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                          id="descricao" name="descricao" rows="3">{{ old('descricao', $categoria->descricao) }}</textarea>
                @error('descricao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="cor" class="form-label">Cor (hexadecimal)</label>
                <input type="color" class="form-control @error('cor') is-invalid @enderror" 
                       id="cor" name="cor" value="{{ old('cor', $categoria->cor) }}" style="height: 50px;">
                @error('cor')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Atualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection