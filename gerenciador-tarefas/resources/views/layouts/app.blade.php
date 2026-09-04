<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gerenciador de Tarefas')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        .navbar-brand { font-weight: bold; }
        .card-shadow {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .card-shadow:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transform: translateY(-2px);
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <!-- MENU SUPERIOR -->
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('projetos.index') }}">
                            <i class="bi bi-folder"></i> Projetos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tarefas.index') }}">
                            <i class="bi bi-tasks"></i> Tarefas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categorias.index') }}">
                            <i class="bi bi-tags"></i> Categorias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="bi bi-people"></i> Usuários
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="py-4">
        <div class="container">
            <!-- Mensagens de sucesso/erro -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Controla a checkbox "concluir tarefa" em qualquer página
        // (aba Tarefas, cards de Projetos e cards de Categorias).
        document.addEventListener('DOMContentLoaded', function () {
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
        });
    </script>
</body>
</html>