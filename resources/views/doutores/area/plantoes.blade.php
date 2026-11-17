@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-area-plantoes'; @endphp
@section('title', 'Plantões')
@section('heading', 'Plantões disponíveis')

@section('content')
    @if ($profile->termos_aceitos_em === null)
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            <div>Aceite os <a href="{{ route('doutores.area.termos') }}"><strong>termos de voluntariado</strong></a> para poder se inscrever.</div>
        </div>
    @endif

    <div class="card">
        <form method="GET" class="filters">
            <div class="field">
                <label>Hospital</label>
                <select name="hospital" class="select">
                    <option value="">Todos</option>
                    @foreach ($hospitais as $h)
                        <option value="{{ $h }}" {{ request('hospital') === $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-secondary"><i class="bi bi-funnel"></i> Filtrar</button>
        </form>

        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Data</th><th>Hospital</th><th>Tipo</th><th>Inscrição</th></tr></thead>
                <tbody>
                    @forelse ($plantoes as $p)
                        @php
                            $minha = $minhas->get($p->id);
                            $ativa = $minha && in_array($minha->status, ['pendente', 'confirmada'], true);
                        @endphp
                        <tr>
                            <td>{{ $p->data?->format('d/m/Y') }}<br><span class="muted-sm">{{ $p->hora_inicio }}{{ $p->hora_fim ? ' – '.$p->hora_fim : '' }}</span></td>
                            <td><strong>{{ $p->hospital }}</strong><br><span class="muted-sm">{{ $p->endereco }}</span></td>
                            <td>{{ \App\Models\Doutores\Plantao::TIPOS[$p->tipo] ?? $p->tipo }}</td>
                            <td style="min-width:300px;">
                                @if ($ativa)
                                    @include('doutores.partials.badge', ['status' => $minha->status])
                                    <a href="{{ route('doutores.area.inscricoes') }}" class="btn btn-ghost btn-sm">Ver inscrição</a>
                                @elseif (! $p->inscricoesAbertas())
                                    <span class="badge badge-gray">Inscrições encerradas</span>
                                @else
                                    <form method="POST" action="{{ route('doutores.area.inscricoes.store') }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                                        @csrf
                                        <input type="hidden" name="plantao_id" value="{{ $p->id }}">
                                        @if (count($funcoes))
                                            <select name="papel" class="select" style="width:auto;" aria-label="Função">
                                                <option value="">Função…</option>
                                                @foreach ($funcoes as $f)
                                                    <option value="{{ $f }}" {{ $profile->cargo === $f ? 'selected' : '' }}>{{ $f }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" name="papel" class="input" style="width:auto;" placeholder="Função" value="{{ $profile->cargo }}" maxlength="100">
                                        @endif
                                        <button type="submit" class="btn btn-sm" {{ $profile->termos_aceitos_em ? '' : 'disabled' }}><i class="bi bi-check2-circle"></i> Inscrever-me</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-state"><i class="bi bi-calendar-x"></i>Nenhum plantão aberto no momento.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
