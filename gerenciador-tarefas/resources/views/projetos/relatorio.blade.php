<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório - {{ $projeto->nome }}</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 11px; color: #222; margin: 0; }
        h1 { font-size: 20px; margin: 0 0 4px 0; }
        h2 { font-size: 14px; margin: 22px 0 8px 0; padding-bottom: 4px; border-bottom: 2px solid #333; }
        .sub { color: #666; font-size: 10px; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #333; color: #fff; text-align: left; padding: 6px; font-size: 10px; }
        td { padding: 6px; border-bottom: 1px solid #ddd; vertical-align: top; }
        tr.zebra td { background: #f6f6f6; }
        .info td { border: none; padding: 3px 0; }
        .info td.rotulo { width: 120px; color: #666; font-weight: bold; }
        .badge { padding: 2px 6px; border-radius: 8px; color: #fff; font-size: 9px; }
        .b-concluida { background: #198754; }
        .b-em_andamento { background: #e0a800; color: #222; }
        .b-pendente { background: #6c757d; }
        .b-cancelada { background: #dc3545; }
        .resumo td { border: none; text-align: center; padding: 6px; }
        .resumo .num { font-size: 18px; font-weight: bold; }
        .right { text-align: right; }
        .muted { color: #777; }
        .total td { font-weight: bold; border-top: 2px solid #333; border-bottom: none; }
        .rodape { position: fixed; bottom: -10px; left: 0; right: 0; text-align: center; font-size: 9px; color: #888; }
    </style>
</head>
<body>
@php
    $rotulos = [
        'concluida' => 'Concluída',
        'em_andamento' => 'Em andamento',
        'pendente' => 'Pendente',
        'cancelada' => 'Cancelada',
    ];
    $fmt = function ($m) {
        $m = (int) $m;
        return $m . ' min' . ($m >= 60 ? ' (' . intdiv($m, 60) . 'h' . str_pad($m % 60, 2, '0', STR_PAD_LEFT) . ')' : '');
    };
    $totalFoco = (int) $foco->sum();
@endphp

<div class="rodape">Relatório gerado em {{ now()->format('d/m/Y H:i') }} — Gerenciador de Tarefas</div>

<h1>Relatório do Projeto</h1>
<div class="sub">{{ $projeto->nome }}</div>

<h2>Dados do projeto</h2>
<table class="info">
    <tr><td class="rotulo">Status</td><td>{{ ucfirst(str_replace('_', ' ', $projeto->status)) }}</td></tr>
    @if($projeto->cliente)
        <tr><td class="rotulo">Cliente</td><td>{{ $projeto->cliente }}</td></tr>
    @endif
    <tr>
        <td class="rotulo">Período</td>
        <td>
            {{ date('d/m/Y', strtotime($projeto->data_inicio)) }}
            @if($projeto->data_fim) até {{ date('d/m/Y', strtotime($projeto->data_fim)) }} @endif
        </td>
    </tr>
    <tr>
        <td class="rotulo">Equipe responsável</td>
        <td>
            {{ $projeto->equipe->nome ?? 'Sem equipe' }}
            @if($projeto->equipe && $projeto->equipe->lider)
                <span class="muted">(líder: {{ $projeto->equipe->lider->name }})</span>
            @endif
        </td>
    </tr>
    @if($projeto->descricao)
        <tr><td class="rotulo">Descrição</td><td>{{ $projeto->descricao }}</td></tr>
    @endif
</table>

<h2>Tarefas ({{ $projeto->tarefas->count() }})</h2>

<table class="resumo" style="margin-bottom: 10px;">
    <tr>
        @foreach($rotulos as $chave => $rotulo)
            <td>
                <div class="num">{{ $resumoStatus[$chave] ?? 0 }}</div>
                <span class="badge b-{{ $chave }}">{{ $rotulo }}</span>
            </td>
        @endforeach
    </tr>
</table>

@if($projeto->tarefas->isEmpty())
    <p class="muted">Nenhuma tarefa cadastrada neste projeto.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Tarefa</th>
                <th>Categoria</th>
                <th>Prioridade</th>
                <th>Vencimento</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projeto->tarefas as $tarefa)
                <tr class="{{ $loop->even ? 'zebra' : '' }}">
                    <td>{{ $tarefa->titulo }}</td>
                    <td>{{ $tarefa->categoria->nome ?? '-' }}</td>
                    <td>{{ ucfirst($tarefa->prioridade) }}</td>
                    <td>{{ $tarefa->data_vencimento ? date('d/m/Y', strtotime($tarefa->data_vencimento)) : '-' }}</td>
                    <td><span class="badge b-{{ $tarefa->status }}">{{ $rotulos[$tarefa->status] ?? $tarefa->status }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<h2>Membros da equipe e tempo de foco</h2>

@if($membros->isEmpty() && $outros->isEmpty())
    <p class="muted">Este projeto não tem equipe nem tempo de foco registrado.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Membro</th>
                <th>Cargo</th>
                <th class="right">Tempo trabalhado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($membros as $membro)
                <tr class="{{ $loop->even ? 'zebra' : '' }}">
                    <td>
                        {{ $membro->name }}
                        @if($projeto->equipe && $projeto->equipe->lider_id === $membro->id)
                            <span class="muted">(líder)</span>
                        @endif
                    </td>
                    <td>{{ $membro->cargo ?? '-' }}</td>
                    <td class="right">{{ $fmt($foco[$membro->id] ?? 0) }}</td>
                </tr>
            @endforeach
            @foreach($outros as $outro)
                <tr>
                    <td>{{ $outro->name }} <span class="muted">(fora da equipe)</span></td>
                    <td>{{ $outro->cargo ?? '-' }}</td>
                    <td class="right">{{ $fmt($foco[$outro->id] ?? 0) }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="2">Total de foco no projeto</td>
                <td class="right">{{ $fmt($totalFoco) }}</td>
            </tr>
        </tbody>
    </table>
@endif

</body>
</html>
