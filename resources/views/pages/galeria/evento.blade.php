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

    .galeria-face-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        min-height: 2.4rem;
    }
    .galeria-face-btn.is-processing {
        background: #ffffff !important;
        color: #1f7a4d !important;
        border: 1px solid rgba(31, 122, 77, 0.28);
        cursor: default;
        pointer-events: none;
    }
    .galeria-face-btn.is-processing:hover {
        background: #ffffff !important;
        color: #1f7a4d !important;
    }
    .galeria-face-btn-loader {
        position: relative;
        width: 1.7rem;
        height: 1.7rem;
        flex-shrink: 0;
    }
    .galeria-face-btn-loader svg {
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
        display: block;
    }
    .galeria-face-btn-loader .face-ring-bg {
        fill: none;
        stroke: rgba(31, 122, 77, 0.18);
        stroke-width: 3.5;
    }
    .galeria-face-btn-loader .face-ring-fg {
        fill: none;
        stroke: #22a06b;
        stroke-width: 3.5;
        stroke-linecap: round;
        transition: stroke-dashoffset 0.35s ease;
    }
    .galeria-face-btn-pct {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: "Roboto", sans-serif;
        font-size: 0.58rem;
        font-weight: 700;
        color: #1f7a4d;
        line-height: 1;
        pointer-events: none;
    }
    .galeria-face-btn-label {
        font-weight: 600;
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
    .galeria-face-banner-actions {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* Overlay de progresso da busca facial (após fechar o modal) */
    .galeria-face-search-overlay {
        --galeria-face-pad: 16px;
        position: fixed;
        top: var(--header-height);
        left: 0;
        right: 14.28%;
        bottom: 0;
        z-index: 10001;
        display: none;
        align-items: center;
        justify-content: center;
        padding: var(--galeria-face-pad);
        background: rgba(8, 18, 32, 0.72);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        box-sizing: border-box;
    }
    .galeria-face-search-overlay.is-open { display: flex; }
    .galeria-face-search-card {
        width: min(100%, 380px);
        text-align: center;
        padding: clamp(1.5rem, 4vw, 2.25rem) clamp(1.25rem, 3vw, 2rem);
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #f4f8fc 100%);
        border: 1px solid rgba(0, 51, 102, 0.12);
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.28);
        color: #003366;
    }
    .galeria-face-search-spinner {
        width: 52px;
        height: 52px;
        margin: 0 auto 1.1rem;
        border-radius: 50%;
        border: 3px solid rgba(0, 51, 102, 0.14);
        border-top-color: #0a5aa8;
        animation: galeria-spin 0.85s linear infinite;
    }
    .galeria-face-search-card.is-done .galeria-face-search-spinner {
        display: none;
    }
    .galeria-face-search-check {
        display: none;
        width: 52px;
        height: 52px;
        margin: 0 auto 1.1rem;
        border-radius: 50%;
        align-items: center;
        justify-content: center;
        background: #e8f5ee;
        color: #1f7a4d;
        font-size: 1.5rem;
    }
    .galeria-face-search-card.is-done .galeria-face-search-check {
        display: inline-flex;
    }
    .galeria-face-search-card.is-error .galeria-face-search-spinner { display: none; }
    .galeria-face-search-card.is-error .galeria-face-search-check {
        display: inline-flex;
        background: #fdecea;
        color: #a12622;
    }
    .galeria-face-search-title {
        margin: 0 0 0.35rem;
        font-family: "Bebas Neue", "Noto Sans JP", sans-serif;
        font-size: clamp(1.45rem, 4vw, 1.75rem);
        letter-spacing: 0.4px;
        color: #003366;
    }
    .galeria-face-search-status {
        margin: 0 0 1rem;
        font-size: 0.92rem;
        color: #556;
        line-height: 1.4;
        min-height: 1.35em;
    }
    .galeria-face-search-count {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.15rem;
        margin: 0 auto;
        padding: 0.85rem 1rem;
        border-radius: 12px;
        background: rgba(0, 51, 102, 0.06);
        min-width: 9rem;
    }
    .galeria-face-search-count-num {
        font-family: "Bebas Neue", "Noto Sans JP", sans-serif;
        font-size: clamp(2.4rem, 8vw, 3.2rem);
        line-height: 1;
        letter-spacing: 1px;
        color: #003366;
        font-variant-numeric: tabular-nums;
    }
    .galeria-face-search-count-label {
        font-size: 0.82rem;
        color: #5a6b7d;
        font-weight: 500;
    }
    .galeria-face-search-retry {
        display: none;
        margin-top: 1.1rem;
    }
    .galeria-face-search-card.is-error .galeria-face-search-retry {
        display: inline-flex;
    }

    @media (max-width: 1100px) {
        .galeria-face-search-overlay { right: 0; }
    }

    /* Modal de busca facial — respeita o header fixo e a coluna lateral (aside),
       no mesmo padrão do lightbox / form-overlay do site */
    .galeria-face-modal {
        --galeria-face-pad: 16px;
        position: fixed;
        top: var(--header-height);
        left: 0;
        right: 14.28%;
        bottom: 0;
        z-index: 10000;
        display: none;
        justify-content: center;
        align-items: flex-start;
        background: rgba(0, 0, 0, 0.6);
        padding: var(--galeria-face-pad);
        padding-bottom: max(var(--galeria-face-pad), env(safe-area-inset-bottom, 0px));
        backdrop-filter: blur(3px);
        overflow-x: hidden;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
        box-sizing: border-box;
    }
    .galeria-face-modal.is-open { display: flex; }
    .galeria-face-dialog {
        position: relative;
        background: #fff;
        border-radius: 16px;
        max-width: 720px;
        width: 100%;
        margin: auto;
        max-height: calc(100vh - var(--header-height) - 32px);
        max-height: calc(100dvh - var(--header-height) - 32px);
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        overflow: hidden;
    }
    .galeria-face-dialog-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-x: hidden;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        padding: clamp(16px, 3vw, 28px);
        padding-bottom: 12px;
    }
    .galeria-face-dialog h2 {
        font-family: "Bebas Neue", "Noto Sans JP", sans-serif;
        color: #003366;
        margin: 0 0 0.25rem;
        font-size: clamp(1.35rem, 4.5vw, 1.8rem);
        letter-spacing: 0.5px;
        padding-right: 2rem;
    }
    .galeria-face-dialog p.face-sub { color: #555; font-size: 0.9rem; margin: 0 0 1rem; }
    .galeria-face-tabs { display: flex; gap: 0.4rem; margin-bottom: 1rem; padding: 0.28rem; background: #f3f6f9; border-radius: 12px; }
    .galeria-face-tab {
        flex: 1;
        padding: 0.55rem 0.6rem;
        border: none;
        border-radius: 9px;
        background: transparent;
        color: #4a5d70;
        cursor: pointer;
        font-family: "Roboto", sans-serif;
        font-size: 0.88rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        transition: background 0.18s ease, color 0.18s ease, box-shadow 0.18s ease;
    }
    .galeria-face-tab:hover { color: #003366; }
    .galeria-face-tab.is-active {
        background: #fff;
        color: #003366;
        box-shadow: 0 1px 3px rgba(0, 51, 102, 0.1), 0 0 0 1px rgba(0, 51, 102, 0.06);
    }
    .galeria-face-stage {
        --face-stage-fg: #003366;
        --face-stage-muted: #5f7388;
        --face-stage-accent: #0a5aa8;
        position: relative;
        width: min(100%, 360px);
        aspect-ratio: 1 / 1;
        max-height: min(360px, 36vh, 36dvh);
        margin: 0 auto 1rem;
        border-radius: 20px;
        overflow: hidden;
        background: linear-gradient(165deg, #f7fafc 0%, #eef4fa 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px dashed rgba(0, 51, 102, 0.2);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9);
        transition:
            border-color 0.22s ease,
            border-style 0.22s ease,
            box-shadow 0.22s ease,
            background 0.22s ease,
            transform 0.22s ease;
    }
    .galeria-face-stage.is-idle {
        cursor: pointer;
    }
    .galeria-face-stage.is-idle:hover {
        border-style: solid;
        border-color: rgba(10, 90, 168, 0.42);
        background: linear-gradient(165deg, #f3f8fd 0%, #e7f1fa 100%);
        box-shadow:
            0 10px 28px rgba(0, 51, 102, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 0.95);
        transform: translateY(-1px);
    }
    .galeria-face-stage.is-idle:focus-visible {
        outline: 3px solid rgba(10, 90, 168, 0.35);
        outline-offset: 3px;
    }
    .galeria-face-stage.is-dragover {
        border-style: solid;
        border-color: var(--face-stage-accent);
        background: linear-gradient(165deg, #eaf4fc 0%, #dcecf8 100%);
        box-shadow:
            0 0 0 4px rgba(10, 90, 168, 0.12),
            0 12px 30px rgba(0, 51, 102, 0.1);
        transform: scale(1.01);
    }
    .galeria-face-stage.is-live,
    .galeria-face-stage.is-preview {
        border-style: solid;
        border-color: rgba(0, 51, 102, 0.08);
        background: #0d1a2b;
        box-shadow: none;
        cursor: default;
        transform: none;
    }
    .galeria-face-stage video, .galeria-face-stage img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    /* Preview ao vivo espelhado (selfie); a captura no canvas usa o frame bruto */
    .galeria-face-stage video {
        transform: scaleX(-1);
    }
    .galeria-face-stage .face-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        color: var(--face-stage-fg);
        text-align: center;
        padding: 1.5rem 1.6rem;
        pointer-events: none;
        user-select: none;
        max-width: 17rem;
    }
    .galeria-face-stage .face-drop-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.55rem;
        background: #fff;
        border: 1px solid rgba(0, 51, 102, 0.08);
        box-shadow:
            0 8px 20px rgba(0, 51, 102, 0.08),
            0 1px 0 rgba(255, 255, 255, 0.9);
        color: var(--face-stage-accent);
        font-size: 1.55rem;
        transition: transform 0.22s ease, box-shadow 0.22s ease, color 0.22s ease, background 0.22s ease;
    }
    .galeria-face-stage.is-idle:hover .face-drop-icon {
        transform: translateY(-2px) scale(1.04);
        color: #084f94;
        box-shadow:
            0 12px 26px rgba(0, 51, 102, 0.12),
            0 1px 0 rgba(255, 255, 255, 0.95);
    }
    .galeria-face-stage.is-dragover .face-drop-icon {
        transform: scale(1.08);
        background: var(--face-stage-accent);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 10px 24px rgba(10, 90, 168, 0.28);
    }
    .galeria-face-stage .face-drop-title {
        margin: 0;
        font-family: "Roboto", sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--face-stage-fg);
        line-height: 1.35;
        letter-spacing: 0;
    }
    .galeria-face-stage .face-drop-hint {
        margin: 0;
        font-family: "Roboto", sans-serif;
        font-size: 0.86rem;
        font-weight: 400;
        color: var(--face-stage-muted);
        line-height: 1.45;
        max-width: 14.5rem;
    }
    .galeria-face-stage.is-dragover .face-drop-title {
        color: var(--face-stage-accent);
    }
    .galeria-face-stage.is-dragover .face-drop-hint {
        color: #3d5f7a;
    }
    .galeria-face-hidden { display: none !important; }
    .galeria-face-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 1rem;
        min-height: 0;
    }
    .galeria-face-buttons:empty,
    .galeria-face-buttons.is-empty { display: none; }
    .galeria-face-consents { margin-bottom: 0.5rem; }
    .galeria-face-consents-legend {
        display: flex;
        align-items: baseline;
        flex-wrap: nowrap;
        gap: 0.35rem;
        margin: 0 0 0.65rem;
        font-family: "Roboto", sans-serif;
        font-size: 0.84rem;
        font-weight: 600;
        color: #003366;
        white-space: nowrap;
    }
    .galeria-face-consents-legend .face-req {
        font-weight: 500;
        color: #8a2f2b;
        font-size: 0.78rem;
        white-space: nowrap;
    }
    .galeria-face-consents label {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        font-size: 0.82rem;
        color: #444;
        margin-bottom: 0.7rem;
        line-height: 1.4;
        cursor: pointer;
    }
    .galeria-face-consents input[type="checkbox"] {
        width: 1.15rem;
        height: 1.15rem;
        min-width: 1.15rem;
        min-height: 1.15rem;
        margin-top: 0.12rem;
        flex-shrink: 0;
        cursor: pointer;
        accent-color: #003366;
    }
    .galeria-face-consents .face-req {
        color: #c0392b;
        font-weight: 700;
        margin-left: 0.15rem;
    }
    .galeria-face-foot .galeria-toolbar-btn.primary:disabled {
        opacity: 0.45;
        cursor: not-allowed;
        filter: grayscale(0.15);
    }
    #galeriaFaceGuardian { display: none; }
    #galeriaFaceGuardian.is-visible { display: block; }
    .galeria-face-status { font-size: 0.85rem; color: #555; min-height: 1.2rem; margin-bottom: 0.5rem; }
    .galeria-face-status.is-error { color: #a12622; }
    .galeria-face-foot {
        flex: 0 0 auto;
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
        flex-wrap: wrap;
        padding: 12px clamp(16px, 3vw, 28px);
        border-top: 1px solid rgba(0, 51, 102, 0.1);
        background: #fff;
    }
    .galeria-face-close {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 2;
        background: rgba(0,0,0,0.06);
        border: none;
        border-radius: 50%;
        width: 34px; height: 34px;
        cursor: pointer;
        color: #333;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 1100px) {
        .galeria-face-modal { right: 0; }
        .galeria-face-search-overlay { right: 0; }
    }

    @media (max-width: 768px) {
        .galeria-face-modal { --galeria-face-pad: 12px; }
        .galeria-face-dialog {
            max-width: 100%;
            border-radius: 14px;
            max-height: calc(100vh - var(--header-height) - 24px);
            max-height: calc(100dvh - var(--header-height) - 24px);
        }
    }

    @media (max-width: 480px) {
        .galeria-face-dialog-body { padding: 14px 14px 8px; }
        .galeria-face-dialog p.face-sub { font-size: 0.84rem; margin-bottom: 0.75rem; }
        .galeria-face-tabs { margin-bottom: 0.75rem; }
        .galeria-face-tab { font-size: 0.84rem; padding: 0.5rem 0.4rem; }
        .galeria-face-stage {
            max-height: min(220px, 28vh, 28dvh);
            margin-bottom: 0.75rem;
        }
        .galeria-face-buttons { margin-bottom: 0.75rem; }
        .galeria-face-buttons .galeria-toolbar-btn {
            flex: 1 1 calc(50% - 0.25rem);
            justify-content: center;
            font-size: 0.82rem;
            padding: 0.45rem 0.6rem;
        }
        .galeria-face-consents label { font-size: 0.78rem; margin-bottom: 0.5rem; }
        .galeria-face-foot {
            padding: 10px 14px calc(10px + env(safe-area-inset-bottom, 0px));
        }
        .galeria-face-foot .galeria-toolbar-btn {
            flex: 1 1 auto;
            justify-content: center;
        }
    }

    @media (max-height: 700px) {
        .galeria-face-stage {
            max-height: min(200px, 26vh, 26dvh);
        }
        .galeria-face-dialog h2 { font-size: 1.35rem; }
        .galeria-face-dialog p.face-sub { margin-bottom: 0.6rem; }
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

    a.galeria-toolbar-btn { text-decoration: none; }

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
        $faceCanSearch = $faceUser && $faceUser->canUseFaceSearch();
        $faceProgress = $faceProgress ?? [
            'total' => 0,
            'scanned' => 0,
            'pending' => 0,
            'ready' => 0,
            'no_face' => 0,
            'failed' => 0,
            'percent' => 0,
            'complete' => false,
        ];
        $faceAlbumReady = (bool) ($faceProgress['complete'] ?? false);
        $facePercent = (int) ($faceProgress['percent'] ?? 0);
        $faceRingC = 2 * M_PI * 15.5;
        $faceRingOffset = $faceRingC * (1 - max(0, min(100, $facePercent)) / 100);
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
                    @if(! $faceAlbumReady)
                        <button
                            type="button"
                            class="galeria-toolbar-btn primary galeria-face-btn is-processing"
                            id="galeriaFaceSearchBtn"
                            disabled
                            aria-disabled="true"
                            title="Indexação facial em andamento"
                            data-face-progress-url="{{ route('galeria.faces.progress', $evento['id']) }}"
                            data-face-login-url="{{ route('member.login', ['redirect' => request()->fullUrlWithQuery(['face' => 1])]) }}"
                            data-face-can-search="{{ $faceCanSearch ? '1' : '0' }}"
                            data-face-percent="{{ $facePercent }}"
                        >
                            <span class="galeria-face-btn-loader" aria-hidden="true">
                                <svg viewBox="0 0 36 36" focusable="false">
                                    <circle class="face-ring-bg" cx="18" cy="18" r="15.5"></circle>
                                    <circle
                                        class="face-ring-fg"
                                        id="galeriaFaceBtnRing"
                                        cx="18" cy="18" r="15.5"
                                        stroke-dasharray="{{ number_format($faceRingC, 3, '.', '') }}"
                                        stroke-dashoffset="{{ number_format($faceRingOffset, 3, '.', '') }}"
                                    ></circle>
                                </svg>
                                <span class="galeria-face-btn-pct" id="galeriaFaceBtnPct">{{ $facePercent }}%</span>
                            </span>
                            <span class="galeria-face-btn-label" id="galeriaFaceBtnLabel">Processando…</span>
                        </button>
                    @elseif($faceCanSearch)
                        <button
                            type="button"
                            class="galeria-toolbar-btn primary galeria-face-btn"
                            id="galeriaFaceSearchBtn"
                            title="Localizar as fotos em que você aparece"
                            data-face-progress-url="{{ route('galeria.faces.progress', $evento['id']) }}"
                            data-face-login-url="{{ route('member.login', ['redirect' => request()->fullUrlWithQuery(['face' => 1])]) }}"
                            data-face-can-search="1"
                            data-face-percent="100"
                        >
                            <i class="bi bi-person-bounding-box"></i>
                            <span class="galeria-face-btn-label">Reconhecimento Facial</span>
                        </button>
                    @else
                        <a
                            href="{{ route('member.login', ['redirect' => request()->fullUrlWithQuery(['face' => 1])]) }}"
                            class="galeria-toolbar-btn primary galeria-face-btn"
                            id="galeriaFaceSearchBtn"
                            title="Entre com sua conta de membro para usar o reconhecimento facial"
                            data-face-progress-url="{{ route('galeria.faces.progress', $evento['id']) }}"
                            data-face-login-url="{{ route('member.login', ['redirect' => request()->fullUrlWithQuery(['face' => 1])]) }}"
                            data-face-can-search="0"
                            data-face-percent="100"
                        >
                            <i class="bi bi-person-bounding-box"></i>
                            <span class="galeria-face-btn-label">Reconhecimento Facial</span>
                        </a>
                    @endif
                @endif
                <button type="button" class="galeria-toolbar-btn galeria-select-toggle" id="galeriaSelectToggle">
                    <span class="galeria-toggle-on"><i class="bi bi-check2-square"></i> Selecionar fotos</span>
                    <span class="galeria-toggle-off"><i class="bi bi-x-lg"></i> Cancelar seleção</span>
                </button>
            </div>
        @endif
    </div>

    @if(count($fotos) > 0 && $faceEnabled && $faceCanSearch && $faceAlbumReady)
        <div class="galeria-face-banner" id="galeriaFaceBanner" hidden>
            <span id="galeriaFaceBannerText"></span>
            <div class="galeria-face-banner-actions">
                <button type="button" class="galeria-toolbar-btn" id="galeriaFaceClear"><i class="bi bi-images"></i> Ver todas</button>
            </div>
        </div>

        <div class="galeria-face-search-overlay" id="galeriaFaceSearchOverlay" aria-live="polite" aria-busy="false" hidden>
            <div class="galeria-face-search-card" id="galeriaFaceSearchCard">
                <div class="galeria-face-search-spinner" aria-hidden="true"></div>
                <div class="galeria-face-search-check" id="galeriaFaceSearchCheck" aria-hidden="true"><i class="bi bi-check-lg"></i></div>
                <h3 class="galeria-face-search-title" id="galeriaFaceSearchTitle">Buscando fotos</h3>
                <p class="galeria-face-search-status" id="galeriaFaceSearchStatus">Preparando…</p>
                <div class="galeria-face-search-count">
                    <span class="galeria-face-search-count-num" id="galeriaFaceSearchCount">0</span>
                    <span class="galeria-face-search-count-label" id="galeriaFaceSearchCountLabel">fotos encontradas</span>
                </div>
                <button type="button" class="galeria-toolbar-btn galeria-face-search-retry" id="galeriaFaceSearchRetry">
                    <i class="bi bi-arrow-counterclockwise"></i> Tentar novamente
                </button>
            </div>
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

    @if(count($fotos) > 0 && $faceEnabled && $faceCanSearch && $faceAlbumReady)
        <div class="galeria-face-modal" id="galeriaFaceModal" role="dialog" aria-modal="true" aria-labelledby="galeriaFaceTitle">
            <div class="galeria-face-dialog">
                <button type="button" class="galeria-face-close" id="galeriaFaceModalClose" aria-label="Fechar"><i class="bi bi-x-lg"></i></button>
                <div class="galeria-face-dialog-body">
                    <h2 id="galeriaFaceTitle">Reconhecimento Facial</h2>
                    <p class="face-sub">Tire uma selfie ou envie uma foto sua. O reconhecimento acontece no seu dispositivo.</p>

                    <div class="galeria-face-tabs">
                        <button type="button" class="galeria-face-tab is-active" data-mode="camera"><i class="bi bi-camera"></i> Câmera</button>
                        <button type="button" class="galeria-face-tab" data-mode="upload"><i class="bi bi-upload"></i> Enviar foto</button>
                    </div>

                    <div
                        class="galeria-face-stage is-idle"
                        id="galeriaFaceStage"
                        role="button"
                        tabindex="0"
                        aria-label="Clique para ativar a câmera ou arraste uma imagem para carregar a foto"
                    >
                        <div class="face-placeholder" id="galeriaFacePlaceholder">
                            <span class="face-drop-icon" aria-hidden="true"><i class="bi bi-camera"></i></span>
                            <p class="face-drop-title" id="galeriaFaceDropTitle">Ativar câmera</p>
                            <p class="face-drop-hint" id="galeriaFaceDropHint">Clique aqui ou arraste uma foto para começar</p>
                        </div>
                        <video id="galeriaFaceVideo" class="galeria-face-hidden" playsinline muted></video>
                        <img id="galeriaFacePreview" class="galeria-face-hidden" alt="Pré-visualização">
                    </div>

                    <input type="file" id="galeriaFaceFile" accept="image/*" hidden>

                    <div class="galeria-face-buttons is-empty" id="galeriaFaceButtons">
                        <button type="button" class="galeria-toolbar-btn galeria-face-hidden" id="galeriaFaceCapture" hidden><i class="bi bi-camera"></i> Capturar</button>
                        <button type="button" class="galeria-toolbar-btn galeria-face-hidden" id="galeriaFaceRetake" hidden><i class="bi bi-arrow-counterclockwise"></i> Refazer</button>
                    </div>

                    <div class="galeria-face-consents">
                        <p class="galeria-face-consents-legend">Autorizações<span class="face-req">* obrigatórias</span></p>
                        <label>
                            <input type="checkbox" id="galeriaConsentSelf" required>
                            <span>Confirmo que sou a pessoa retratada e não estou enviando imagem de terceiro.<span class="face-req" aria-hidden="true">*</span></span>
                        </label>
                        <label>
                            <input type="checkbox" id="galeriaConsentBiometric" required>
                            <span>Autorizo o uso temporário do meu descriptor biométrico exclusivamente para localizar minhas fotos neste álbum.<span class="face-req" aria-hidden="true">*</span></span>
                        </label>
                        <label>
                            <input type="checkbox" id="galeriaConsentLimitations" required>
                            <span>Entendo que o recurso pode apresentar falsos positivos/negativos, não comprova identidade e não possui verificação de vivacidade (liveness).<span class="face-req" aria-hidden="true">*</span></span>
                        </label>
                        <div id="galeriaFaceGuardian" class="{{ $faceUser && $faceUser->isMinor() ? 'is-visible' : '' }}">
                            <label>
                                <input type="checkbox" id="galeriaConsentGuardian" {{ $faceUser && $faceUser->isMinor() ? 'required' : '' }}>
                                <span>Declaro que quem realiza esta busca é o responsável legal pelo menor e autoriza o tratamento dos dados.<span class="face-req" aria-hidden="true">*</span></span>
                            </label>
                        </div>
                    </div>

                    <div class="galeria-face-status" id="galeriaFaceStatus"></div>
                </div>

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
            'photoCount' => count($fotos),
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
@if(count($fotos) > 0 && $faceEnabled)
<script src="{{ asset('js/face-progress.js') }}?v={{ filemtime(public_path('js/face-progress.js')) }}" defer></script>
@endif
@if(count($fotos) > 0 && $faceEnabled && $faceCanSearch && $faceAlbumReady)
<script src="{{ asset('js/face-engine.js') }}?v={{ filemtime(public_path('js/face-engine.js')) }}" defer></script>
<script src="{{ asset('js/face-search.js') }}?v={{ filemtime(public_path('js/face-search.js')) }}" defer></script>
@endif
@endpush
