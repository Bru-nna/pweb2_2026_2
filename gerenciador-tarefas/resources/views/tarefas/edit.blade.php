@extends('layouts.app')

@section('title', 'Editar Tarefa')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-pencil"></i> Editar Tarefa</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('tarefas.update', $tarefa->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="titulo" class="form-label">Título da Tarefa *</label>
                <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                       id="titulo" name="titulo" value="{{ old('titulo', $tarefa->titulo) }}" required>
                @error('titulo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                          id="descricao" name="descricao" rows="3">{{ old('descricao', $tarefa->descricao) }}</textarea>
                @error('descricao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="projeto_id" class="form-label">Projeto *</label>
                    <select class="form-select @error('projeto_id') is-invalid @enderror" id="projeto_id" name="projeto_id" required>
                        <option value="">Selecione...</option>
                        @foreach($projetos as $projeto)
                            <option value="{{ $projeto->id }}" {{ old('projeto_id', $tarefa->projeto_id) == $projeto->id ? 'selected' : '' }}>
                                {{ $projeto->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('projeto_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="categoria_id" class="form-label">Categoria *</label>
                    <select class="form-select @error('categoria_id') is-invalid @enderror" id="categoria_id" name="categoria_id" required>
                        <option value="">Selecione...</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" style="background-color: {{ $categoria->cor }}; color: white;" 
                                    {{ old('categoria_id', $tarefa->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="prioridade" class="form-label">Prioridade</label>
                    <select class="form-select @error('prioridade') is-invalid @enderror" id="prioridade" name="prioridade">
                        <option value="baixa" {{ old('prioridade', $tarefa->prioridade) == 'baixa' ? 'selected' : '' }}>Baixa</option>
                        <option value="media" {{ old('prioridade', $tarefa->prioridade) == 'media' ? 'selected' : '' }}>Média</option>
                        <option value="alta" {{ old('prioridade', $tarefa->prioridade) == 'alta' ? 'selected' : '' }}>Alta</option>
                        <option value="urgente" {{ old('prioridade', $tarefa->prioridade) == 'urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                    @error('prioridade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                    <input type="date" class="form-control @error('data_vencimento') is-invalid @enderror" 
                           id="data_vencimento" name="data_vencimento" value="{{ old('data_vencimento', $tarefa->data_vencimento) }}">
                    @error('data_vencimento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="pendente" {{ old('status', $tarefa->status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="em_andamento" {{ old('status', $tarefa->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="concluida" {{ old('status', $tarefa->status) == 'concluida' ? 'selected' : '' }}>Concluída</option>
                        <option value="cancelada" {{ old('status', $tarefa->status) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('tarefas.index') }}" class="btn btn-secondary">
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