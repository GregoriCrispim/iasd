@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Início')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="slider">
    <div class="list">
        <div class="item">
            <img src="{{ asset('img/carrousel/1.webp') }}" alt="Slide 1 - IASD Central de Brasília" fetchpriority="high" decoding="async" width="1280" height="720">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/2.webp') }}" alt="Slide 2" decoding="async" width="1280" height="720">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/3.webp') }}" alt="Slide 3" decoding="async" width="1280" height="720">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/4.webp') }}" alt="Slide 4" decoding="async" width="1280" height="720">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/5.webp') }}" alt="Slide 5" decoding="async" width="1280" height="720">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/6.webp') }}" alt="Slide 6" decoding="async" width="1280" height="720">
        </div>
    </div>

    <div class="buttons">
        <button id="prev"><img src="{{ asset('img/btn-left.webp') }}" alt="Anterior" width="40" height="40"></button>
        <button id="next"><img src="{{ asset('img/btn-right.webp') }}" alt="Próximo" width="40" height="40"></button>
    </div>

    <ul class="dots">
        <li class="active"></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>

<span class="span_cards">
    <div class="container_cards">
        <a class="card" href="{{ route('estudo-biblico') }}">
            <img src="{{ asset('img/cards/estudo_biblico.webp') }}" alt="Estudo Bíblico" loading="lazy" decoding="async" width="400" height="300">
            <h2 class="acb-title-serif">Estudo Bíblico:<br>Uma Jornada para Conectar-se com Deus</h2>
            <p>Procurando respostas, fortalecimento espiritual ou alívio para desafios emocionais? O Estudo Bíblico é o caminho!</p>
            <span class="card_cta">Saiba mais</span>
        </a>

        <a class="card" href="{{ route('escola-sabatina') }}">
            <img src="{{ asset('img/cards/escola_sabatina.webp') }}" alt="Escola Sabatina" loading="lazy" decoding="async" width="400" height="300">
            <h2 class="acb-title-serif">Venha Crescer Conosco na Escola Sabatina!</h2>
            <p>A Escola Sabatina é um presente de Deus para você! Não é apenas um momento de estudo, mas um encontro semanal que alimenta a alma, fortalece a fé e nos une como família em Cristo.</p>
            <span class="card_cta">Saiba mais</span>
        </a>

        <a class="card" href="{{ route('oracao-visita') }}">
            <img src="{{ asset('img/cards/oracao.webp') }}" alt="Oração e Visita" loading="lazy" decoding="async" width="400" height="300">
            <h2 class="acb-title-serif">Precisa de Oração ou Visita? Vamos Interceder por Você!</h2>
            <p>Não carregue suas lutas sozinho(a). Deus ouve cada oração e, através da nossa comunidade, queremos ser um canal de esperança para sua vida.</p>
            <span class="card_cta">Saiba mais</span>
        </a>

        <a class="card" href="{{ route('programacoes') }}">
            <img src="{{ asset('img/cards/eventos.webp') }}" alt="Programações e Eventos" loading="lazy" decoding="async" width="400" height="300">
            <h2 class="acb-title-serif">Programações </h2>
            <p>Nossa comunidade está em constante movimento! Todos os meses, os ministérios organizam programações especiais que abraçam todas as idades. Venha participar e fortalecer sua fé junto à família da igreja. Aqui, há espaço para todos!</p>
            <span class="card_cta">Saiba mais</span>
        </a>

        <a class="card" href="{{ route('asa') }}">
            <img src="{{ asset('img/cards/asa.webp') }}" alt="Ação Solidária Adventista" loading="lazy" decoding="async" width="400" height="300">
            <h2 class="acb-title-serif">Ação Solidária Adventista (ASA) </h2>
            <p>A ASA é o braço social da Igreja Adventista, dedicado a servir e transformar vidas através de ações de amor e solidariedade. Seja parte desta corrente do bem!</p>
            <span class="card_cta">Saiba mais</span>
        </a>

        <a class="card" href="{{ route('secretaria') }}">
            <img src="{{ asset('img/cards/secretaria.webp') }}" alt="Secretaria da Igreja" loading="lazy" decoding="async" width="400" height="300">
            <h2 class="acb-title-serif">Fale com a secretaria </h2>
            <p>Na Igreja Adventista do Sétimo Dia, cada membro é parte essencial da família de Deus. Para cuidar bem uns dos outros e garantir que nossa missão avance com eficiência, é fundamental que seus dados estejam sempre atualizados.</p>
            <span class="card_cta">Saiba mais</span>
        </a>
    </div>
</span>

<div class="canais">
    <div class="btn_canais">
        <button id="btn1">Youtube</button>
        <button id="btn2">Instagram</button>
    </div>
    <div id="div1" class="youtube">
        <h2 class="acb-title-serif">Acompanhe nossas transmissões</h2>
        <div class="yt_stage is-loading" data-yt-stage>
            <div class="yt_loader" data-yt-loader role="status" aria-live="polite" aria-label="Carregando vídeos do YouTube">
                <span class="yt_loader_spinner" aria-hidden="true"></span>
            </div>
            <div class="content_yt" data-yt-content aria-hidden="true">
                <div class="yt_ao_vivo">
                    <button type="button" class="yt_lazy" data-yt-lazy aria-label="Reproduzir vídeo do YouTube">
                        <img class="yt_lazy_thumb" src="{{ asset('img/canais/yt_em_breve.avif') }}" alt="" loading="lazy" decoding="async">
                        <span class="yt_lazy_play" aria-hidden="true"></span>
                    </button>
                </div>

                <div class="yt_em_breve">
                    <div class="thumb_em_breve">
                        <a href="" class="thumb_em_breve_link" aria-label="Abrir vídeo no YouTube" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('img/canais/yt_em_breve.avif') }}" alt="Em breve" loading="lazy" decoding="async">
                        </a>
                        <a href="" class="thumb_em_breve_title" target="_blank" rel="noopener noreferrer">Título sermão</a>
                    </div>
                    <div class="thumb_em_breve">
                        <a href="" class="thumb_em_breve_link" aria-label="Abrir vídeo no YouTube" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('img/canais/yt_em_breve.avif') }}" alt="Em breve" loading="lazy" decoding="async">
                        </a>
                        <a href="" class="thumb_em_breve_title" target="_blank" rel="noopener noreferrer">Título sermão</a>
                    </div>
                    <div class="thumb_em_breve">
                        <a href="" class="thumb_em_breve_link" aria-label="Abrir vídeo no YouTube" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('img/canais/yt_em_breve.avif') }}" alt="Em breve" loading="lazy" decoding="async">
                        </a>
                        <a href="" class="thumb_em_breve_title" target="_blank" rel="noopener noreferrer">Título sermão</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="div2" class="content_insta">
        <div class="insta">
            <h2 class="acb-title-serif">instagram</h2>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/slider.js') }}" defer></script>
<script src="{{ asset('js/canais.js') }}" defer></script>
<script src="{{ asset('js/videos_youtube.js') }}" defer></script>
@endpush

