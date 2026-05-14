@extends('layouts.app')

@section('title', 'Clube do Livro: Páginas que Conectam')

@push('styles')
<style>

    .clube-livro-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .intro-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 30px 40px;
        border-radius: 15px;
        margin-bottom: 30px;
        text-align: center;
    }

    .intro-section h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 10px;
        font-weight: 500;
        white-space: nowrap;
    }

    .intro-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #1a5490;
        margin-bottom: 20px;
        font-weight: 400;
    }

    .intro-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto 20px auto;
    }

    .section-title {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin: 30px 0 25px 0;
        font-weight: 500;
    }

    .como-funciona-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
        margin: 25px 0;
    }

    .info-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .info-card .icon-box {
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

    .info-card:hover .icon-box {
        transform: scale(1.1);
    }

    .info-card .icon-box.amber {
        background: #fef3c7;
    }

    .info-card .icon-box.indigo {
        background: #e0e7ff;
    }

    .info-card .icon-box.pink {
        background: #fce7f3;
    }

    .info-card .icon-box.cyan {
        background: #cffafe;
    }

    .info-card h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2em;
        color: #003366;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .info-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        line-height: 1.7;
        color: #666;
    }

    .destaque-box {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 40px;
        border-radius: 15px;
        margin: 50px 0;
        color: #fff;
        text-align: center;
    }

    .destaque-box h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.2em;
        color: #fff;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .destaque-box h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
        color: #fff;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .destaque-box p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #f8f9fa;
        margin-bottom: 15px;
    }

    .livro-destaque {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 30px;
        border-radius: 15px;
        margin: 30px 0;
        color: #ffffff;
    }

    /* Sem espaço entre "Leitura Atual" e "Quer Participar?" */
    .clube-livro-container > .livro-destaque {
        margin-bottom: 0 !important;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .clube-livro-container > .cta-section {
        margin-top: 0 !important;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .livro-destaque h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #ffffff;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .livro-destaque p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: rgba(248, 250, 252, 0.92);
        margin-bottom: 15px;
    }

    .livro-destaque .livro-card {
        background: rgba(255, 255, 255, 0.10);
        border: 1px solid rgba(255, 255, 255, 0.16);
        padding: 25px;
        border-radius: 12px;
        margin: 20px 0;
        text-align: center;
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
    }

    .livro-destaque .livro-card h4 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #ffffff;
        margin-bottom: 10px;
        font-weight: 500;
    }

    .livro-destaque .livro-card .livro-autor {
        font-style: italic;
        color: rgba(248, 250, 252, 0.85);
        margin-bottom: 0;
    }

    .cta-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
        border: 0;
    }

    .cta-section img {
        width: 100%;
        max-width: 400px;
        height: auto;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.2em;
        color: #003366;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .cta-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 20px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-participar {
        display: inline-block;
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        color: #fff;
        padding: 18px 50px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.2em;
        margin: 30px auto;
        transition: transform 0.3s, box-shadow 0.3s;
        font-family: 'Roboto', sans-serif;
    }

    .btn-participar:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .contato-section {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
    }

    .contato-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #003366;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .contato-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #333;
        margin-bottom: 10px;
    }

    .contato-section a {
        color: #003366;
        text-decoration: none;
        font-weight: 600;
    }

    .contato-section a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .clube-livro-container {
            padding: 20px 15px;
        }

        .intro-section {
            padding: 30px 20px;
        }

        .intro-section h1 {
            font-size: 2.2em;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .como-funciona-grid {
            grid-template-columns: 1fr;
        }

        .cta-section{
            padding: 32px 18px;
        }

        .cta-section img{
            margin: 18px auto;
        }
    }
</style>
@endpush

@section('content')
<img src="{{ asset('img/clube_do_livro/clube_livro_header.webp') }}" alt="Clube do Livro" style="width: 100%;" fetchpriority="high" decoding="async">

<div class="clube-livro-container">

    <!-- Seção Introdutória -->
    <div class="intro-section acb-fullbleed">
        <h1 class="acb-title-serif">CLUBE DO LIVRO: Páginas que Conectam</h1>
        <p>
            Bem-vindo(a) ao espaço oficial do Clube do Livro da Igreja Adventista Central de Brasília. Este é um convite aberto a todos que amam a leitura e buscam nela uma ferramenta para o crescimento espiritual, reflexão e, claro, uma ótima comunhão.
        </p>
        <p>
            Nosso clube é um ambiente acolhedor e dinâmico, onde juntos exploramos obras cristãs que desafiam nossa mente e aquecem nosso coração.
        </p>
    </div>

    <!-- Como Funciona -->
    <h2 class="section-title acb-title-serif"><i class="bi bi-lightbulb"></i> Como Funciona o Nosso Clube?</h2>

    <p style="text-align: center; font-family: 'Roboto', sans-serif; font-size: 1.1rem; color: #666; margin-bottom: 20px; max-width: 800px; margin-left: auto; margin-right: auto;">
        Para que todos possam participar, nossos encontros são objetivos, acessíveis e focados na partilha de ideias.
    </p>

    <div class="como-funciona-grid">
        <div class="info-card">
            <div class="icon-box amber">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
            <h3>Onde?</h3>
            <p>Nossas reuniões são 100% remotas, realizadas confortavelmente através do Google Meet.</p>
        </div>

        <div class="info-card">
            <div class="icon-box indigo">
                <i class="bi bi-calendar-date"></i>
            </div>
            <h3>Quando?</h3>
            <p>Nos encontramos a cada 15 dias (quinzenalmente), sempre às terças-feiras.</p>
        </div>

        <div class="info-card">
            <div class="icon-box pink">
                <i class="bi bi-clock"></i>
            </div>
            <h3>Horário?</h3>
            <p>Começamos pontualmente às 19h30.</p>
        </div>

        <div class="info-card">
            <div class="icon-box cyan">
                <i class="bi bi-journals"></i>
            </div>
            <h3>O que lemos?</h3>
            <p>A seleção é democrática! Lemos livros cristãos inspiradores, escolhidos pelos próprios participantes do grupo.</p>
        </div>
    </div>

    <!-- Leitura Atual -->
    <div class="livro-destaque acb-fullbleed">
        <h3 class="acb-title-serif"><i class="bi bi-journals"></i> Leitura Atual</h3>
        <p>
            No momento, estamos mergulhados na fascinante obra:
        </p>
        <div class="livro-card">
            <h4>"Cartas de um Diabo a seu Aprendiz"</h4>
            <p class="livro-autor">de C.S. Lewis</p>
        </div>
        <p>
            Uma leitura instigante que explora a teologia e a natureza humana através de uma perspectiva única. Mesmo que você não tenha começado o livro, sinta-se à vontade para participar e conhecer a dinâmica do grupo!
        </p>
    </div>

    <!-- Quer Participar -->
    <div class="cta-section acb-fullbleed">
        <h2 class="acb-title-serif"><i class="bi bi-chat-dots"></i> Quer Participar?<br>Junte-se a Nós!</h2>
        <p>
            Ficou interessado? Você é nosso convidado especial!
        </p>
        <p>
            Não importa se você é um leitor ávido ou se está apenas começando sua jornada literária. O Clube do Livro é um ministério aberto a todos que desejam aprender e compartilhar.
        </p>
        <p>
            Para receber o link da próxima reunião no Google Meet e ser adicionado ao nosso grupo, por favor, entre em contato:
        </p>
        <img src="{{ asset('img/clube_do_livro/clubelivro.webp') }}" alt="Capa do livro Cartas de um Diabo a seu Aprendiz" loading="lazy" decoding="async" style="max-width: 400px; height: auto; margin: 30px auto; display: block; box-shadow: 0 6px 25px rgba(0,0,0,0.3); border-radius: 10px;">
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animação suave para scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Animação de fade-in para os cards
    const cards = document.querySelectorAll('.info-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endpush
