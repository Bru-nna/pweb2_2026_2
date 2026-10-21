@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Nova Equipe</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('equipes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nome da Equipe</label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Cor da Equipe</label>
            <input type="color" name="cor" class="form-control form-control-color" value="{{ old('cor', '#3357FF') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Líder</label>
            <select name="lider_id" class="form-select" required>
                <option value="">-- Selecione --</option>
                @foreach($usuarios as $u)
                    <option value="{{ $u->id }}" @selected(old('lider_id') == $u->id)>
                        {{ $u->name }} ({{ $u->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Integrantes (segure Ctrl/Cmd para múltiplos)</label>
            <select name="integrantes[]" class="form-select" multiple size="8" required>
                @foreach($usuarios as $u)
                    <option value="{{ $u->id }}" @selected(in_array($u->id, old('integrantes', [])))>
                        {{ $u->name }} ({{ $u->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Salvar</button>
        <a href="{{ route('equipes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection