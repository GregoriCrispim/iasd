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
    .galeria-meta-actions {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-left: auto;
        flex-wrap: wrap;
    }

    .galeria-face-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        background: #eef4fb;
        border: 1px solid rgba(0, 51, 102, 0.15);
        color: #003366;
        font-size: 0.92rem;
    }
    .galeria-face-banner[hidden] { display: none; }

    /* Modal de busca facial */
    .galeria-face-modal {
        position: fixed;
        inset: 0;
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.6);
        padding: 16px;
        backdrop-filter: blur(3px);
    }
    .galeria-face-modal.is-open { display: flex; }
    .galeria-face-dialog {
        background: #fff;
        border-radius: 16px;
        max-width: 520px;
        width: 100%;
        max-height: 92vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        padding: clamp(20px, 3vw, 32px);
    }
    .galeria-face-dialog h2 {
        font-family: "Bebas Neue", "Noto Sans JP", sans-serif;
        color: #003366;
        margin: 0 0 0.25rem;
        font-size: 1.8rem;
        letter-spacing: 0.5px;
    }
    .galeria-face-dialog p.face-sub { color: #555; font-size: 0.9rem; margin: 0 0 1rem; }
    .galeria-face-tabs { display: flex; gap: 0.5rem; margin-bottom: 1rem; }
    .galeria-face-tab {
        flex: 1;
        padding: 0.55rem;
        border: 1px solid rgba(0, 51, 102, 0.25);
        border-radius: 8px;
        background: #fff;
        color: #003366;
        cursor: pointer;
        font-size: 0.9rem;
    }
    .galeria-face-tab.is-active { background: #003366; color: #fff; }
    .galeria-face-stage {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        max-height: 320px;
        border-radius: 12px;
        overflow: hidden;
        background: #0b1b2e;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .galeria-face-stage video, .galeria-face-stage img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .galeria-face-stage .face-placeholder { color: rgba(255,255,255,0.6); font-size: 0.9rem; text-align: center; padding: 1rem; }
    .galeria-face-hidden { display: none !important; }
    .galeria-face-buttons { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .galeria-face-consents { margin-bottom: 1rem; }
    .galeria-face-consents label { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.82rem; color: #444; margin-bottom: 0.6rem; line-height: 1.4; }
    .galeria-face-consents input { margin-top: 0.2rem; }
    #galeriaFaceGuardian { display: none; }
    #galeriaFaceGuardian.is-visible { display: block; }
    .galeria-face-status { font-size: 0.85rem; color: #555; min-height: 1.2rem; margin-bottom: 0.75rem; }
    .galeria-face-status.is-error { color: #a12622; }
    .galeria-face-foot { display: flex; gap: 0.5rem; justify-content: flex-end; }
    .galeria-face-close {
        position: absolute;
        top: 14px; right: 14px;
        background: rgba(0,0,0,0.06);
        border: none;
        border-radius: 50%;
        width: 34px; height: 34px;
        cursor: pointer;
        color: #333;
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

    /* CTA de visitante: destaque para incentivar o login antes da busca facial */
    .galeria-face-cta {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.6rem 1.15rem;
        border: none;
        border-radius: 999px;
        background: linear-gradient(135deg, #0a5aa8 0%, #003366 58%, #00223f 100%);
        color: #fff;
        font-size: 0.9rem;
        font-weight: 600;
        font-family: "Roboto", sans-serif;
        line-height: 1;
        text-decoration: none;
        cursor: pointer;
        box-shadow: 0 6px 18px rgba(0, 51, 102, 0.28);
        transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
    }
    .galeria-face-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 51, 102, 0.38);
        filter: brightness(1.06);
    }
    .galeria-face-cta:active { transform: translateY(0); box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3); }
    .galeria-face-cta:focus-visible { outline: 3px solid rgba(10, 90, 168, 0.45); outline-offset: 3px; }
    .galeria-face-cta .galeria-face-cta-icon { font-size: 1.1rem; }
    .galeria-face-cta .galeria-face-cta-arrow { font-size: 1.15rem; transition: transform 0.18s ease; }
    .galeria-face-cta:hover .galeria-face-cta-arrow { transform: translateX(3px); }
    .galeria-face-cta.is-secondary {
        background: #fff;
        color: #003366;
        border: 1px solid rgba(0, 51, 102, 0.28);
        box-shadow: none;
    }
    .galeria-face-cta.is-secondary:hover {
        background: #f4f8fc;
        filter: none;
        box-shadow: 0 6px 16px rgba(0, 51, 102, 0.12);
    }
    .galeria-face-auth {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        flex-wrap: wrap;
    }

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

    @php
        $faceUser = auth('web')->user();
        $faceEnabled = (bool) config('face.enabled', true);
        $faceCanSearch = $faceUser && $faceUser->isActiveMember();
    @endphp

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
            <div class="galeria-meta-actions">
                @if($faceEnabled)
                    @if($faceCanSearch)
                        <button type="button" class="galeria-toolbar-btn primary" id="galeriaFaceSearchBtn">
                            <i class="bi bi-person-bounding-box"></i> Encontrar minhas fotos
                        </button>
                    @else
                        <div class="galeria-face-auth">
                            <a href="{{ route('member.login', ['redirect' => url()->current()]) }}" class="galeria-face-cta is-secondary" title="Entre com sua conta de membro">
                                <i class="bi bi-box-arrow-in-right galeria-face-cta-icon" aria-hidden="true"></i>
                                <span>Entrar</span>
                            </a>
                            <a href="{{ route('member.register', ['redirect' => url()->current()]) }}" class="galeria-face-cta" title="Crie uma conta de membro para localizar as fotos em que você aparece">
                                <i class="bi bi-stars galeria-face-cta-icon" aria-hidden="true"></i>
                                <span>Criar conta</span>
                                <i class="bi bi-arrow-right-short galeria-face-cta-arrow" aria-hidden="true"></i>
                            </a>
                        </div>
                    @endif
                @endif
                <button type="button" class="galeria-toolbar-btn galeria-select-toggle" id="galeriaSelectToggle">
                    <span class="galeria-toggle-on"><i class="bi bi-check2-square"></i> Selecionar fotos</span>
                    <span class="galeria-toggle-off"><i class="bi bi-x-lg"></i> Cancelar seleção</span>
                </button>
            </div>
        @endif
    </div>

    @if(count($fotos) > 0 && $faceEnabled && $faceCanSearch)
        <div class="galeria-face-banner" id="galeriaFaceBanner" hidden>
            <span id="galeriaFaceBannerText"></span>
            <button type="button" class="galeria-toolbar-btn" id="galeriaFaceClear"><i class="bi bi-x-circle"></i> Limpar busca</button>
        </div>
    @endif

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

    @if(count($fotos) > 0 && $faceEnabled && $faceCanSearch)
        <div class="galeria-face-modal" id="galeriaFaceModal" role="dialog" aria-modal="true" aria-labelledby="galeriaFaceTitle">
            <div class="galeria-face-dialog">
                <button type="button" class="galeria-face-close" id="galeriaFaceModalClose" aria-label="Fechar"><i class="bi bi-x-lg"></i></button>
                <h2 id="galeriaFaceTitle">Encontrar minhas fotos</h2>
                <p class="face-sub">Tire uma selfie ou envie uma foto sua. O reconhecimento acontece no seu dispositivo — só um código numérico temporário é enviado para comparar com as fotos deste álbum.</p>

                <div class="galeria-face-tabs">
                    <button type="button" class="galeria-face-tab is-active" data-mode="camera"><i class="bi bi-camera"></i> Câmera</button>
                    <button type="button" class="galeria-face-tab" data-mode="upload"><i class="bi bi-upload"></i> Enviar foto</button>
                </div>

                <div class="galeria-face-stage" id="galeriaFaceStage">
                    <div class="face-placeholder" id="galeriaFacePlaceholder">Toque em “Ativar câmera” para começar.</div>
                    <video id="galeriaFaceVideo" class="galeria-face-hidden" playsinline muted></video>
                    <img id="galeriaFacePreview" class="galeria-face-hidden" alt="Pré-visualização">
                </div>

                <input type="file" id="galeriaFaceFile" accept="image/*" hidden>

                <div class="galeria-face-buttons">
                    <button type="button" class="galeria-toolbar-btn" id="galeriaFaceCameraStart"><i class="bi bi-camera-video"></i> Ativar câmera</button>
                    <button type="button" class="galeria-toolbar-btn" id="galeriaFaceCapture" hidden><i class="bi bi-camera"></i> Capturar</button>
                    <button type="button" class="galeria-toolbar-btn galeria-face-hidden" id="galeriaFacePick"><i class="bi bi-image"></i> Escolher foto</button>
                    <button type="button" class="galeria-toolbar-btn" id="galeriaFaceRetake" hidden><i class="bi bi-arrow-counterclockwise"></i> Refazer</button>
                </div>

                <div class="galeria-face-consents">
                    <label><input type="checkbox" id="galeriaConsentSelf"> Confirmo que sou a pessoa retratada e não estou enviando imagem de terceiro.</label>
                    <label><input type="checkbox" id="galeriaConsentBiometric"> Autorizo o uso temporário do meu descriptor biométrico exclusivamente para localizar minhas fotos neste álbum.</label>
                    <label><input type="checkbox" id="galeriaConsentLimitations"> Entendo que o recurso pode apresentar falsos positivos/negativos, não comprova identidade e não possui verificação de vivacidade (liveness).</label>
                    <div id="galeriaFaceGuardian" class="{{ $faceUser && $faceUser->isMinor() ? 'is-visible' : '' }}">
                        <label><input type="checkbox" id="galeriaConsentGuardian"> Declaro que quem realiza esta busca é o responsável legal pelo menor e autoriza o tratamento dos dados.</label>
                    </div>
                </div>

                <div class="galeria-face-status" id="galeriaFaceStatus"></div>

                <div class="galeria-face-foot">
                    <button type="button" class="galeria-toolbar-btn" id="galeriaFaceCancel">Cancelar</button>
                    <button type="button" class="galeria-toolbar-btn primary" id="galeriaFaceSubmit" disabled><i class="bi bi-search"></i> Buscar minhas fotos</button>
                </div>
            </div>
        </div>

        <script type="application/json" id="faceConfig">{!! json_encode([
            'scriptUrl' => config('face.script_url'),
            'modelsUrl' => config('face.models_url'),
            'searchUrl' => route('galeria.busca-facial', $evento['id']),
            'csrf' => csrf_token(),
            'isMinor' => (bool) ($faceUser && $faceUser->isMinor()),
            'selfie' => [
                'minScore' => (float) config('face.detection.selfie.min_score', 0.7),
                'minSizeRatio' => (float) config('face.detection.selfie.min_size_ratio', 0.12),
                'maxSide' => (int) config('face.detection.selfie.analysis_max_side', 640),
            ],
        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    @endif

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/galeria.js') }}?v={{ filemtime(public_path('js/galeria.js')) }}" defer></script>
@if(count($fotos) > 0 && $faceEnabled && $faceCanSearch)
<script src="{{ asset('js/face-engine.js') }}?v={{ filemtime(public_path('js/face-engine.js')) }}" defer></script>
<script src="{{ asset('js/face-search.js') }}?v={{ filemtime(public_path('js/face-search.js')) }}" defer></script>
@endif
@endpush
