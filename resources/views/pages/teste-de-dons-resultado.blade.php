@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Resultado do Teste de Dons')

@section('meta-description', 'Resultado do teste de dons espirituais — veja a tendência dos seus principais dons.')
@section('og-title', 'Resultado do Teste de Dons - IASD Central de Brasília')
@section('og-description', 'Confira o ranking dos seus dons espirituais.')
@section('page-name', 'Resultado do Teste de Dons')
<meta name="robots" content="noindex">

@push('styles')
<style>
    /* Container padrão das páginas do site */
    .tdr-container {
        width: 80%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 0 60px;
    }

    /* Cabeçalho (padrão .page-hero + .acb-fullbleed) */
    .tdr-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 40px 40px 30px;
        margin-bottom: 20px;
        text-align: center;
    }

    .tdr-header h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .tdr-header p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        color: #333;
        margin: 0 auto;
        max-width: 900px;
    }

    /* Tendência dos principais dons (padrão da seção convite navy) */
    .tdr-top {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        color: #fff;
        border-radius: 20px;
        padding: 30px 50px;
        margin: 20px 0 60px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 51, 102, 0.3);
        text-align: center;
        font-family: 'Roboto', sans-serif;
    }

    .tdr-top::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: tdr-pulse 15s ease-in-out infinite;
    }

    @keyframes tdr-pulse {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(-10px, -10px) scale(1.1); }
    }

    .tdr-top h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.2em;
        margin: 0 0 10px;
        font-weight: 500;
    }

    .tdr-top p {
        position: relative;
        z-index: 1;
        margin: 0;
        font-size: 1.15rem;
        line-height: 1.7;
    }

    .tdr-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 18px;
    }

    .tdr-item {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 22px 18px;
    }

    .tdr-item--top {
        border-color: #003366;
    }

    .tdr-rank-row {
        display: flex;
        align-items: baseline;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }

    .tdr-rank {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
        color: #d35400;
        min-width: 44px;
    }

    .tdr-name {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #003366;
    }

    .tdr-points {
        margin-left: auto;
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #333;
        white-space: nowrap;
    }

    .tdr-bar-track {
        height: 12px;
        border-radius: 999px;
        background: #e9ecef;
        overflow: hidden;
        margin-bottom: 12px;
    }

    .tdr-bar-fill {
        height: 100%;
        min-width: 2px;
        background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%);
        border-radius: 999px;
    }

    .tdr-desc {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        line-height: 1.7;
        color: #333;
        margin: 0;
        text-align: justify;
    }

    /* Nota final (padrão faq-item / callout) */
    .tdr-note {
        background: #f8f9fa;
        border-left: 5px solid #003366;
        border-radius: 12px;
        padding: 18px;
        margin: 45px 0 0;
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.7;
        color: #333;
    }

    .tdr-note .label {
        color: #003366;
    }

    .tdr-actions {
        margin-top: 40px;
        text-align: center;
    }

    .tdr-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 15px 36px;
        border: none;
        border-radius: 10px;
        font-family: 'Roboto', sans-serif;
        font-size: 1.15em;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%);
        color: #fff;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .tdr-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(211, 84, 0, 0.35);
        color: #fff;
    }

    .tdr-btn:focus-visible {
        outline: 3px solid #f5a623;
        outline-offset: 2px;
    }

    @media (max-width: 768px) {
        .tdr-container {
            width: 90%;
        }

        .tdr-header {
            padding: 30px 18px 20px;
        }

        .tdr-top {
            padding: 25px 22px;
        }
    }

    @media (max-width: 480px) {
        .tdr-points {
            margin-left: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="tdr-container">
    <header class="tdr-header">
        <h1>{{ $texts['labels']['result_title'] }}</h1>
        <p>Soma de cada dom: 4 afirmações × 0 a 3 pontos (máximo de {{ $scored['max_points_per_gift'] }} pontos por dom).</p>
    </header>

    <section class="tdr-top" aria-label="Tendência dos principais dons">
        <h2>Tendência dos seus principais dons</h2>
        <p>
            @forelse ($results->take(3) as $row)
                {{ $row['name'] }}@if(!$loop->last), @endif
            @empty
                —
            @endforelse
        </p>
    </section>

    <ol class="tdr-list">
        @foreach ($results as $row)
            <li class="tdr-item {{ $row['rank'] <= 3 ? 'tdr-item--top' : '' }}">
                <div class="tdr-rank-row">
                    <span class="tdr-rank" aria-label="Posição {{ $row['rank'] }}">{{ $row['rank'] }}º</span>
                    <span class="tdr-name">{{ $row['name'] }}</span>
                    <span class="tdr-points">{{ $row['points'] }} de {{ $scored['max_points_per_gift'] }} pontos</span>
                </div>
                <div class="tdr-bar-track" role="img"
                     aria-label="Proporção de {{ $row['percentage'] }}% em relação ao maior resultado">
                    <div class="tdr-bar-fill" style="width: {{ (int) $row['percentage'] }}%;"></div>
                </div>
                <p class="tdr-desc">{{ $row['description'] }}</p>
            </li>
        @endforeach
    </ol>

    <div class="tdr-note">
        {!! $texts['result_comment_html'] !!}
    </div>

    <div class="tdr-actions">
        <a class="tdr-btn" href="{{ route('teste-de-dons') }}?refazer=1"
           data-td-refazer>Refazer o teste</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var STORAGE_KEY = 'teste-dons:answers';
    var link = document.querySelector('[data-td-refazer]');
    if (!link) return;

    var confirmMessage = @js($texts['messages']['clear_data_confirm']);

    link.addEventListener('click', function (event) {
        var saved = null;
        try { saved = window.localStorage.getItem(STORAGE_KEY); } catch (e) { /* sem storage */ }

        if (!saved) return; // nada a descartar: segue direto

        if (!window.confirm(confirmMessage)) {
            event.preventDefault();
            return;
        }
        try { window.localStorage.removeItem(STORAGE_KEY); } catch (e) { /* segue sem limpar */ }
    });
})();
</script>
@endpush
