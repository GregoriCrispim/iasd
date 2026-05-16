@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Ministério da Mulher')

@section('meta-description', 'Ministério da Mulher da IASD Central de Brasília. Conheça nossa filosofia, missão, objetivos, atividades para 2026, calendário e muito mais.')
@section('og-title', 'Ministério da Mulher - Mulher Plena')
@section('og-description', 'Mulher Plena - Ministério da Mulher da IASD Central de Brasília. Filosofia, missão, objetivos e atividades para 2026.')
@section('page-name', 'Ministério da Mulher')

@push('styles')
<style>
    .mm-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 40px;
        box-sizing: border-box;
    }

    .mm-header-wrap {
        width: 100%;
        overflow: hidden;
        aspect-ratio: 1920 / 300;
    }

    .mm-page-header-img {
        width: 100%;
        max-width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    .mm-intro {
        background: linear-gradient(135deg, #d81b60 0%, #880e4f 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 40px;
        text-align: center;
    }

    .mm-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #fff;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .mm-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #f8f9fa;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto;
    }

    /* Abas Navigation */
    .tabs-container {
        margin: 40px 0;
    }

    .tabs-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 0;
        border-bottom: 3px solid #d81b60;
    }

    .tab-button {
        background: #f5f5f5;
        border: none;
        padding: 15px 25px;
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        cursor: pointer;
        transition: all 0.3s;
        border-radius: 8px 8px 0 0;
        flex: 1;
        min-width: 150px;
        text-align: center;
    }

    .tab-button:hover {
        background: #e0e0e0;
        color: #d81b60;
    }

    .tab-button.active {
        background: #d81b60;
        color: #fff;
    }

    /* Tab Content */
    .tab-content {
        display: none;
        background: #fff;
        padding: 40px;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .tab-content.active {
        display: block;
    }

    .tab-content h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.2em;
        color: #d81b60;
        margin-bottom: 25px;
        font-weight: 500;
        border-bottom: 2px solid #d81b60;
        padding-bottom: 10px;
    }

    .tab-content h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #880e4f;
        margin: 30px 0 15px;
        font-weight: 500;
    }

    .tab-content h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3em;
        color: #d81b60;
        margin: 20px 0 10px;
        font-weight: 600;
    }

    .tab-content p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 15px;
        text-align: justify;
    }

    .tab-content ul, .tab-content ol {
        margin: 15px 0;
        padding-left: 30px;
    }

    .tab-content li {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        margin: 10px 0;
    }

    /* Cards para os 4 Pilares */
    .pilares-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin: 30px 0;
    }

    .pilar-card {
        background: linear-gradient(135deg, #fce4ec 0%, #f8bbd0 100%);
        border: 2px solid #d81b60;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(216, 27, 96, 0.2);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .pilar-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(216, 27, 96, 0.3);
    }

    .pilar-card .icon {
        font-size: 3em;
        margin-bottom: 15px;
        display: block;
    }

    .pilar-card h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3em;
        color: #880e4f;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .pilar-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #333;
        line-height: 1.6;
        text-align: center;
        margin: 0;
    }

    /* Calendário */
    .calendario-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .calendario-item {
        background: #f8f9fa;
        border-left: 4px solid #d81b60;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .calendario-item h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2em;
        color: #d81b60;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .calendario-item .data {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #880e4f;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .calendario-item p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #333;
        line-height: 1.6;
        margin: 0;
        text-align: left;
    }

    /* Imagens da pesquisa */
    .pesquisa-images {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 30px;
        margin: 30px 0;
    }

    .pesquisa-image-container {
        text-align: center;
    }

    .pesquisa-image-container img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        margin-bottom: 15px;
    }

    /* Seção O Que Esperar */
    .esperar-section {
        background: linear-gradient(135deg, #fce4ec 0%, #f8bbd0 100%);
        padding: 30px;
        border-radius: 12px;
        margin: 20px 0;
        border-left: 5px solid #d81b60;
    }

    .esperar-section h4 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
        color: #880e4f;
        margin-bottom: 15px;
        font-weight: 500;
    }

    /* Responsivo */
    @media (max-width: 768px) {
        .mm-container {
            padding: 0 15px 20px;
        }

        .mm-intro {
            padding: 30px 20px;
        }

        .mm-intro h1 {
            font-size: 2.2em;
        }

        .tabs-nav {
            flex-direction: column;
        }

        .tab-button {
            width: 100%;
            margin-bottom: 5px;
            border-radius: 8px;
        }

        .tab-content {
            padding: 25px 20px;
        }

        .pilares-grid {
            grid-template-columns: 1fr;
        }

        .pesquisa-images {
            grid-template-columns: 1fr;
        }

        .calendario-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="mm-header-wrap">
    <img class="mm-page-header-img" src="{{ asset('img/cards/ministerio-mulher/mm_header.webp') }}" alt="Ministério da Mulher - Mulher Plena" fetchpriority="high" decoding="async">
</div>

<div class="mm-container">

    <!-- Seção Introdutória -->
    <div class="mm-intro acb-fullbleed">
        <h1>Ministério da Mulher - Mulher Plena</h1>
        <p>
            Bem-vinda ao Ministério da Mulher da IASD Central de Brasília! Nosso mission é promover o crescimento espiritual, emocional e social das mulheres, criando um ambiente de acolhimento, apoio e desenvolvimento pessoal.
        </p>
    </div>

    <!-- Sistema de Abas -->
    <div class="tabs-container">
        <div class="tabs-nav">
            <button class="tab-button active" onclick="openTab(event, 'geral')">Geral</button>
            <button class="tab-button" onclick="openTab(event, 'calendario')">Calendário 2026</button>
            <button class="tab-button" onclick="openTab(event, 'pesquisa')">Pesquisa</button>
            <button class="tab-button" onclick="openTab(event, 'esperar')">O Que Esperar</button>
        </div>

        <!-- Aba Geral -->
        <div id="geral" class="tab-content active">
            <h2>1. Filosofia</h2>
            <p>
                O Ministério da Mulher crê que cada mulher é chamada a conhecer Jesus como seu Salvador e a usar seus dons para servir como discípula no lar, na igreja e na comunidade.
            </p>

            <h2>2. Missão</h2>
            <p>
                O Ministério da Mulher existe para cuidar, encorajar, desafiar e capacitar a mulher adventista do sétimo dia em sua caminhada diária como discípula de Jesus Cristo e como membro da Igreja.
            </p>

            <h2>3. Objetivos</h2>
            <ul>
                <li>Capacitar a mulher a aprofundar sua fé, crescer e renovar-se espiritualmente.</li>
                <li>Dignificar a mulher como pessoa de valor inestimável, criada e redimida por Deus.</li>
                <li>Cooperar com outros departamentos da igreja para atender às necessidades das mulheres.</li>
                <li>Expressar a visão feminina adventista nos diversos espaços deliberativos da Igreja.</li>
                <li>Ampliar oportunidades de serviço cristão dinâmico, desafiando cada mulher ao cumprimento da missão.</li>
            </ul>

            <h2>4. Os 4 Pilares da Plenitude</h2>

            <h3>1. Mulher Plena com Deus – Discipulado</h3>
            <ul>
                <li>Mulher de oração</li>
                <li>Estudante da Bíblia, do Espírito de Profecia e da Lição da Escola Sabatina</li>
                <li>Mulher missionária</li>
            </ul>

            <h3>2. Mulher Plena Consigo Mesma – Identidade</h3>
            <ul>
                <li>Cuidado com a saúde física e emocional</li>
                <li>Ser autora da própria história</li>
                <li>Crescimento profissional</li>
                <li>Educação financeira e empreendedorismo</li>
            </ul>

            <h3>3. Mulher Plena com a Família – Identidade e Novas Gerações</h3>
            <ul>
                <li>Relacionamento conjugal</li>
                <li>Educação dos filhos para servir a Deus</li>
                <li>Delegar responsabilidades</li>
                <li>Vida sexual nas diferentes fases</li>
            </ul>

            <h3>4. Mulher Plena com a Missão – Liderança e Serviço</h3>
            <ul>
                <li>Desenvolver dons e transformá-los em ministério</li>
                <li>Preparar-se para estudar a Bíblia com pelo menos uma pessoa</li>
                <li>Apoiar a Semana de Evangelismo Feminino</li>
            </ul>

            <h2>5. Atividades para 2026</h2>

            <h3>MULHER PLENA COM DEUS</h3>

            <h4>Devoção Pessoal:</h4>
            <ul>
                <li>Grupo de WhatsApp "Mulher Plena BSB"</li>
                <li>Incentivo ao Projeto Maná</li>
                <li>Fortalecimento da Escola Sabatina</li>
                <li>Ministério de Oração ativo (Líder: Joana)</li>
            </ul>

            <h4>Dez Dias de Clamor e 365 Dias de Oração:</h4>
            <ul>
                <li>Apoiar e colaborar com a liderança da igreja</li>
                <li>Lançar a jornada anual dos Frutos do Espírito</li>
                <li><strong>Meta:</strong> Todas as Mulheres Envolvidas</li>
            </ul>

            <h4>Quartas de Poder:</h4>
            <p>Última quarta-feira de cada mês – A partir do mês de junho</p>

            <h4>Retiro Espiritual:</h4>
            <p>Realização de Retiro Espiritual com as mulheres da Igreja<br>
            <strong>Data:</strong> 07 e 08 de agosto</p>

            <h3>MULHER PLENA CONSIGO E COM A FAMÍLIA</h3>

            <h4>Curso de Liderança Feminina (APLaC):</h4>
            <p>Divulgação e incentivo à participação</p>

            <h4>Projeto RENOVA (Outubro):</h4>
            <p>Revista com jornada de 30 dias para formação de hábitos saudáveis (físico, espiritual e emocional).</p>

            <h4>Dia da Mulher – 07/03/2026:</h4>
            <p>Recepção e homenagem especial na Igreja</p>

            <h4>PG FEMININO (Quinzenal):</h4>
            <p>Encontros com o material "Floresça", promovendo comunhão, discipulado, identidade em Cristo e crescimento espiritual.</p>

            <h4>ENTRE ELAS (Mensal):</h4>
            <p>Encontros para fortalecimento espiritual, emocional e relacional (Mensal, aos domingos).</p>

            <h5>Temas trabalhados:</h5>
            <ul>
                <li>Rede de apoio e discipulado</li>
                <li>Ansiedade e saúde mental</li>
                <li>Dupla jornada e exaustão</li>
                <li>Depressão e fé</li>
                <li>Educação de filhos</li>
                <li>Comunicação no casamento</li>
                <li>Intimidade com Deus</li>
                <li>Propósito e identidade</li>
                <li>Alimentação saudável</li>
                <li>Representação cristã</li>
            </ul>

            <h4>QUEBRANDO O SILÊNCIO – 22/08:</h4>
            <ul>
                <li>Sermão temático pela manhã</li>
                <li>Programação especial à tarde (a definir)</li>
            </ul>

            <h4>EXPERIÊNCIA DO SÁBADO:</h4>
            <p><strong>Lançamento:</strong> Chá Entre Amigas</p>
            <ul>
                <li>Folder orientativo</li>
                <li>Caixa quebra-gelo</li>
                <li>Livreto "Estarei pronta para o Sábado"</li>
                <li>Resgate da guarda do sábado como estilo de vida</li>
            </ul>

            <h4>PROJETO VIDA QUE NUTRE:</h4>
            <p>
                O Projeto Vida que Nutre, realizado em parceria com a ASA, propõe oficinas práticas sobre saúde e alimentação saudável, promovendo conhecimento acessível e hábitos que fortalecem o corpo e a vida espiritual. Com encontros semestrais na igreja, as oficinas oferecerão orientações simples e aplicáveis ao dia a dia, incentivando o cuidado integral da mulher e da família, além de fortalecer a comunhão e o serviço à comunidade.
            </p>

            <h3>MULHER PLENA COM A MISSÃO</h3>

            <h4>EVANGELISMO FEMININO – 03 a 07/06:</h4>
            <ul>
                <li>Semana especial em PG</li>
                <li>Envolvimento ativo das mulheres</li>
            </ul>

            <h4>SÁBADO MISSIONÁRIO – 06/06:</h4>
            <ul>
                <li>Sermão com ênfase missionária</li>
                <li>Chá evangelístico à tarde</li>
            </ul>

            <h4>REDE DE AMOR:</h4>
            <p>
                O trabalho de apoio às mulheres que necessitam e às que estão afastadas deve ser realizado com sensibilidade, intencionalidade e espírito de discipulado. Organizar uma equipe preparada para ouvir sem julgamentos, oferecer apoio emocional e espiritual, orar junto e manter acompanhamento constante, criando vínculos de confiança. É importante identificar previamente as necessidades, sejam elas emocionais, familiares ou espirituais e agir com discrição e amor cristão. Além das visitas presenciais, o contato pode continuar por mensagens, convites para pequenos grupos, chás evangelísticos proporcionando um caminho gradual de reconexão com a igreja e, principalmente, com Cristo.
            </p>

            <h4>CORAL FEMININO:</h4>
            <p>
                O Coral Feminino, em parceria com o Ministério da Mulher, é uma ferramenta poderosa de integração, evangelismo e fortalecimento espiritual. Essa união amplia as oportunidades de envolvimento, permitindo que mulheres de diferentes faixas etárias participem ativamente, desenvolvendo seus dons e criando laços de amizade e pertencimento. Ao trabalharem juntas, as duas frentes fortalecem a comunhão, promovem o discipulado e tornam as programações mais inclusivas e representativas, alcançando tanto as mulheres da igreja quanto a comunidade.
            </p>
        </div>

        <!-- Aba Calendário 2026 -->
        <div id="calendario" class="tab-content">
            <h2>CALENDÁRIO 2026</h2>

            <div class="calendario-grid">
                <div class="calendario-item">
                    <h4>Março</h4>
                    <div class="data">07 de março (sábado)</div>
                    <p>Homenagem às Mulheres (Dia Internacional da Mulher)</p>
                    <div class="data">26 de março (quinta)</div>
                    <p>PG Feminino – Elza</p>
                </div>

                <div class="calendario-item">
                    <h4>Abril</h4>
                    <div class="data">09 de abril (quinta)</div>
                    <p>PG Feminino – Lourdes</p>
                    <div class="data">23 de abril (quinta)</div>
                    <p>PG Feminino – Lu Mesquita</p>
                    <div class="data">26 de abril (domingo)</div>
                    <p>Entre ELAS</p>
                </div>

                <div class="calendario-item">
                    <h4>Maio</h4>
                    <div class="data">10 de maio (domingo)</div>
                    <p>Apoio à Homenagem Dia das Mães</p>
                    <div class="data">14 de maio (quinta)</div>
                    <p>PG Feminino – Ada (espaço reservado)</p>
                    <div class="data">24 de maio (domingo)</div>
                    <p>Entre ELAS</p>
                    <div class="data">28 de maio (quinta)</div>
                    <p>PG Feminino – Elisabete</p>
                    <div class="data">31 de maio (domingo)</div>
                    <p>Projeto Vida que Nutri</p>
                </div>

                <div class="calendario-item">
                    <h4>Junho</h4>
                    <div class="data">06 de junho (sábado)</div>
                    <p>Sábado Missionário da Mulher Adventista</p>
                    <div class="data">11 de junho (quinta)</div>
                    <p>PG Feminino – Adaiane</p>
                    <div class="data">21 de junho (domingo)</div>
                    <p>Entre ELAS</p>
                    <div class="data">25 de junho (quinta)</div>
                    <p>PG Feminino – Eliane</p>
                    <div class="data">27 de junho (sábado)</div>
                    <p>Junta Panelas (Almoço + Atividade Social)</p>
                </div>

                <div class="calendario-item">
                    <h4>Julho</h4>
                    <div class="data">09 de julho (quinta)</div>
                    <p>PG Feminino</p>
                    <div class="data">23 de julho (quinta)</div>
                    <p>PG Feminino</p>
                    <div class="data">29 de julho (quarta)</div>
                    <p>Quartas de Poder</p>
                </div>

                <div class="calendario-item">
                    <h4>Agosto</h4>
                    <div class="data">07 e 08 de agosto (sexta e sábado)</div>
                    <p>Retiro Espiritual para Mulheres</p>
                    <div class="data">09 de agosto (domingo)</div>
                    <p>Apoio à Homenagem Dia dos Pais</p>
                    <div class="data">13 de agosto (quinta)</div>
                    <p>PG Feminino</p>
                    <div class="data">16 de agosto (domingo)</div>
                    <p>Entre ELAS</p>
                    <div class="data">22 de agosto (sábado)</div>
                    <p>Quebrando o Silêncio</p>
                    <div class="data">26 de agosto (quarta)</div>
                    <p>Quartas de Poder</p>
                </div>

                <div class="calendario-item">
                    <h4>Setembro</h4>
                    <div class="data">03 de setembro (quinta)</div>
                    <p>PG Feminino</p>
                    <div class="data">20 de setembro (domingo)</div>
                    <p>Entre ELAS</p>
                    <div class="data">24 de setembro (quinta)</div>
                    <p>PG Feminino</p>
                    <div class="data">30 de setembro (quarta)</div>
                    <p>Quartas de Poder</p>
                </div>

                <div class="calendario-item">
                    <h4>Outubro</h4>
                    <div class="data">04 de outubro (domingo)</div>
                    <p>Projeto RENOVA</p>
                    <div class="data">08 de outubro (quinta)</div>
                    <p>PG Feminino</p>
                    <div class="data">18 de outubro (domingo)</div>
                    <p>Entre ELAS</p>
                    <div class="data">28 de outubro (quarta)</div>
                    <p>Quartas de Poder</p>
                </div>

                <div class="calendario-item">
                    <h4>Novembro</h4>
                    <div class="data">05 de novembro (quinta)</div>
                    <p>PG Feminino</p>
                    <div class="data">25 de novembro (quarta)</div>
                    <p>Quartas de Poder</p>
                    <div class="data">29 de novembro (domingo)</div>
                    <p>Piquenique (Atividade Social)</p>
                </div>
            </div>
        </div>

        <!-- Aba Pesquisa -->
        <div id="pesquisa" class="tab-content">
            <h2>Pesquisa de Perfil e Interesse</h2>
            <p>Conheça nossa pesquisa para entender melhor o perfil das mulheres da nossa igreja e suas áreas de interesse.</p>

            <div class="pesquisa-images">
                <div class="pesquisa-image-container">
                    <img src="{{ asset('img/cards/ministerio-mulher/pesquisa01.webp') }}" alt="Página 1 da Pesquisa" loading="lazy" decoding="async">
                    <p>Página 1 - Informações Pessoais</p>
                </div>

                <div class="pesquisa-image-container">
                    <img src="{{ asset('img/cards/ministerio-mulher/pesquisa02.webp') }}" alt="Página 2 da Pesquisa" loading="lazy" decoding="async">
                    <p>Página 2 - Áreas de Interesse</p>
                </div>

                <div class="pesquisa-image-container">
                    <img src="{{ asset('img/cards/ministerio-mulher/pesquisa03.webp') }}" alt="Página 3 da Pesquisa" loading="lazy" decoding="async">
                    <p>Página 3 - Disponibilidade e Talentos</p>
                </div>
            </div>

            <div style="background: #f8f9fa; padding: 30px; border-radius: 12px; margin-top: 30px; text-align: center;">
                <h3 style="font-family: 'Bebas neue', sans-serif; font-size: 1.8em; color: #d81b60; margin-bottom: 20px; font-weight: 500;">Participe da Pesquisa!</h3>
                <p style="text-align: center; margin-bottom: 20px;">
                    Sua participação é fundamental para planejarmos atividades que realmente atendam suas necessidades e interesses. A pesquisa leva apenas 5-10 minutos para ser preenchida.
                </p>
                <a href="#" style="display: inline-block; background: linear-gradient(135deg, #d81b60 0%, #880e4f 100%); color: #fff; padding: 15px 40px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 1.1em; transition: transform 0.3s, box-shadow 0.3s; font-family: 'Roboto', sans-serif;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(216, 27, 96, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">Preencher Pesquisa</a>
            </div>
        </div>

        <!-- Aba O Que Esperar -->
        <div id="esperar" class="tab-content">
            <h2>9. O que você espera do Ministério da Mulher?</h2>

            <div class="esperar-section">
                <h4>1. ACOLHIMENTO, UNIÃO E REDE DE APOIO</h4>
                <p><em>(Área mais recorrente na pesquisa)</em></p>
                <p><strong>Inclui:</strong></p>
                <ul>
                    <li>Apoio e união verdadeira</li>
                    <li>Mulheres sem rede de apoio</li>
                    <li>Integração e acolhimento</li>
                    <li>União entre mulheres</li>
                    <li>Mulheres acolhendo mulheres</li>
                    <li>Pequenos grupos itinerantes</li>
                    <li>Encontros para fortalecer laços</li>
                    <li>Eventos de confraternização</li>
                    <li>Ministério ativo e atencioso</li>
                    <li>Maior integração entre membros</li>
                </ul>
                <p><strong>Necessidade central identificada:</strong> Criar um ambiente seguro, relacional e de pertencimento.</p>
            </div>

            <div class="esperar-section">
                <h4>2. VIDA ESPIRITUAL E FORTALECIMENTO DA FÉ</h4>
                <p><strong>Inclui:</strong></p>
                <ul>
                    <li>Fortalecimento da fé</li>
                    <li>Encontros de oração</li>
                    <li>Programas permanentes de oração</li>
                    <li>Leitura bíblica em conjunto (online)</li>
                    <li>Devocionais</li>
                    <li>Participação nos cultos</li>
                    <li>Vigílias</li>
                    <li>Retiro espiritual</li>
                    <li>Comunhão e intimidade com Deus</li>
                    <li>Reavivamento espiritual</li>
                </ul>
                <p><strong>Necessidade central:</strong> Continuidade espiritual, não apenas eventos pontuais.</p>
            </div>

            <div class="esperar-section">
                <h4>3. FAMÍLIA, CASAMENTO E CRIAÇÃO DE FILHOS</h4>
                <p><strong>Inclui:</strong></p>
                <ul>
                    <li>Como ter um lar cristão</li>
                    <li>Educação de filhos</li>
                    <li>Criar filhos sozinha</li>
                    <li>Relação conjugal</li>
                    <li>Casamento</li>
                    <li>Apoio a mães sobrecarregadas</li>
                    <li>Vida no lar</li>
                    <li>Delegação e responsabilidade familiar</li>
                    <li>Apoiar neurodivergentes</li>
                </ul>
                <p><strong>Necessidade central:</strong> Suporte prático para desafios familiares reais.</p>
            </div>

            <div class="esperar-section">
                <h4>4. SAÚDE EMOCIONAL E SOBRECARGA FEMININA</h4>
                <p><strong>Inclui:</strong></p>
                <ul>
                    <li>Saúde emocional</li>
                    <li>Depressão</li>
                    <li>Ansiedade</li>
                    <li>Controle emocional</li>
                    <li>Dupla jornada</li>
                    <li>Exaustão feminina</li>
                    <li>Autocuidado</li>
                    <li>Palestras sobre saúde mental</li>
                    <li>Bem-estar da mulher</li>
                </ul>
                <p><strong>Necessidade central:</strong> A igreja como espaço de cuidado emocional.</p>
            </div>

            <div class="esperar-section">
                <h4>5. SAÚDE FÍSICA E ESTILO DE VIDA</h4>
                <p><strong>Inclui:</strong></p>
                <ul>
                    <li>Saúde natural</li>
                    <li>Alimentação saudável</li>
                    <li>Vida alimentar</li>
                    <li>Vida equilibrada</li>
                </ul>
                <p><strong>Necessidade central:</strong> Cuidado integral (corpo, mente e espírito).</p>
            </div>

            <div class="esperar-section">
                <h4>6. ENCONTROS, CHÁS E FORMATO DAS ATIVIDADES</h4>
                <p><strong>Inclui:</strong></p>
                <ul>
                    <li>Chá evangelístico</li>
                    <li>Piqueniques</li>
                    <li>Almoços</li>
                    <li>Atividades manuais</li>
                    <li>Oficinas práticas (cozinhar, fotografar, imagem pessoal)</li>
                    <li>PG Feminino</li>
                    <li>Congresso de mulheres</li>
                    <li>Coral feminino</li>
                </ul>
                <p><strong>Necessidade central:</strong> Formatos leves, frequentes e relacionais.</p>
            </div>

            <div class="esperar-section">
                <h4>7. MISSÃO E EVANGELISMO</h4>
                <p><strong>Inclui:</strong></p>
                <ul>
                    <li>Quebrando o Silêncio</li>
                    <li>Visitação</li>
                    <li>Trabalho com mulheres afastadas</li>
                    <li>Pequenos grupos</li>
                    <li>Engajamento com projetos da igreja</li>
                    <li>Ações sociais</li>
                    <li>Apresentar Deus a novas pessoas</li>
                </ul>
                <p><strong>Necessidade central:</strong> Ministério feminino intencional e missionário.</p>
            </div>

            <div class="esperar-section">
                <h4>8. IDENTIDADE, PROPÓSITO E DESENVOLVIMENTO PESSOAL</h4>
                <p><strong>Inclui:</strong></p>
                <ul>
                    <li>Encontrar o propósito</li>
                    <li>Atuação da mulher na igreja</li>
                    <li>Vida profissional</li>
                    <li>Empreendedorismo</li>
                    <li>Vida financeira</li>
                    <li>Etiqueta e imagem pessoal</li>
                    <li>Crescimento pessoal</li>
                </ul>
                <p><strong>Necessidade central:</strong> Formação da mulher em todas as áreas da vida.</p>
            </div>

            <div class="esperar-section">
                <h4>9. INCLUSÃO E ACESSIBILIDADE</h4>
                <p><strong>Inclui:</strong></p>
                <ul>
                    <li>Acessibilidade em Libras</li>
                    <li>Apoio a mulheres com filhos neurodivergentes</li>
                </ul>
                <p><strong>Necessidade central:</strong> Ministério inclusivo e sensível às diferenças.</p>
            </div>

            <blockquote class="acb-quote" style="max-width: 900px; margin: 40px auto 0;">
                <p>
                    "A mulher virtuosa é a coroa do seu marido, mas a que procede vergonhosamente é como cáncer nos seus ossos."
                </p>
                <span class="acb-quote__ref"><i class="bi bi-book-half"></i> Provérbios 12:4</span>
            </blockquote>
        </div>
    </div>

    <!-- Seção de Contato -->
    <div class="contato-section acb-fullbleed" style="background: linear-gradient(135deg, #d81b60 0%, #880e4f 100%); color: #fff;">
        <h3 style="font-family: 'Bebas neue', sans-serif; font-size: 2em; color: #fff; margin-bottom: 20px; font-weight: 500;">Contato - Ministério da Mulher</h3>
        <p style="font-family: 'Roboto', sans-serif; font-size: 1.1rem; color: #f8f9fa; margin-bottom: 10px;">
            <strong><i class="bi bi-envelope"></i> Email:</strong> <a href="mailto:mulherescentralbsb@gmail.com" style="color: #fff; text-decoration: none; font-weight: 600;">mulherescentralbsb@gmail.com</a>
        </p>
        <p style="font-family: 'Roboto', sans-serif; font-size: 1.1rem; color: #f8f9fa;">
            <strong><i class="bi bi-whatsapp"></i> WhatsApp:</strong> <a href="https://wa.me/5561999999999" style="color: #fff; text-decoration: none; font-weight: 600;">(61) 99999-9999</a>
        </p>
        <blockquote class="acb-quote" style="max-width: 900px; margin: 22px auto 0; border-left-color: #fff;">
            <p style="color: #f8f9fa;">
                "Enganosa é a graça, e vã é a formosura, mas a mulher que teme ao Senhor, essa será louvada."
            </p>
            <span class="acb-quote__ref" style="color: #fce4ec;"><i class="bi bi-book-half"></i> Provérbios 31:30</span>
        </blockquote>
    </div>

</div>
@endsection

@push('scripts')
<script>
function openTab(evt, tabName) {
    // Esconder todo conteúdo das abas
    const tabContents = document.getElementsByClassName('tab-content');
    for (let i = 0; i < tabContents.length; i++) {
        tabContents[i].classList.remove('active');
    }

    // Remover classe active de todos os botões
    const tabButtons = document.getElementsByClassName('tab-button');
    for (let i = 0; i < tabButtons.length; i++) {
        tabButtons[i].classList.remove('active');
    }

    // Mostrar a aba atual e adicionar classe active ao botão clicado
    document.getElementById(tabName).classList.add('active');
    evt.currentTarget.classList.add('active');
}

// Animação suave ao clicar nas abas
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Scroll suave para o conteúdo da aba
            setTimeout(function() {
                const tabsContainer = document.querySelector('.tabs-container');
                if (tabsContainer) {
                    tabsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        });
    });
});
</script>
@endpush