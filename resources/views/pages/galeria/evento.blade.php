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

    .galeria-evento-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .galeria-evento-back {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-left: auto;
        color: #003366;
        font-weight: 500;
        font-size: 0.9rem;
        opacity: 0.85;
        text-decoration: none;
        white-space: nowrap;
    }
    .galeria-evento-back:hover { opacity: 1; }
    .galeria-evento-back i { font-size: 1rem; line-height: 1; }

    .galeria-evento-title {
        font-family: "Bebas Neue", "Noto Sans JP", sans-serif;
        color: #003366;
        font-size: clamp(2.2rem, 4vw, 3rem);
        letter-spacing: 1px;
        margin: 0;
        text-transform: capitalize;
        flex: 1 1 auto;
        min-width: 0;
    }

    .galeria-evento-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 0.5rem;
        flex-wrap: wrap;
        font-size: 0.9rem;
        color: #555555;
    }
    .galeria-evento-meta-info {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
        min-width: 0;
    }
    .galeria-evento-meta-info span { display: inline-flex; align-items: center; gap: 0.4rem; }
    .galeria-evento-meta .galeria-toolbar-btn {
        margin-left: auto;
        flex-shrink: 0;
    }

    .galeria-photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(min(100%, 200px), 1fr));
        gap: 16px;
        margin-top: 2rem;
        align-items: stretch;
    }

    .galeria-photo-card {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 1 / 1;
        width: 100%;
        min-width: 0;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        background: #eee;
    }

    .galeria-photo-card img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        transition: transform 0.4s ease;
    }
    .galeria-photo-card:hover img { transform: scale(1.05); }

    .galeria-photo-card.is-selected { outline: 3px solid #003366; outline-offset: -3px; }
    .galeria-photo-grid:not(.is-select-mode) .galeria-photo-card.is-selected { outline: none; }

    /* Bolinha CSS pura (sem fonte de ícones): já vem no card, alternada por classe */
    .galeria-select-check {
        position: absolute;
        top: 8px;
        left: 8px;
        z-index: 2;
        width: 28px;
        height: 28px;
        padding: 0;
        border: 2px solid #fff;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.35);
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.35);
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        visibility: hidden;
        pointer-events: none;
    }
    .galeria-photo-grid.is-select-mode .galeria-select-check {
        visibility: visible;
        pointer-events: auto;
    }
    .galeria-photo-card.is-selected .galeria-select-check {
        background: #003366;
        border-color: #fff;
    }
    .galeria-photo-card.is-selected .galeria-select-check::after {
        content: '';
        position: absolute;
        left: 8px;
        top: 4px;
        width: 7px;
        height: 12px;
        border: solid #fff;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
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

    .galeria-pagination {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 0.4rem;
        width: 100%;
        margin-top: 1.5rem;
    }
    .galeria-pagination[hidden] { display: none; }
    .galeria-page-btn {
        min-width: 40px;
        height: 40px;
        padding: 0 0.7rem;
        border: 1px solid rgba(0, 51, 102, 0.2);
        border-radius: 8px;
        background: #fff;
        color: #003366;
        font-family: "Roboto", sans-serif;
        font-size: 0.9rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .galeria-page-btn:hover:not(:disabled) { background: #003366; color: #fff; }
    .galeria-page-btn.is-active { background: #003366; color: #fff; border-color: #003366; }
    .galeria-page-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    .galeria-page-ellipsis {
        color: #555555;
        padding: 0 0.25rem;
        font-family: "Roboto", sans-serif;
    }
    .galeria-page-info {
        flex: 0 0 100%;
        width: 100%;
        text-align: right;
        margin-top: 0.35rem;
        font-size: 0.85rem;
        color: #555555;
        font-family: "Roboto", sans-serif;
    }

    /* Barra de ferramentas: seleção e ordenação */
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
    .galeria-select-toggle > span { display: inline-flex; align-items: center; gap: 0.4rem; }
    .galeria-select-toggle > .galeria-toggle-off { display: none; }
    .galeria-select-toggle.is-active > .galeria-toggle-on { display: none; }
    .galeria-select-toggle.is-active > .galeria-toggle-off { display: inline-flex; }
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

    <div class="galeria-evento-header">
        <h1 class="galeria-evento-title">{{ $evento['title'] }}</h1>
        <a href="{{ route('galeria') }}" class="galeria-evento-back">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Voltar para as galerias
        </a>
    </div>

    <div class="galeria-evento-meta">
        <div class="galeria-evento-meta-info">
            @if($evento['dateLong'])
                <span><i class="bi bi-calendar-event"></i> {{ $evento['dateLong'] }}</span>
            @endif
            @if(count($fotos) > 0)
                <span><i class="bi bi-images"></i> {{ count($fotos) }} foto{{ count($fotos) !== 1 ? 's' : '' }}</span>
            @endif
        </div>
        @if(count($fotos) > 0)
            <button type="button" class="galeria-toolbar-btn galeria-select-toggle" id="galeriaSelectToggle">
                <span class="galeria-toggle-on"><i class="bi bi-check2-square"></i> Selecionar fotos</span>
                <span class="galeria-toggle-off"><i class="bi bi-x-lg"></i> Cancelar seleção</span>
            </button>
        @endif
    </div>

    @if(count($fotos) === 0)
        <div class="galeria-empty">Nenhuma foto encontrada nesta programação.</div>
    @else
        <div class="galeria-photo-grid" id="galeriaPhotoGrid" data-download-url="{{ route('galeria.download', $evento['id']) }}" data-page-size="{{ $fotosPorLote }}"></div>
        <nav class="galeria-pagination" id="galeriaPagination" aria-label="Paginação das fotos" hidden></nav>

        <script type="application/json" id="galeriaFotosData">{!! json_encode($fotos, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
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
<script src="{{ asset('js/galeria.js') }}?v={{ filemtime(public_path('js/galeria.js')) }}" defer></script>
@endpush
