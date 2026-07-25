@extends('layouts.app')

@section('title', 'Galeria de Fotos - IASD Central de Brasília')
@section('meta-description', 'Reviva os melhores momentos da IASD Central de Brasília em fotos: cultos, congressos e programações especiais.')
@section('og-title', 'Galeria de Fotos - IASD Central de Brasília')
@section('og-description', 'Reviva os melhores momentos da nossa igreja em fotos.')
@section('page-name', 'Galeria de Fotos')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<style>
    /* Estilos isolados desta view (prefixo galeria-) */
    .galeria-container {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: clamp(24px, 4vw, 56px) 16px;
    }

    .galeria-header { margin-bottom: 1.5rem; }

    .galeria-title {
        font-family: "Bebas Neue", "Noto Sans JP", sans-serif;
        color: #003366;
        font-size: clamp(2.2rem, 4vw, 3rem);
        letter-spacing: 1px;
        margin: 0 0 8px 0;
    }

    .galeria-subtitle {
        font-family: "Roboto", "Noto Sans JP", sans-serif;
        color: #555555;
        font-size: 0.98rem;
        margin: 0;
    }

    .galeria-search-bar {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .galeria-search-wrap {
        position: relative;
        flex: 1;
        min-width: 220px;
    }

    .galeria-search-wrap i {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #555555;
        font-size: 0.9rem;
    }

    .galeria-search-input {
        width: 100%;
        padding: 0.65rem 1rem 0.65rem 2.4rem;
        border: 1px solid rgba(0, 51, 102, 0.2);
        border-radius: 8px;
        font-size: 0.95rem;
        font-family: "Roboto", sans-serif;
        background: #fff;
        color: #1a1a1a;
    }

    .galeria-filter-select {
        padding: 0.65rem 1rem;
        border: 1px solid rgba(0, 51, 102, 0.2);
        border-radius: 8px;
        font-size: 0.9rem;
        font-family: "Roboto", sans-serif;
        background: #fff;
        color: #1a1a1a;
        cursor: pointer;
    }

    .galeria-search-input:focus,
    .galeria-filter-select:focus {
        outline: none;
        border-color: #003366;
    }

    .galeria-result-count {
        font-size: 0.85rem;
        color: #555555;
        margin-bottom: 1rem;
    }

    .galeria-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.5rem;
    }

    .galeria-card {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding: 1rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
        height: 100%;
    }

    .galeria-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 51, 102, 0.15);
    }

    .galeria-card img,
    .galeria-card-placeholder {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
    }

    .galeria-card-placeholder {
        background: #f0f4f8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.6rem;
        color: #b9c6d6;
    }

    .galeria-card h2 {
        font-family: "Roboto", sans-serif;
        font-size: 1.05rem;
        color: #003366;
        font-weight: 700;
        line-height: 1.3;
        margin: 0;
        text-transform: capitalize;
    }

    .galeria-card-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-top: 0.4rem;
        font-size: 0.85rem;
        color: #555555;
    }

    .galeria-card-meta span { display: inline-flex; align-items: center; gap: 0.35rem; }

    .galeria-empty {
        text-align: center;
        color: #555555;
        padding: 3rem 0;
    }

    @media (max-width: 640px) {
        .galeria-search-bar { flex-direction: column; align-items: stretch; }
    }

    /* Carrossel de fotos recentes */
    .galeria-carrossel {
        position: relative;
        width: 100%;
        height: clamp(220px, 34vw, 420px);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 2rem;
        background: #001531;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .galeria-carrossel-track { position: relative; width: 100%; height: 100%; }

    .galeria-carrossel-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.8s ease;
        display: block;
    }
    .galeria-carrossel-slide.is-active { opacity: 1; visibility: visible; z-index: 1; }

    .galeria-carrossel-slide img { width: 100%; height: 100%; object-fit: cover; display: block; }

    .galeria-carrossel-caption {
        position: absolute;
        left: 0; right: 0; bottom: 0;
        padding: 1.5rem clamp(1rem, 4vw, 2.5rem) 1.25rem;
        background: linear-gradient(transparent, rgba(0, 21, 49, 0.85));
        color: #fff;
        font-family: "Bebas Neue", sans-serif;
        font-size: clamp(1.3rem, 3vw, 1.9rem);
        letter-spacing: 0.5px;
        text-transform: capitalize;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.15rem;
    }

    .galeria-carrossel-date {
        font-family: "Roboto", sans-serif;
        font-size: 0.85rem;
        font-weight: 400;
        letter-spacing: 0;
        text-transform: none;
    }

    .galeria-carrossel-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        background: rgba(0, 0, 0, 0.35);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff;
        width: 42px; height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.2rem;
        transition: background 0.2s;
    }
    .galeria-carrossel-nav:hover { background: rgba(0, 0, 0, 0.6); }
    .galeria-carrossel-nav.prev { left: 14px; }
    .galeria-carrossel-nav.next { right: 14px; }

    .galeria-carrossel-dots { position: absolute; bottom: 14px; right: 16px; z-index: 2; display: flex; gap: 6px; }
    .galeria-carrossel-dot {
        width: 9px; height: 9px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.45);
        cursor: pointer;
        padding: 0;
        transition: background 0.2s, transform 0.2s;
    }
    .galeria-carrossel-dot.is-active { background: #fff; transform: scale(1.2); }

    @media (max-width: 640px) {
        .galeria-carrossel-nav { width: 36px; height: 36px; font-size: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="galeria-container">

    @if(count($carrossel) > 0)
        <div class="galeria-carrossel" id="galeriaCarrossel" role="region" aria-label="Fotos recentes da galeria">
            <div class="galeria-carrossel-track">
                @foreach($carrossel as $index => $item)
                    <a href="{{ route('galeria.show', $item['eventoId']) }}"
                       class="galeria-carrossel-slide{{ $index === 0 ? ' is-active' : '' }}">
                        <img src="{{ $item['thumbUrl'] }}" alt="{{ $item['eventoTitle'] }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}" decoding="async">
                        <span class="galeria-carrossel-caption">
                            <span>{{ $item['eventoTitle'] }}</span>
                            @if($item['eventoDate'])
                                <span class="galeria-carrossel-date">{{ $item['eventoDate'] }}</span>
                            @endif
                        </span>
                    </a>
                @endforeach
            </div>
            @if(count($carrossel) > 1)
                <button type="button" class="galeria-carrossel-nav prev" id="galeriaCarrosselPrev" aria-label="Foto anterior"><i class="bi bi-chevron-left"></i></button>
                <button type="button" class="galeria-carrossel-nav next" id="galeriaCarrosselNext" aria-label="Próxima foto"><i class="bi bi-chevron-right"></i></button>
                <div class="galeria-carrossel-dots" id="galeriaCarrosselDots">
                    @foreach($carrossel as $index => $item)
                        <button type="button" class="galeria-carrossel-dot{{ $index === 0 ? ' is-active' : '' }}" aria-label="Ir para foto {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <div class="galeria-header">
        <h1 class="galeria-title">Galeria de Fotos</h1>
        <p class="galeria-subtitle">Reviva os melhores momentos da nossa igreja.</p>
    </div>

    @if(count($eventos) > 0)
        <div class="galeria-search-bar">
            <div class="galeria-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="galeriaSearchInput" class="galeria-search-input" placeholder="Buscar programação...">
            </div>
            <select id="galeriaMonthFilter" class="galeria-filter-select">
                <option value="">Todos os meses</option>
                @foreach($months as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            <select id="galeriaSortSelect" class="galeria-filter-select">
                <option value="recentes">Mais recentes</option>
                <option value="antigos">Mais antigos</option>
                <option value="nome-az">Nome (A-Z)</option>
                <option value="nome-za">Nome (Z-A)</option>
            </select>
        </div>
    @endif

    <p id="galeriaResultCount" class="galeria-result-count" style="display: none;"></p>

    <div id="galeriaGrid" class="galeria-grid">
        @forelse($eventos as $evento)
            <a href="{{ route('galeria.show', $evento['id']) }}"
               class="galeria-card"
               data-title="{{ mb_strtolower($evento['title']) }}"
               data-month="{{ $evento['monthKey'] }}"
               data-date="{{ $evento['rawDate'] }}">
                @if($evento['coverThumbUrl'])
                    <img src="{{ $evento['coverThumbUrl'] }}" alt="{{ $evento['title'] }}" loading="lazy" decoding="async">
                @else
                    <div class="galeria-card-placeholder"><i class="bi bi-folder-fill"></i></div>
                @endif
                <div>
                    <h2>{{ $evento['title'] }}</h2>
                    <div class="galeria-card-meta">
                        @if($evento['dateShort'])
                            <span><i class="bi bi-calendar-event"></i> {{ $evento['dateShort'] }}</span>
                        @endif
                        @if($evento['photoCount'] > 0)
                            <span><i class="bi bi-images"></i> {{ $evento['photoCount'] }} foto{{ $evento['photoCount'] !== 1 ? 's' : '' }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="galeria-empty">Nenhuma programação publicada ainda. Publique um álbum no painel de gestão (Galeria → Visível no site).</div>
        @endforelse
    </div>

    <div id="galeriaEmptyFiltered" class="galeria-empty" style="display: none;">Nenhuma programação encontrada para esta busca.</div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/galeria.js') }}" defer></script>
@endpush
