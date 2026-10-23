<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Usuários</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .sub { color: #666; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #2c3e50; color: #fff; text-align: left; padding: 8px; }
        td { padding: 7px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) td { background: #f6f6f6; }
        .center { text-align: center; }
        .total td { font-weight: bold; border-top: 2px solid #2c3e50; background: #eee; }
    </style>
</head>
<body>
    <h1>Relatório de Usuários</h1>
    <div class="sub">Gerado em {{ $geradoEm->format('d/m/Y H:i') }}</div>

    @php $totalMin = 0; @endphp

    <table>
        <thead>
            <tr>
                <th>Usuário</th>
                <th>Cargo</th>
                <th>Tempo total de foco</th>
                <th class="center">Projetos</th>
                <th class="center">Equipes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                @php
                    $min = (int) $user->registros_pomodoros_sum_tempo_foco_minutos;
                    $totalMin += $min;
                @endphp
                <tr>
                    <td>{{ $user->name }}<br><small style="color:#777">{{ $user->email }}</small></td>
                    <td>{{ $user->cargo ?? '-' }}</td>
                    <td>{{ intdiv($min, 60) }}h {{ str_pad($min % 60, 2, '0', STR_PAD_LEFT) }}min</td>
                    <td class="center">{{ $user->projetos_count }}</td>
                    <td class="center">{{ $user->equipes_count }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="2">Total geral</td>
                <td colspan="3">{{ intdiv($totalMin, 60) }}h {{ str_pad($totalMin % 60, 2, '0', STR_PAD_LEFT) }}min</td>
            </tr>
        </tbody>
    </table>
</body>
</html>