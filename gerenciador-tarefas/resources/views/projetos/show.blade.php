@extends('layouts.app')

@section('title', $projeto->nome)

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-folder"></i> {{ $projeto->nome }}</h4>
    </div>
    <div class="card-body">
        <p><strong>Descrição:</strong> {{ $projeto->descricao ?? 'Sem descrição' }}</p>
        <p><strong>Data de Início:</strong> {{ date('d/m/Y', strtotime($projeto->data_inicio)) }}</p>
        @if($projeto->data_fim)
            <p><strong>Data de Término:</strong> {{ date('d/m/Y', strtotime($projeto->data_fim)) }}</p>
        @endif
        <p>
            <strong>Status:</strong>
            <span class="badge bg-{{ $projeto->status == 'concluido' ? 'success' : ($projeto->status == 'cancelado' ? 'danger' : 'primary') }}">
                {{ ucfirst(str_replace('_', ' ', $projeto->status)) }}
            </span>
        </p>
        <p><strong>Criado em:</strong> {{ date('d/m/Y H:i', strtotime($projeto->created_at)) }}</p>
        <p><strong>Atualizado em:</strong> {{ date('d/m/Y H:i', strtotime($projeto->updated_at)) }}</p>
        
        <div class="mt-3">
            <a href="{{ route('projetos.edit', $projeto->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="{{ route('projetos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</div>
@endsection