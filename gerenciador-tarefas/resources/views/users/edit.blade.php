@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="card card-shadow border-0 mx-auto" style="max-width: 800px;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fs-6"><i class="bi bi-pencil text-primary"></i> Editar Usuário</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label text-muted small fw-bold">Nome *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label text-muted small fw-bold">Email *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label text-muted small fw-bold">Nova Senha (opcional)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label text-muted small fw-bold">Confirmar Nova Senha</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label text-muted small fw-bold">Telefone</label>
                    <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" value="{{ old('telefone', $user->telefone) }}">
                    @error('telefone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cargo" class="form-label text-muted small fw-bold">Cargo</label>
                    <input type="text" class="form-control @error('cargo') is-invalid @enderror" id="cargo" name="cargo" value="{{ old('cargo', $user->cargo) }}">
                    @error('cargo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="imagem" class="form-label text-muted small fw-bold">Foto de Perfil</label>
                    @if($user->imagem)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $user->imagem) }}" width="60" height="60" class="rounded-circle" style="object-fit: cover; border: 1px solid #333;">
                        </div>
                    @endif
                    <input type="file" class="form-control @error('imagem') is-invalid @enderror" id="imagem" name="imagem" accept="image/*">
                    <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Deixe vazio para manter a atual.</small>
                    @error('imagem') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-4">
                    <label for="status" class="form-label text-muted small fw-bold">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="ativo" {{ old('status', $user->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                        <option value="inativo" {{ old('status', $user->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between border-top border-secondary pt-3">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg"></i> Atualizar Usuário</button>
            </div>
        </form>
    </div>
</div>
@endsection