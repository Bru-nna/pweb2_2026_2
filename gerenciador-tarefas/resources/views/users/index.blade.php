@extends('layouts.app')

@section('title', 'Lista de Usuários')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-people"></i> Usuários</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Novo Usuário
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('users.search') }}" method="POST" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-4">
                <label for="tipo" class="form-label mb-1">Buscar por</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="name">Nome</option>
                    <option value="email">Email</option>
                    <option value="cargo">Cargo</option>
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

@if($users->isEmpty())
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Nenhum usuário cadastrado.
        <a href="{{ route('users.create') }}" class="alert-link">Crie o primeiro!</a>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Cargo</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            @if($user->imagem)
                                <img src="{{ asset('storage/' . $user->imagem) }}" 
                                     alt="Foto de {{ $user->name }}" 
                                     width="40" height="40" 
                                     style="object-fit: cover; border-radius: 50%;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-secondary text-white"
                                     style="width: 40px; height: 40px; border-radius: 50%; font-weight: bold;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->telefone ?? '-' }}</td>
                        <td>{{ $user->cargo ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $user->status == 'ativo' ? 'success' : 'danger' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Tem certeza que deseja excluir esse usuário?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection