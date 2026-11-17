@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-area-avisos'; @endphp
@section('title', 'Avisos')
@section('heading', 'Avisos')

@section('content')
    @forelse ($avisos as $a)
        @php $lido = $lidos->has($a->id); @endphp
        <div class="card" style="margin-bottom:16px;{{ $lido ? '' : 'border-left:4px solid var(--adm-primary);' }}">
            <div class="card-head">
                <h2>{{ $a->titulo }}</h2>
                <div class="adm-actions">
                    @if ($a->equipe_numero)<span class="badge badge-purple">Equipe {{ $a->equipe_numero }}</span>@endif
                    @unless ($lido)<span class="badge badge-amber">Novo</span>@endunless
                </div>
            </div>
            <div class="card-body">
                <div class="muted-sm" style="margin-bottom:8px;">{{ $a->plantao?->hospital }} · {{ $a->plantao?->data?->format('d/m/Y') }} · publicado em {{ $a->created_at?->format('d/m/Y H:i') }}</div>
                <div style="white-space:pre-line;">{{ $a->conteudo }}</div>
                @unless ($lido)
                    <form method="POST" action="{{ route('doutores.area.avisos.lido', $a) }}" style="margin-top:14px;">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-check2-all"></i> Marcar como lido</button>
                    </form>
                @endunless
            </div>
        </div>
    @empty
        <div class="card"><div class="empty-state"><i class="bi bi-megaphone"></i>Nenhum aviso para você no momento.</div></div>
    @endforelse
    <div class="adm-pagination">{{ $avisos->links() }}</div>
@endsection
