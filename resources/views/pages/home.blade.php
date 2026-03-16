@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Início')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="slider">
    <div class="list">
        <div class="item">
            <img src="{{ asset('img/carrousel/1.webp') }}?v=2" alt="">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/2.webp') }}?v=2" alt="">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/3.webp') }}?v=2" alt="">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/4.webp') }}" alt="">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/5.webp') }}" alt="">
        </div>
    </div>

    <!--Butão Anterior e Próximo-->
    <div class="buttons">
        <button id="prev"><img src="{{ asset('img/btn-left.png') }}" alt=""></button>
        <button id="next"><img src="{{ asset('img/btn-right.png') }}" alt=""></button>
    </div>

    <!--Pontos-->
    <ul class="dots">
        <li class="active"></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div><!--Fim Slider-->

<span class="span_cards"><!--Span Cards-->
    <div class="container_cards">
        <!--Card 1-->
        <div class="card">
            <img src="{{ asset('img/cards/estudo_biblico.jpg') }}" alt="">
            <h2>Estudo Bíblico:<br>Uma Jornada para Conectar-se com Deus</h2>
            <p>Procurando respostas, fortalecimento espiritual ou alívio para desafios emocionais? O Estudo Bíblico é o caminho!</p>
            <a href="{{ route('estudo-biblico') }}">Saiba mais</a>
        </div>

        <!--Card 2-->
        <div class="card">
            <img src="{{ asset('img/cards/escola_sabatina.jpg') }}" alt="">
            <h2>Venha Crescer Conosco na Escola Sabatina!</h2>
            <p>A Escola Sabatina é um presente de Deus para você! Não é apenas um momento de estudo, mas um encontro semanal que alimenta a alma, fortalece a fé e nos une como família em Cristo.</p>
            <a href="{{ route('escola-sabatina') }}">Saiba mais</a>
        </div>

        <!--Card 3-->
        <div class="card">
            <img src="{{ asset('img/cards/oracao.jpg') }}" alt="">
            <h2>Precisa de Oração ou Visita? Vamos Interceder por Você!</h2>
            <p>Não carregue suas lutas sozinho(a). Deus ouve cada oração e, através da nossa comunidade, queremos ser um canal de esperança para sua vida.</p>
            <a href="{{ route('oracao-visita') }}">Saiba mais</a>
        </div>

        <!--Card 4-->
        <div class="card">
            <img src="{{ asset('img/cards/eventos.jpg') }}" alt="">
            <h2>Programações </h2>
            <p>Nossa comunidade está em constante movimento! Todos os meses, os ministérios organizam programações especiais que abraçam todas as idades. Venha participar e fortalecer sua fé junto à família da igreja. Aqui, há espaço para todos!</p>
            <a href="{{ route('programacoes') }}">Saiba mais</a>
        </div>

        <!--Card 5-->
        <div class="card">
            <img src="{{ asset('img/cards/asa.jpeg') }}" alt="">
            <h2>Ação Solidária Adventista (ASA) </h2>
            <p>A ASA é o braço social da Igreja Adventista, dedicado a servir e transformar vidas através de ações de amor e solidariedade. Seja parte desta corrente do bem!</p>
            <a href="{{ route('asa') }}">Saiba mais</a>
        </div>

        <!--Card 6-->
        <div class="card">
            <img src="{{ asset('img/cards/secretaria.jpg') }}" alt="">
            <h2>Fale com a secretaria </h2>
            <p>Na Igreja Adventista do Sétimo Dia, cada membro é parte essencial da família de Deus. Para cuidar bem uns dos outros e garantir que nossa missão avance com eficiência, é fundamental que seus dados estejam sempre atualizados.</p>
            <a href="{{ route('secretaria') }}">Saiba mais</a>
        </div>
    </div><!--Fim container_cards-->
</span><!--Fim Span Cards-->

<div class="canais">
    <div class="btn_canais">
        <button id="btn1">Youtube</button>
        <button id="btn2">Instagram</button>
    </div>
    <div id="div1" class="youtube">
        <h2>Acompanhe nossas transmissões</h2>
        <div class="content_yt">
            <div class="yt_ao_vivo">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/629KDII-r94?si=I4oLWFGER1l1G_Wn&amp;controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div><!--Fim yt_ao_vivo-->

            <div class="yt_em_breve">
                <div class="thumb_em_breve">
                    <img src="{{ asset('img/canais/yt_em_breve.avif') }}" alt="">
                    <a href="" style="font-size: 0.8em;">Título sermão</a>
                </div>
                <div class="thumb_em_breve">
                    <img src="{{ asset('img/canais/yt_em_breve.avif') }}" alt="">
                    <a href="" style="font-size: 0.8em;">Título sermão</a>
                </div>
                <div class="thumb_em_breve">
                    <img src="{{ asset('img/canais/yt_em_breve.avif') }}" alt="">
                    <a href="" style="font-size: 0.8em;">Título sermão</a>
                </div>
            </div><!--Fim yt_em_breve-->
        </div><!--Fim content_yt-->
    </div><!--Fim youtube-->
    <div id="div2" class="content_insta">
        <div class="insta">
            <h2>instagram</h2>
        </div>
    </div>
</div><!--Fim canais-->
@endsection

@push('scripts')
<script src="{{ asset('js/slider.js') }}"></script>
<script src="{{ asset('js/canais.js') }}"></script>
<script src="{{ asset('js/videos_youtube.js') }}"></script>
@endpush

