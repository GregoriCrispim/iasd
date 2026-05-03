@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Corais e Orquestras')

@section('meta-description', 'Conheça os corais e orquestras da IASD Central de Brasília. Coral infantil, adulto, orquestra e muito mais. Venha fazer música conosco!')
@section('og-title', 'Corais e Orquestras - IASD Central de Brasília')
@section('og-description', 'Música para glorificar a Deus! Participe de nossos corais e orquestras.')
@section('page-name', 'Corais e Orquestras')

@php
// Definição dos dados dos corais em Array PHP para fácil manutenção
$choirs = [
    [
        "name" => "Coral Infantil de Brasília - CIB",
        "icon" => "smile",
        "images" => ["infantil1.webp", "infantil2.webp", "infantil3.webp"],
        "leaders" => [
            ["role" => "Diretora", "name" => "Érika Patrícia Monteiro Garcia"],
            ["role" => "Secretaria", "name" => "Raquel Menon"],
            ["role" => "Regente", "name" => "Elisângela Rosa Terto da Silva"],
            ["role" => "Pianista", "name" => "Eddie Schultz Henrique"]
        ],
        "description" => "O Coral Infantil de Brasília foi formado em 2014, nesta igreja, com a participação de 120 crianças realizando sua primeira apresentação em um sábado especial, comemorando o Dia das Mães. A regente inicial, Simone Bohry, liderou o coral por 10 anos, apoiada por uma dedicada equipe de mães. Elas se uniram para plantar nos corações de seus filhos e das crianças da igreja o amor por Jesus através do louvor. Os ensaios ocorriam durante a Escola Sabatina, e as crianças se envolviam cada vez mais nesse ministério. Participações em datas comemorativas, cantatas com o Grande Coro e envolvimento solidário marcaram a história do coral. O objetivo era promover o envolvimento ativo das crianças na igreja, aumentando seu interesse pelo louvor e adoração a Deus.

O Coral Infantil sempre foi grande, com crianças de 3 a 9 anos. Atualmente, conta com cerca de 130 crianças inscritas, sob a regência da Prof. Elisângela Rosa Terto da Silva, conhecida como Tia Rosinha, e com Eddie Schultz Henrique como pianista. O coral continua a ser apoiado por mães voluntárias que ajudam na organização e cuidado das crianças participantes.

Em 2024, o Coral Infantil celebrou 10 anos de louvor, missão e ensino sobre o amor a Deus. Crianças que começaram no coral há 10 anos continuam esse legado, participando de outros corais da igreja, como o Coral Juvenil, Coral de Adolescentes e Coral Jovem. Uma característica marcante é a participação ativa dos pais, que são os principais motivadores deste ministério. Os pais são os principais em apoiarem os ensaios e envolverem os filhos nas programações.

A Bíblia diz em Mateus 21:16 que Jesus ama ouvir o louvor das crianças. Ele também disse que é da 'boca de pequeninos que surge o perfeito louvor'."
    ],
    [
        "name" => "Coral Juvenil de Brasília",
        "icon" => "users",
        "images" => ["juvenil1.webp", "juvenil2.webp", "juvenil3.webp", "juvenil4.webp"],
        "leaders" => [
            ["role" => "Diretora", "name" => "Juliana Bullón"],
            ["role" => "Regente", "name" => "Simone Bohry"],
            ["role" => "Pianista", "name" => "Marcos de Paula"]
        ],
        "description" => "O Coral Juvenil de Brasília é o mais novo coral da Igreja Adventista Central de Brasília. Diante do crescimento dessa comunidade nos últimos anos e da ausência de um coral que atendesse à faixa etária entre 10 e 12 anos, o Coral Juvenil foi criado com o objetivo de envolver e fortalecer a adoração e o louvor dos nossos meninos e meninas.

Simone Bohry, nossa maestrina e pianista, é psicóloga, palestrante, esposa e mãe de dois filhos. Um deles participa do Coral Juvenil. Foi pianista do Coral Jovem de Brasília e maestrina do Coral Infantil de Bsb por quase 10 anos. Além disso, dirige também o Coral Feminino da Igreja Central de Brasília.

Iniciamos nossas atividades no dia 24 de fevereiro de 2024 e, apesar de ainda não termos completado um ano de ministério, estamos muito felizes e gratos a Deus pelas bênçãos e desafios vencidos até aqui."
    ],
    [
        "name" => "Coral Adolescente de Brasília",
        "icon" => "user-plus",
        "images" => ["adolescente1.webp", "adolescente2.webp", "adolescente3.webp"],
        "leaders" => [
            ["role" => "Regente", "name" => "Cristiane Lamarques"],
            ["role" => "Pianista", "name" => "André Prata"]
        ],
        "description" => "O Coral Adolescente de Brasília fez 1 ano de vida no mês de agosto de 2024. O Coral já se apresentou em musicais na igreja Adventista do Sétimo Dia, nos cultos de adoração de sábado, e teve a grata satisfação de cantar juntamente com o Quarteto Arautos do Rei em agosto deste mesmo ano. A sua última apresentação foi na comemoração de 1 ano de Coral, também em agosto de 2024. O objetivo desse coral é despertar, no coração dos adolescentes e das demais pessoas, o amor a Jesus."
    ],
    [
        "name" => "Coral Jovem de Brasília - CJB",
        "icon" => "music",
        "images" => ["jovem1.webp", "jovem2.webp", "jovem3.webp", "jovem4.webp"],
        "leaders" => [
            ["role" => "Diretora", "name" => "Juliana Cesario"],
            ["role" => "Secretaria", "name" => "Maisa Miranda"],
            ["role" => "Regente/Pianista", "name" => "Marcos de Paula"]
        ],
        "description" => "Somos o Coral Jovem de Brasília: um grupo que ama cantar e espalhar amor, esperança e paz há mais de 27 anos. Nossa sede fica na Igreja Adventista do Sétimo Dia Central de Brasília, Distrito Federal. De lá, já viajamos por vários estados brasileiros, além de Moçambique, Estados Unidos e Peru. Ao longo desses anos, já produzimos 3 DVDs - 'O Encontro', 'Jornada de Esperança' e 'Para Sempre', 2 CDs - 'O Centro de Tudo' e 'Infinito', 1 EP - 'Live Session' - e diversos singles e clipes. Além disso, estamos envolvidos em ações solidárias e apresentações de musicais em datas comemorativas, como Páscoa e Natal."
    ],
    [
        "name" => "Coral Adventista de Brasília - CAB",
        "icon" => "award",
        "images" => ["adventista1.webp", "adventista2.webp", "adventista3.webp"],
        "leaders" => [
            ["role" => "Diretora", "name" => "Christiane Coelho"],
            ["role" => "Secretaria", "name" => "Gilmara Crispim"],
            ["role" => "Regentes", "name" => "Luiz Arado Júnior e Eldom Soares"],
            ["role" => "Pianistas", "name" => "Margarete Santos e Luz Arado"]
        ],
        "description" => "Com mais de cinco décadas de história, o Coral Adventista de Brasília é uma das instituições musicais mais tradicionais da Capital Federal, reconhecido por unir o rigor da música erudita ao propósito do evangelismo. Ao longo de sua trajetória, o grupo executou obras monumentais de mestres como Bach, Handel e Beethoven, colaborando frequentemente com a Orquestra Sinfônica do Teatro Nacional Claudio Santoro e alcançando palcos internacionais de prestígio, como o Carnegie Hall, em Nova York.

Após um longo e sólido legado sob a regência do Maestro Eldom Soares, o CAB é conduzido atualmente pelo Maestro Luiz Arado Junior, mantendo sua missão de excelência artística e adoração ao Salvador, a quem dedica todo o seu louvor e trajetória."
    ],
    [
        "name" => "Coral Feminino de Brasília",
        "icon" => "heart",
        "images" => ["feminino1.webp", "feminino2.webp", "feminino3.webp"],
        "leaders" => [
            ["role" => "Regente", "name" => "Simone Bohry"],
            ["role" => "Pianista", "name" => "Marcos de Paula"]
        ],
        "description" => "O Coral Feminino Adventista da Igreja Adventista do Sétimo Dia Central de Brasília iniciou suas atividades em março de 2023, por ocasião dos projetos do Ministério da Mulher e do Ministério da Música, visando o envolvimento de suas participantes em atividades que promovam a adoração à Deus através da música, além de um espaço para as mulheres compartilharem sua fé, apoiarem-se mutuamente e crescerem espiritual e plenamente. Composto por mulheres de todas as gerações, o coral se destaca não apenas pela diversidade de suas integrantes, mas também pelo compromisso em contribuir para o crescimento e fortalecimento espiritual da missão Viva da igreja."
    ],
    [
        "name" => "Coro Masculino de Brasília",
        "icon" => "shield",
        "images" => ["masculino1.webp", "masculino2.webp"],
        "captions" => ["FOTO DA PARTICIPAÇÃO NO 21º ENQM 2025 DA IGREJA MEMORIAL BATISTA DE BRASÍLIA", ""],
        "leaders" => [
            ["role" => "Regente", "name" => "Maestro Francisco Crispim"]
        ],
        "description" => "O Coro Adventista Masculino de Brasília destaca-se como um dos agrupamentos vocais mais tradicionais e expressivos da Capital Federal. Sob a regência do Maestro Francisco Crispim, o conjunto dedica-se à preservação e difusão da rica literatura coral escrita especificamente para vozes masculinas. Combinando técnica vocal apurada e um profundo compromisso ministerial, o coro busca a excelência na adoração, apresentando um repertório que transita entre os grandes clássicos da música sacra e hinos contemporâneos, sempre com a sonoridade robusta e solene que caracteriza sua identidade."
    ],
    [
        "name" => "Madrigal de Brasília",
        "icon" => "star",
        "images" => ["madrigal1.webp", "madrigal2.webp", "madrigal3.webp"],
        "leaders" => [
            ["role" => "Regente", "name" => "Maestro Francisco Crispim"]
        ],
        "description" => "O Madrigal Adventista de Brasília configura-se como um agrupamento vocal de elite, dedicado à interpretação de um repertório sacro de elevada complexidade e beleza. Sob a experiente regência do Maestro Francisco Crispim, o grupo é composto por vozes selecionadas que buscam a excelência técnica e o apuro estético em cada execução. Mais do que um conjunto musical, o Madrigal é um ministério de adoração que entende a música como uma extensão da pregação do Evangelho. Unindo precisão artística e profundidade espiritual, o grupo reafirma seu compromisso com a edificação da igreja e a proclamação da mensagem bíblica através da arte coral em seu mais alto nível."
    ]
];

$orchestras = [
    [
        "name" => "Orquestra CEMAB",
        "icon" => "graduation-cap",
        "images" => ["cemab1.webp", "cemab2.webp", "cemab3.webp"],
        "leaders" => [
            ["role" => "Direção", "name" => "José Maria Batista"],
            ["role" => "Regência", "name" => "Maestro Marcos de Paula"]
        ],
        "description" => "A Orquestra do Centro Musical Adventista de Brasília (CEMAB) representa o estágio de evolução do músico no projeto de educação musical. Sob a direção de José Maria Batista e a regência do Maestro Marcos de Paula, o conjunto é formado por alunos que percorrem um ciclo completo de aprendizado: desde os primeiros passos na musicalização com flauta doce até a especialização em instrumentos de orquestra. Este modelo pedagógico garante não apenas a proficiência técnica, mas o fortalecimento da identidade musical de cada integrante, consolidando o CEMAB como um centro de excelência no ensino e na prática orquestral na Capital Federal."
    ],
    [
        "name" => "Orquestra Adventista de Brasília",
        "icon" => "music",
        "images" => ["orquestra1.webp", "orquestra2.webp", "orquestra3.webp"],
        "leaders" => [
            ["role" => "Regente e Direção Artística", "name" => "Maestro Marcos de Paula"]
        ],
        "description" => "A Orquestra Adventista de Brasília consolida-se como um dos principais expoentes da música instrumental eclesiástica na Capital Federal. Sob a regência e direção artística do Maestro Marcos de Paula, a orquestra dedica-se à interpretação de um repertório que une o rigor da música técnica ao profundo propósito do louvor cristão. Composta por músicos dedicados à excelência executiva, a orquestra desempenha um papel fundamental na liturgia e na difusão cultural da Igreja Central de Brasília, promovendo o desenvolvimento artístico e a comunhão por meio da arte orquestral."
    ]
];
@endphp

@push('styles')
<style>
    html { scroll-behavior: smooth; }

    .corais-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px 0;
    }

    .corais-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    .corais-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .corais-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto;
    }

    .section-title {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.8em;
        color: #1e3a8a;
        text-align: center;
        margin: 50px 0 40px 0;
        font-weight: 500;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, #1e3a8a, #3b82f6);
        border-radius: 2px;
    }

    .groups-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 40px;
        margin-bottom: 50px;
    }

    .group-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        flex-direction: column;
    }

    .group-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border-color: #3b82f6;
    }

    .group-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 20px;
    }

    .group-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        flex-shrink: 0;
    }

    .group-icon svg {
        width: 28px;
        height: 28px;
    }

    .group-name {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15em;
        color: #1e3a8a;
        font-weight: 700;
        line-height: 1.3;
    }

    .group-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .group-images-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .image-wrapper {
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .group-images-gallery .group-image {
        height: 280px;
        margin-bottom: 0;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .group-images-gallery .group-image:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .image-caption {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        padding: 10px 15px;
        font-family: 'Roboto', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
        border-radius: 0 0 10px 10px;
        margin-top: -5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.4;
    }

    .group-description {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #475569;
        line-height: 1.7;
        margin-bottom: 20px;
        flex-grow: 1;
        white-space: pre-line;
    }

    .group-leaders {
        background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        padding: 14px;
        border-radius: 12px;
        border: 1px solid rgba(15, 23, 42, 0.10);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
    }

    .group-leaders-title {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-family: 'Roboto', sans-serif;
        font-size: 0.9em;
        color: #1e3a8a;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 12px;
        letter-spacing: 0.6px;
    }

    .group-leaders-title i {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: rgba(59, 130, 246, 0.12);
        color: #1e3a8a;
    }

    .leader-item {
        display: grid;
        grid-template-columns: max-content 1fr;
        gap: 10px;
        align-items: start;
        margin-bottom: 10px;
        font-size: 0.95rem;
        padding: 10px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(15, 23, 42, 0.08);
    }

    .leader-item:last-child {
        margin-bottom: 0;
    }

    .leader-role {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(59, 130, 246, 0.12);
        color: #1e3a8a;
        font-weight: 800;
        font-size: 0.82rem;
        letter-spacing: 0.2px;
    }

    .leader-name {
        color: #0f172a;
        font-weight: 650;
        line-height: 1.35;
    }

    .minister-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin: 50px 0 0;
        text-align: center;
    }

    /* Sem espaço entre a última seção e o rodapé */
    .corais-container > .minister-section {
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .minister-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #1e3a8a;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .minister-content {
        max-width: 800px;
        margin: 0 auto;
    }

    .minister-gallery {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .minister-photo {
        width: 250px;
        height: 250px;
        object-fit: cover;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        border: 3px solid #3b82f6;
    }

    .minister-photo:first-child {
        width: 400px;
        height: 250px;
    }

    .minister-photo:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    }

    .minister-name {
        font-family: 'Roboto', sans-serif;
        font-size: 1.8em;
        color: #1e3a8a;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .minister-title {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1em;
        color: #3b82f6;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .minister-description {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #475569;
        line-height: 1.8;
    }

    @media (max-width: 768px) {
        .corais-container {
            padding: 20px 15px 0;
        }

        .corais-intro {
            padding: 30px 20px;
        }

        .corais-intro h1 {
            font-size: 2.2em;
        }

        .section-title {
            font-size: 2em;
            margin: 40px 0 30px 0;
        }

        .groups-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .group-card {
            padding: 25px 20px;
        }

        .group-image {
            height: 180px;
        }

        .group-images-gallery {
            grid-template-columns: 1fr;
        }

        .group-images-gallery .group-image {
            height: 250px;
        }

        .minister-section {
            padding: 35px 25px;
        }

        .minister-gallery {
            gap: 15px;
        }

        .minister-photo {
            width: 200px;
            height: 200px;
        }

        .minister-photo:first-child {
            width: 100%;
            max-width: 350px;
            height: 200px;
        }

        .minister-name {
            font-size: 1.5em;
        }

        .leader-item {
            grid-template-columns: 1fr;
        }
    }

    /* Lightbox Styles */
    .lightbox {
        --corais-lightbox-right-gap: 0px;
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: var(--header-height);
        width: calc(100vw - var(--corais-lightbox-right-gap));
        height: calc(100vh - var(--header-height));
        height: calc(100dvh - var(--header-height));
        background-color: rgba(0, 0, 0, 0.9);
        justify-content: center;
        align-items: center;
        padding: 20px;
        box-sizing: border-box;
        overflow: hidden;
    }

    .lightbox.active {
        display: flex;
    }

    .lightbox-content {
        max-width: min(90%, 1200px);
        max-height: 90%;
        object-fit: contain;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        margin: 0 auto;
    }

    .lightbox-close {
        position: absolute;
        top: 18px;
        right: 28px;
        color: #fff;
        font-size: 50px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s;
        user-select: none;
        line-height: 1;
        z-index: 1;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: rgba(0, 0, 0, 0.45);
    }

    .lightbox-close:hover {
        color: #3b82f6;
    }
</style>
@endpush

@section('content')
<img class="page-header-img" src="{{ asset('img/corais/corais_header.webp') }}" alt="Corais e Orquestras" fetchpriority="high" decoding="async">

<div class="corais-container">
    <!-- Seção Introdutória -->
    <div class="corais-intro acb-fullbleed">
        <h1 class="acb-title-serif">Corais e Orquestras</h1>
        <p>
            Bem-vindo ao ministério de música da Igreja Adventista Central de Brasília. Aqui você encontrará uma diversidade de corais e orquestras que dedicam seus talentos para louvar a Deus e edificar a igreja através da música sacra.
        </p>
    </div>

    <!-- Seção de Corais -->
    <h2 class="section-title acb-title-serif">Nossos Corais</h2>

    <div class="groups-grid">
        @foreach($choirs as $choir)
        <div class="group-card">
            <div class="group-header">
                <div class="group-icon">
                    <i data-lucide="{{ $choir['icon'] }}"></i>
                </div>
                <h3 class="group-name">{{ $choir['name'] }}</h3>
            </div>
            @if(isset($choir['images']) && count($choir['images']) > 0)
            <div class="group-images-gallery">
                @foreach($choir['images'] as $index => $image)
                <div class="image-wrapper">
                    <img src="{{ asset('img/corais/' . $image) }}" alt="{{ $choir['name'] }}" loading="lazy" decoding="async" class="group-image lightbox-trigger" data-full="{{ asset('img/corais/' . $image) }}">
                    @if(isset($choir['captions']) && isset($choir['captions'][$index]) && !empty($choir['captions'][$index]))
                    <div class="image-caption">{{ $choir['captions'][$index] }}</div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
            <p class="group-description">{{ $choir['description'] }}</p>
            <div class="group-leaders">
                <div class="group-leaders-title"><i class="bi bi-people-fill"></i> Liderança</div>
                @foreach($choir['leaders'] as $leader)
                <div class="leader-item">
                    <span class="leader-role">{{ $leader['role'] }}</span>
                    <span class="leader-name">{{ $leader['name'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <!-- Seção de Orquestras -->
    <h2 class="section-title acb-title-serif">Nossas Orquestras</h2>

    <div class="groups-grid">
        @foreach($orchestras as $orchestra)
        <div class="group-card">
            <div class="group-header">
                <div class="group-icon">
                    <i data-lucide="{{ $orchestra['icon'] }}"></i>
                </div>
                <h3 class="group-name">{{ $orchestra['name'] }}</h3>
            </div>
            @if(isset($orchestra['images']) && count($orchestra['images']) > 0)
            <div class="group-images-gallery">
                @foreach($orchestra['images'] as $image)
                <div class="image-wrapper">
                    <img src="{{ asset('img/corais/' . $image) }}" alt="{{ $orchestra['name'] }}" loading="lazy" decoding="async" class="group-image lightbox-trigger" data-full="{{ asset('img/corais/' . $image) }}">
                </div>
                @endforeach
            </div>
            @endif
            <p class="group-description">{{ $orchestra['description'] }}</p>
            <div class="group-leaders">
                <div class="group-leaders-title"><i class="bi bi-people-fill"></i> Liderança</div>
                @foreach($orchestra['leaders'] as $leader)
                <div class="leader-item">
                    <span class="leader-role">{{ $leader['role'] }}</span>
                    <span class="leader-name">{{ $leader['name'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <!-- Seção Ministro de Música -->
    <div class="minister-section acb-fullbleed">
        <h2 class="acb-title-serif">Ministro de Música</h2>
        <div class="minister-content">
            <div class="minister-gallery">
                <img src="{{ asset('img/corais/maestro1.webp') }}" alt="Maestro Marcos de Paula" loading="lazy" decoding="async" class="minister-photo lightbox-trigger" data-full="{{ asset('img/corais/maestro1.webp') }}">
                <img src="{{ asset('img/corais/maestro2.webp') }}" alt="Maestro Marcos de Paula" loading="lazy" decoding="async" class="minister-photo lightbox-trigger" data-full="{{ asset('img/corais/maestro2.webp') }}">
            </div>
            <div class="minister-name">Marcos de Paula</div>
            <div class="minister-title">Bacharelando em Música pela Universidade de São Paulo (USP)</div>
            <p class="minister-description">
                Sob a mentoria do Dr. Silvio Ferraz Mello, é o atual Ministro de Música da Igreja Adventista Central de Brasília. Sua formação técnica contempla especializações em Piano e Educação Musical com referências do cenário nacional. Unindo o rigor acadêmico à prática ministerial, possui um histórico expressivo como educador em conservatórios e na direção musical de grandes agrupamentos corais, destacando-se pela liderança e excelência na gestão de projetos musicais institucionais.
            </p>
        </div>
    </div>
</div>

<!-- Lightbox -->
<div class="lightbox" id="lightbox" aria-hidden="true">
    <span class="lightbox-close" aria-label="Fechar">&times;</span>
    <img class="lightbox-content" id="lightbox-img" src="" alt="" loading="lazy" decoding="async">
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@0.460.0/dist/umd/lucide.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxClose = document.querySelector('.lightbox-close');
    const lightboxTriggers = document.querySelectorAll('.lightbox-trigger');
    const pageAside = document.querySelector('aside');

    function syncLightboxBounds() {
        if (!lightbox) return;

        let rightGap = 0;

        if (pageAside) {
            const asideRect = pageAside.getBoundingClientRect();

            if (asideRect.width > 0 && asideRect.right > asideRect.left) {
                rightGap = Math.max(0, asideRect.width);
            }
        }

        lightbox.style.setProperty('--corais-lightbox-right-gap', `${rightGap}px`);
    }

    function openLightbox() {
        if (!lightbox) return;
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
        syncLightboxBounds();
        lightbox.classList.add('active');
        lightbox.setAttribute('aria-hidden', 'false');
    }

    function closeLightbox() {
        if (!lightbox || !lightboxImg) return;
        lightbox.classList.remove('active');
        lightbox.setAttribute('aria-hidden', 'true');
        lightboxImg.src = '';
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
    }

    if (lightbox && lightboxImg && lightboxClose && lightboxTriggers.length > 0) {
        syncLightboxBounds();
        window.addEventListener('resize', syncLightboxBounds);

        lightboxTriggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                syncLightboxBounds();
                const fullSizeSrc = this.getAttribute('data-full') || this.getAttribute('src');
                const altText = this.getAttribute('alt') || '';
                lightboxImg.src = fullSizeSrc || '';
                lightboxImg.alt = altText;
                openLightbox();
            });
        });

        lightboxClose.addEventListener('click', function() {
            closeLightbox();
        });

        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && lightbox.classList.contains('active')) {
                closeLightbox();
            }
        });
    }
});
</script>
@endpush
