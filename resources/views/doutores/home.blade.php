@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-home'; @endphp
@section('title', 'Doutores da Esperança')
@section('heading', 'Doutores da Esperança')

@section('actions')
    <a href="{{ route('doutores.plantoes.create') }}" class="btn"><i class="bi bi-plus-lg"></i> Novo plantão</a>
@endsection

@section('content')
    <p class="text-muted mt-0">
        Bem-vindo(a), <strong>{{ auth('doutores')->user()->name }}</strong>.
        @unless (\App\Support\DoutoresPoloScope::isUnrestricted(auth('doutores')->user(), request()))
            Você está vendo apenas os dados dos hospitais do seu polo.
        @endunless
    </p>

    <div class="stat-grid">
        <div class="stat">
            <i class="bi bi-people stat-icon"></i>
            <div class="stat-label">Voluntários ativos</div>
            <div class="stat-value">{{ $stats['voluntarios_ativos'] }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-person-exclamation stat-icon"></i>
            <div class="stat-label">Cadastros pendentes</div>
            <div class="stat-value">{{ $stats['voluntarios_pendentes'] }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-calendar-check stat-icon"></i>
            <div class="stat-label">Plantões abertos</div>
            <div class="stat-value">{{ $stats['plantoes_abertos'] }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-journal-check stat-icon"></i>
            <div class="stat-label">Inscrições pendentes</div>
            <div class="stat-value">{{ $stats['inscricoes_pendentes'] }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-book stat-icon"></i>
            <div class="stat-label">Estudos pendentes</div>
            <div class="stat-value">{{ $stats['estudos_pendentes'] }}</div>
        </div>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <div class="card-head">
            <h2>Próximos plantões</h2>
            <div class="adm-actions"><a href="{{ route('doutores.plantoes.index') }}" class="btn btn-secondary btn-sm">Ver todos</a></div>
        </div>
        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Data</th><th>Hospital</th><th>Tipo</th><th>Status</th><th>Inscrições</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($proximosPlantoes as $p)
                        <tr>
                            <td>{{ $p->data?->format('d/m/Y') }} <span class="text-muted">{{ $p->hora_inicio }}</span></td>
                            <td><strong>{{ $p->hospital }}</strong></td>
                            <td>{{ \App\Models\Doutores\Plantao::TIPOS[$p->tipo] ?? $p->tipo }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $p->status])</td>
                            <td>{{ $p->inscricoes_count }}</td>
                            <td class="col-actions">
                                <div class="row-actions">
                                    <a href="{{ route('doutores.plantoes.show', $p) }}" class="btn btn-secondary btn-sm" title="Abrir"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('doutores.plantoes.equipes', $p) }}" class="btn btn-secondary btn-sm" title="Equipes"><i class="bi bi-diagram-3"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-calendar-x"></i>Nenhum plantão agendado.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-row">
        <div class="card">
            <div class="card-head">
                <h2>Cadastros aguardando aprovação</h2>
                <div class="adm-actions"><a href="{{ route('doutores.voluntarios.index', ['status' => 'pendente']) }}" class="btn btn-secondary btn-sm">Ver</a></div>
            </div>
            <div class="table-wrap">
                <table class="adm-table">
                    <tbody>
                        @forelse ($pendentes as $profile)
                            <tr>
                                <td><strong>{{ $profile->user?->name }}</strong><br><span class="muted-sm">{{ $profile->hospital_preferido ?? 'Sem hospital preferido' }}</span></td>
                                <td class="col-actions">
                                    <a href="{{ route('doutores.voluntarios.show', $profile) }}" class="btn btn-secondary btn-sm">Analisar</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td><div class="empty-state"><i class="bi bi-check2-circle"></i>Nenhum cadastro pendente.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <h2>Solicitações de estudo pendentes</h2>
                <div class="adm-actions"><a href="{{ route('doutores.estudos.index', ['status' => 'pendente']) }}" class="btn btn-secondary btn-sm">Ver</a></div>
            </div>
            <div class="table-wrap">
                <table class="adm-table">
                    <tbody>
                        @forelse ($estudosRecentes as $estudo)
                            <tr>
                                <td><strong>{{ $estudo->nome_paciente }}</strong><br><span class="muted-sm">{{ $estudo->hospital ?? 'Sem hospital' }} · {{ $estudo->created_at?->format('d/m/Y') }}</span></td>
                                <td class="col-actions">
                                    <a href="{{ route('doutores.estudos.show', $estudo) }}" class="btn btn-secondary btn-sm">Abrir</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td><div class="empty-state"><i class="bi bi-check2-circle"></i>Nenhuma solicitação pendente.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
