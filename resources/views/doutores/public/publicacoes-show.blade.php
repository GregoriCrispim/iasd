@extends('doutores.layouts.portal')

@section('title', $publicacao->titulo)

@section('content')
    <p><a href="{{ route('doutores.public.publicacoes.index') }}"><i class="bi bi-arrow-left"></i> Todas as publicações</a></p>

    @if (session('success'))
        <div class="dd-flash ok" role="status">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="dd-flash err" role="alert">
            <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <article class="dd-article">
        <h1>{{ $publicacao->titulo }}</h1>
        <span class="dd-pub-meta">{{ $publicacao->created_at?->format('d/m/Y') }}</span>

        <div class="dd-article-body">{{ $publicacao->conteudo }}</div>

        @if (! empty($publicacao->imagens))
            <div class="dd-article-gallery">
                @foreach ($publicacao->imagens as $img)
                    <img src="{{ \App\Support\DoutoresStorage::publicUrl($img) }}" alt="{{ $publicacao->titulo }}" loading="lazy">
                @endforeach
            </div>
        @endif
    </article>

    <section class="dd-article" style="margin-top:1.5rem;">
        <h2 style="margin-top:0;">Comentários</h2>

        @forelse ($publicacao->comentarios as $c)
            <div class="dd-comment">
                <strong>{{ $c->nome }}</strong> <span class="dd-pub-meta">· {{ $c->created_at?->format('d/m/Y H:i') }}</span>
                <p>{{ $c->mensagem }}</p>
            </div>
        @empty
            <p class="text-muted">Seja o primeiro a comentar.</p>
        @endforelse

        <h3 style="margin-top:1.75rem;">Deixe seu comentário</h3>
        <form method="POST" action="{{ route('doutores.public.publicacoes.comentar', $publicacao) }}" class="form-grid">
            @csrf
            <div class="form-row">
                <div class="field">
                    <label for="nome">Nome <span class="req">*</span></label>
                    <input type="text" id="nome" name="nome" class="input" value="{{ old('nome') }}" required maxlength="120">
                </div>
                <div class="field">
                    <label for="email">E-mail (não será exibido)</label>
                    <input type="email" id="email" name="email" class="input" value="{{ old('email') }}" maxlength="190">
                </div>
            </div>
            <div class="field">
                <label for="mensagem">Mensagem <span class="req">*</span></label>
                <textarea id="mensagem" name="mensagem" class="input" rows="4" required maxlength="2000">{{ old('mensagem') }}</textarea>
            </div>
            <div><button type="submit" class="dd-btn-brand">Enviar comentário</button></div>
        </form>
    </section>
@endsection
