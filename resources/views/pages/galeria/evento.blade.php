@extends('layouts.app')

@section('title', $evento['title'].' - Galeria de Fotos - IASD Central de Brasília')
@section('meta-description', 'Fotos da programação '.$evento['title'].' da IASD Central de Brasília.')
@section('og-title', $evento['title'].' - Galeria de Fotos')
@section('og-description', 'Reviva os melhores momentos da nossa igreja em fotos.')
@if($evento['coverUrl'])
@section('og-image', $evento['coverUrl'])
@endif
@section('page-name', 'Galeria de Fotos')

@push('styles')
<style>
    /* Estilos isolados desta view (prefixo galeria-) */
    .galeria-evento-container {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: clamp(24px, 4vw, 56px) 16px;
    }

    .galeria-evento-back {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: #003366;
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        opacity: 0.85;
        text-decoration: none;
    }
    .galeria-evento-back:hover { opacity: 1; }

    .galeria-evento-title {
        font-family: "Bebas Neue", "Noto Sans JP", sans-serif;
        color: #003366;
        font-size: clamp(2.2rem, 4vw, 3rem);
        letter-spacing: 1px;
        margin: 0;
        text-transform: capitalize;
    }

    .galeria-evento-meta {
        display: flex;
        gap: 1.25rem;
        margin-top: 0.5rem;
        flex-wrap: wrap;
        font-size: 0.9rem;
        color: #555555;
    }
    .galeria-evento-meta span { display: inline-flex; align-items: center; gap: 0.4rem; }

    .galeria-photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 2rem;
    }

    .galeria-photo-card {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 1;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        background: #eee;
    }

    .galeria-photo-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .galeria-photo-card:hover img { transform: scale(1.05); }

    .galeria-photo-card.is-selected { outline: 3px solid #003366; outline-offset: -3px; }
    .galeria-photo-card.is-selected .galeria-select-check { background: #003366; }

    /* Loader de miniatura */
    .galeria-thumb-loader {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(90deg, #e8e8e8 25%, #f2f2f2 50%, #e8e8e8 75%);
        background-size: 600px 100%;
        animation: galeria-shimmer 1.4s infinite linear;
        z-index: 1;
    }
    @keyframes galeria-shimmer {
        0% { background-position: -600px 0; }
        100% { background-position: 600px 0; }
    }

    .galeria-spinner {
        width: 22px; height: 22px;
        border-radius: 50%;
        border: 3px solid rgba(0, 51, 102, 0.25);
        border-top-color: #003366;
        animation: galeria-spin 0.8s linear infinite;
        display: inline-block;
    }
    @keyframes galeria-spin { to { transform: rotate(360deg); } }

    .galeria-grid-sentinel { height: 1px; }

    /* Barra de ferramentas: seleção e ordenação */
    .galeria-photo-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.75rem;
    }

    .galeria-toolbar-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 0.9rem;
        border: 1px solid rgba(0, 51, 102, 0.25);
        border-radius: 8px;
        background: #fff;
        color: #003366;
        font-size: 0.88rem;
        font-family: "Roboto", sans-serif;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }
    .galeria-toolbar-btn:hover { background: #003366; color: #fff; }
    .galeria-toolbar-btn.is-active { background: #003366; color: #fff; }
    .galeria-toolbar-btn.primary { background: #003366; color: #fff; }
    .galeria-toolbar-btn.primary:hover { background: #002244; }

    .galeria-sort-wrap { display: flex; align-items: center; gap: 0.5rem; }
    .galeria-sort-label { font-size: 0.85rem; color: #555555; font-family: "Roboto", sans-serif; }
    .galeria-sort-wrap select {
        padding: 0.5rem 0.85rem;
        border: 1px solid rgba(0, 51, 102, 0.2);
        border-radius: 8px;
        font-size: 0.88rem;
        font-family: "Roboto", sans-serif;
        background: #fff;
        color: #1a1a1a;
        cursor: pointer;
    }

    /* Seleção de fotos para download em lote */
    .galeria-select-check {
        position: absolute;
        top: 8px; left: 8px;
        z-index: 2;
        width: 28px; height: 28px;
        border-radius: 50%;
        border: none;
        background: rgba(0, 0, 0, 0.35);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.1rem;
    }

    .galeria-batch-bar {
        position: fixed;
        left: 50%;
        bottom: 20px;
        transform: translate(-50%, 130%);
        background: rgba(0, 21, 49, 0.95);
        color: #fff;
        padding: 0.75rem 1.25rem;
        border-radius: 999px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        z-index: 500;
        transition: transform 0.3s ease;
        flex-wrap: wrap;
        justify-content: center;
    }
    .galeria-batch-bar.is-visible { transform: translate(-50%, 0); }
    .galeria-batch-bar .galeria-toolbar-btn { background: transparent; color: #fff; border-color: rgba(255, 255, 255, 0.35); }
    .galeria-batch-bar .galeria-toolbar-btn.primary { background: #fff; color: #003366; }
    .galeria-batch-bar .galeria-toolbar-btn:hover { background: rgba(255, 255, 255, 0.15); }
    .galeria-batch-bar .galeria-toolbar-btn.primary:hover { background: #e8eef5; }

    .galeria-download-all {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 1rem;
        color: #003366;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
    }
    .galeria-download-all:hover { text-decoration: underline; }

    .galeria-photo-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 1rem;
        background: linear-gradient(transparent, rgba(0, 21, 49, 0.8));
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .galeria-photo-card:hover .galeria-photo-overlay { opacity: 1; }

    .galeria-photo-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 50%;
        color: #fff;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.1rem;
        backdrop-filter: blur(4px);
    }

    .galeria-empty {
        text-align: center;
        color: #555555;
        padding: 3rem 0;
    }

    /* Lightbox (mesmo padrão de overlay usado em /asa: respeita o header fixo
       e a coluna do logo lateral fixo, evitando sobreposição) */
    .galeria-lightbox {
        --galeria-lightbox-right-gap: 0px;
        position: fixed;
        left: 0;
        top: var(--header-height);
        width: calc(100vw - var(--galeria-lightbox-right-gap));
        height: calc(100vh - var(--header-height));
        height: calc(100dvh - var(--header-height));
        background: rgba(0, 0, 0, 0.92);
        z-index: 9999;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
        overflow: hidden;
    }
    .galeria-lightbox.is-open { display: flex; }

    .galeria-lightbox-loader {
        position: absolute;
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 3;
    }
    .galeria-lightbox-loader.is-visible { display: flex; }
    .galeria-lightbox-loader .galeria-spinner {
        width: 44px; height: 44px;
        border-width: 4px;
        border-color: rgba(255, 255, 255, 0.25);
        border-top-color: #fff;
    }

    .galeria-lightbox-counter {
        position: absolute;
        top: 18px; left: 20px;
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
        font-family: "Roboto", sans-serif;
    }

    .galeria-lightbox-actions {
        position: absolute;
        top: 15px; right: 15px;
        display: flex;
        gap: 10px;
    }

    .galeria-lightbox-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 50%;
        color: #fff;
        width: 42px; height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.1rem;
    }
    .galeria-lightbox-btn.close { background: rgba(220, 50, 50, 0.5); }

    .galeria-lightbox img {
        max-height: 88vh;
        max-width: 85vw;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 0 40px rgba(0, 0, 0, 0.6);
    }

    .galeria-lightbox-name {
        position: absolute;
        bottom: 16px;
        color: rgba(255, 255, 255, 0.45);
        font-size: 0.8rem;
        font-family: "Roboto", sans-serif;
    }

    .galeria-lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        color: rgba(255, 255, 255, 0.75);
        width: 52px; height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.4rem;
    }
    .galeria-lightbox-nav.prev { left: 12px; }
    .galeria-lightbox-nav.next { right: 12px; }
</style>
@endpush

@section('content')
<div class="galeria-evento-container">

    <a href="{{ route('galeria') }}" class="galeria-evento-back">&larr; Voltar para as galerias</a>

    <h1 class="galeria-evento-title">{{ $evento['title'] }}</h1>

    <div class="galeria-evento-meta">
        @if($evento['dateLong'])
            <span><i class="bi bi-calendar-event"></i> {{ $evento['dateLong'] }}</span>
        @endif
        @if(count($fotos) > 0)
            <span><i class="bi bi-images"></i> {{ count($fotos) }} foto{{ count($fotos) !== 1 ? 's' : '' }}</span>
        @endif
    </div>

    @if(count($fotos) === 0)
        <div class="galeria-empty">Nenhuma foto encontrada nesta programação.</div>
    @else
        <div class="galeria-photo-toolbar">
            <button type="button" class="galeria-toolbar-btn" id="galeriaSelectToggle"><i class="bi bi-check2-square"></i> Selecionar fotos</button>
        </div>

        <div class="galeria-photo-grid" id="galeriaPhotoGrid" data-download-url="{{ route('galeria.download', $evento['id']) }}"></div>
        <div id="galeriaGridSentinel" class="galeria-grid-sentinel" aria-hidden="true"></div>

        <a href="{{ route('galeria.download', $evento['id']) }}" class="galeria-download-all">
            <i class="bi bi-file-earmark-zip"></i> Baixar todas as fotos (.zip)
        </a>

        <script type="application/json" id="galeriaFotosData">{!! json_encode($fotos) !!}</script>
    @endif

    <div class="galeria-lightbox" id="galeriaLightbox">
        <div class="galeria-lightbox-counter" id="galeriaLightboxCounter"></div>
        <div class="galeria-lightbox-actions">
            <button type="button" class="galeria-lightbox-btn" id="galeriaLightboxDownload" title="Baixar"><i class="bi bi-download"></i></button>
            <button type="button" class="galeria-lightbox-btn" id="galeriaLightboxShare" title="Compartilhar"><i class="bi bi-share-fill"></i></button>
            <button type="button" class="galeria-lightbox-btn close" id="galeriaLightboxClose" title="Fechar (Esc)"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="galeria-lightbox-loader" id="galeriaLightboxLoader"><span class="galeria-spinner"></span></div>
        <img id="galeriaLightboxImg" src="" alt="">
        <div class="galeria-lightbox-name" id="galeriaLightboxName"></div>
        <button type="button" class="galeria-lightbox-nav prev" id="galeriaLightboxPrev" title="Anterior (←)"><i class="bi bi-chevron-left"></i></button>
        <button type="button" class="galeria-lightbox-nav next" id="galeriaLightboxNext" title="Próxima (→)"><i class="bi bi-chevron-right"></i></button>
    </div>

    <div class="galeria-batch-bar" id="galeriaBatchBar">
        <span id="galeriaBatchCount">0 selecionadas</span>
        <button type="button" class="galeria-toolbar-btn" id="galeriaBatchSelectAll">Selecionar todas</button>
        <button type="button" class="galeria-toolbar-btn primary" id="galeriaBatchDownload"><i class="bi bi-file-earmark-zip"></i> Baixar selecionadas (.zip)</button>
        <button type="button" class="galeria-toolbar-btn" id="galeriaBatchCancel">Cancelar</button>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/galeria.js') }}" defer></script>
@endpush
