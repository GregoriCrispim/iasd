@extends('layouts.app')

@section('title', 'IASD Central de Brasília - MAP (Ministério Adventista das Possibilidades)')

@section('meta-description', 'Conheça o MAP - Ministério Adventista das Possibilidades da IASD Central. Inclusão, acolhimento e participação de todos na missão da igreja: saúde mental, surdos, cegos, deficiência física, cuidadores, crianças e enlutados.')
@section('og-title', 'MAP - Ministério Adventista das Possibilidades')
@section('og-description', 'Promovendo o respeito e a inclusão de todas as pessoas na comunidade da igreja.')
@section('page-name', 'MAP')

@push('styles')
<style>

    .map-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .map-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    .map-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .map-intro .map-logo {
        width: 110px;
        height: auto;
        margin-bottom: 20px;
    }

    .map-sobre {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 40px;
        align-items: center;
        text-align: left;
        margin-top: 30px;
    }

    .map-sobre p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        margin-bottom: 18px;
    }

    .map-sobre p:last-child {
        margin-bottom: 0;
    }

    .map-sobre figure {
        margin: 0;
        text-align: center;
    }

    .map-sobre figure img {
        width: 100%;
        max-width: 360px;
        height: auto;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
    }

    .map-sobre figcaption {
        font-family: 'Roboto', sans-serif;
        font-size: 0.9rem;
        color: #666;
        margin-top: 12px;
    }

    .map-versiculos {
        background: #0b2a4a;
        padding: 50px 40px;
        border-radius: 18px;
        margin: 50px 0;
        color: #fff;
        text-align: center;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .map-versiculos blockquote {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3rem;
        font-style: italic;
        color: #f8f9fa;
        line-height: 1.8;
        max-width: 850px;
        margin: 0 auto 18px;
    }

    .map-versiculos blockquote:last-of-type {
        margin-bottom: 0;
    }

    .map-versiculos cite {
        display: block;
        font-style: normal;
        font-size: 1rem;
        color: #f1c9a1;
        font-weight: 600;
        margin-top: 8px;
    }

    .map-areas {
        margin: 60px 0;
    }

    .map-areas h2,
    .map-implantar h2,
    .map-personagens h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .map-areas > p,
    .map-implantar > p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #555;
        text-align: center;
        max-width: 850px;
        margin: 0 auto 40px;
    }

    .areas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .area-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-top: 5px solid var(--area-cor, #003366);
        border-radius: 15px;
        padding: 30px 25px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .area-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.14);
    }

    .area-card img {
        height: 76px;
        width: auto;
        margin-bottom: 18px;
    }

    .area-card h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15em;
        color: var(--area-cor, #003366);
        margin-bottom: 12px;
        font-weight: 700;
        line-height: 1.4;
    }

    .area-card h3 .sigla {
        display: block;
        font-size: 1.35em;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }

    .area-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.98rem;
        line-height: 1.7;
        color: #555;
        text-align: justify;
        margin: 0;
    }

    .map-personagens {
        margin: 60px 0;
        text-align: center;
    }

    .map-personagens figure {
        margin: 30px auto 0;
        max-width: 1000px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        padding: 40px 30px 25px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .map-personagens img {
        width: 100%;
        height: auto;
    }

    .map-personagens figcaption {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #666;
        margin-top: 18px;
    }

    .map-implantar {
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-top: 4px solid rgba(11, 42, 74, 0.22);
        padding: 60px 40px;
        border-radius: 18px;
        margin: 60px 0;
        color: #0f172a;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .passos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
    }

    .passo-card {
        background: #f8f9fa;
        border-radius: 14px;
        padding: 25px 20px;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .passo-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.12);
    }

    .passo-numero {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        color: #fff;
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
        margin-bottom: 14px;
    }

    .passo-card h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05em;
        color: #003366;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .passo-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        line-height: 1.6;
        color: #555;
        margin: 0;
    }

    .passo-card a {
        color: #003366;
        font-weight: 600;
    }

    .map-redes {
        margin: 60px 0;
    }

    .redes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .rede-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .rede-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .rede-card .icon {
        font-size: 2.4em;
        margin-bottom: 12px;
        display: block;
    }

    .rede-card h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1em;
        color: #003366;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .rede-card a,
    .rede-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #333;
        text-decoration: none;
        word-break: break-word;
    }

    .rede-card a:hover {
        text-decoration: underline;
        color: #003366;
    }

    .map-site {
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-top: 4px solid rgba(11, 42, 74, 0.22);
        padding: 50px 40px;
        border-radius: 18px;
        margin: 50px 0;
        text-align: center;
        color: #0f172a;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .map-site h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #0b2a4a;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .map-site p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: rgba(15, 23, 42, 0.78);
        margin-bottom: 25px;
    }

    .btn-site-map {
        display: inline-block;
        background-color: #0b2a4a;
        color: #fff;
        border: 1px solid rgba(11, 42, 74, 0.20);
        padding: 15px 40px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.1em;
        transition: transform 0.3s, box-shadow 0.3s;
        font-family: 'Roboto', sans-serif;
    }

    .btn-site-map:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.18);
        background-color: #083055;
    }

    @media (max-width: 768px) {
        .map-container {
            padding: 20px 15px;
        }

        .map-intro {
            padding: 30px 20px;
        }

        .map-intro h1 {
            font-size: 2.2em;
        }

        .map-sobre {
            grid-template-columns: 1fr;
        }

        .map-sobre figure {
            order: -1;
        }

        .map-versiculos,
        .map-implantar,
        .map-site {
            padding: 40px 20px;
        }

        .map-versiculos blockquote {
            font-size: 1.1rem;
        }

        .areas-grid,
        .redes-grid,
        .passos-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<img class="page-header-img" src="{{ asset('img/map/map_header.webp') }}" alt="Ministério Adventista das Possibilidades - MAP" fetchpriority="high" decoding="async">

<div class="map-container">

    <!-- Seção Introdutória / Sobre Nós -->
    <div class="map-intro acb-fullbleed">
        <img class="map-logo" src="{{ asset('img/map/logo-map.webp') }}" alt="Logotipo do Ministério Adventista das Possibilidades" width="110" decoding="async">
        <h1>MAP - Ministério Adventista das Possibilidades</h1>
        <div class="map-sobre">
            <div>
                <p>
                    O Ministério Adventista das Possibilidades (MAP) da Igreja Adventista do Sétimo Dia tem como objetivo
                    principal promover o respeito e a inclusão de todas as pessoas em sua comunidade.
                </p>
                <p>
                    Indo além da simples acessibilidade física, o MAP busca envolver ativamente todos os membros da igreja
                    em seus programas e projetos, visando à participação de todos na missão evangelística da igreja,
                    conforme instruído em Mateus 28:18-20.
                </p>
                <p>
                    Este ministério não se limita a um departamento isolado, mas busca integrar-se em todas as áreas da vida
                    da igreja, desenvolvendo estratégias para acolher e incluir indivíduos com diferentes necessidades, como
                    cegos, surdos, pessoas com deficiência física ou mobilidade reduzida, cuidadores, crianças órfãs ou em
                    situação de vulnerabilidade, saúde mental e bem-estar, e enlutados.
                </p>
            </div>
            <figure>
                <img src="{{ asset('img/map/arvore.webp') }}" alt="Árvore com cubos coloridos representando a inclusão de todas as possibilidades" loading="lazy" decoding="async">
                <figcaption>Uma igreja para todos, com todas as possibilidades.</figcaption>
            </figure>
        </div>
    </div>

    <!-- Seção Versículos -->
    <div class="map-versiculos acb-fullbleed">
        <blockquote>
            "Pois ele trata a todos com igualdade"
            <cite>Romanos 2:11 - NTLH</cite>
        </blockquote>
        <blockquote>
            "...pertencem ao mesmo Senhor, que está no céu, o qual trata a todos igualmente"
            <cite>Efesios 6:9 - NTLH</cite>
        </blockquote>
    </div>

    <!-- Seção 7 Áreas do MAP -->
    <div class="map-areas">
        <h2 class="acb-title-serif"><i class="bi bi-grid-3x3-gap-fill"></i> 7 Áreas do MAP</h2>
        <p>
            O MAP atua por meio de sete frentes de acolhimento e inclusão. Conheça cada uma delas:
        </p>

        <div class="areas-grid">
            <div class="area-card" style="--area-cor: #e4a032;">
                <img src="{{ asset('img/map/icon-masm.webp') }}" alt="Ícone da área de Saúde Mental: perfil de cabeça com coração" loading="lazy" decoding="async">
                <h3><span class="sigla">MASM</span> Ministério Adventista da Saúde Mental e Bem-Estar</h3>
                <p>
                    Nossa maior missão é amar, acolher e abraçar as famílias atípicas através do relacionamento,
                    apresentando uma esperança e conforto através da comunhão com Deus.
                </p>
            </div>

            <div class="area-card" style="--area-cor: #c44217;">
                <img src="{{ asset('img/map/icon-maoc.webp') }}" alt="Ícone da área de Crianças Órfãs: mão acolhendo família" loading="lazy" decoding="async">
                <h3><span class="sigla">MAOC</span> Ministério Adventista de Crianças Órfãs e em Situação de Vulnerabilidade</h3>
                <p>
                    O exemplo de Jesus estava claramente estendido à compreensão, à esperança e à crença no indivíduo
                    enquanto abria portas de oportunidade para o serviço, atendendo às necessidades únicas de crianças
                    órfãs e vulneráveis.
                </p>
            </div>

            <div class="area-card" style="--area-cor: #b61449;">
                <img src="{{ asset('img/map/icon-madv.webp') }}" alt="Ícone da área de Cegos e Baixa Visão: olho com símbolo de visão" loading="lazy" decoding="async">
                <h3><span class="sigla">MADV</span> Ministério Adventista de Cegos e Baixa Visão</h3>
                <p>
                    Ser e fazer discípulos cegos e com baixa visão através da comunhão, relacionamento e missão. Todos
                    somos chamados para pregar o evangelho, pessoas com ou sem deficiência — essa é a missão que Cristo
                    nos deixou.
                </p>
            </div>

            <div class="area-card" style="--area-cor: #2291c0;">
                <img src="{{ asset('img/map/icon-madf.webp') }}" alt="Ícone da área de Deficiência Física: cadeira de rodas" loading="lazy" decoding="async">
                <h3><span class="sigla">MADF</span> Ministério Adventista de Deficiência Física e Mobilidade Reduzida</h3>
                <p>
                    Com maior aceitação, as pessoas podem experimentar maior pertencimento, incluindo a sensação de serem
                    dotadas, necessárias e valorizadas.
                </p>
            </div>

            <div class="area-card" style="--area-cor: #3c988b;">
                <img src="{{ asset('img/map/icon-mac.webp') }}" alt="Ícone da área de Cuidadores: cuidadora acompanhando pessoa" loading="lazy" decoding="async">
                <h3><span class="sigla">MAC</span> Ministério Adventista de Cuidadores</h3>
                <p>
                    Nossa missão é oferecer suporte e atenção às necessidades e desafios dos cuidadores, promovendo a
                    conscientização entre os membros da igreja e comunidade local.
                </p>
            </div>

            <div class="area-card" style="--area-cor: #5db9af;">
                <img src="{{ asset('img/map/icon-mas.webp') }}" alt="Ícone da área dos Surdos: mãos em Libras" loading="lazy" decoding="async">
                <h3><span class="sigla">MAS</span> Ministério Adventista dos Surdos</h3>
                <p>
                    Nosso propósito é levar as verdades bíblicas à Comunidade Surda, que possui necessidades linguísticas
                    próprias, e apresentar Cristo como o verdadeiro Deus.
                </p>
            </div>

            <div class="area-card" style="--area-cor: #8fb897;">
                <img src="{{ asset('img/map/icon-maen.webp') }}" alt="Ícone da área de Enlutados: pessoa sendo abraçada no banco" loading="lazy" decoding="async">
                <h3><span class="sigla">MAEn</span> Ministério Adventista de Enlutados</h3>
                <p>
                    Acolher pessoas dilaceradas pela dor da perda e apresentar a elas o Único capaz de vencer a morte, a
                    esperança do reencontro e da vida eterna com aqueles que partiram.
                </p>
            </div>
        </div>
    </div>

    <!-- Seção Ilustração -->
    <div class="map-personagens acb-fullbleed">
        <h2 class="acb-title-serif"><i class="bi bi-people-fill"></i> Todos Têm um Lugar na Missão</h2>
        <figure>
            <img src="{{ asset('img/map/personagens.webp') }}" alt="Ilustração de pessoas representando as sete áreas do MAP: Laura (MASM), Maria e Vitória (MAOC), Victor (MADV), Julinho (MADF), Karen e José (MAC), Vivaldo e Paulo (MAS) e Rita (MAEn)" loading="lazy" decoding="async">
            <figcaption>
                Cada pessoa representa uma área do MAP — porque a igreja é completa quando todos participam.
            </figcaption>
        </figure>
    </div>

    <!-- Seção Como Implantar -->
    <div class="map-implantar acb-fullbleed">
        <h2 class="acb-title-serif"><i class="bi bi-rocket-takeoff-fill"></i> Como Implantar o MAP na Sua Igreja</h2>
        <p>
            Implementar o MAP na igreja local requer cuidado, sensibilidade e estratégias adequadas para a pregação do
            evangelho. Por isso, aqui estão 8 dicas práticas para implantar esse ministério de forma eficaz:
        </p>

        <div class="passos-grid">
            <div class="passo-card">
                <span class="passo-numero">1</span>
                <h4>Ore!</h4>
                <p>Fale com Deus sobre o desejo de servir neste Ministério.</p>
            </div>

            <div class="passo-card">
                <span class="passo-numero">2</span>
                <h4>Fale com seu Pastor</h4>
                <p>Informe a ele o seu desejo de ajudar neste Ministério.</p>
            </div>

            <div class="passo-card">
                <span class="passo-numero">3</span>
                <h4>Vote em comissão</h4>
                <p>Conforme o Manual da Igreja - 2022, pág. 95-97.</p>
            </div>

            <div class="passo-card">
                <span class="passo-numero">4</span>
                <h4>Sensibilização e Conscientização</h4>
                <p>Da igreja — o chamado.</p>
            </div>

            <div class="passo-card">
                <span class="passo-numero">5</span>
                <h4>Lançamento do MAP</h4>
                <p>Sugestão: no sábado pela manhã e, à tarde, reúna os membros e divida as equipes.</p>
            </div>

            <div class="passo-card">
                <span class="passo-numero">6</span>
                <h4>Monte a equipe</h4>
                <p>Una-se a eles e crie um calendário de ações pontuais.</p>
            </div>

            <div class="passo-card">
                <span class="passo-numero">7</span>
                <h4>Projetos</h4>
                <p>Crie condições para a realização dos projetos.</p>
            </div>

            <div class="passo-card">
                <span class="passo-numero">8</span>
                <h4>Questionário</h4>
                <p>Aplique o questionário em sua igreja.<br><a href="http://adv.st/questionario-map" target="_blank" rel="noopener noreferrer">adv.st/questionario-map</a></p>
            </div>
        </div>
    </div>

    <!-- Seção Redes Sociais e Contato -->
    <div class="map-redes acb-fullbleed">
        <h2 class="acb-title-serif"><i class="bi bi-share-fill"></i> Siga-nos em Nossas Redes Sociais</h2>
        <div class="redes-grid">
            <div class="rede-card">
                <i class="bi bi-instagram icon" style="color: #833ab4;"></i>
                <h4>Instagram / Facebook</h4>
                <a href="https://instagram.com/map.dsa" target="_blank" rel="noopener noreferrer">@map.dsa</a>
            </div>

            <div class="rede-card">
                <i class="bi bi-youtube icon" style="color: #ff0000;"></i>
                <h4>YouTube</h4>
                <a href="https://youtube.com/@mapdsaoficial" target="_blank" rel="noopener noreferrer">youtube.com/@mapdsaoficial</a>
            </div>

            <div class="rede-card">
                <i class="bi bi-envelope-fill icon" style="color: #003366;"></i>
                <h4>E-mail</h4>
                <a href="mailto:map@adventistas.org">map@adventistas.org</a>
            </div>
        </div>
    </div>

    <!-- Seção Site Oficial -->
    <div class="map-site acb-fullbleed">
        <h3 class="acb-title-serif"><i class="bi bi-globe-americas"></i> Saiba Mais</h3>
        <p>Conheça mais sobre o Ministério Adventista das Possibilidades acessando a página oficial da igreja!</p>
        <a href="https://www.adventistas.org/pt/possibilidades/" target="_blank" rel="noopener noreferrer" class="btn-site-map">Acessar Site Oficial do MAP</a>
    </div>

</div>
@endsection
