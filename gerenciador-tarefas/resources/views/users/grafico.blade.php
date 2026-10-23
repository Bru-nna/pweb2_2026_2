@extends('layouts.app')

@section('title', 'Gráfico de Usuários')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-3 fw-bold mb-0"><i class="bi bi-bar-chart text-primary"></i> Minutos Trabalhados por Usuário</h1>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

@if(count($nomes) === 0)
    <div class="alert alert-dark border-secondary text-center py-5">
        <i class="bi bi-bar-chart fs-1 text-muted d-block mb-3"></i>
        Nenhum usuário cadastrado para gerar o gráfico.
    </div>
@else
    @if($total === 0)
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Ainda não há minutos de foco registrados. Use o Pomodoro para começar a contabilizar.
        </div>
    @endif

    {{-- Fundo claro para o texto padrão do gráfico ficar legível no tema escuro --}}
    <div class="card border-0 card-shadow">
        <div class="card-body bg-white rounded p-3">
            {!! $grafico->container() !!}
        </div>
    </div>

    <p class="text-muted small mt-3 mb-0">
        Total de foco registrado: <strong class="text-light">{{ $total }} min</strong>
    </p>

    <script src="{{ $grafico->cdn() }}"></script>
    {{ $grafico->script() }}
@endif
@endsection
