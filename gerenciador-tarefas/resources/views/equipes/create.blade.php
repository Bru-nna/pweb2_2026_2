@extends('layouts.app')

@section('title', 'Nova Equipe')

@section('content')
<div class="card card-shadow border-0 mx-auto" style="max-width: 800px;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fs-6"><i class="bi bi-plus-circle text-primary"></i> Nova Equipe</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('equipes.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="nome" class="form-label text-muted small fw-bold">Nome da Equipe *</label>
                    <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome') }}" required placeholder="Ex: Desenvolvimento Backend">
                    @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="cor" class="form-label text-muted small fw-bold">Cor da Equipe *</label>
                    <input type="color" class="form-control form-control-color w-100 @error('cor') is-invalid @enderror" id="cor" name="cor" value="{{ old('cor', '#6c757d') }}" required>
                    @error('cor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="lider_id" class="form-label text-muted small fw-bold">Líder *</label>
                <select class="form-select @error('lider_id') is-invalid @enderror" id="lider_id" name="lider_id" required>
                    <option value="">Selecione um líder...</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}" {{ old('lider_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
                @error('lider_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="integrantes" class="form-label text-muted small fw-bold">Integrantes *</label>
                <select class="form-select @error('integrantes') is-invalid @enderror" id="integrantes" name="integrantes[]" multiple size="6" required>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}" {{ in_array($u->id, old('integrantes', [])) ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
                <small class="text-muted d-block mt-1">Segure <kbd class="bg-secondary">Ctrl</kbd> ou <kbd class="bg-secondary">Cmd</kbd> para selecionar mais de um.</small>
                @error('integrantes') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between border-top border-secondary pt-3">
                <a href="{{ route('equipes.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg"></i> Salvar Equipe</button>
            </div>
        </form>
    </div>
</div>
@endsection