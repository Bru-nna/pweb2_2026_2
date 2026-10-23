@extends('layouts.app')

@section('title', 'Gráfico de Tarefas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-3 fw-bold mb-0"><i class="bi bi-pie-chart text-primary"></i> Tarefas por Status</h1>
    <a href="{{ route('tarefas.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

@if($total === 0)
    <div class="alert alert-dark border-secondary text-center py-5">
        <i class="bi bi-pie-chart fs-1 text-muted d-block mb-3"></i>
        Nenhuma tarefa cadastrada para gerar o gráfico.
    </div>
@else
    <div class="row">
        <div class="col-lg-8 mb-4">
            {{-- Fundo claro para o texto padrão do gráfico ficar legível no tema escuro --}}
            <div class="card border-0 card-shadow">
                <div class="card-body bg-white rounded p-3">
                    {!! $grafico->container() !!}
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 card-shadow h-100">
                <div class="card-header">Resumo</div>
                <ul class="list-group list-group-flush">
                    @foreach($labels as $i => $rotulo)
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-secondary">
                            <span class="text-light">
                                <span class="d-inline-block rounded-circle me-2" style="width:10px; height:10px; background-color: {{ $cores[$i] }};"></span>
                                {{ $rotulo }}
                            </span>
                            <span class="badge bg-dark border border-secondary text-light">
                                {{ $dados[$i] }} ({{ round($dados[$i] / $total * 100) }}%)
                            </span>
                        </li>
                    @endforeach
                    <li class="list-group-item bg-transparent d-flex justify-content-between border-secondary">
                        <strong class="text-light">Total</strong>
                        <strong class="text-light">{{ $total }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script src="{{ $grafico->cdn() }}"></script>
    {{ $grafico->script() }}
@endif
@endsection
