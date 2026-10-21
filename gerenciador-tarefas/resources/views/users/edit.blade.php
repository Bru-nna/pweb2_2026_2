@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-pencil"></i> Editar Usuário: {{ $user->name }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nome *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="imagem" class="form-label">Foto de Perfil</label>

                @if($user->imagem)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $user->imagem) }}" 
                             alt="Foto de {{ $user->name }}" 
                             width="90" height="90" 
                             style="object-fit: cover; border-radius: 50%;">
                    </div>
                @endif

                <input type="file" class="form-control @error('imagem') is-invalid @enderror" 
                       id="imagem" name="imagem" accept="image/*">
                <small class="text-muted">Deixe em branco para manter a atual. JPG, PNG ou WEBP — máx. 2MB.</small>
                @error('imagem')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Nova Senha (deixe em branco para manter a atual)</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                <input type="password" class="form-control" 
                       id="password_confirmation" name="password_confirmation">
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                       id="telefone" name="telefone" value="{{ old('telefone', $user->telefone) }}">
                @error('telefone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="cargo" class="form-label">Cargo</label>
                <input type="text" class="form-control @error('cargo') is-invalid @enderror" 
                       id="cargo" name="cargo" value="{{ old('cargo', $user->cargo) }}">
                @error('cargo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                    <option value="ativo" {{ old('status', $user->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="inativo" {{ old('status', $user->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
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