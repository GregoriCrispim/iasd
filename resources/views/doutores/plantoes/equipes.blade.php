@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-plantoes'; @endphp
@section('title', 'Equipes — '.$plantao->hospital)
@section('heading', 'Equipes — '.$plantao->hospital.' ('.$plantao->data?->format('d/m/Y').')')

@section('actions')
    <a href="{{ route('doutores.plantoes.show', $plantao) }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Plantão</a>
@endsection

@section('content')
    <div class="card" style="margin-bottom:20px;">
        <div class="card-head"><h2>Montar equipes</h2></div>
        <div class="card-body">
            <p class="text-muted mt-0">As inscrições ativas são agrupadas pela função escolhida. Opcionalmente, divida cada grupo em equipes menores.</p>
            <form method="POST" action="{{ route('doutores.plantoes.equipes.gerar', $plantao) }}" class="filters" style="padding:0;border:0;">
                @csrf
                <div class="field">
                    <label for="tamanho">Máx. de pessoas por equipe</label>
                    <input type="number" min="1" max="200" id="tamanho" name="tamanho" class="input" value="{{ old('tamanho') }}" placeholder="Sem limite">
                </div>
                <label class="checkbox-field"><input type="checkbox" name="somente_confirmadas" value="1"> Somente inscrições confirmadas</label>
                <button type="submit" name="acao" value="gerar" class="btn"><i class="bi bi-diagram-3"></i> {{ count($equipes) ? 'Refazer equipes' : 'Gerar equipes' }}</button>
                @if (count($equipes))
                    <button type="submit" name="acao" value="limpar" class="btn btn-danger"><i class="bi bi-trash"></i> Limpar</button>
                @endif
            </form>
        </div>
    </div>

    @forelse ($equipes as $e)
        <div class="card" style="margin-bottom:16px;">
            <div class="card-head">
                <h2>{{ $e['nome'] ?? 'Equipe '.($e['numero'] ?? '') }}</h2>
                <div class="adm-actions">
                    <span class="badge badge-gray">{{ count($e['membros'] ?? []) }} pessoa(s)</span>
                    <a href="{{ route('doutores.avisos.create', ['plantao_id' => $plantao->id, 'equipe_numero' => $e['numero'] ?? null]) }}" class="btn btn-secondary btn-sm"><i class="bi bi-megaphone"></i> Avisar equipe</a>
                </div>
            </div>
            <div class="table-wrap">
                <table class="adm-table">
                    <tbody>
                        @foreach ($e['membros'] ?? [] as $m)
                            <tr>
                                <td><strong>{{ $m['nome'] ?? '—' }}</strong></td>
                                <td>{{ $m['papel'] ?? '—' }}</td>
                                <td>@include('doutores.partials.badge', ['status' => $m['status'] ?? ''])</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="card"><div class="empty-state"><i class="bi bi-diagram-3"></i>Nenhuma equipe montada ainda.</div></div>
    @endforelse

    <p class="muted-sm">Inscrições ativas atualmente: {{ $plantao->inscricoes->whereIn('status', ['pendente', 'confirmada'])->count() }}.</p>
@endsection
