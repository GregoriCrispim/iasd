@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Criacionismo e Ciência')

@php
// Definição dos dados dos cientistas em Array PHP para fácil manutenção
$scientists = [
    [
        "title" => "Isaac Newton: O Cientista que Viu Deus na Matemática",
        "items" => [
            ["name" => "Deus como Criador Inteligente", "desc" => "Newton afirmava que as leis da gravidade e do movimento só faziam sentido com um 'agente inteligente' por trás delas. Para ele, a complexidade do cosmos era prova de um Designer Divino."],
            ["name" => "Estudos Bíblicos Profundos", "desc" => "Dedicou mais tempo à teologia do que à ciência! Analisou profecias de Daniel e Apocalipse, buscando decifrar códigos matemáticos nas Escrituras."]
        ]
    ],
    [
        "title" => "Outros Cientistas que acreditavam no Criador",
        "items" => [
            ["name" => "Galileu Galilei", "subtitle" => "Pai da Astronomia Moderna", "desc" => "Defendia que 'a Bíblia mostra o caminho para o céu, não como o céu funciona', harmonizando ciência e fé. Via a ordem cósmica como obra divina."],
            ["name" => "Johannes Kepler", "subtitle" => "Leis do Movimento Planetário", "desc" => "Acreditava que o universo era um 'reflexo da mente de Deus'. Suas descobertas matemáticas foram motivadas pela busca da harmonia cósmica."],
            ["name" => "Louis Pasteur", "subtitle" => "Pai da Microbiologia", "desc" => "Comprovou que a vida surge de vida preexistente. Declarou: 'Quanto mais estudo a natureza, mais me maravilho com a perfeição do Criador'."],
            ["name" => "Michael Faraday", "subtitle" => "Pioneiro do Eletromagnetismo", "desc" => "Cientista profundamente religioso, via suas descobertas como uma 'exploração das leis divinas'. Ensinava que a ciência e a Bíblia são partes da mesma verdade."]
        ]
    ]
];
@endphp

@push('styles')
<style>
    html { 
        scroll-behavior: smooth; 
    }

    .page-hero {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    /* Seção de Cientistas */
    .cientistas-section {
        margin: 50px 0;
    }

    .cientistas-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .cientista-category-title {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 30px;
    }

    .cientista-category-title i {
        font-size: 1.5em;
        color: #003366;
    }

    .newton-section {
        background: #fff;
        padding: 40px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 2px solid #e0e0e0;
        max-width: 1000px;
        margin: 0 auto 50px;
        transition: box-shadow 0.3s;
    }

    .newton-section:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .newton-intro {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        color: #333;
        line-height: 1.8;
        text-align: justify;
        margin-bottom: 30px;
    }

    .newton-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .newton-card {
        background: #eef2f6;
        padding: 25px;
        border-radius: 12px;
        border-left: 4px solid #003366;
    }

    .newton-card h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3em;
        font-weight: 700;
        color: #003366;
        margin-bottom: 15px;
    }

    .newton-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #333;
        line-height: 1.7;
        text-align: justify;
        margin: 0;
    }

    .quote-card {
        border-left: 4px solid #003366;
        background-color: #eef2f6;
        padding: 25px;
        margin: 30px 0;
        font-style: italic;
        color: #003366;
        border-radius: 8px;
    }

    .quote-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        margin: 0;
        text-align: justify;
    }

    .quote-author {
        display: block;
        margin-top: 15px;
        font-weight: 700;
        font-style: normal;
        font-size: 0.95rem;
        color: #001531;
    }

    .cientistas-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .cientistas-highlight {
        background: linear-gradient(180deg, #f8fbff 0%, #eef3f8 100%);
        border: 1px solid rgba(0, 51, 102, 0.08);
        border-radius: 18px;
        padding: 36px;
        max-width: 1400px;
        margin: 0 auto;
        box-shadow: 0 10px 30px rgba(0, 51, 102, 0.08);
    }

    .cientistas-highlight-intro {
        max-width: 920px;
        margin: 0 auto 28px;
        text-align: center;
    }

    .cientistas-highlight-intro span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(0, 51, 102, 0.08);
        color: #003366;
        font-family: 'Roboto', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 16px;
    }

    .cientistas-highlight-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.08rem;
        color: #334155;
        line-height: 1.8;
        margin: 0;
    }

    .cientista-card {
        background: #fff;
        padding: 30px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 2px solid #e0e0e0;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .cientista-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .cientista-card::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 5px;
        background: linear-gradient(90deg, #003366 0%, #2f6fad 100%);
    }

    .cientista-card-icon {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        color: #003366;
        font-size: 1.45rem;
    }

    .cientista-card-icon.amber {
        background: #fef3c7;
    }

    .cientista-card-icon.indigo {
        background: #e0e7ff;
    }

    .cientista-card-icon.sky {
        background: #e0f2fe;
    }

    .cientista-card-icon.emerald {
        background: #d1fae5;
    }

    .cientista-card h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3em;
        font-weight: 700;
        color: #003366;
        margin-bottom: 8px;
    }

    .cientista-card .subtitle {
        font-family: 'Roboto', sans-serif;
        font-size: 0.9rem;
        color: #003366;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
        opacity: 0.8;
    }

    .cientista-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #333;
        line-height: 1.7;
        text-align: left;
        margin: 0;
    }

    /* Seção SCB */
    .scb-section {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        color: #fff;
        padding: 50px 40px;
        border-radius: 15px;
        margin: 50px 0;
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
    }

    .scb-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #fff;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .scb-section > p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #f8f9fa;
        margin-bottom: 30px;
        opacity: 0.95;
    }

    .scb-list {
        list-style: none;
        padding: 0;
        margin: 0 0 40px 0;
    }

    .scb-list li {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 20px;
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.7;
        color: #f8f9fa;
    }

    .scb-list li::before {
        content: "✓";
        background-color: rgba(255, 255, 255, 0.2);
        color: #fff;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-weight: bold;
        margin-top: 2px;
    }

    .scb-list li strong {
        font-weight: 700;
    }

    .scb-actions {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 30px;
        margin-top: 40px;
    }

    .btn-scb {
        display: inline-block;
        background: #fff;
        color: #003366;
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        padding: 15px 35px;
        border-radius: 50px;
        text-decoration: none;
        transition: transform 0.3s, box-shadow 0.3s;
        font-size: 1.05rem;
    }

    .btn-scb:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    }

    .scb-social {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .scb-social-label {
        font-family: 'Roboto', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255, 255, 255, 0.8);
        text-align: center;
    }

    .scb-social-links {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
        text-decoration: none;
        transition: all 0.3s;
    }

    .social-link:hover {
        background-color: rgba(255, 255, 255, 0.25);
        transform: scale(1.1);
    }

    .social-link svg {
        width: 24px;
        height: 24px;
        fill: currentColor;
    }

    /* Seção Conclusão */
    .conclusao-section {
        margin: 44px 0 0;
        text-align: center;
    }

    .conclusao-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        margin-bottom: 16px;
        font-weight: 500;
    }

    .conclusao-section > p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        color: #333;
        line-height: 1.8;
        margin-bottom: 12px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .verse-box {
        background: transparent;
        color: inherit;
        padding: 10px 0 0;
        border-radius: 15px;
        margin: 0 auto;
        max-width: 900px;
    }

    /* Une "Conclusão" com a citação e remove gap no rodapé */
    .conclusao-section .verse-box {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    /* A citação em si com fundo azul escuro */
    .conclusao-section .verse-box blockquote.acb-quote {
        background: linear-gradient(135deg, #003366 0%, #001531 100%) !important;
        color: #ffffff !important;
        border-left-color: rgba(255, 255, 255, 0.35) !important;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
        padding: 18px 22px !important;
        margin: 0 auto !important;
        border-radius: 14px !important;
    }

    .conclusao-section .verse-box blockquote.acb-quote p {
        color: #ffffff !important;
    }

    .conclusao-section .verse-box blockquote.acb-quote .acb-quote__ref {
        color: rgba(255, 255, 255, 0.88) !important;
    }

    .verse-text {
        font-family: 'Roboto', sans-serif;
        font-size: 1.5em;
        font-weight: 700;
        font-style: italic;
        margin-bottom: 15px;
        line-height: 1.6;
    }

    .verse-reference {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        opacity: 0.9;
    }

    .conclusao-links {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 15px;
        margin-top: 30px;
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .conclusao-links a {
        color: #003366;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: color 0.3s;
    }

    .conclusao-links a:hover {
        color: #001531;
    }

    .conclusao-links .separator {
        color: #ccc;
    }

    .credits {
        text-align: center;
        padding: 30px 0;
        color: #999;
        font-family: 'Roboto', sans-serif;
        font-size: 0.85rem;
    }

    /* Responsivo */
    @media (max-width: 1200px) {
        .cientistas-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .page-hero,
        .newton-section,
        .scb-section,
        .cientistas-highlight {
            padding: 30px 20px;
        }

        .newton-grid,
        .cientistas-grid {
            grid-template-columns: 1fr;
        }

        .cientistas-highlight-intro {
            margin-bottom: 24px;
        }

        .cientistas-section h2,
        .scb-section h2,
        .conclusao-section h2 {
            font-size: 2em;
        }

        .conclusao-section h2 {
            margin-bottom: 12px;
        }

        .conclusao-section > p {
            font-size: 1.05rem;
            margin-bottom: 10px;
        }

        .verse-box {
            padding-top: 8px;
        }

        .conclusao-section .verse-box blockquote.acb-quote {
            padding: 16px 16px !important;
        }

        .verse-text {
            font-size: 1.2em;
        }

        .scb-actions {
            flex-direction: column;
        }

        .conclusao-links {
            flex-direction: column;
        }

        .conclusao-links .separator {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<img class="page-header-img" src="{{ asset('img/criacionismo/criacionismo_header.webp') }}" alt="Criacionismo e Ciência" fetchpriority="high" decoding="async">

<div class="page-container" style="padding-bottom: 0;">
    <div class="page-hero acb-fullbleed">
        <h1 class="page-title">Criacionismo e Ciência</h1>
        <h2 class="page-subtitle" style="font-style: italic;">A Harmonia entre Fé e Razão</h2>
        <p class="page-text">
            Aqui, exploramos como a ciência e a Bíblia se complementam, revelando a grandiosidade de um Criador amoroso. Inspirados pela Sociedade Criacionista Brasileira (SCB), destacamos a jornada de cientistas brilhantes que viram na natureza a assinatura divina.
        </p>
    </div>
</div>

<div class="page-container" style="padding-top: 0; padding-bottom: 40px;">
    <!-- Seção de Cientistas -->
    <div class="page-section cientistas-section">
        @foreach($scientists as $category)
        <div class="mb-5">
            <div class="cientista-category-title">
                @if(isset($category['icon']))
                <i class="bi bi-{{ $category['icon'] }}"></i>
                @endif
                <h2 class="acb-title-serif">{{ $category['title'] }}</h2>
            </div>

            @if($category['title'] === 'Isaac Newton: O Cientista que Viu Deus na Matemática')
                <!-- Layout especial para Newton -->
                <div class="newton-section">
                    <p class="newton-intro">
                        Isaac Newton, pai da física moderna, é um exemplo inspirador de como fé e ciência coexistem. Ele via o universo como um "livro escrito pelo geômetra supremo":
                    </p>

                    <div class="newton-grid">
                        @foreach($category['items'] as $item)
                        <div class="newton-card">
                            <h3>{{ $item['name'] }}</h3>
                            <p>{{ $item['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>

                    <div class="quote-card acb-quote">
                        <p>
                            "As Escrituras de Deus são a mais sublime filosofia. Encontro mais autenticidade na Bíblia que em qualquer história secular."
                            <span class="quote-author acb-quote__ref">— Isaac Newton</span>
                        </p>
                    </div>
                </div>
            @else
                <!-- Grid layout para outros cientistas -->
                <div class="cientistas-highlight">
                    <div class="cientistas-highlight-intro">
                        <span><i class="bi bi-stars"></i> Fé e conhecimento</span>
                        <p>
                            Grandes nomes da história da ciência também enxergavam propósito, ordem e inteligência na criação. Suas descobertas não afastaram a fé, mas reforçaram a convicção de que o universo carrega a assinatura do Criador.
                        </p>
                    </div>

                    <div class="cientistas-grid">
                    @foreach($category['items'] as $item)
                    @php
                        $scientistAccentMap = [
                            'Galileu Galilei' => ['icon' => 'binoculars', 'color' => 'amber'],
                            'Johannes Kepler' => ['icon' => 'globe2', 'color' => 'indigo'],
                            'Louis Pasteur' => ['icon' => 'virus', 'color' => 'sky'],
                            'Michael Faraday' => ['icon' => 'lightning-charge', 'color' => 'emerald'],
                        ];
                        $scientistAccent = $scientistAccentMap[$item['name']] ?? ['icon' => 'stars', 'color' => 'indigo'];
                    @endphp
                    <div class="cientista-card">
                        <div class="cientista-card-icon {{ $scientistAccent['color'] }}">
                            <i class="bi bi-{{ $scientistAccent['icon'] }}"></i>
                        </div>
                        <h3>{{ $item['name'] }}</h3>
                        @if(isset($item['subtitle']))
                        <div class="subtitle">{{ $item['subtitle'] }}</div>
                        @endif
                        <p>{{ $item['desc'] }}</p>
                    </div>
                    @endforeach
                    </div>
                </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Seção SCB -->
    <div class="page-section">
        <div class="scb-section acb-fullbleed">
            <h2 class="acb-title-serif">Conheça a Sociedade Criacionista Brasileira (SCB)!</h2>
            <p>A SCB promove o criacionismo com rigor científico e teológico através de diversos recursos:</p>

            <ul class="scb-list">
                <li>
                    <strong>Publicações:</strong> Revistas como a Folha/Revista Criacionista e livros como "Em Busca das Origens: Evolução ou Criação?".
                </li>
                <li>
                    <strong>Eventos:</strong> Seminários como "A Filosofia das Origens" que conectam fé e ciência.
                </li>
                <li>
                    <strong>Recursos Online:</strong> Artigos, vídeos e cursos disponíveis no site oficial.
                </li>
            </ul>

            <div class="scb-actions">
                <a href="https://scb.org.br/" target="_blank" rel="noopener noreferrer" class="btn-scb">
                    Visitar Site da SCB
                </a>

                <div class="scb-social">
                    <span class="scb-social-label">Siga a SCB nas redes sociais</span>
                    <div class="scb-social-links">
                        <!-- Instagram -->
                        <a href="https://www.instagram.com/scbexperience/" target="_blank" rel="noopener noreferrer" class="social-link" title="Instagram">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <!-- YouTube -->
                        <a href="https://www.youtube.com/channel/UC5OGhGF5w1EkyUDy32RkZzw" target="_blank" rel="noopener noreferrer" class="social-link" title="YouTube">
                            <svg viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                        <!-- Facebook -->
                        <a href="https://web.facebook.com/SociedadeCriacionistaBrasileira/?_rdc=1&_rdr#_" target="_blank" rel="noopener noreferrer" class="social-link" title="Facebook">
                            <svg viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção Conclusão -->
    <div class="page-section conclusao-section">
        <h2 class="acb-title-serif">Conclusão</h2>
        <p>
            A ciência não é inimiga da fé, mas uma aliada para revelar a majestade de Deus. A complexidade do universo e a precisão das leis naturais nos aproximam ainda mais do Criador.
        </p>

        <div class="verse-box acb-fullbleed">
            <blockquote class="acb-quote acb-quote--glass" style="max-width: 900px; margin: 0 auto;">
                <p>"Os céus declaram a glória de Deus; o firmamento proclama a obra das suas mãos."</p>
                <span class="acb-quote__ref">— Salmo 19:1</span>
            </blockquote>
        </div>
    </div>
</div>
@endsection

