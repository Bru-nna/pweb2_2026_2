@extends('layouts.app')

@section('title', 'Editar Projeto')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-pencil"></i> Editar Projeto</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('projetos.update', $projeto->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Projeto *</label>
                <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                       id="nome" name="nome" value="{{ old('nome', $projeto->nome) }}" required>
                @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                          id="descricao" name="descricao" rows="3">{{ old('descricao', $projeto->descricao) }}</textarea>
                @error('descricao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="responsaveis" class="form-label">Responsáveis</label>
                <input type="text" class="form-control @error('responsaveis') is-invalid @enderror" 
                    id="responsaveis" name="responsaveis" value="{{ old('responsaveis', $projeto->responsaveis) }}" 
                    placeholder="Ex: João Silva, Maria Santos">
                @error('responsaveis')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Separe os nomes por vírgula.</small>
            </div>

                        <div class="mb-3">
                <label for="responsavel_id" class="form-label">Responsável (Usuário) — relacionamento 1:1</label>
                <select class="form-select @error('responsavel_id') is-invalid @enderror"
                        id="responsavel_id" name="responsavel_id">
                    <option value="">Selecione um responsável...</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('responsavel_id', $projeto->responsavel_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('responsavel_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Cada projeto tem um único usuário responsável.</small>
            </div>

            <div class="mb-3">
                <label for="cliente" class="form-label">Cliente</label>
                <input type="text" class="form-control @error('cliente') is-invalid @enderror" 
                    id="cliente" name="cliente" value="{{ old('cliente', $projeto->cliente) }}" 
                    placeholder="Ex: Empresa ABC">
                @error('cliente')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="orcamento" class="form-label">Orçamento (R$)</label>
                <input type="number" step="0.01" class="form-control @error('orcamento') is-invalid @enderror" 
                    id="orcamento" name="orcamento" value="{{ old('orcamento', $projeto->orcamento) }}" 
                    placeholder="Ex: 15000.00">
                @error('orcamento')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="data_inicio" class="form-label">Data de Início *</label>
                    <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" 
                           id="data_inicio" name="data_inicio" value="{{ old('data_inicio', $projeto->data_inicio) }}" required>
                    @error('data_inicio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="data_fim" class="form-label">Data de Término</label>
                    <input type="date" class="form-control @error('data_fim') is-invalid @enderror" 
                           id="data_fim" name="data_fim" value="{{ old('data_fim', $projeto->data_fim) }}">
                    @error('data_fim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                    <option value="planejado" {{ old('status', $projeto->status) == 'planejado' ? 'selected' : '' }}>Planejado</option>
                    <option value="em_andamento" {{ old('status', $projeto->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                    <option value="concluido" {{ old('status', $projeto->status) == 'concluido' ? 'selected' : '' }}>Concluído</option>
                    <option value="cancelado" {{ old('status', $projeto->status) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('projetos.index') }}" class="btn btn-secondary">
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