@extends('layouts.app')

@section('title', 'Detalhes da Equipe')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-3 fw-bold mb-0">
        <span class="d-inline-block rounded-circle me-2" style="width: 16px; height: 16px; background-color: {{ $equipe->cor }}; box-shadow: 0 0 10px {{ $equipe->cor }};"></span>
        {{ $equipe->nome }}
    </h1>
    <div class="d-flex gap-2">
        <a href="{{ route('equipes.edit', $equipe->id) }}" class="btn btn-outline-warning btn-sm">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('equipes.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="row">
    <!-- COLUNA ESQUERDA: PROJETOS DA EQUIPE -->
    <div class="col-lg-8 mb-4">
        <div class="card card-shadow border-0 h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-folder text-muted"></i>
                <span class="fw-bold text-light">Projetos Atrelados</span>
            </div>
            <div class="card-body p-0">
                @if($equipe->projetos->isEmpty())
                    <div class="p-4 text-center text-muted">
                        <p class="mb-0">Nenhum projeto cadastrado para esta equipe ainda.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-nowrap" style="background-color: transparent;">
                            <thead class="border-bottom border-secondary">
                                <tr>
                                    <th class="py-3 ps-4">Projeto</th>
                                    <th class="py-3">Cliente</th>
                                    <th class="py-3">Status</th>
                                    <th class="text-end py-3 pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipe->projetos as $projeto)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="text-light fw-medium">{{ $projeto->nome }}</span>
                                        </td>
                                        <td class="text-muted">{{ $projeto->cliente ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $projeto->status == 'concluido' ? 'success' : ($projeto->status == 'cancelado' ? 'danger' : 'primary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $projeto->status)) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('projetos.show', $projeto->id) }}" class="btn btn-sm btn-outline-info border-0" title="Ver Projeto">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- COLUNA DIREITA: MEMBROS DA EQUIPE -->
    <div class="col-lg-4 mb-4">
        <div class="card card-shadow border-0 h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-people-fill text-muted"></i>
                <span class="fw-bold text-light">Membros da Equipe</span>
            </div>
            
            <div class="card-body p-0">
                <!-- Líder em destaque -->
                @if($equipe->lider)
                    <div class="p-3 border-bottom border-secondary" style="background-color: rgba(255,255,255,0.02);">
                        <small class="text-muted text-uppercase fw-bold d-block mb-2" style="font-size: 0.7rem;">Líder</small>
                        <div class="d-flex align-items-center gap-3">
                            @if($equipe->lider->imagem)
                                <img src="{{ asset('storage/' . $equipe->lider->imagem) }}" width="40" height="40" class="rounded-circle" style="object-fit:cover; border: 2px solid {{ $equipe->cor }};">
                            @else
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px; background-color: {{ $equipe->cor }}; font-weight: bold;">
                                    {{ strtoupper(substr($equipe->lider->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <span class="text-light d-block fw-medium lh-1">{{ $equipe->lider->name }}</span>
                                <small class="text-muted">{{ $equipe->lider->cargo ?? 'Membro' }}</small>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Outros Integrantes -->
                <div class="p-3">
                    <small class="text-muted text-uppercase fw-bold d-block mb-3" style="font-size: 0.7rem;">Integrantes ({{ $equipe->integrantes->count() }})</small>
                    <ul class="list-unstyled mb-0">
                        @forelse($equipe->integrantes as $integrante)
                            <li class="d-flex align-items-center gap-2 mb-3">
                                @if($integrante->imagem)
                                    <img src="{{ asset('storage/' . $integrante->imagem) }}" width="32" height="32" class="rounded-circle" style="object-fit:cover; border: 1px solid #333;">
                                @else
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:32px; height:32px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($integrante->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <span class="text-light d-block lh-1" style="font-size: 0.9rem;">{{ $integrante->name }}</span>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $integrante->email }}</small>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted small">Nenhum integrante além do líder.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection