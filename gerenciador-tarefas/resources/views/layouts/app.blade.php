<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gerenciador de Tarefas')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        .navbar-brand { font-weight: bold; }
        .card-shadow { box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s; }
        .card-shadow:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.2); transform: translateY(-2px); }
        .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; }
        
        /* Estilos do Pomodoro */
        .timer-display {
            font-size: 4rem;
            font-weight: bold;
            font-variant-numeric: tabular-nums;
            color: #333;
        }
        .timer-focus { color: #dc3545; } /* Vermelho para foco */
        .timer-break { color: #0dcaf0; } /* Azul para pausa */
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('projetos.index') }}">
                <i class="bi bi-check2-square"></i> Gerenciador de Tarefas
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
                    
                    <!-- BOTÃO GLOBAL DO POMODORO NO CABEÇALHO -->
                    <li class="nav-item me-3">
                        <button class="btn btn-outline-light btn-sm" id="btn-pomodoro-global" data-bs-toggle="modal" data-bs-target="#pomodoroModal">
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
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill"></i> Por favor, corrija os erros abaixo.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- MODAL DO POMODORO -->
    <div class="modal fade" id="pomodoroModal" tabindex="-1" aria-labelledby="pomodoroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pomodoroModalLabel"><i class="bi bi-hourglass-split"></i> Pomodoro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    
                    <!-- TELA DE CONFIGURAÇÃO -->
                    <div id="pomodoro-config">
                        <h6 id="tarefa-foco-nome" class="text-muted mb-4 d-none"></h6>
                        
                        <div class="row mb-3">
                            <div class="col-4">
                                <label class="form-label small">Foco (min)</label>
                                <input type="number" class="form-control text-center" id="config-foco" value="25" min="1">
                            </div>
                            <div class="col-4">
                                <label class="form-label small">Pausa (min)</label>
                                <input type="number" class="form-control text-center" id="config-pausa" value="5" min="1">
                            </div>
                            <div class="col-4">
                                <label class="form-label small">Ciclos</label>
                                <input type="number" class="form-control text-center" id="config-ciclos" value="4" min="1">
                            </div>
                        </div>
                        <button class="btn btn-danger w-100 mt-3" id="btn-iniciar-pomodoro">
                            <i class="bi bi-play-fill"></i> Iniciar Foco
                        </button>
                    </div>

                    <!-- TELA DO CRONÔMETRO -->
                    <div id="pomodoro-timer" class="d-none">
                        <h5 id="timer-status" class="mb-3 text-danger">Foco</h5>
                        <div class="timer-display timer-focus" id="timer-display">25:00</div>
                        <p class="text-muted mt-2" id="timer-ciclo-atual">Ciclo 1 de 4</p>
                        <p class="text-muted small" id="timer-tarefa-nome"></p>
                        
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button class="btn btn-outline-secondary" id="btn-pausar-timer"><i class="bi bi-pause-fill"></i> Pausar</button>
                            <button class="btn btn-outline-danger" id="btn-cancelar-timer"><i class="bi bi-x-circle"></i> Cancelar</button>
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
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    })
                    .then(function (response) {
                        if (!response.ok) throw new Error('Falha ao atualizar a tarefa');
                        return response.json();
                    })
                    .then(function (data) {
                        const concluida = data.status === 'concluida';
                        checkboxEl.checked = concluida;

                        document.querySelectorAll('[data-tarefa-titulo="' + tarefaId + '"]').forEach(function (el) {
                            el.classList.toggle('text-decoration-line-through', concluida);
                            el.classList.toggle('text-muted', concluida);
                        });
                        document.querySelectorAll('[data-tarefa-row="' + tarefaId + '"]').forEach(function (el) {
                            el.classList.toggle('table-success', concluida);
                        });
                        document.querySelectorAll('[data-tarefa-status="' + tarefaId + '"]').forEach(function (el) {
                            el.textContent = data.status_label;
                            el.className = 'badge ' + data.status_class;
                            el.setAttribute('data-tarefa-status', tarefaId);
                        });
                    })
                    .catch(function () {
                        checkboxEl.checked = !checkboxEl.checked;
                        alert('Não foi possível atualizar a tarefa. Tente novamente.');
                    });
                });
            });

            // 2. Lógica do Pomodoro
            const btnGlobal = document.getElementById('btn-pomodoro-global');
            const tarefaFocoNome = document.getElementById('tarefa-foco-nome');
            const configDiv = document.getElementById('pomodoro-config');
            const timerDiv = document.getElementById('pomodoro-timer');
            const timerDisplay = document.getElementById('timer-display');
            const timerStatus = document.getElementById('timer-status');
            const timerCiclo = document.getElementById('timer-ciclo-atual');
            const timerTarefaNome = document.getElementById('timer-tarefa-nome');
            
            const btnIniciar = document.getElementById('btn-iniciar-pomodoro');
            const btnPausar = document.getElementById('btn-pausar-timer');
            const btnCancelar = document.getElementById('btn-cancelar-timer');

            let interval;
            let tempoRestante = 0;
            let emFoco = true;
            let cicloAtual = 1;
            let totalCiclos = 4;
            let pausado = false;
            let tempoFoco = 25;
            let tempoPausa = 5;
            
            // ALTERAÇÃO 1: Variável para guardar o ID da tarefa
            let tarefaIdAtual = null; 

            // Abrir Pomodoro a partir do botão de uma Tarefa específica
            document.querySelectorAll('.btn-pomodoro-tarefa').forEach(btn => {
                btn.addEventListener('click', function() {
                    const tituloTarefa = this.dataset.titulo;
                    
                    // ALTERAÇÃO 2: Captura o ID da tarefa
                    tarefaIdAtual = this.dataset.tarefaId; 
                    
                    tarefaFocoNome.textContent = "Tarefa: " + tituloTarefa;
                    tarefaFocoNome.classList.remove('d-none');
                    timerTarefaNome.textContent = tituloTarefa;
                });
            });

            // Abrir Pomodoro Global (limpa a tarefa)
            btnGlobal.addEventListener('click', function() {
                resetarModal();
            });

            function resetarModal() {
                clearInterval(interval);
                configDiv.classList.remove('d-none');
                timerDiv.classList.add('d-none');
                tarefaFocoNome.classList.add('d-none');
                tarefaFocoNome.textContent = '';
                timerTarefaNome.textContent = '';
                pausado = false;
                btnPausar.innerHTML = '<i class="bi bi-pause-fill"></i> Pausar';
                
                // Limpa o ID ao abrir o global
                tarefaIdAtual = null; 
            }

            btnIniciar.addEventListener('click', function() {
                tempoFoco = parseInt(document.getElementById('config-foco').value);
                tempoPausa = parseInt(document.getElementById('config-pausa').value);
                totalCiclos = parseInt(document.getElementById('config-ciclos').value);
                
                emFoco = true;
                cicloAtual = 1;
                configDiv.classList.add('d-none');
                timerDiv.classList.remove('d-none');
                
                iniciarTempo(tempoFoco * 60, 'Foco');
            });

            function iniciarTempo(segundos, status) {
                clearInterval(interval);
                tempoRestante = segundos;
                timerStatus.textContent = status;
                
                if (status === 'Foco') {
                    timerStatus.className = 'mb-3 text-danger';
                    timerDisplay.className = 'timer-display timer-focus';
                } else {
                    timerStatus.className = 'mb-3 text-info';
                    timerDisplay.className = 'timer-display timer-break';
                }

                timerCiclo.textContent = `Ciclo ${cicloAtual} de ${totalCiclos}`;
                atualizarDisplay();

                interval = setInterval(() => {
                    if (!pausado) {
                        tempoRestante--;
                        atualizarDisplay();

                        if (tempoRestante <= 0) {
                            clearInterval(interval);
                            finalizarCiclo();
                        }
                    }
                }, 1000);
            }

            function atualizarDisplay() {
                const minutos = Math.floor(tempoRestante / 60);
                const segundos = tempoRestante % 60;
                timerDisplay.textContent = `${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')}`;
            }

            function finalizarCiclo() {
                const audio = new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg');
                audio.play().catch(() => {});

                if (emFoco) {
                    // ALTERAÇÃO 3: Salva no banco via fetch
                    fetch("{{ route('pomodoro.salvar') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            tempo_foco_minutos: tempoFoco,
                            tarefa_id: tarefaIdAtual
                        })
                    }).catch(err => console.error('Erro ao salvar pomodoro:', err));

                    if (cicloAtual >= totalCiclos) {
                        timerStatus.textContent = "Concluído!";
                        timerDisplay.textContent = "00:00";
                        timerCiclo.textContent = "Você completou todos os ciclos!";
                        return;
                    }
                    emFoco = false;
                    iniciarTempo(tempoPausa * 60, 'Pausa');
                } else {
                    emFoco = true;
                    cicloAtual++;
                    iniciarTempo(tempoFoco * 60, 'Foco');
                }
            }

            btnPausar.addEventListener('click', function() {
                pausado = !pausado;
                if (pausado) {
                    btnPausar.innerHTML = '<i class="bi bi-play-fill"></i> Retomar';
                } else {
                    btnPausar.innerHTML = '<i class="bi bi-pause-fill"></i> Pausar';
                }
            });

            btnCancelar.addEventListener('click', function() {
                resetarModal();
                const modalEl = document.getElementById('pomodoroModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
            });
        });
    </script>
</body>
</html>