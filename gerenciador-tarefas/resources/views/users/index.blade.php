@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-3 fw-bold mb-0"><i class="bi bi-people text-primary"></i> Usuários</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('users.relatorio') }}" target="_blank" class="btn btn-outline-success btn-sm px-3">
            <i class="bi bi-file-earmark-pdf"></i> Relatório
        </a>
        <a href="{{ route('users.grafico') }}" class="btn btn-outline-info btn-sm px-3">
            <i class="bi bi-bar-chart"></i> Gerar Gráfico
        </a>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm px-3">
            <i class="bi bi-plus-lg"></i> Novo Usuário
        </a>
    </div>
</div>

<div class="card mb-4 border-0" style="background-color: transparent;">
    <div class="card-body p-0">
        <form action="{{ route('users.search') }}" method="POST" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-4">
                <select name="tipo" id="tipo" class="form-select form-select-sm">
                    <option value="name">Buscar por Nome</option>
                    <option value="email">Buscar por Email</option>
                    <option value="cargo">Buscar por Cargo</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="valor" id="valor" class="form-control form-control-sm" placeholder="Digite o termo...">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</div>

@if($users->isEmpty())
    <div class="alert alert-dark border-secondary text-center py-5">
        <i class="bi bi-person-x fs-1 text-muted d-block mb-3"></i>
        <h5 class="fw-normal">Nenhum usuário encontrado</h5>
        <a href="{{ route('users.index') }}" class="text-decoration-none">Limpar busca</a>
    </div>
@else
    <div class="card border-0 card-shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap" style="background-color: transparent;">
                    <thead class="border-bottom border-secondary">
                        <tr>
                            <th class="py-3 ps-4" style="width: 60px;">Foto</th>
                            <th class="py-3">Nome / Email</th>
                            <th class="py-3">Telefone</th>
                            <th class="py-3">Cargo</th>
                            <th class="py-3">Status</th>
                            <th class="text-end py-3 pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="ps-4">
                                    @if($user->imagem)
                                        <img src="{{ asset('storage/' . $user->imagem) }}" alt="Foto" width="36" height="36" class="rounded-circle" style="object-fit: cover; border: 1px solid #333;">
                                    @else
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-light fw-medium d-block">{{ $user->name }}</span>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </td>
                                <td class="text-muted">{{ $user->telefone ?? '-' }}</td>
                                <td class="text-muted">{{ $user->cargo ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->status == 'ativo' ? 'success' : 'danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-outline-info border-0" title="Visualizar">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning border-0" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Tem certeza?')" title="Apagar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection