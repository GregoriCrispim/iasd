@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Classe de Saúde')

@push('styles')
<style>

    .classe-saude-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .classe-saude-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    .classe-saude-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 10px;
        font-weight: 500;
        letter-spacing: 2px;
    }

    .classe-saude-intro h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #003366;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .classe-saude-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto 20px;
    }

    .citacao-destaque {
        background: #fff;
        border-left: 5px solid #003366;
        padding: 25px 30px;
        margin: 30px auto;
        max-width: 900px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .citacao-destaque p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #333;
        font-style: italic;
        text-align: center;
        margin: 0;
    }

    .citacao-destaque .fonte {
        text-align: center;
        font-size: 0.9rem;
        color: #666;
        margin-top: 10px;
    }

    .video-section {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin: 15px 0;
    }

    .video-section a {
        display: inline-block;
        padding: 12px 30px;
        background: #003366;
        color: #fff;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .video-section a:hover {
        background: #1b4472;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .saude-section {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 25px;
        margin: 25px 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .saude-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .saude-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        margin-bottom: 20px;
        font-weight: 500;
        letter-spacing: 2px;
    }

    .saude-section .icone {
        font-size: 4em;
        text-align: center;
        margin-bottom: 15px;
    }

    .saude-section ul {
        list-style: none;
        padding: 0;
        margin: 0 0 15px 0;
    }

    .saude-section li {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #333;
        line-height: 2;
        padding: 12px 20px;
        margin: 8px 0;
        background: #f8f9fa;
        border-left: 4px solid #003366;
        border-radius: 5px;
    }

    .citacao-box {
        background: linear-gradient(135deg, #f0f7ff 0%, #e3f2fd 100%);
        border-left: 5px solid #003366;
        padding: 15px 20px;
        margin: 15px 0;
        border-radius: 8px;
    }

    .citacao-box p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #003366;
        font-style: italic;
        margin: 0;
    }

    .citacao-box .fonte {
        display: block;
        text-align: right;
        font-size: 0.9rem;
        color: #666;
        margin-top: 10px;
        font-style: normal;
    }

    .participe-section {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 60px 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
        color: #fff;
    }

    .participe-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.8em;
        color: #fff;
        margin-bottom: 25px;
        font-weight: 500;
        letter-spacing: 2px;
    }

    .participe-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2rem;
        color: #f8f9fa;
        line-height: 1.8;
        margin-bottom: 20px;
    }

    .info-box {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        padding: 30px;
        margin: 30px auto;
        max-width: 700px;
    }

    .info-box h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
        color: #fff;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .info-box p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #f8f9fa;
        margin: 10px 0;
    }

    .info-box .destaque {
        font-size: 1.4em;
        font-weight: 600;
        color: #fff;
        margin-top: 15px;
    }

    @media (max-width: 768px) {
        .classe-saude-container {
            padding: 20px 15px;
        }

        .classe-saude-intro {
            padding: 30px 20px;
        }

        .classe-saude-intro h1 {
            font-size: 2.2em;
        }

        .saude-section {
            padding: 25px 20px;
        }

        .video-section a {
            width: 100%;
            max-width: 260px;
            text-align: center;
        }

        .participe-section {
            padding: 40px 18px;
            border-radius: 14px;
            margin: 40px 0;
        }

        .participe-section h2 {
            font-size: 2.2em;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }

        .participe-section p {
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 16px;
        }

        .info-box {
            padding: 18px 16px;
            margin: 18px auto 0;
            max-width: 100%;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.08);
            -webkit-backdrop-filter: blur(8px);
            backdrop-filter: blur(8px);
            text-align: left;
        }

        .info-box h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: flex-start;
            font-size: 1.35em;
            margin: 18px 0 8px;
        }

        .info-box h3:first-of-type {
            margin-top: 0;
        }

        .info-box p {
            margin: 6px 0;
            font-size: 1.02rem;
        }

        .info-box .destaque {
            font-size: 1.2em;
            margin-top: 10px;
        }
    }
</style>
@endpush

@section('content')
<img src="{{ asset('img/classe_saude/classe_saude_header.webp') }}" alt="Classe de Saúde" style="width: 100%;" fetchpriority="high" decoding="async">

<div class="classe-saude-container">

    <!-- Seção Introdutória -->
    <div class="classe-saude-intro acb-fullbleed">
        <h1>Bem-vindo à Classe de Saúde</h1>
        <p>
            A Classe de Saúde é um espaço especial criado para membros e visitantes que desejam aprender e compartilhar princípios sobre uma vida saudável em seus aspectos físico, mental e espiritual.
        </p>
        <p>
            Nosso objetivo é ajudar cada participante a desenvolver hábitos que promovam bem-estar integral, em harmonia com os ensinamentos bíblicos e com os conselhos inspirados de Ellen G. White.
        </p>

        <div class="citacao-destaque acb-quote" style="max-width: 900px; margin: 30px auto;">
            <p>"A verdadeira educação significa mais do que o preparo meramente acadêmico. É o desenvolvimento harmonioso das faculdades físicas, mentais e espirituais."</p>
            <span class="fonte acb-quote__ref">- Educação, p. 13</span>
        </div>

        <div class="video-section">
            <a href="https://www.instagram.com/reel/DFieE1Rvem4/?igsh=MnRibWN1MnpmNWI1" target="_blank">
                <i class="bi bi-play-circle"></i> Assista ao Vídeo
            </a>
        </div>
    </div>

    <!-- Seção Saúde Física -->
    <div class="saude-section">
        <div class="icone">
            <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="60" cy="60" r="55" fill="#28a745" opacity="0.1"/>
                <!-- Coração -->
                <path d="M60 95C60 95 25 70 25 45C25 30 35 25 45 25C52 25 57 30 60 35C63 30 68 25 75 25C85 25 95 30 95 45C95 70 60 95 60 95Z" fill="url(#grad1)"/>
                <!-- ECG/Pulso cardíaco -->
                <path d="M30 60L40 60L45 45L50 75L55 60L70 60L75 40L80 80L85 60L90 60" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                <defs>
                    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#28a745;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#20c997;stop-opacity:1" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <h2>SAÚDE FÍSICA</h2>
        <ul>
            <li>Orientações sobre nutrição equilibrada</li>
            <li>Exercícios e alongamentos práticos</li>
            <li>Hábitos de descanso e sono restaurador</li>
            <li>Prevenção de doenças e cuidados práticos do dia a dia</li>
        </ul>

        <div class="citacao-box acb-quote">
            <p>"O apetite pervertido, quando satisfeito, enfraquece o intelecto e corrompe as faculdades morais."</p>
            <span class="fonte acb-quote__ref">- Conselhos sobre o Regime Alimentar, p. 45</span>
        </div>

        <div class="video-section">
            <a href="https://www.instagram.com/reel/DGehyNEP9ZN/?igsh=MWQ1b2VyNGl3d2Y2ZA==" target="_blank">
                <i class="bi bi-play-circle"></i> Vídeo 1
            </a>
            <a href="https://www.instagram.com/reel/DB9vqqvvFAX/?igsh=Z2FmdGQ1OWl6Z3E1" target="_blank">
                <i class="bi bi-play-circle"></i> Vídeo 2
            </a>
        </div>
    </div>

    <!-- Seção Saúde Mental -->
    <div class="saude-section">
        <div class="icone">
            <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="60" cy="60" r="55" fill="#6f42c1" opacity="0.1"/>
                <!-- Rosto de perfil -->
                <circle cx="50" cy="50" r="25" fill="url(#grad2)" opacity="0.3"/>
                <!-- Cabeça -->
                <circle cx="60" cy="45" r="30" fill="url(#grad2)"/>
                <!-- Cérebro visível -->
                <path d="M45 40Q50 35 60 35Q70 35 75 40Q80 45 80 55Q80 65 75 70Q70 75 60 75Q50 75 45 70Q40 65 40 55Q40 45 45 40" fill="white" opacity="0.25"/>
                <!-- Contorno do cérebro -->
                <path d="M50 45C50 45 55 40 60 40C65 40 70 42 70 42" stroke="white" stroke-width="2" stroke-linecap="round" opacity="0.6"/>
                <path d="M48 50C48 50 55 48 60 48C68 48 72 50 72 50" stroke="white" stroke-width="2" stroke-linecap="round" opacity="0.6"/>
                <path d="M47 55C47 55 55 53 60 53C68 53 73 55 73 55" stroke="white" stroke-width="2" stroke-linecap="round" opacity="0.6"/>
                <path d="M48 60C48 60 58 58 63 58C70 58 74 60 74 60" stroke="white" stroke-width="2" stroke-linecap="round" opacity="0.6"/>
                <!-- Olho fechado (meditação) -->
                <path d="M45 52Q50 48 55 52" stroke="white" stroke-width="2" stroke-linecap="round" opacity="0.9"/>
                <!-- Boca serena -->
                <path d="M50 65Q55 63 60 65" stroke="white" stroke-width="2" stroke-linecap="round" opacity="0.7"/>
                <!-- Pescoço -->
                <rect x="52" y="72" width="16" height="15" fill="url(#grad2)" opacity="0.5"/>
                <defs>
                    <linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#6f42c1;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#9b59b6;stop-opacity:1" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <h2>SAÚDE MENTAL</h2>
        <ul>
            <li>Como lidar com o estresse e a ansiedade</li>
            <li>A importância da gratidão e do otimismo</li>
            <li>O poder do convívio social saudável</li>
            <li>Técnicas de relaxamento e respiração</li>
        </ul>

        <div class="citacao-box acb-quote">
            <p>"É dever de todos cultivar a esperança em lugar da dúvida, a alegria em vez da melancolia."</p>
            <span class="fonte acb-quote__ref">- Mente, Caráter e Personalidade, vol. 2, p. 492</span>
        </div>

        <div class="video-section">
            <a href="https://www.instagram.com/reel/C_n1konudN-/?igsh=MTVvY3FuaHJ3MXNuag==" target="_blank">
                <i class="bi bi-play-circle"></i> Assista ao Vídeo
            </a>
        </div>
    </div>

    <!-- Seção Saúde Espiritual -->
    <div class="saude-section">
        <div class="icone">
            <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="60" cy="60" r="55" fill="#ffc107" opacity="0.1"/>
                <!-- Bíblia aberta - capa traseira -->
                <path d="M35 35L35 85C35 90 40 95 60 95C80 95 85 90 85 85L85 35C85 40 80 45 60 45C40 45 35 40 35 35Z" fill="url(#grad3)"/>
                <!-- Bíblia aberta - páginas -->
                <path d="M60 45C70 45 85 50 85 35L85 85C85 100 70 95 60 95" fill="white" opacity="0.15"/>
                <path d="M60 45C50 45 35 50 35 35L35 85C35 100 50 95 60 95" fill="white" opacity="0.25"/>
                <!-- Lombada central -->
                <rect x="57" y="35" width="6" height="60" fill="#b7892b" opacity="0.4"/>
                <!-- Cruz na capa -->
                <path d="M60 55L60 75M52 65L68 65" stroke="white" stroke-width="3" stroke-linecap="round"/>
                <!-- Raios de luz -->
                <circle cx="60" cy="30" r="4" fill="white" opacity="0.8"/>
                <path d="M60 15L60 22" stroke="url(#grad3)" stroke-width="2" stroke-linecap="round" opacity="0.6"/>
                <path d="M45 20L50 25" stroke="url(#grad3)" stroke-width="2" stroke-linecap="round" opacity="0.6"/>
                <path d="M75 20L70 25" stroke="url(#grad3)" stroke-width="2" stroke-linecap="round" opacity="0.6"/>
                <!-- Linhas de texto -->
                <line x1="45" y1="55" x2="55" y2="55" stroke="white" stroke-width="1.5" opacity="0.4"/>
                <line x1="45" y1="62" x2="55" y2="62" stroke="white" stroke-width="1.5" opacity="0.4"/>
                <line x1="45" y1="69" x2="52" y2="69" stroke="white" stroke-width="1.5" opacity="0.4"/>
                <line x1="65" y1="55" x2="75" y2="55" stroke="white" stroke-width="1.5" opacity="0.4"/>
                <line x1="65" y1="62" x2="75" y2="62" stroke="white" stroke-width="1.5" opacity="0.4"/>
                <line x1="65" y1="69" x2="73" y2="69" stroke="white" stroke-width="1.5" opacity="0.4"/>
                <defs>
                    <linearGradient id="grad3" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#ffc107;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#ff9800;stop-opacity:1" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <h2>SAÚDE ESPIRITUAL</h2>
        <ul>
            <li>Estudo da Bíblia aplicado à vida prática</li>
            <li>Momentos de oração e meditação</li>
            <li>Como a fé fortalece o corpo e a mente</li>
            <li>Princípios do estilo de vida adventista</li>
        </ul>

        <div class="citacao-box acb-quote">
            <p>"É o amor de Cristo que deve ser a força motivadora da vida. Esse amor é o poder restaurador."</p>
            <span class="fonte acb-quote__ref">- O Desejado de Todas as Nações, p. 824</span>
        </div>

        <div class="video-section">
            <a href="https://www.instagram.com/reel/DOY-9Pwj6xw/?igsh=MWdlMWhoNnFjYXdrdg==" target="_blank">
                <i class="bi bi-play-circle"></i> Assista ao Vídeo
            </a>
        </div>
    </div>

</div>
@endsection
