@extends('layouts.app')

@section('title', 'Detalhes do Usuário')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-person"></i> {{ $user->name }}</h4>
    </div>
    <div class="card-body">
        <p><strong>ID:</strong> {{ $user->id }}</p>
        <p><strong>Nome:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Telefone:</strong> {{ $user->telefone ?? 'Não informado' }}</p>
        <p><strong>Cargo:</strong> {{ $user->cargo ?? 'Não informado' }}</p>
        <p>
            <strong>Status:</strong>
            <span class="badge bg-{{ $user->status == 'ativo' ? 'success' : 'danger' }}">
                {{ ucfirst($user->status) }}
            </span>
        </p>
        <p><strong>Criado em:</strong> {{ date('d/m/Y H:i', strtotime($user->created_at)) }}</p>
        <p><strong>Atualizado em:</strong> {{ date('d/m/Y H:i', strtotime($user->updated_at)) }}</p>

        <div class="mt-3">
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</div>
@endsection