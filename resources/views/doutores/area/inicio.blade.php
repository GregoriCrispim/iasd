@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-area'; @endphp
@section('title', 'Área do voluntário')
@section('heading', 'Área do voluntário')

@section('content')
    <p class="text-muted mt-0">Que bom ter você conosco, <strong>{{ auth('doutores')->user()->name }}</strong>! 💙</p>

    @if ($profile->termos_aceitos_em === null)
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            <div>
                Para se inscrever em plantões, aceite os <a href="{{ route('doutores.area.termos') }}"><strong>termos de voluntariado</strong></a>.
            </div>
        </div>
    @endif

    @if ($anexosRejeitados > 0)
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i>
            <div>Você tem {{ $anexosRejeitados }} documento(s) rejeitado(s). <a href="{{ route('doutores.anexos.index') }}">Envie novamente</a>.</div>
        </div>
    @endif

    <div class="stat-grid">
        <div class="stat">
            <i class="bi bi-calendar-check stat-icon"></i>
            <div class="stat-label">Plantões abertos</div>
            <div class="stat-value">{{ $plantoesAbertos }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-journal-check stat-icon"></i>
            <div class="stat-label">Minhas próximas inscrições</div>
            <div class="stat-value">{{ $inscricoes->count() }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-megaphone stat-icon"></i>
            <div class="stat-label">Avisos não lidos</div>
            <div class="stat-value">{{ $avisosNaoLidos }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <h2>Minhas próximas escalas</h2>
            <div class="adm-actions"><a href="{{ route('doutores.area.plantoes') }}" class="btn btn-sm"><i class="bi bi-plus-lg"></i> Inscrever-me</a></div>
        </div>
        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Data</th><th>Hospital</th><th>Função</th><th>Status</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($inscricoes as $i)
                        <tr>
                            <td>{{ $i->data?->format('d/m/Y') }} <span class="text-muted">{{ $i->plantao?->hora_inicio }}</span></td>
                            <td><strong>{{ $i->hospital }}</strong></td>
                            <td>{{ $i->papel ?? '—' }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $i->status])</td>
                            <td class="col-actions">
                                @if (! $i->confirmada_por_voluntario_em)
                                    <form method="POST" action="{{ route('doutores.area.inscricoes.confirmar', $i) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check2"></i> Confirmar presença</button>
                                    </form>
                                @else
                                    <span class="badge badge-green">Presença confirmada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="bi bi-calendar-x"></i>Você ainda não tem inscrições futuras.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
