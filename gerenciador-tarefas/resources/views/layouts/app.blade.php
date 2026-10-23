<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gerenciador de Tarefas')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* Variáveis de Cor do Sistema */
        :root {
            --bg-body: #121214;       /* Fundo geral da tela */
            --bg-card: #1e1e24;       /* Fundo dos cards */
            --bg-navbar: #18181b;     /* Fundo do menu superior */
            --border-color: #2d2d35;  /* Cor das bordas */
            --text-main: #e1e1e6;     /* Cor do texto principal */
            --text-muted: #8d8d99;    /* Cor do texto secundário */
        }

        body {
            background-color: var(--bg-body) !important;
            color: var(--text-main);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .navbar {
            background-color: var(--bg-navbar) !important;
            border-bottom: 1px solid var(--border-color);
        }
        
        .navbar-brand { font-weight: 600; letter-spacing: 0.5px; }

        .card {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-main);
        }
        
        .card-header {
            border-bottom: 1px solid var(--border-color);
            background-color: transparent !important;
            font-weight: 500;
        }

        .card-shadow {
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-shadow:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.4);
        }

        /* Inputs e Selects mais discretos */
        .form-control, .form-select {
            background-color: #121214 !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-main) !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: #555 !important;
            box-shadow: 0 0 0 0.25rem rgba(255,255,255,0.05) !important;
        }

        /* Textos secundários */
        .text-muted { color: var(--text-muted) !important; }
        hr { border-color: var(--border-color); }

        /* Estilos do Pomodoro */
        .timer-display { font-size: 4rem; font-weight: bold; font-variant-numeric: tabular-nums; color: var(--text-main); }
        .timer-focus { color: #ff6b6b; } /* Vermelho suave */
        .timer-break { color: #4dabf7; } /* Azul suave */
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('projetos.index') }}">
                <i class="bi bi-check2-square text-primary"></i> Gerenciador de Tarefas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('projetos.index') }}"><i class="bi bi-folder"></i> Projetos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('tarefas.index') }}"><i class="bi bi-tasks"></i> Tarefas</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('categorias.index') }}"><i class="bi bi-tags"></i> Categorias</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}"><i class="bi bi-people"></i> Usuários</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('equipes.index') }}"><i class="bi bi-diagram-3"></i> Equipes</a></li>
                    
                    <li class="nav-item me-3">
                        <button class="btn btn-outline-secondary btn-sm" id="btn-pomodoro-global" data-bs-toggle="modal" data-bs-target="#pomodoroModal">
                            <i class="bi bi-clock-history"></i> Modo Foco
                        </button>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Sair</button></form></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0" style="background-color: #1e3a29; color: #75d192;">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0" style="background-color: #3a1e1e; color: #f28b82;">
                    <i class="bi bi-exclamation-triangle-fill"></i> Por favor, corrija os erros abaixo.
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- MODAL DO POMODORO -->
    <div class="modal fade" id="pomodoroModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="modal-header border-0">
                    <h5 class="modal-title"><i class="bi bi-hourglass-split text-danger"></i> Pomodoro</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    
                    <div id="pomodoro-config">
                        <p class="small text-muted mb-3">
                            <i class="bi bi-person-circle"></i> Foco de: <strong class="text-light">{{ auth()->user()->name }}</strong>
                        </p>

                        <div class="mb-3 text-start">
                            <label for="config-tarefa" class="form-label small text-muted">Tarefa</label>
                            <select class="form-select" id="config-tarefa">
                                <option value="">Sem tarefa (não conta em nenhum projeto)</option>
                                @foreach(($tarefasPomodoro ?? []) as $tarefaOpcao)
                                    <option value="{{ $tarefaOpcao->id }}">
                                        {{ $tarefaOpcao->titulo }}{{ $tarefaOpcao->projeto ? ' — ' . $tarefaOpcao->projeto->nome : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <label class="form-label small text-muted">Foco (min)</label>
                                <input type="number" class="form-control text-center" id="config-foco" value="25" min="1">
                            </div>
                            <div class="col-4">
                                <label class="form-label small text-muted">Pausa (min)</label>
                                <input type="number" class="form-control text-center" id="config-pausa" value="5" min="1">
                            </div>
                            <div class="col-4">
                                <label class="form-label small text-muted">Ciclos</label>
                                <input type="number" class="form-control text-center" id="config-ciclos" value="4" min="1">
                            </div>
                        </div>
                        <button class="btn btn-danger w-100 mt-3" id="btn-iniciar-pomodoro" style="background-color: #d9534f; border: none;">
                            <i class="bi bi-play-fill"></i> Iniciar Foco
                        </button>
                    </div>

                    <div id="pomodoro-timer" class="d-none">
                        <h5 id="timer-status" class="mb-3 text-danger">Foco</h5>
                        <div class="timer-display timer-focus" id="timer-display">25:00</div>
                        <p class="text-muted mt-2" id="timer-ciclo-atual">Ciclo 1 de 4</p>
                        <p class="text-muted small" id="timer-tarefa-nome"></p>

                        <div class="alert alert-warning small py-2 d-none" id="timer-erro"></div>

                        <div class="d-flex justify-content-center gap-2 mt-4" id="timer-acoes">
                            <button class="btn btn-outline-secondary" id="btn-pausar-timer"><i class="bi bi-pause-fill"></i> Pausar</button>
                            <button class="btn btn-outline-danger" id="btn-cancelar-timer"><i class="bi bi-x-circle"></i> Cancelar</button>
                        </div>

                        <div class="d-grid mt-4 d-none" id="timer-finalizar">
                            <button class="btn btn-success" id="btn-finalizar-timer"><i class="bi bi-check-circle"></i> Finalizar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Controle de Checkbox de Tarefas
            document.querySelectorAll('.tarefa-toggle-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    const tarefaId = this.dataset.tarefaId;
                    const url = this.dataset.toggleUrl;
                    const checkboxEl = this;

                    fetch(url, {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    })
                    .then(response => { if (!response.ok) throw new Error(); return response.json(); })
                    .then(data => {
                        const concluida = data.status === 'concluida';
                        checkboxEl.checked = concluida;
                        document.querySelectorAll('[data-tarefa-titulo="' + tarefaId + '"]').forEach(el => { el.classList.toggle('text-decoration-line-through', concluida); el.classList.toggle('text-muted', concluida); });
                        document.querySelectorAll('[data-tarefa-row="' + tarefaId + '"]').forEach(el => el.classList.toggle('table-success', concluida));
                        document.querySelectorAll('[data-tarefa-status="' + tarefaId + '"]').forEach(el => { el.textContent = data.status_label; el.className = 'badge ' + data.status_class; });
                    })
                    .catch(() => { checkboxEl.checked = !checkboxEl.checked; alert('Erro ao atualizar.'); });
                });
            });

            // 2. Lógica do Pomodoro
            const USUARIO_POMODORO = @json(auth()->user()->name);
            const URL_SALVAR_POMODORO = "{{ route('pomodoro.salvar') }}";

            const btnGlobal = document.getElementById('btn-pomodoro-global');
            const configDiv = document.getElementById('pomodoro-config');
            const configTarefa = document.getElementById('config-tarefa');
            const timerDiv = document.getElementById('pomodoro-timer');
            const timerDisplay = document.getElementById('timer-display');
            const timerStatus = document.getElementById('timer-status');
            const timerCiclo = document.getElementById('timer-ciclo-atual');
            const timerTarefaNome = document.getElementById('timer-tarefa-nome');
            const timerErro = document.getElementById('timer-erro');
            const timerAcoes = document.getElementById('timer-acoes');
            const timerFinalizar = document.getElementById('timer-finalizar');
            const btnIniciar = document.getElementById('btn-iniciar-pomodoro');
            const btnPausar = document.getElementById('btn-pausar-timer');
            const btnCancelar = document.getElementById('btn-cancelar-timer');
            const btnFinalizar = document.getElementById('btn-finalizar-timer');
            const modalEl = document.getElementById('pomodoroModal');

            let interval, tempoRestante = 0, emFoco = true, cicloAtual = 1, totalCiclos = 4, pausado = false, tempoFoco = 25, tempoPausa = 5;
            let tarefaIdAtual = null;
            let timerAtivo = false; // true enquanto o relógio está rodando

            // Botão de relógio em cada tarefa: abre o modal já com a tarefa selecionada
            document.querySelectorAll('.btn-pomodoro-tarefa').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (timerAtivo) return; // não troca a tarefa de um foco em andamento
                    resetarModal();
                    configTarefa.value = this.dataset.tarefaId;
                });
            });

            // Botão do menu: abre o modal sem tarefa pré-selecionada
            btnGlobal.addEventListener('click', function() {
                if (timerAtivo) return; // mantém o foco em andamento
                resetarModal();
                configTarefa.value = '';
            });

            function resetarModal() {
                clearInterval(interval);
                timerAtivo = false;
                pausado = false;
                tarefaIdAtual = null;
                configDiv.classList.remove('d-none');
                timerDiv.classList.add('d-none');
                timerTarefaNome.textContent = '';
                timerErro.classList.add('d-none');
                timerAcoes.classList.remove('d-none');
                timerFinalizar.classList.add('d-none');
                btnFinalizar.disabled = false;
                btnPausar.innerHTML = '<i class="bi bi-pause-fill"></i> Pausar';
            }

            btnIniciar.addEventListener('click', function() {
                tempoFoco = parseInt(document.getElementById('config-foco').value);
                tempoPausa = parseInt(document.getElementById('config-pausa').value);
                totalCiclos = parseInt(document.getElementById('config-ciclos').value);

                if ([tempoFoco, tempoPausa, totalCiclos].some(v => isNaN(v) || v < 1)) {
                    alert('Informe valores válidos (mínimo 1) para foco, pausa e ciclos.');
                    return;
                }

                // A tarefa é fixada aqui, no início, para não mudar no meio do foco
                tarefaIdAtual = configTarefa.value || null;
                const nomeTarefa = tarefaIdAtual ? configTarefa.options[configTarefa.selectedIndex].text.trim() : 'Sem tarefa';
                timerTarefaNome.textContent = USUARIO_POMODORO + ' · ' + nomeTarefa;

                emFoco = true; cicloAtual = 1; timerAtivo = true;
                configDiv.classList.add('d-none'); timerDiv.classList.remove('d-none');
                iniciarTempo(tempoFoco * 60, 'Foco');
            });

            function iniciarTempo(segundos, status) {
                clearInterval(interval);
                tempoRestante = segundos;
                timerStatus.textContent = status;
                if (status === 'Foco') { timerStatus.className = 'mb-3 text-danger'; timerDisplay.className = 'timer-display timer-focus'; }
                else { timerStatus.className = 'mb-3 text-info'; timerDisplay.className = 'timer-display timer-break'; }
                timerCiclo.textContent = `Ciclo ${cicloAtual} de ${totalCiclos}`;
                atualizarDisplay();
                interval = setInterval(() => {
                    if (!pausado) {
                        tempoRestante--; atualizarDisplay();
                        if (tempoRestante <= 0) { clearInterval(interval); finalizarCiclo(); }
                    }
                }, 1000);
            }

            function atualizarDisplay() {
                timerDisplay.textContent = `${String(Math.floor(tempoRestante / 60)).padStart(2, '0')}:${String(tempoRestante % 60).padStart(2, '0')}`;
            }

            // Registra os minutos de foco do usuário logado (o servidor usa auth()->id())
            function salvarFoco(minutos) {
                return fetch(URL_SALVAR_POMODORO, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ tempo_foco_minutos: minutos, tarefa_id: tarefaIdAtual })
                }).then(response => {
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    return response.json();
                });
            }

            function mostrarErroSalvar() {
                timerErro.textContent = 'Não foi possível registrar os minutos deste foco. Verifique sua conexão/sessão.';
                timerErro.classList.remove('d-none');
            }

            function finalizarCiclo() {
                new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg').play().catch(() => {});

                if (!emFoco) {
                    emFoco = true; cicloAtual++;
                    iniciarTempo(tempoFoco * 60, 'Foco');
                    return;
                }

                const salvando = salvarFoco(tempoFoco).catch(err => { console.error(err); mostrarErroSalvar(); });

                if (cicloAtual >= totalCiclos) {
                    // Último ciclo: mostra o botão Finalizar (liberado após o registro ser salvo)
                    timerAtivo = false;
                    timerStatus.textContent = 'Concluído!';
                    timerStatus.className = 'mb-3 text-success';
                    timerDisplay.textContent = '00:00';
                    timerCiclo.textContent = `Todos os ciclos finalizados! ${tempoFoco * totalCiclos} min de foco.`;
                    timerAcoes.classList.add('d-none');
                    timerFinalizar.classList.remove('d-none');
                    btnFinalizar.disabled = true;
                    salvando.finally(() => { btnFinalizar.disabled = false; });
                    return;
                }

                emFoco = false;
                iniciarTempo(tempoPausa * 60, 'Pausa');
            }

            btnPausar.addEventListener('click', function() {
                pausado = !pausado;
                btnPausar.innerHTML = pausado ? '<i class="bi bi-play-fill"></i> Retomar' : '<i class="bi bi-pause-fill"></i> Pausar';
            });

            btnCancelar.addEventListener('click', function() {
                resetarModal();
                bootstrap.Modal.getInstance(modalEl).hide();
            });

            btnFinalizar.addEventListener('click', function() {
                // Se a página mostra o painel da equipe, recarrega para atualizar os minutos
                const atualizarPagina = document.getElementById('equipe-foco') !== null;
                resetarModal();
                bootstrap.Modal.getInstance(modalEl).hide();
                if (atualizarPagina) location.reload();
            });
        });
    </script>
</body>
</html>