@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Classe Novo Tempo')

@push('styles')
<style>

    .cnt-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 40px;
    }

    /* Cola a 1ª seção na imagem do header e a 1ª na 2ª seção */
    .cnt-container > .cnt-intro:first-child {
        margin-bottom: 0;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .cnt-container > .convite-section {
        margin-top: 0;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    /* Cola a penúltima seção ("Participe") com a última */
    .cnt-container > .participe-section {
        margin-bottom: 0;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .cnt-container > .cnt-intro:last-child {
        margin-top: 0;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .cnt-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    .cnt-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .cnt-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto 20px;
    }

    .convite-section {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 30px 40px;
        border-radius: 15px;
        margin: 20px 0 50px 0;
        text-align: center;
        color: #fff;
    }

    .convite-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #fff;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .convite-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #f8f9fa;
        margin-bottom: 25px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
        text-align: justify;
        text-indent: 0;
    }

    .expectativas-section {
        margin: 60px 0;
    }

    .expectativas-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .expectativas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .expectativa-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .expectativa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .expectativa-card .icon-box {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 1.8em;
        transition: transform 0.3s;
    }

    .expectativa-card:hover .icon-box {
        transform: scale(1.1);
    }

    .expectativa-card .icon-box.amber {
        background: #fef3c7;
    }

    .expectativa-card .icon-box.indigo {
        background: #e0e7ff;
    }

    .expectativa-card .icon-box.pink {
        background: #fce7f3;
    }

    .expectativa-card .icon-box.cyan {
        background: #cffafe;
    }

    .expectativa-card h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2em;
        color: #003366;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .expectativa-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #666;
        line-height: 1.7;
    }

    .participe-section {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
        color: #fff;
    }

    .participe-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #fff;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .participe-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #f8f9fa;
        margin-bottom: 15px;
    }

    .versiculo-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
    }

    .versiculo-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3rem;
        line-height: 1.8;
        color: #333;
        font-style: italic;
        margin-bottom: 15px;
    }

    .versiculo-section .reference {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #003366;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .cnt-container {
            padding: 20px 15px;
        }

        .cnt-intro {
            padding: 30px 20px;
        }

        .cnt-intro h1 {
            font-size: 2.2em;
        }

        .convite-section {
            padding: 40px 20px;
        }

        .convite-section h2 {
            font-size: 2em;
        }

        .expectativas-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<img src="{{ asset('img/classe_novo_tempo/classe_novo_tempo_header.webp') }}" alt="Classe Novo Tempo" style="width: 100%;" fetchpriority="high" decoding="async">

<div class="cnt-container">

    <!-- Seção Introdutória -->
    <div class="cnt-intro acb-fullbleed">
        <h1>Bem-vindos à Classe Novo Tempo!</h1>
        <p>
            Um espaço de amizade, estudo da Bíblia e esperança, inspirado na TV Novo Tempo, especialmente para você que nos visita!
        </p>
    </div>

    <!-- Seção Convite -->
    <div class="convite-section acb-fullbleed">
        <h2 class="acb-title-serif">Um Convite Especial para Você</h2>
        <p>
            Você já conhece a TV Novo Tempo? Gosta dos programas e das mensagens de fé e esperança que eles transmitem? Então, você vai se sentir em casa na nossa Classe Novo Tempo!
        </p>
        <p>
            Criamos este espaço com muito carinho, pensando em você, nosso amigo e visitante, que deseja conhecer mais sobre a Bíblia e os ensinamentos de Jesus em um ambiente acolhedor e descontraído. A cada sábado de manhã, nos reunimos para aprender juntos, conversar e fortalecer nossa amizade.
        </p>
    </div>

    <!-- Seção O Que Esperar -->
    <div class="expectativas-section">
        <h2 class="acb-title-serif">O que esperar da nossa classe?</h2>

        <div class="expectativas-grid">
            <div class="expectativa-card">
                <div class="icon-box amber">
                    <i class="bi bi-house-heart-fill"></i>
                </div>
                <h4>Ambiente Acolhedor</h4>
                <p>Um lugar onde você pode se sentir à vontade para perguntar, compartilhar suas ideias e fazer novos amigos.</p>
            </div>

            <div class="expectativa-card">
                <div class="icon-box indigo">
                    <i class="bi bi-book-half"></i>
                </div>
                <h4>Estudo da Bíblia</h4>
                <p>De forma simples e prática, estudamos as Escrituras Sagradas, buscando respostas para as grandes questões da vida.</p>
            </div>

            <div class="expectativa-card">
                <div class="icon-box pink">
                    <i class="bi bi-lightbulb"></i>
                </div>
                <h4>Temas Atuais</h4>
                <p>Conversamos sobre assuntos relevantes para o nosso dia a dia, sempre com uma perspectiva de fé e esperança.</p>
            </div>

            <div class="expectativa-card">
                <div class="icon-box cyan">
                    <i class="bi bi-heart"></i>
                </div>
                <h4>Comunhão e Oração</h4>
                <p>Momentos especiais para compartilharmos nossos pedidos e agradecimentos, fortalecendo nossa conexão com Deus e uns com os outros.</p>
            </div>
        </div>
    </div>

    <!-- Seção Participe Conosco -->
    <div class="participe-section acb-fullbleed">
        <h3 class="acb-title-serif">Participe Conosco!</h3>
        <p>
            Nossa classe se reúne todos os sábados, às 11h, ao lado da nave principal da igreja.
        </p>
        <p>
            Será uma grande alegria ter você conosco! Venha fazer novos amigos e, o mais importante, se aproximar de Deus.
        </p>
    </div>

    <!-- Seção Convite Final -->
    <div class="cnt-intro acb-fullbleed">
        <h2 class="acb-title-serif" style="font-size: 2em; color: #003366; margin-bottom: 20px;">Esperamos por você no próximo sábado!</h2>
        <p style="text-align: center;">
            Venha fazer parte dessa experiência especial de amizade, aprendizado e crescimento espiritual!
        </p>
        <blockquote class="acb-quote" style="max-width: 900px; margin: 20px auto 0;">
            <p>"Porque onde estiverem dois ou três reunidos em meu nome, ali estou no meio deles."</p>
            <span class="acb-quote__ref"><i class="bi bi-book-half"></i> Mateus 18:20</span>
        </blockquote>
    </div>

</div>
@endsection
