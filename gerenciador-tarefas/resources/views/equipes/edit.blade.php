@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Equipe — {{ $equipe->nome }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('equipes.update', $equipe->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nome da Equipe</label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome', $equipe->nome) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Cor da Equipe</label>
            <input type="color" name="cor" class="form-control form-control-color" value="{{ old('cor', $equipe->cor) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Líder</label>
            <select name="lider_id" class="form-select" required>
                <option value="">-- Selecione --</option>
                @foreach($usuarios as $u)
                    <option value="{{ $u->id }}" @selected(old('lider_id', $equipe->lider_id) == $u->id)>
                        {{ $u->name }} ({{ $u->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Integrantes (segure Ctrl/Cmd para múltiplos)</label>
            @php $selecionados = old('integrantes', $equipe->integrantes->pluck('id')->toArray()); @endphp
            <select name="integrantes[]" class="form-select" multiple size="8" required>
                @foreach($usuarios as $u)
                    <option value="{{ $u->id }}" @selected(in_array($u->id, $selecionados))>
                        {{ $u->name }} ({{ $u->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Atualizar</button>
        <a href="{{ route('equipes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection