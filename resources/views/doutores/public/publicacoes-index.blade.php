@extends('doutores.layouts.portal')

@section('title', 'Publicações')
@section('meta-description', 'Novidades, histórias e registros do voluntariado Doutores da Esperança.')

@section('content')
    <section class="dd-hero">
        <h1>{{ $config?->titulo_hero ?: 'Publicações' }}</h1>
        <p>{{ $config?->subtitulo_hero ?: 'Novidades, histórias e registros do voluntariado Doutores da Esperança.' }}</p>
        <p style="margin-top:1.25rem;">
            <a href="{{ route('doutores.cadastrar') }}" class="dd-btn-brand">Quero ser voluntário(a)</a>
        </p>
    </section>

    @if ($publicacoes->count())
        <div class="dd-pub-grid">
            @foreach ($publicacoes as $p)
                @php $capa = collect($p->imagens ?? [])->first(); @endphp
                <article class="dd-pub-card">
                    @if ($capa)
                        <img src="{{ \App\Support\DoutoresStorage::publicUrl($capa) }}" alt="{{ $p->titulo }}" loading="lazy">
                    @endif
                    <div class="dd-pub-card-body">
                        <span class="dd-pub-meta">{{ $p->created_at?->format('d/m/Y') }} · {{ $p->comentarios_aprovados_count }} comentário(s)</span>
                        <h2><a href="{{ route('doutores.public.publicacoes.show', $p) }}">{{ $p->titulo }}</a></h2>
                        <p style="margin:0;color:var(--dd-muted);">{{ \Illuminate\Support\Str::limit(strip_tags((string) $p->conteudo), 140) }}</p>
                        <a href="{{ route('doutores.public.publicacoes.show', $p) }}" style="font-weight:700;">Ler mais →</a>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="adm-pagination" style="margin-top:1.5rem;">{{ $publicacoes->links() }}</div>
    @else
        <div class="empty-state"><i class="bi bi-newspaper"></i> Nenhuma publicação por enquanto.</div>
    @endif
@endsection
