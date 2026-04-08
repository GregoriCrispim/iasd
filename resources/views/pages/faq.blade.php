@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Perguntas Frequentes')

@section('meta-description', 'Encontre respostas para as perguntas mais frequentes sobre a IASD Central de Brasília. Horários, programações, estudos bíblicos e mais.')
@section('og-title', 'Perguntas Frequentes - IASD Central de Brasília')
@section('og-description', 'Dúvidas sobre nossa igreja? Encontre respostas aqui!')
@section('page-name', 'FAQ')

@push('schema-faq')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Quais são os horários dos cultos?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Temos cultos aos sábados às 08h45 e 19h00. A Escola Sabatina acontece às 11h00."
      }
    },
    {
      "@type": "Question",
      "name": "Como fazer um estudo bíblico?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Você pode solicitar um estudo bíblico através do formulário em nossa página 'Estudo Bíblico'. Oferecemos estudos presenciais, online ou por telefone."
      }
    },
    {
      "@type": "Question",
      "name": "Onde fica a igreja?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Estamos localizados em Brasília, DF. Consulte o mapa em nossa página de contato para obter a localização exata."
      }
    },
    {
      "@type": "Question",
      "name": "Quais são as programações da igreja?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Temos diversas programações durante todo o ano, incluindo estudos bíblicos, classes, corais, eventos especiais e muito mais. Consulte nossa página de Programações 2026 para mais detalhes."
      }
    },
    {
      "@type": "Question",
      "name": "Como posso contribuir com dízimos e ofertas?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Você pode contribuir através de nossa página 'Dízimos e Ofertas', onde encontrará informações sobre como fazer sua contribuição."
      }
    },
    {
      "@type": "Question",
      "name": "A igreja oferece estudos para crianças e jovens?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Sim! Temos Aventureiros, Desbravadores, classes bíblicas para juvenis e jovens, e diversas atividades específicas para cada faixa etária."
      }
    },
    {
      "@type": "Question",
      "name": "O que a IASD Central de Brasília oferece?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Oferecemos estudos bíblicos, escola sabatina, corais e orquestras, ministérios para todas as idades, eventos especiais, ações comunitárias através da ASA, e muito mais."
      }
    },
    {
      "@type": "Question",
      "name": "Como participar da IASD Central de Brasília?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Você pode participar dos nossos cultos aos sábados, inscrever-se em estudos bíblicos, juntar-se a um ministério, ou participar de nossos eventos e programações especiais."
      }
    }
  ]
}
</script>
@endpush

@push('styles')
<style>
html { scroll-behavior: smooth; }

.faq-container {
    width: 100%;
    background: linear-gradient(135deg, #003366 0%, #004080 50%, #003366 100%);
    padding: 80px 20px;
    text-align: center;
    color: white;
    margin-bottom: 0;
}

.faq-banner h1 {
    font-family: 'Bebas neue', sans-serif;
    font-size: 3.5em;
    color: white;
    margin-bottom: 15px;
    font-weight: 500;
}

.faq-banner p {
    font-family: 'Roboto', sans-serif;
    font-size: 1.3em;
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
}

.faq-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}
    font-family: 'Roboto', sans-serif;
    font-size: 1.1rem;
    color: #666;
}

.faq-tabs {
    display: flex;
    gap: 15px;
    margin-top: 50px;
    margin-bottom: 40px;
    border-bottom: 3px solid #e0e0e0;
    padding-bottom: 20px;
    justify-content: center;
}

.faq-tab-button {
    flex: 1;
    padding: 15px 25px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-family: 'Roboto', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.faq-tab-button:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    border-color: rgba(211, 84, 0, 0.4);
}

.faq-tab-button.active {
    background: linear-gradient(135deg, #003366 0%, #004080 100%);
    color: white;
    border-color: #003366;
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
}

.faq-tab-content {
    display: none;
    animation: fadeIn 0.3s ease-in-out;
}

.faq-tab-content.active {
    display: block;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.faq-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.faq-item {
    background: #fff;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 25px;
    transition: all 0.3s ease;
}

.faq-item:hover {
    border-color: rgba(211, 84, 0, 0.4);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.faq-item h3 {
    font-family: 'Roboto', sans-serif;
    font-size: 1.2em;
    color: #003366;
    margin-bottom: 12px;
    font-weight: 600;
}

.faq-item p {
    font-family: 'Roboto', sans-serif;
    font-size: 1rem;
    color: #666;
    line-height: 1.7;
    margin: 0;
}

.faq-contact {
    margin-top: 50px;
    text-align: center;
    padding: 30px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    border-top: 3px solid rgba(211, 84, 0, 0.4);
}

.faq-contact h3 {
    font-family: 'Bebas neue', sans-serif;
    font-size: 1.8em;
    color: #003366;
    margin-bottom: 15px;
}

.faq-contact p {
    font-family: 'Roboto', sans-serif;
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 10px;
}

.faq-contact a {
    color: #d35400;
    text-decoration: none;
    font-weight: 600;
}

.faq-contact a:hover {
    text-decoration: underline;
}

.sub-tabs {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 8px;
    margin-top: 30px;
    margin-bottom: 30px;
}

.sub-tab-button {
    padding: 12px 10px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-family: 'Roboto', sans-serif;
    font-size: 0.75rem;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    white-space: normal;
    line-height: 1.3;
    min-height: 65px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sub-tab-button:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    border-color: rgba(211, 84, 0, 0.4);
}

.sub-tab-button.active {
    background: linear-gradient(135deg, #003366 0%, #004080 100%);
    color: white;
    border-color: #003366;
    box-shadow: 0 3px 8px rgba(0, 51, 102, 0.3);
}

.sub-tab-content {
    display: none;
    animation: fadeIn 0.3s ease-in-out;
}

.sub-tab-content.active {
    display: block;
}

.sub-section-title {
    font-family: 'Bebas neue', sans-serif;
    font-size: 1.5em;
    color: #003366;
    margin-bottom: 25px;
    padding-bottom: 10px;
    border-bottom: 2px solid rgba(211, 84, 0, 0.4);
}

.doctrine-item {
    background: #fff;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.doctrine-item:hover {
    border-color: rgba(211, 84, 0, 0.4);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.doctrine-item h4 {
    font-family: 'Roboto', sans-serif;
    font-size: 1.1em;
    color: #003366;
    margin-bottom: 12px;
    font-weight: 600;
    line-height: 1.4;
}

.doctrine-item p {
    font-family: 'Roboto', sans-serif;
    font-size: 0.95rem;
    color: #666;
    line-height: 1.7;
    margin: 0;
}

.doctrine-item p strong {
    color: #003366;
    font-weight: 700;
}

@media (max-width: 768px) {
    .faq-container {
        padding: 20px 15px;
    }

    .faq-header h1 {
        font-size: 2.2em;
    }

    .faq-tabs {
        flex-direction: column;
        gap: 10px;
    }

    .faq-tab-button {
        padding: 12px 20px;
        font-size: 0.95rem;
    }

    .sub-tabs {
        grid-template-columns: 1fr;
        gap: 6px;
    }

    .sub-tab-button {
        padding: 10px 8px;
        font-size: 0.7rem;
        white-space: normal;
        text-align: left;
        min-height: auto;
        line-height: 1.2;
    }

    .sub-section-title {
        font-size: 1.3em;
    }

    .doctrine-item {
        padding: 15px;
    }

    .doctrine-item h4 {
        font-size: 1em;
    }

    .doctrine-item p {
        font-size: 0.9rem;
    }

    .faq-item {
        padding: 20px;
    }
}

/* Seção de Estudo Bíblico */
.faq-estudo-biblico {
    margin-top: 40px;
    text-align: center;
    padding: 30px;
    background: linear-gradient(135deg, #003366 0%, #001531 100%);
    border-radius: 12px;
    border-top: 3px solid rgba(211, 84, 0, 0.4);
    color: #fff;
}

.faq-estudo-biblico h3 {
    font-family: 'Bebas neue', sans-serif;
    font-size: 1.8em;
    color: #fff;
    margin-bottom: 15px;
}

.faq-estudo-biblico p {
    font-family: 'Roboto', sans-serif;
    font-size: 1.1rem;
    color: #f8f9fa;
    margin-bottom: 15px;
}

.faq-estudo-biblico strong {
    color: #f39c12;
    font-weight: 600;
}

.btn-estudo-biblico {
    background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%);
    color: white;
    text-decoration: none;
    padding: 12px 30px;
    border-radius: 8px;
    display: inline-block;
    transition: transform 0.3s, box-shadow 0.3s;
    font-weight: 600;
}

.btn-estudo-biblico:hover {
    background: linear-gradient(135deg, #ba4a00 0%, #9e4100 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(211, 84, 0, 0.4);
    text-decoration: none;
}
</style>
@endpush

@section('content')
<div class="faq-banner">
    <h1>Perguntas Frequentes</h1>
    <p>Encontre respostas para as dúvidas mais comuns sobre nossa igreja e doutrina</p>
</div>

<div class="evidencias-container">

    <div class="faq-tabs">
        <button class="faq-tab-button active" data-tab="igreja">IASD Central de Brasília</button>
        <button class="faq-tab-button" data-tab="doutrina">Questões sobre Doutrina</button>
    </div>

    <div id="igreja" class="faq-tab-content active">
        <div class="faq-list">
            <div class="faq-item">
                <h3>Quais são os horários dos cultos?</h3>
                <p>Temos cultos aos sábados às 08h45 e 18h00. A Escola Sabatina acontece às 11h00. Os cultos também acontecem aos Domingos e Quartas às 19h00. Você é muito bem-vindo(a) em todos os nossos encontros!</p>
            </div>

            <div class="faq-item">
                <h3>Como fazer um estudo bíblico?</h3>
                <p>Você pode solicitar um estudo bíblico através do formulário em nossa página <a href="{{ route('estudo-biblico') }}">Estudo Bíblico</a>. Oferecemos estudos presenciais (na sua residência ou na igreja), online por videoconferência, ou por telefone/mensagem. Escolha a forma que preferir!</p>
            </div>

            <div class="faq-item">
                <h3>Onde fica a igreja?</h3>
                <p>Estamos localizados em Brasília, DF. Consulte o mapa no rodapé de nosso site para obter a localização exata. Se precisar de instruções detalhadas, entre em contato conosco.</p>
            </div>

            <div class="faq-item">
                <h3>Quais são as programações da igreja?</h3>
                <p>Temos diversas programações durante todo o ano, incluindo estudos bíblicos, classes, corais, eventos especiais e muito mais. Consulte nossa página <a href="{{ route('programacoes') }}">Programações 2026</a> para ver todos os eventos e atividades planejados.</p>
            </div>

            <div class="faq-item">
                <h3>Como posso contribuir com dízimos e ofertas?</h3>
                <p>Você pode contribuir através de nossa página <a href="{{ route('dizimos-ofertas') }}">Dízimos e Ofertas</a>, onde encontrará informações sobre como fazer sua contribuição de forma segura e prática.</p>
            </div>

            <div class="faq-item">
                <h3>A igreja oferece estudos para crianças e jovens?</h3>
                <p>Sim! Temos o Clube dos Aventureiros (crianças de 4-9 anos), o Clube de Desbravadores (adolescentes de 10-15 anos), classes bíblicas para juvenis e jovens, e diversas atividades específicas para cada faixa etária. Nossos ministérios infantis e jovens são especialmente preparados para atender cada etapa do desenvolvimento.</p>
            </div>

            <div class="faq-item">
                <h3>O que a IASD Central de Brasília oferece?</h3>
                <p>Oferecemos estudos bíblicos gratuitos, escola sabatina para todas as idades, corais e orquestras, ministérios para todas as faixas etárias (crianças, adolescentes, jovens, adultos e idosos), eventos especiais ao longo do ano, ações comunitárias através da <a href="{{ route('asa') }}">ASA (Ação Solidária Adventista)</a>, suporte espiritual através do <a href="{{ route('oracao-visita') }}">Ministério de Oração</a>, e muito mais.</p>
            </div>

            <div class="faq-item">
                <h3>Como participar da IASD Central de Brasília?</h3>
                <p>Você pode participar dos nossos cultos aos sábados, inscrever-se gratuitamente em estudos bíblicos, juntar-se a um dos nossos ministérios, participar de nossos eventos e programações especiais, ou simplesmente visitar-nos para conhecer nossa comunidade. Todos são bem-vindos!</p>
            </div>
        </div>
    </div>

    <div id="doutrina" class="faq-tab-content">
        <div class="sub-tabs">
            <button class="sub-tab-button active" data-subtab="sabado-lei">1. SÁBADO E LEI DE DEUS</button>
            <button class="sub-tab-button" data-subtab="ellen-white">2. ELLEN G. WHITE, BÍBLIA E DOM DE PROFECIA</button>
            <button class="sub-tab-button" data-subtab="juizo-investigativo">3. JUÍZO INVESTIGATIVO, SANTUÁRIO CELESTIAL E 1844</button>
            <button class="sub-tab-button" data-subtab="estado-mortos">4. ESTADO DOS MORTOS, ALMA E VIDA APÓS A MORTE</button>
            <button class="sub-tab-button" data-subtab="inferno-destruicao">5. INFERNO E DESTRUIÇÃO FINAL DOS ÍMPIOS</button>
            <button class="sub-tab-button" data-subtab="salvacao-graca">6. SALVAÇÃO, GRAÇA, LEI E PERFEIÇÃO CRISTÃ</button>
            <button class="sub-tab-button" data-subtab="escatologia">7. ESCATOLOGIA, MARCA DA BESTA E EVENTOS FINAIS</button>
            <button class="sub-tab-button" data-subtab="igreja-remanescente">8. IGREJA REMANESCENTE, IDENTIDADE E RELAÇÃO COM OUTRAS IGREJAS</button>
            <button class="sub-tab-button" data-subtab="trindade-deus">9. TRINDADE, DEUS E CRISTOLOGIA</button>
            <button class="sub-tab-button" data-subtab="saude-alimentacao">10. SAÚDE, ALIMENTAÇÃO E ESTILO DE VIDA</button>
            <button class="sub-tab-button" data-subtab="dizimos-ofertas">11. DÍZIMOS, OFERTAS E FINANÇAS</button>
            <button class="sub-tab-button" data-subtab="batismo-ceia">12. BATISMO, CEIA E PRÁTICAS LITÚRGICAS</button>
            <button class="sub-tab-button" data-subtab="casamento-familia">13. CASAMENTO, FAMÍLIA E QUESTÕES ÉTICAS</button>
            <button class="sub-tab-button" data-subtab="criacao-evolucao">14. CRIAÇÃO, EVOLUÇÃO E ORIGENS</button>
            <button class="sub-tab-button" data-subtab="costumes-festas">15. COSTUMES, FESTAS E PRÁTICAS CULTURAIS</button>
            <button class="sub-tab-button" data-subtab="lideranca-feminina">16. LIDERANÇA FEMININA E ORDENAÇÃO DE MULHERES</button>
        </div>

        <div id="sabado-lei" class="sub-tab-content active">
            <h2 class="sub-section-title">1. SÁBADO E LEI DE DEUS</h2>

            <div class="doctrine-item">
                <h4>1.1 Por que os adventistas guardam o sábado em vez do domingo como a maioria dos cristãos?</h4>
                <p>Os adventistas guardam o sábado porque entendem que o sétimo dia foi estabelecido por Deus na criação como memorial universal, antes da existência do povo judeu, e foi depois incluído no coração da lei moral, os Dez Mandamentos. Em <a href="#" class="biblical-reference" data-reference="Gênesis 2:1-3">Gênesis 2:1-3</a>, Deus descansou no sétimo dia, o abençoou e o santificou. Em <a href="#" class="biblical-reference" data-reference="Êxodo 20:8-11">Êxodo 20:8-11</a>, o mandamento não institui algo novo, mas manda "lembrar" do sábado, mostrando continuidade com a criação. Para a compreensão adventista, não há no Novo Testamento qualquer texto que transfira a santidade do sétimo dia para o primeiro dia da semana.</p>
                <p>Além disso, Jesus guardou o sábado (<a href="#" class="biblical-reference" data-reference="Lucas 4:16">Lucas 4:16</a>), declarou-se "Senhor do sábado" (<a href="#" class="biblical-reference" data-reference="Marcos 2:27-28">Marcos 2:27-28</a>) e ensinou seu verdadeiro sentido contra distorções farisaicas, não contra o próprio mandamento. Os apóstolos também mantiveram a prática sabática em seu ministério (<a href="#" class="biblical-reference" data-reference="Atos 13:14">Atos 13:14</a>, Atos 13:42-44; <a href="#" class="biblical-reference" data-reference="Atos 16:13">16:13</a>; <a href="#" class="biblical-reference" data-reference="Atos 17:2">17:2</a>; <a href="#" class="biblical-reference" data-reference="Atos 18:4">18:4</a>). Por isso, os adventistas entendem que a observância do domingo se desenvolveu historicamente na igreja pós-apostólica, mas não por mandamento bíblico. A base da guarda do sábado, portanto, é a autoridade da Palavra de Deus, não o costume majoritário do cristianismo.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.2 Por que os adventistas guardam o sábado se o apóstolo Paulo ensina em <a href="#" class="biblical-reference" data-reference="Romanos 14:5">Romanos 14:5</a> que cada um deve estar plenamente convicto em sua própria mente sobre dias?</h4>
                <p>Os adventistas entendem que <a href="#" class="biblical-reference" data-reference="Romanos 14:5">Romanos 14:5</a> não está tratando do sábado do quarto mandamento, mas de dias de observância facultativa, provavelmente relacionados a jejuns, práticas devocionais pessoais ou questões ascéticas que causavam disputas na igreja de Roma. O contexto imediato do capítulo fala de quem come ou não come, de quem considera um dia mais especial e de quem o considera igual, sempre no campo de convicções pessoais, e não de mandamentos morais universais (<a href="#" class="biblical-reference" data-reference="Romanos 14:1-6">Romanos 14:1-6</a>).</p>
                <p>A interpretação adventista sustenta que Paulo não poderia, em um capítulo sobre disputas de consciência, revogar um mandamento dado pelo próprio Deus em sua lei moral. O próprio Paulo afirma que a lei é "santa, justa e boa" (<a href="#" class="biblical-reference" data-reference="Romanos 7:12">Romanos 7:12</a>) e que a fé não anula a lei, mas a confirma (<a href="#" class="biblical-reference" data-reference="Romanos 3:31">Romanos 3:31</a>). Assim, Romanos 14 é visto como texto sobre práticas não mandatórias, não sobre o sábado bíblico instituído na criação e reafirmado no Decálogo.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.3 O sábado foi instituído para toda a humanidade na criação (Gênesis 2) ou apenas aos israelitas no Antigo Testamento no Monte Sinai depois da saída do Egito (<a href="#" class="biblical-reference" data-reference="Êxodo 20:1-6">Êxodo 20:2</a>)?</h4>
                <p>A posição adventista é que o sábado foi instituído para toda a humanidade na criação e não apenas para Israel no Sinai. Em <a href="#" class="biblical-reference" data-reference="Gênesis 2:1-3">Gênesis 2:1-3</a>, antes de haver judeus, hebreus ou o sistema mosaico, Deus separou o sétimo dia como santo. Isso significa que sua origem é edenica, criacional e universal. Quando o sábado aparece em <a href="#" class="biblical-reference" data-reference="Êxodo 20:8-11">Êxodo 20:8-11</a>, ele é apresentado como memorial da criação: "porque em seis dias fez o Senhor os céus e a terra... e ao sétimo dia descansou". A própria justificativa do mandamento remete à criação, não apenas ao êxodo.</p>
                <p>É verdade que em <a href="#" class="biblical-reference" data-reference="Deuteronômio 5:15">Deuteronômio 5:15</a> aparece também a lembrança da libertação do Egito como motivo adicional para Israel guardar o sábado. Os adventistas entendem isso não como origem do sábado, mas como uma camada redentiva adicional aplicada à experiência do povo. O sábado, portanto, possui dimensão criacional para toda a humanidade e dimensão pactual para o povo de Deus. Por isso, a igreja adventista vê o sábado como parte da vontade divina para todos os seres humanos, não como mera marca étnica judaica.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.4 O Novo Testamento não ensina que a guarda de dias, incluindo o sábado, foi abolida ou relativizada (<a href="#" class="biblical-reference" data-reference="Colossenses 2:16-17">Colossenses 2:16-17</a>, Gálatas 4) não sendo mais obrigatória para cristãos?</h4>
                <p>A interpretação adventista distingue cuidadosamente entre o sábado semanal do quarto mandamento e os sábados cerimoniais ligados ao calendário ritual judaico. Em <a href="#" class="biblical-reference" data-reference="Colossenses 2:16-17">Colossenses 2:16-17</a>, Paulo menciona comida, bebida, festas, lua nova e sábados, uma fórmula que ecoa o calendário cerimonial anual, mensal e festivo do Antigo Testamento (<a href="#" class="biblical-reference" data-reference="1 Crônicas 23:31">1 Crônicas 23:31</a>; <a href="#" class="biblical-reference" data-reference="2 Crônicas 2:4">2 Crônicas 2:4</a>; <a href="#" class="biblical-reference" data-reference="2 Crônicas 31:3">31:3</a>; <a href="#" class="biblical-reference" data-reference="Ezequiel 45:17">Ezequiel 45:17</a>). Esses elementos eram "sombras das coisas futuras", apontando para Cristo. Os adventistas entendem que Paulo está falando do sistema cerimonial, não abolindo o sábado da criação inscrito nos Dez Mandamentos.</p>
                <p>Quanto a <a href="#" class="biblical-reference" data-reference="Gálatas 4:9-11">Gálatas 4:9-11</a>, Paulo repreende a volta a observâncias legalistas ligadas a um sistema de justificação por obras e ao retorno a "rudimentos fracos e pobres". O foco, na leitura adventista, é a dependência de ordenanças como meio de aceitação diante de Deus, e não a obediência motivada pela graça aos mandamentos morais. Assim, o Novo Testamento relativiza dias cerimoniais e usos legalistas, mas não o mandamento moral do sábado. A mesma Bíblia que ensina liberdade em Cristo também afirma a permanência da lei moral (<a href="#" class="biblical-reference" data-reference="Romanos 3:31">Romanos 3:31</a>; Tiago 2:10-12).</p>
            </div>

            <div class="doctrine-item">
                <h4>1.5 O fato de Jesus ter ressuscitado no domingo tornou esse dia o novo dia de adoração cristã?</h4>
                <p>Os adventistas creem que a ressurreição de Jesus no primeiro dia da semana é um fato central da fé cristã, mas não alterou o mandamento do sábado. O Novo Testamento honra a ressurreição, porém não registra nenhuma ordem de Cristo ou dos apóstolos santificando o domingo em substituição ao sétimo dia. O memorial estabelecido por Cristo para sua morte e ressurreição não foi a mudança do dia de adoração, mas o batismo (<a href="#" class="biblical-reference" data-reference="Romanos 6">Romanos 6</a>:3-6; <a href="#" class="biblical-reference" data-reference="Colossenses 2:12">Colossenses 2:12</a>).</p>
                <p>A posição adventista destaca que, se a ressurreição de Cristo tivesse transferido a santidade para o primeiro dia, seria razoável esperar uma instrução clara nas Escrituras. No entanto, isso não ocorre. Jesus morreu na sexta, repousou no sábado e ressuscitou no domingo, mas o texto bíblico não atribui santidade sabática ao primeiro dia. Assim, o domingo pode ser reconhecido como dia importante na história da redenção, mas não como substituto do sábado bíblico.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.6 As reuniões dos primeiros cristãos no primeiro dia da semana (<a href="#" class="biblical-reference" data-reference="Atos 20:7">Atos 20:7</a> e <a href="#" class="biblical-reference" data-reference="1 Coríntios 16:2">1 Coríntios 16:2</a>) não indicam a substituição do sábado pelo domingo?</h4>
                <p>Os adventistas entendem que esses textos não estabelecem a santificação do domingo. Em <a href="#" class="biblical-reference" data-reference="Atos 20:7">Atos 20:7</a>, a reunião em Trôade ocorreu numa ocasião específica, à noite, com Paulo prestes a viajar no dia seguinte. Como o dia bíblico começa ao pôr do sol, essa reunião pode ter ocorrido no que hoje chamaríamos de sábado à noite. O texto descreve um encontro extraordinário, não um mandamento universal para mudar o dia de adoração.</p>
                <p>Em <a href="#" class="biblical-reference" data-reference="1 Coríntios 16:2">1 Coríntios 16:2</a>, Paulo pede que cada um, no primeiro dia da semana, separe em casa uma oferta para a coleta dos santos. O texto não fala de culto público nem de santificação do dia, mas de organização prática e pessoal. Para a leitura adventista, nenhum desses textos possui força para revogar o quarto mandamento. Eles mostram reuniões ou instruções ocasionais, não a instituição de um novo sábado cristão.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.7 Como os adventistas explicam a distinção entre lei moral (Dez Mandamentos) e lei cerimonial (leis de Moisés)?</h4>
                <p>A distinção adventista entre lei moral e lei cerimonial se baseia em características bíblicas diferentes entre os dois conjuntos. A lei moral, resumida nos Dez Mandamentos, foi escrita pelo dedo de Deus em tábuas de pedra (<a href="#" class="biblical-reference" data-reference="Êxodo 31:18">Êxodo 31:18</a>; Deuteronômio 10:1-5), colocada dentro da arca (<a href="#" class="biblical-reference" data-reference="Deuteronômio 10:5">Deuteronômio 10:5</a>) e reflete princípios permanentes do caráter divino: amor a Deus e ao próximo (<a href="#" class="biblical-reference" data-reference="Mateus 22:37-40">Mateus 22:37-40</a>; <a href="#" class="biblical-reference" data-reference="Romanos 13:8-10">Romanos 13:8-10</a>).</p>
                <p>Já a lei cerimonial foi escrita por Moisés em um livro (<a href="#" class="biblical-reference" data-reference="Deuteronômio 31:24-26">Deuteronômio 31:24-26</a>), colocada ao lado da arca e incluía sacrifícios, festas anuais, alimentos rituais, purificações e ordenanças que apontavam tipologicamente para Cristo (<a href="#" class="biblical-reference" data-reference="Hebreus 9:9-10">Hebreus 9:9-10</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 10:1">10:1</a>). Essas sombras encontraram cumprimento na obra redentora de Jesus. Por isso, os adventistas sustentam que a cruz encerrou a validade do sistema cerimonial como obrigação religiosa, mas não aboliu a lei moral eterna.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.8 Guardar o sábado significa legalismo ou tentativa de salvação pelas obras da lei?</h4>
                <p>Para os adventistas, guardar o sábado não é legalismo quando feito como resposta de amor e fidelidade ao Deus que salva pela graça. O legalismo acontece quando a pessoa tenta merecer a salvação por meio da obediência. A fé adventista rejeita isso claramente. A salvação é inteiramente pela graça, mediante a fé, por causa dos méritos de Cristo (<a href="#" class="biblical-reference" data-reference="Efésios 2:8-9">Efésios 2:8-9</a>). No entanto, a mesma passagem mostra que os salvos foram criados em Cristo para boas obras (<a href="#" class="biblical-reference" data-reference="Efésios 2:10">Efésios 2:10</a>).</p>
                <p>Nesse sentido, a obediência, inclusive a guarda do sábado, é vista como fruto da salvação, não como sua causa. Jesus disse: "Se me amais, guardareis os meus mandamentos" (<a href="#" class="biblical-reference" data-reference="João 14:15">João 14:15</a>). O sábado é guardado não para comprar o favor divino, mas porque o crente já foi alcançado por esse favor. Assim, na compreensão adventista, o sábado se torna sinal de relacionamento, lealdade e descanso em Deus, e não instrumento de justificação pelas obras.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.9 Se o cristão está "morto para a lei" segundo <a href="#" class="biblical-reference" data-reference="Romanos 7:4">Romanos 7:4</a>, por que os adventistas ainda defendem a obrigatoriedade dos Dez Mandamentos?</h4>
                <p>Em <a href="#" class="biblical-reference" data-reference="Romanos 7:4">Romanos 7:4</a>, Paulo ensina que o crente morreu para a lei no sentido de não estar mais sob sua condenação nem sob a tentativa de obter justificação por ela. A metáfora do casamento mostra mudança de relacionamento: o crente agora pertence a Cristo ressuscitado. Os adventistas entendem que isso não significa que a lei moral perdeu validade, mas que mudou a condição do crente diante dela. Antes, a lei condenava; agora, em Cristo, a pessoa é perdoada e capacitada a obedecer.</p>
                <p>O próprio Paulo evita qualquer conclusão antinomista. Ele pergunta: "Anulamos, pois, a lei pela fé? Não, de maneira nenhuma; antes, confirmamos a lei" (<a href="#" class="biblical-reference" data-reference="Romanos 3:31">Romanos 3:31</a>). Também afirma: "a lei é santa" (<a href="#" class="biblical-reference" data-reference="Romanos 7:12">Romanos 7:12</a>). Portanto, "morrer para a lei" significa morrer para sua função condenatória como caminho de justiça, não abolir os mandamentos como padrão moral da vida cristã.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.10 Se os adventistas guardam o sábado, por que não observam outras leis do mesmo conjunto, como circuncisão, sábados ceremoniais, ano sabático, jubileu, etc?</h4>
                <p>A resposta adventista é que essas práticas pertencem a categorias diferentes. O sábado semanal faz parte do Decálogo, a lei moral universal. Já circuncisão, sábados ceremoniais, festas anuais, ano sabático e jubileu pertencem ao sistema civil e cerimonial de Israel, ligado à teocracia, ao templo e ao simbolismo redentivo que apontava para Cristo. A circuncisão, por exemplo, é tratada no Novo Testamento como não obrigatória aos cristãos (<a href="#" class="biblical-reference" data-reference="Atos 15">Atos 15</a>:1-29; Gálatas 5:2-6).</p>
                <p>Os sábados ceremoniais estavam associados às festas anuais e possuíam função tipológica (Levítico 23). O ano sabático e o jubileu integravam a estrutura socioeconômica da nação israelita na terra prometida (Levítico 25). Os adventistas, portanto, não veem incoerência em manter o sábado moral e não praticar elementos que pertenciam ao sistema cerimonial ou civil de Israel. O critério é bíblico: o que é permanente no caráter moral de Deus continua; o que era sombra ou legislação nacional teve cumprimento ou cessação em sua forma obrigatória.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.11 Se o Concílio de Jerusalém em <a href="#" class="biblical-reference" data-reference="Atos 15">Atos 15</a> não mencionou a obrigatoriedade do sábado aos gentios, por que os adventistas o consideram obrigatório?</h4>
                <p>Os adventistas entendem que <a href="#" class="biblical-reference" data-reference="Atos 15">Atos 15</a> tratou de uma controvérsia específica: se os gentios precisavam ser circuncidados e guardar a lei cerimonial de Moisés para serem salvos (<a href="#" class="biblical-reference" data-reference="Atos 15:1">Atos 15:1</a>, <a href="#" class="biblical-reference" data-reference="Atos 15:5">Atos 15:5</a>). O concílio não tinha como objetivo listar toda a ética cristã, mas resolver uma questão imediata de acesso à salvação e comunhão entre judeus e gentios. Por isso, a ausência de menção explícita ao sábado não significa sua revogação.</p>
                <p>Além disso, em <a href="#" class="biblical-reference" data-reference="Atos 15:21">Atos 15:21</a>, Tiago afirma que Moisés era lido nas sinagogas todos os sábados, sugerindo que a instrução dos gentios continuaria. A leitura adventista é que o concílio não aboliu princípios já aceitos da moral bíblica; apenas não os transformou em objeto de debate naquele momento. Do mesmo modo, o concílio também não repetiu proibições ao homicídio, adultério ou idolatria em toda sua extensão, e nem por isso esses mandamentos deixaram de valer.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.12 A lei dos dez mandamentos, gravada em pedra, não é chamada de "ministério de morte" em <a href="#" class="biblical-reference" data-reference="2 Coríntios 3:7">2 Coríntios 3:7</a>? Como os adventistas explicam isso?</h4>
                <p>Os adventistas explicam que em <a href="#" class="biblical-reference" data-reference="2 Coríntios 3:7">2 Coríntios 3:7</a> Paulo não está dizendo que a lei moral é má ou abolida, mas que ela se torna "ministério de morte" para o pecador porque revela o pecado e, sem Cristo, só pode condenar. A lei mostra a justiça de Deus e a culpa humana. Nesse sentido, sua função condenatória é real. Mas isso não diminui sua santidade; ao contrário, mostra a gravidade do pecado diante de um padrão santo.</p>
                <p>O contraste de Paulo é entre a glória do antigo ministério, ligado à letra que condena o pecador, e a glória superior do ministério do Espírito, que escreve a lei no coração e traz vida em Cristo (<a href="#" class="biblical-reference" data-reference="2 Coríntios 3:3">2 Coríntios 3:3</a>, <a href="#" class="biblical-reference" data-reference="2 Coríntios 3:6">3:6</a>; <a href="#" class="biblical-reference" data-reference="Jeremias 31:33">Jeremias 31:33</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 8:10">Hebreus 8:10</a>). Assim, a lei não foi anulada; o que muda é o modo como o povo de Deus se relaciona com ela sob a nova aliança. Antes, condenação; agora, perdão e transformação.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.13 <a href="#" class="biblical-reference" data-reference="Êxodo 35:3">Êxodo 35:3</a> proíbe acender fogo no sábado. Como os adventistas justificam o uso de fogão, carro e eletricidade nesse dia?</h4>
                <p>A interpretação adventista leva em conta o contexto do texto. Em <a href="#" class="biblical-reference" data-reference="Êxodo 35:3">Êxodo 35:3</a>, a proibição de acender fogo aparece em ligação com a construção do tabernáculo e com a interrupção de trabalho comum no sábado. A compreensão adventista não trata esse verso como proibição absoluta de qualquer uso de calor ou energia em qualquer circunstância, mas como vedação ao trabalho servil e à atividade comum incompatível com a santidade do dia.</p>
                <p>Por isso, o princípio aplicado hoje não é um literalismo mecânico, mas o uso reverente do sábado segundo sua finalidade bíblica: cessar trabalho secular, dedicar-se à adoração, comunhão, misericórdia e descanso santo (<a href="#" class="biblical-reference" data-reference="Isaías 58:13-14">Isaías 58:13-14</a>; Mateus 12:1-12). Assim, usar fogão, eletricidade ou carro não é automaticamente quebra do sábado; tudo depende do propósito. Se o uso serve à adoração, necessidade, deslocamento para culto, socorro, visita missionária ou cuidado essencial, ele pode ser harmonizado com o espírito do mandamento. O foco adventista é a santificação do tempo, não um casuísmo técnico desconectado da intenção divina.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.14 Se a lei do sábado inclui pena de morte por violação (<a href="#" class="biblical-reference" data-reference="Êxodo 31:14-15">Êxodo 31:14-15</a>, <a href="#" class="biblical-reference" data-reference="Êxodo 35:2-3">Êxodo 35:2-3</a>), por que não seguem essa parte da lei?</h4>
                <p>Os adventistas entendem que a pena civil aplicada em Israel pertencia à estrutura teocrática do Antigo Testamento, na qual Deus governava uma nação específica com sanções civis e religiosas integradas. A igreja cristã, porém, não é um Estado teocrático. Portanto, não executa penalidades civis do antigo Israel. Isso vale não apenas para o sábado, mas para várias punições legais do código nacional israelita.</p>
                <p>A obrigação moral do mandamento permanece, mas a sanção civil vinculada à teocracia não. Esse princípio é coerente com o Novo Testamento, no qual a igreja disciplina espiritualmente, mas não impõe penas civis de morte por infrações religiosas. O sábado continua sendo santo; o modo de administração judicial próprio da antiga nação de Israel não foi transferido à igreja.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.15 O que "pode" e "não pode" fazer no sábado (trabalhar, estudar, dirigir, cozinhar, comprar, vender, internet, esportes, entretenimento)?</h4>
                <p>Na compreensão adventista, o sábado não deve ser reduzido a uma lista meramente mecânica de permissões e proibições, mas orientado por princípios bíblicos. O centro é separar o dia para Deus, cessar o trabalho secular e os interesses comuns, e dedicar tempo à adoração, ao descanso santo, à família, ao serviço e às obras de misericórdia (<a href="#" class="biblical-reference" data-reference="Êxodo 20:8-11">Êxodo 20:8-11</a>; <a href="#" class="biblical-reference" data-reference="Isaías 58:13-14">Isaías 58:13-14</a>; <a href="#" class="biblical-reference" data-reference="Marcos 2:27">Marcos 2:27</a>; <a href="#" class="biblical-reference" data-reference="Mateus 12:12">Mateus 12:12</a>).</p>
                <p>Em termos práticos:</p>
                <p><strong>Trabalhar:</strong> deve ser evitado quando se trata de atividade secular comum e remunerada, exceto em casos de necessidade, misericórdia ou dever essencial.</p>
                <p><strong>Estudar:</strong> estudos seculares, acadêmicos ou profissionais normalmente não harmonizam com o propósito do sábado; estudo bíblico e espiritual, sim.</p>
                <p><strong>Dirigir:</strong> pode ser apropriado quando ligado ao culto, serviço cristão, visitação, necessidade familiar ou socorro.</p>
                <p><strong>Cozinhar:</strong> idealmente se prepara antes, em espírito de planejamento sabático <a href="#" class="biblical-reference" data-reference="Êxodo 16:22-23">Êxodo 16:22-23</a>, mas aquecer ou finalizar alimentos simples não é visto da mesma forma que trabalho culinário pesado e rotineiro.</p>
                <p><strong>Comprar e vender:</strong> são geralmente evitados, com base em <a href="#" class="biblical-reference" data-reference="Neemias 13:15-22">Neemias 13:15-22</a>, porque mantêm o ciclo comercial do dia comum.</p>
                <p><strong>Internet:</strong> depende do uso. Pode ser apropriada para culto, estudo bíblico, evangelismo ou comunhão; usos banais, comerciais ou de entretenimento comum tendem a descaracterizar a santidade do dia.</p>
                <p><strong>Esportes:</strong> atividades competitivas, centradas em desempenho e recreação secular, em regra, não correspondem ao espírito sabático; caminhadas na natureza, contemplação e atividades familiares reverentes podem ser apropriadas.</p>
                <p><strong>Entretenimento:</strong> a pergunta básica é se aquilo conduz a mente a Deus ou a reconduz ao espírito comum da semana. O sábado deve ser diferente em foco, conteúdo e atmosfera.</p>
                <p>Os adventistas, portanto, aplicam princípios de santidade, deleite em Deus, afastamento do secular e prática da misericórdia, mais do que um código puramente externo.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.16 Quando o sábado começa e termina, e por que os adventistas o contam de pôr do sol a pôr do sol?</h4>
                <p>Os adventistas contam o sábado de pôr do sol a pôr do sol porque este é o padrão temporal bíblico. Em Gênesis 1, cada dia é marcado por "tarde e manhã". Em <a href="#" class="biblical-reference" data-reference="Levítico 23:32">Levítico 23:32</a>, aparece claramente a expressão "de uma tarde a outra tarde". Assim, o sétimo dia começa no entardecer da sexta-feira e termina no entardecer do sábado.</p>
                <p>Essa prática também está em harmonia com o modo como o povo de Deus marcava o tempo nas Escrituras. Para a fé adventista, a observância sabática não começa apenas com uma pausa formal, mas com uma preparação espiritual e prática antes da chegada do dia santo, para que o sábado seja recebido com reverência, alegria e liberdade das pressões comuns da semana.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.17 O que o adventista deve fazer quando o trabalho ou o emprego exige atividade aos sábados?</h4>
                <p>A posição adventista é que o crente deve buscar, com sabedoria, fidelidade e espírito pacífico, preservar a santidade do sábado mesmo diante de exigências profissionais. Isso inclui diálogo honesto com empregadores, busca de escalas alternativas, mudança de função quando possível e confiança na providência divina. A prioridade é obedecer a Deus acima de exigências humanas quando há conflito direto de consciência (<a href="#" class="biblical-reference" data-reference="Atos 5:29">Atos 5:29</a>).</p>
                <p>Ao mesmo tempo, a igreja reconhece que existem profissões ligadas a necessidade e misericórdia, como saúde, segurança e socorro, em que certas atividades sabáticas podem ser moralmente legítimas à luz do ensino de Cristo (<a href="#" class="biblical-reference" data-reference="Mateus 12:10-13">Mateus 12:10-13</a>; <a href="#" class="biblical-reference" data-reference="Lucas 13:15-16">Lucas 13:15-16</a>; <a href="#" class="biblical-reference" data-reference="Lucas 14:3-5">Lucas 14:3-5</a>). O ideal é que, mesmo nesses contextos, o sábado seja preservado do máximo possível de secularização. Quando o trabalho exigido é comum, comercial ou rotineiro, o adventista é chamado a buscar alternativa coerente com sua fé, ainda que isso envolva sacrifício.</p>
            </div>

            <div class="doctrine-item">
                <h4>1.18 Por que alguns acusam os adventistas de "judaizar" por guardarem o sábado?</h4>
                <p>Alguns acusam os adventistas de "judaizar" porque associam a guarda do sábado a práticas distintivamente judaicas do Antigo Testamento. No entanto, a resposta adventista é que guardar o sábado bíblico não é retorno ao judaísmo rabínico nem à lei cerimonial mosaica, mas fidelidade a um mandamento moral instituído na criação e reafirmado por Cristo e pelos apóstolos. O sábado antecede o Sinai e antecede o próprio Israel (<a href="#" class="biblical-reference" data-reference="Gênesis 2:1-3">Gênesis 2:1-3</a>).</p>
                <p>Além disso, judaizar no sentido neotestamentário implica impor elementos do sistema judaico como condição de salvação, especialmente circuncisão e dependência da lei cerimonial (<a href="#" class="biblical-reference" data-reference="Gálatas 5:1-4">Gálatas 5:1-4</a>; <a href="#" class="biblical-reference" data-reference="Atos 15">Atos 15</a>:1-11). Os adventistas rejeitam isso. Guardam o sábado não como meio de justificação, mas como expressão de obediência da fé. Portanto, para a teologia adventista, chamar a guarda do sábado de "judaização" confunde o mandamento moral universal com o sistema cerimonial temporário.</p>
            </div>
        </div>

        <div id="ellen-white" class="sub-tab-content">
            <h2 class="sub-section-title">2. ELLEN G. WHITE, BÍBLIA E DOM DE PROFECIA</h2>

            <div class="doctrine-item">
                <h4>2.1 Qual é exatamente o papel de Ellen White na Igreja: profetiza, conselheira espiritual, ou autoridade doutrinária equivalente à Bíblia?</h4>
                <p>Na compreensão adventista, Ellen G. White exerceu o dom bíblico de profecia e, por isso, atuou como mensageira do Senhor, conselheira espiritual, orientadora da igreja e voz de exortação, correção e encorajamento. No entanto, ela não é colocada em igualdade de autoridade com a Bíblia. A Escritura permanece como norma suprema de fé e prática.</p>
                <p>Seu papel é entendido como subordinado e derivado. Ela não ocupa o lugar de uma segunda Bíblia, nem de um cânon adicional. Sua função é conduzir a igreja de volta à Palavra, aplicar princípios bíblicos, oferecer advertências espirituais e fortalecer a missão da igreja. A base para aceitar a continuidade do dom profético está em textos como <a href="#" class="biblical-reference" data-reference="Joel 2:28-29">Joel 2:28-29</a>, <a href="#" class="biblical-reference" data-reference="Atos 2:17-18">Atos 2:17-18</a>, <a href="#" class="biblical-reference" data-reference="1 Coríntios 12:28">1 Coríntios 12:28</a>, <a href="#" class="biblical-reference" data-reference="Efésios 4:11-13">Efésios 4:11-13</a>, <a href="#" class="biblical-reference" data-reference="Apocalipse 12:17">Apocalipse 12:17</a> e <a href="#" class="biblical-reference" data-reference="Apocalipse 19:10">19:10</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>2.2 Os escritos de Ellen G. White são inspirados no mesmo nível da Bíblia?</h4>
                <p>A resposta adventista exige precisão. Os adventistas afirmam que o dom profético é genuinamente dado pelo Espírito Santo e, nesse sentido, seus escritos são considerados inspirados. Mas isso não significa que estejam no mesmo nível funcional e normativo da Bíblia. A Bíblia é a revelação normativa, canônica e suprema para toda a igreja. Os escritos de Ellen White são autoridade subordinada, útil para edificação, correção e orientação, mas sempre testados pela Escritura.</p>
                <p>A diferença fundamental não é entre "inspirado" e "não inspirado", mas entre cânon normativo universal e manifestação posterior do dom profético sujeita à Palavra já revelada. A igreja adventista sustenta que Deus continua a conceder dons espirituais, inclusive o profético, sem com isso abrir novo cânon ou relativizar a suficiência da Bíblia <a href="#" class="biblical-reference" data-reference="1 Tessalonicenses 5:19-21">1 Tessalonicenses 5:19-21</a>; <a href="#" class="biblical-reference" data-reference="1 Coríntios 14:1">1 Coríntios 14:1</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>2.3 Como a IASD afirma seguir a Bíblia como autoridade suprema e, ao mesmo tempo, aceitar os escritos de Ellen White?</h4>
                <p>A IASD entende que não há contradição nisso porque a própria Bíblia ensina que a igreja pode receber dons espirituais, inclusive o profético, após o período apostólico. Aceitar um dom profético genuíno não equivale a substituir a Escritura, mas a reconhecer que o mesmo Espírito que inspirou a Bíblia continua operando na igreja. A regra é simples: a Bíblia julga todo dom; nenhum dom julga a Bíblia (<a href="#" class="biblical-reference" data-reference="Isaías 8:20">Isaías 8:20</a>; <a href="#" class="biblical-reference" data-reference="1 João 4:1">1 João 4:1</a>; <a href="#" class="biblical-reference" data-reference="1 Tessalonicenses 5:20-21">1 Tessalonicenses 5:20-21</a>).</p>
                <p>Assim, a Bíblia é a fonte doutrinária final, enquanto os escritos de Ellen White exercem papel ministerial e pastoral dentro da comunidade adventista. Eles orientam, advertem e aplicam a verdade bíblica, mas não estabelecem um padrão acima ou ao lado da Escritura. A supremacia da Bíblia é preservada porque toda doutrina deve poder ser demonstrada biblicamente.</p>
            </div>

            <div class="doctrine-item">
                <h4>2.4 Se a Bíblia adverte contra acrescentar à profecia em Apocalipse 22, como a igreja justifica uma profetisa moderna?</h4>
                <p>Os adventistas entendem que <a href="#" class="biblical-reference" data-reference="Apocalipse 22:18-19">Apocalipse 22:18-19</a> é, em primeiro lugar, uma advertência contra adulterar a mensagem do próprio livro do Apocalipse e, por extensão, contra corromper a revelação divina. Esse texto não ensina que Deus deixaria de conceder dons proféticos legítimos à igreja após o fechamento do cânon. Se fosse assim, o próprio ensino do Novo Testamento sobre profecia contínua até o fim teria de ser negado (<a href="#" class="biblical-reference" data-reference="Efésios 4:11-13">Efésios 4:11-13</a>; <a href="#" class="biblical-reference" data-reference="Joel 2:28-29">Joel 2:28-29</a>; <a href="#" class="biblical-reference" data-reference="Atos 2:17-18">Atos 2:17-18</a>).</p>
                <p>Portanto, a existência de uma profetisa moderna só seria ilegítima se ela contradissesse a Escritura, competisse com o cânon ou reivindicasse autoridade superior à Bíblia. A igreja adventista rejeita essas ideias. O ponto não é "nenhum profeta posterior pode existir", mas "nenhuma revelação posterior pode alterar ou invalidar a revelação bíblica já dada".</p>
            </div>

            <div class="doctrine-item">
                <h4>2.5 Ellen G. White cometeu plágio ou usou empréstimos literários indevidos em seus escritos? Como explicar as semelhanças extensas com outros autores sem citar as fontes? Como a igreja explica o uso de fontes não inspiradas nos escritos de Ellen White?</h4>
                <p>A explicação adventista é que Ellen White, como outros autores religiosos e inclusive escritores bíblicos em certos contextos, fez uso de materiais, linguagem e formulações já existentes para expressar verdades que cria terem sido confiadas por Deus. A inspiração, na compreensão adventista, é inspiração do pensamento, não ditado verbal mecânico. Isso significa que o profeta pode usar vocabulário, estrutura literária, secretários, documentos e fontes auxiliares sem que isso negue a realidade do dom profético.</p>
                <p>A igreja também argumenta que os padrões modernos de citação acadêmica não eram os mesmos do século XIX. Além disso, o uso de fontes não inspiradas não é, por si só, problema bíblico. Lucas investigou cuidadosamente fontes e testemunhos <a href="#" class="biblical-reference" data-reference="Lucas 1:1-4">Lucas 1:1-4</a>; Paulo cita autores não bíblicos em certos contextos (<a href="#" class="biblical-reference" data-reference="Atos 17:28">Atos 17:28</a>; <a href="#" class="biblical-reference" data-reference="Tito 1:12">Tito 1:12</a>). O ponto decisivo, para a avaliação adventista, não é se houve dependência literária em algum grau, mas se o ministério, os frutos, a mensagem central e a fidelidade bíblica de Ellen White confirmam a autenticidade de seu chamado.</p>
            </div>

            <div class="doctrine-item">
                <h4>2.6 Ellen G. White fez profecias que não se cumpriram (como previsões sobre o fim do mundo ainda no século XIX). Como a Igreja explica isso? Ela fez previsões ou afirmações que não se cumpriram?</h4>
                <p>A resposta adventista distingue entre expectativas humanas, condicionais históricas, aplicações pastorais e profecias propriamente ditas. A igreja sustenta que Ellen White não marcou data para a volta de Cristo após 1844 e explicitamente desaconselhou a fixação de datas. Quando certas declarações parecem sugerir iminência, os adventistas as entendem à luz do padrão bíblico de linguagem escatológica, que frequentemente apresenta a volta de Cristo como próxima para manter a igreja em vigilância <a href="#" class="biblical-reference" data-reference="Mateus 24:42-44">Mateus 24:42-44</a>; <a href="#" class="biblical-reference" data-reference="Tiago 5:8-9">Tiago 5:8-9</a>.</p>
                <p>Além disso, a Bíblia contém profecias condicionais, nas quais o desfecho histórico depende da resposta humana <a href="#" class="biblical-reference" data-reference="Jeremias 18:7-10">Jeremias 18:7-10</a>; <a href="#" class="biblical-reference" data-reference="Jonas 3:4-10">Jonas 3:4-10</a>. A análise adventista, portanto, procura separar leitura isolada de frases, interpretação fora de contexto e natureza real da declaração. A posição oficial é que Ellen White deve ser avaliada de forma integral, à luz de seus frutos, sua exaltação de Cristo, sua fidelidade às Escrituras e do conjunto de sua obra, não por leituras fragmentárias.</p>
            </div>

            <div class="doctrine-item">
                <h4>2.7 Como a igreja responde a alegadas contradições de Ellen White em temas de ciência, história e saúde que parecem contradizer os conhecimentos atuais?</h4>
                <p>A resposta adventista parte do princípio de que o dom profético não transforma o mensageiro em enciclopédia infalível em todas as áreas de linguagem técnica, científica ou histórica. A mensagem profética é dada em linguagem humana, situada no tempo, e pode usar expressões correntes da época sem que seu propósito espiritual seja invalidado. Isso também é observado na própria Bíblia, que fala fenomenologicamente do nascer do sol, por exemplo, sem compromisso com linguagem científica moderna.</p>
                <p>No campo da saúde, os adventistas argumentam que muitas orientações de Ellen White foram amplamente confirmadas em seu valor preventivo e integral: moderação, pureza, sobriedade, cuidado com o corpo, equilíbrio entre trabalho, alimentação, descanso e vida espiritual <a href="#" class="biblical-reference" data-reference="1 Coríntios 6:19-20">1 Coríntios 6:19-20</a>; <a href="#" class="biblical-reference" data-reference="1 Coríntios 10:31">10:31</a>; <a href="#" class="biblical-reference" data-reference="3 João 2">3 João 2</a>. Onde há debates interpretativos, a igreja tende a ler suas declarações em contexto histórico e temático, evitando absolutizações indevidas de frases isoladas.</p>
            </div>

            <div class="doctrine-item">
                <h4>2.8 Acreditar em Ellen G. White e em seus escritos é obrigatório para ser membro batizado da igreja adventista ou pertencer à "Igreja Remanescente" ou para ser salvo?</h4>
                <p>A salvação, para os adventistas, vem somente por Cristo, pela graça, mediante a fé, e não pela aceitação formal de Ellen White <a href="#" class="biblical-reference" data-reference="João 3:16">João 3:16</a>; <a href="#" class="biblical-reference" data-reference="Efésios 2:8-9">Efésios 2:8-9</a>; <a href="#" class="biblical-reference" data-reference="Atos 4:12">Atos 4:12</a>. Ninguém é salvo por crer em um mensageiro humano. No entanto, a igreja entende que o dom de profecia é uma característica bíblica do povo remanescente (<a href="#" class="biblical-reference" data-reference="Apocalipse 12:17">Apocalipse 12:17</a>; 19:10) e, por isso, reconhecer esse dom faz parte da identidade doutrinária adventista.</p>
                <p>Em termos eclesiásticos, a pessoa que se une à igreja é chamada a aceitar seus ensinos fundamentais, entre eles a manifestação do dom profético no ministério de Ellen White. Mas isso não significa colocá-la como condição meritória de salvação. A distinção é importante: uma coisa é o critério de salvação; outra é a coerência doutrinária e comunitária de quem decide fazer parte da IASD.</p>
            </div>

            <div class="doctrine-item">
                <h4>2.9 Quais são os critérios bíblicos para testar um profeta, e como Ellen White é avaliada à luz deles?</h4>
                <p>Os adventistas usam critérios bíblicos clássicos:</p>
                <p><strong>Concordância com a Palavra de Deus:</strong> <a href="#" class="biblical-reference" data-reference="Isaías 8:20">Isaías 8:20</a></p>
                <p><strong>Exaltação de Cristo encarnado:</strong> <a href="#" class="biblical-reference" data-reference="1 João 4:1-3">1 João 4:1-3</a></p>
                <p><strong>Frutos do ministério e da vida:</strong> <a href="#" class="biblical-reference" data-reference="Mateus 7:15-20">Mateus 7:15-20</a></p>
                <p><strong>Cumprimento de predições quando aplicável:</strong> <a href="#" class="biblical-reference" data-reference="Jeremias 28:9">Jeremias 28:9</a></p>
                <p><strong>Edificação da igreja:</strong> <a href="#" class="biblical-reference" data-reference="1 Coríntios 14:3">1 Coríntios 14:3</a></p>
                <p>À luz desses critérios, a IASD entende que Ellen White:</p>
                <p>exaltou constantemente a Bíblia e a centralidade de Cristo;</p>
                <p>promoveu reforma espiritual, missionária, educacional e de saúde;</p>
                <p>chamou a igreja à obediência, santidade e missão;</p>
                <p>manifestou frutos duradouros em âmbito mundial;</p>
                <p>não ensinou doutrinas contrárias às Escrituras.</p>
                <p>Por isso, a igreja conclui que seu ministério corresponde aos testes bíblicos de um dom profético genuíno.</p>
            </div>

            <div class="doctrine-item">
                <h4>2.10 Os adventistas realmente fundamentam suas doutrinas apenas na Bíblia, ou dependem de escritos de Ellen White para sustentar ensinos específicos?</h4>
                <p>A posição oficial adventista é que as doutrinas devem ser fundamentadas na Bíblia. Os escritos de Ellen White podem apoiar, esclarecer, encorajar e aplicar, mas não servem como base primária de prova doutrinária. A IASD insiste que crenças fundamentais devem ser demonstráveis pelas Escrituras.</p>
                <p>Na prática histórica, Ellen White exerceu influência forte na consolidação da identidade adventista, mas a autocompreensão oficial da igreja é que nenhuma doutrina deve depender exclusivamente dela. Seu papel é confirmatório, pastoral e orientador. Quando usada corretamente, ela aponta de volta à Bíblia. Assim, a dependência doutrinária final é da Escritura, não dos seus escritos.</p>
            </div>

            <div class="doctrine-item">
                <h4>2.11 O que significa "Espírito de Profecia" para os adventistas?</h4>
                <p>Para os adventistas, "Espírito de Profecia" tem sentido bíblico derivado de <a href="#" class="biblical-reference" data-reference="Apocalipse 19:10">Apocalipse 19:10</a>, onde se diz que "o testemunho de Jesus é o espírito de profecia". A expressão pode se referir tanto ao dom profético dado pelo Espírito Santo à igreja quanto, no uso adventista mais específico, ao ministério de Ellen G. White como manifestação desse dom nos últimos dias.</p>
                <p>Portanto, quando os adventistas falam em "Espírito de Profecia", podem estar se referindo:</p>
                <p>ao princípio bíblico do dom profético na igreja;</p>
                <p>à evidência desse dom no remanescente;</p>
                <p>ou, de modo mais específico, ao conjunto dos escritos de Ellen White.</p>
                <p>Em todos os casos, a ideia central é que Jesus continua testemunhando à sua igreja por meio do Espírito.</p>
            </div>

            <div class="doctrine-item">
                <h4>2.12 Os escritos de Ellen White foram alterados, editados ou corrigidos após sua morte?</h4>
                <p>A resposta adventista é que sim, seus escritos passaram por processos editoriais, tradução, padronização textual, atualização gramatical e compilação, mas isso não é entendido como adulteração maliciosa da mensagem. Muitos manuscritos e publicações já tinham passado por revisão editorial ainda em vida, com conhecimento dela, algo comum na produção literária da época.</p>
                <p>Após sua morte, o trabalho de custodiar seus manuscritos incluiu organização, comparação de versões, preparação de edições e preservação textual. A posição adventista sustenta que isso deve ser distinguido de falsificação doutrinária. A questão correta não é se houve edição, porque houve, mas se houve mudança corruptora da mensagem. A defesa adventista é que a essência de seu pensamento e ensino foi preservada.</p>
            </div>

            <div class="doctrine-item">
                <h4>2.13 Por que Ellen White deu orientações detalhadas sobre saúde, vestimenta, educação e alimentação?</h4>
                <p>Os adventistas entendem essas orientações como aplicação prática da religião à vida toda. A fé bíblica não se limita à doutrina abstrata; alcança corpo, mente, família, trabalho, hábitos e testemunho. Textos como <a href="#" class="biblical-reference" data-reference="1 Coríntios 10:31">1 Coríntios 10:31</a>, <a href="#" class="biblical-reference" data-reference="1 Coríntios 6:19-20">1 Coríntios 6:19-20</a>, <a href="#" class="biblical-reference" data-reference="Romanos 12:1-2">Romanos 12:1-2</a> e <a href="#" class="biblical-reference" data-reference="Lucas 2:52">Lucas 2:52</a> mostram uma visão integral do ser humano.</p>
                <p>Por isso, Ellen White abordou saúde, alimentação, educação, simplicidade no vestir e formação do caráter como áreas espiritualmente relevantes. Na compreensão adventista, essas orientações não são excentricidades periféricas, mas extensões práticas do senhorio de Cristo sobre toda a vida. O objetivo era preparar um povo saudável, equilibrado, útil ao próximo e apto para discernimento espiritual e missão.</p>
            </div>

            <div class="doctrine-item">
                <h4>2.14 Ellen White ensinou que Jesus é Miguel, o arcanjo? Isso é consistente com a Bíblia?</h4>
                <p>A teologia adventista, acompanhando uma tradição cristã histórica presente também em outros intérpretes, entende que "Miguel" é um título para Cristo em seu papel de comandante dos exércitos celestiais, e não que Jesus seja um ser criado angélico. "Arcanjo" é entendido como chefe dos anjos, não como afirmação de natureza angelical. Textos usados nessa compreensão incluem <a href="#" class="biblical-reference" data-reference="Daniel 10:13">Daniel 10:13</a>, <a href="#" class="biblical-reference" data-reference="Daniel 12:1">Daniel 12:1</a>, <a href="#" class="biblical-reference" data-reference="Judas 9">Judas 9</a> e <a href="#" class="biblical-reference" data-reference="1 Tessalonicenses 4:16">1 Tessalonicenses 4:16</a>.</p>
                <p>Assim, quando se afirma que Cristo é Miguel, não se está rebaixando sua divindade, mas identificando-o como líder celestial supremo na guerra contra o mal. Isso é mantido em harmonia com a plena divindade de Cristo ensinada em <a href="#" class="biblical-reference" data-reference="João 1:1-3">João 1:1-3</a>, <a href="#" class="biblical-reference" data-reference="Colossenses 1:15-17">Colossenses 1:15-17</a>, <a href="#" class="biblical-reference" data-reference="Hebreus 1:1-8">Hebreus 1:1-8</a>. Os adventistas rejeitam qualquer ideia de que Jesus seja um anjo criado. Para eles, "Miguel" é título funcional, não definição ontológica inferior.</p>
            </div>
        </div>

        <div id="juizo-investigativo" class="sub-tab-content">
            <h2 class="sub-section-title">3. JUÍZO INVESTIGATIVO, SANTUÁRIO CELESTIAL E 1844</h2>

            <div class="doctrine-item">
                <h4>3.1 O que é o Juízo Investigativo (ou "juízo pré-advento") e onde essa doutrina aparece claramente na Bíblia?</h4>
                <p>O Juízo Investigativo, na teologia adventista, é a fase pré-advento do juízo divino em que os registros da vida dos que professaram fé em Deus são examinados no céu antes da segunda vinda de Cristo. Não é um processo para informar Deus de algo que Ele não saiba, mas a manifestação pública, justa e transparente de seus juízos perante o universo. Trata-se da etapa final do ministério sacerdotal de Cristo no santuário celestial.</p>
                <p>Sua base bíblica é construída por convergência de textos:</p>
                <p><a href="#" class="biblical-reference" data-reference="Daniel 7:9-10">Daniel 7:9-10</a>: tribunal assentado, livros abertos;</p>
                <p><a href="#" class="biblical-reference" data-reference="Daniel 8:14">Daniel 8:14</a>: purificação do santuário;</p>
                <p><a href="#" class="biblical-reference" data-reference="Daniel 9:24-27">Daniel 9:24-27</a>: conexão cronológica das 70 semanas com o período maior;</p>
                <p>Hebreus 8-9: ministério de Cristo no santuário celestial;</p>
                <p><a href="#" class="biblical-reference" data-reference="Apocalipse 14:6-7">Apocalipse 14:6-7</a>: anúncio de que "é chegada a hora do seu juízo" antes da volta de Cristo;</p>
                <p><a href="#" class="biblical-reference" data-reference="Apocalipse 22:12">Apocalipse 22:12</a>: Cristo traz sua recompensa consigo, implicando juízo prévio.</p>
                <p>A doutrina, portanto, não se baseia em um único verso isolado, mas em uma estrutura profético-tipológica integrada.</p>
            </div>

            <div class="doctrine-item">
                <h4>3.2 Como a IASD fundamenta biblicamente a data de 1844 e, em especial, 22 de outubro de 1844? A profecia dos 2300 dias/tardes e manhãs de Daniel 8 realmente aponta para 1844 e o início do juízo?</h4>
                <p>A IASD aplica o princípio dia-ano em profecias apocalípticas <a href="#" class="biblical-reference" data-reference="Números 14:34">Números 14:34</a>; <a href="#" class="biblical-reference" data-reference="Ezequiel 4:6">Ezequiel 4:6</a> e entende que os 2300 dias de <a href="#" class="biblical-reference" data-reference="Daniel 8:14">Daniel 8:14</a> representam 2300 anos. Como Daniel 8 não fornece explicitamente o ponto inicial, a igreja relaciona a profecia com Daniel 9, onde as 70 semanas são "determinadas" para o povo de Israel e começam com o decreto para restaurar Jerusalém. O decreto mais completo é o de Artaxerxes, em 457 a.C. <a href="#" class="biblical-reference" data-reference="Esdras 7:11-26">Esdras 7:11-26</a>.</p>
                <p>Contando 2300 anos a partir de 457 a.C., chega-se a 1844 d.C. Quanto à data 22 de outubro de 1844, ela resulta da associação entre a purificação do santuário e o Dia da Expiação de Levítico 16, calculado segundo o calendário judaico caraíta usado pelos mileritas. A posição adventista atual mantém 1844 como marco profético do início da fase final do ministério de Cristo no santuário celestial. A data específica de 22 de outubro é entendida historicamente como o dia do Grande Desapontamento, ligado ao cálculo do calendário religioso daquele ano.</p>
            </div>

            <div class="doctrine-item">
                <h4>3.3 O Juízo Investigativo não foi criado para explicar o fracasso da predição de Guilherme Miller e o Grande Desapontamento? Como a Igreja surgiu a partir de uma profecia falha e ainda assim se considera portadora da verdade?</h4>
                <p>A resposta adventista reconhece que Guilherme Miller e outros mileritas erraram quanto ao evento esperado em 1844. Eles pensaram que a purificação do santuário significava a volta de Cristo à terra. A igreja adventista entende que o erro não esteve necessariamente no tempo profético, mas na natureza do acontecimento. Isso é comparado, por analogia, à experiência dos discípulos, que também interpretaram de forma equivocada a missão messiânica de Jesus antes da cruz, embora estivessem em relação real com o plano de Deus (<a href="#" class="biblical-reference" data-reference="Lucas 24:25-27, 44-47">Lucas 24:25-27, 44-47</a>).</p>
                <p>Assim, o Juízo Investigativo não é visto como invenção para salvar um fracasso, mas como resultado de novo estudo bíblico após o desapontamento. A conclusão adventista foi que o santuário a ser purificado em <a href="#" class="biblical-reference" data-reference="Daniel 8:14">Daniel 8:14</a> não era a terra, mas o santuário celestial descrito em Hebreus. Portanto, a igreja afirma ter surgido de um movimento sincero que acertou o tempo profético, mas errou o evento esperado. A correção veio por reexame das Escrituras.</p>
            </div>

            <div class="doctrine-item">
                <h4>3.4 O Juízo Investigativo não implica que a expiação de Cristo na cruz foi incompleta? Isso não anula a afirmação de Jesus na cruz de que "Está consumado"? A expiação não terminou na cruz?</h4>
                <p>A teologia adventista afirma com clareza que o sacrifício expiatório de Cristo na cruz foi perfeito, completo, suficiente e irrepetível (<a href="#" class="biblical-reference" data-reference="João 19:30">João 19:30</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 10:10-14">Hebreus 10:10-14</a>). Nada pode ser acrescentado ao valor do sangue de Cristo. Quando Jesus disse "Está consumado", a oferta sacrificial foi plenamente realizada.</p>
                <p>O que os adventistas distinguem é entre o sacrifício expiatório e a aplicação sacerdotal dos benefícios desse sacrifício. Em linguagem tipológica, a vítima era morta no pátio, mas o ministério sacerdotal prosseguia no santuário. Da mesma forma, Cristo morreu uma vez por todas, mas seu ministério sumo-sacerdotal continua no céu (<a href="#" class="biblical-reference" data-reference="Hebreus 7:25">Hebreus 7:25</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 8:1-2">Hebreus 8:1-2</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 9:24">9:24</a>). O Juízo Investigativo não completa um sacrifício incompleto; ele integra a administração final dos méritos de um sacrifício já consumado e suficiente.</p>
            </div>

            <div class="doctrine-item">
                <h4>3.5 Se Deus é onisciente, por que precisaria "investigar" os registros humanos antes de julgar?</h4>
                <p>Os adventistas respondem que Deus não investiga para descobrir o que ignora. Sendo onisciente, Ele conhece perfeitamente cada ser humano (<a href="#" class="biblical-reference" data-reference="Salmo 139:1-4">Salmo 139:1-4</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 4:13">Hebreus 4:13</a>). O juízo investigativo existe para a manifestação pública da justiça divina diante do universo moral. No grande conflito entre Cristo e Satanás, Deus não apenas age com justiça; Ele também demonstra sua justiça de forma aberta, transparente e inteligível às criaturas.</p>
                <p>Textos como <a href="#" class="biblical-reference" data-reference="Daniel 7:9-10">Daniel 7:9-10</a> e <a href="#" class="biblical-reference" data-reference="1 Coríntios 4:5">1 Coríntios 4:5</a> mostram uma dimensão forense e reveladora do juízo. O objetivo é vindicar o caráter de Deus, mostrar a retidão de suas decisões e confirmar diante dos seres criados que os salvos são redimidos pela graça de Cristo e que os perdidos rejeitaram essa graça. O juízo é, portanto, revelação pública da verdade, não aquisição de informação por parte de Deus.</p>
            </div>

            <div class="doctrine-item">
                <h4>3.6 O Juízo Investigativo compromete a certeza da salvação e gera medo espiritual ou legalismo? Ele não contradiz o ensino bíblico de que o crente já tem segurança de salvação (<a href="#" class="biblical-reference" data-reference="João 5:24">João 5:24</a>; <a href="#" class="biblical-reference" data-reference="Romanos 8:1">Romanos 8:1</a>)?</h4>
                <p>A posição adventista é que o Juízo Investigativo, corretamente entendido, não destrói a segurança da salvação, porque essa segurança está em Cristo, não em desempenho humano autônomo. Textos como <a href="#" class="biblical-reference" data-reference="João 5:24">João 5:24</a>, <a href="#" class="biblical-reference" data-reference="Romanos 8:1">Romanos 8:1</a>, <a href="#" class="biblical-reference" data-reference="1 João 5:11-13">1 João 5:11-13</a> são aceitos plenamente. O crente pode ter segurança presente da salvação ao permanecer em Cristo.</p>
                <p>O juízo não existe para introduzir incerteza arbitrária, mas para revelar quem de fato permaneceu unido a Cristo pela fé. A mesma Bíblia que oferece segurança também chama à perseverança (<a href="#" class="biblical-reference" data-reference="Mateus 24:13">Mateus 24:13</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 3:14">Hebreus 3:14</a>; <a href="#" class="biblical-reference" data-reference="Apocalipse 14:12">Apocalipse 14:12</a>). Assim, os adventistas evitam tanto o desespero legalista quanto a presunção. A segurança é real, mas relacional e perseverante. O juízo confirma, diante do universo, a autenticidade dessa união salvadora com Cristo.</p>
            </div>

            <div class="doctrine-item">
                <h4>3.7 Existe realmente um santuário literal no céu, e qual sua base bíblica?</h4>
                <p>Sim, a doutrina adventista afirma a existência de um santuário celestial real. A base principal está em <a href="#" class="biblical-reference" data-reference="Hebreus 8:1-2">Hebreus 8:1-2</a>, que fala de Cristo como ministro do santuário e do verdadeiro tabernáculo que o Senhor erigiu. <a href="#" class="biblical-reference" data-reference="Hebreus 9:23-24">Hebreus 9:23-24</a> também distingue entre as figuras terrenas e as realidades celestiais. O tabernáculo mosaico era cópia e sombra do celestial (<a href="#" class="biblical-reference" data-reference="Êxodo 25:8-9">Êxodo 25:8-9</a>, <a href="#" class="biblical-reference" data-reference="Êxodo 25:40">40</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 8:5">Hebreus 8:5</a>).</p>
                <p>Os adventistas entendem que esse santuário não deve ser imaginado de maneira simplista ou materialista, como mera reprodução terrena ampliada, mas como realidade celestial objetiva, da qual o sistema mosaico era tipo pedagógico. É nesse contexto que Cristo exerce seu ministério sacerdotal após a ascensão.</p>
            </div>

            <div class="doctrine-item">
                <h4>3.8 <a href="#" class="biblical-reference" data-reference="Hebreus 9:12">Hebreus 9:12</a> não ensina que Cristo entrou no Santíssimo de uma vez por todas ao ascender, e não apenas em 1844?</h4>
                <p>Esse é um dos pontos mais debatidos, e a resposta adventista é que <a href="#" class="biblical-reference" data-reference="Hebreus 9:12">Hebreus 9:12</a> afirma que Cristo entrou no santuário celestial com seu próprio sangue, garantindo redenção eterna. O texto ensina a realidade e eficácia de sua entrada celestial, mas não necessariamente resolve sozinho toda a questão da progressão funcional do ministério sacerdotal em termos tipológicos.</p>
                <p>A leitura adventista argumenta que Hebreus enfatiza a superioridade do ministério de Cristo sobre o levítico, não pretende fornecer um mapa cronológico completo de todas as fases desse ministério. Assim, Cristo ascendeu ao céu e iniciou seu ministério sacerdotal ali; porém, a fase correspondente ao antítipo do Dia da Expiação é entendida como tendo começo profético em 1844, à luz de <a href="#" class="biblical-reference" data-reference="Daniel 8:14">Daniel 8:14</a>. Portanto, não há negação da entrada celestial na ascensão, mas distinção entre entrada no santuário celestial e início da obra final de juízo e purificação.</p>
            </div>

            <div class="doctrine-item">
                <h4>3.9 A doutrina do santuário celestial contradiz Hebreus 8–10 e a suficiência do sacrifício de Cristo?</h4>
                <p>Para os adventistas, não. Ao contrário, a doutrina do santuário celestial busca preservar justamente o ensino de Hebreus: Cristo é o verdadeiro Sumo Sacerdote, seu sacrifício foi único e suficiente, e seu ministério atual é celestial e eficaz (<a href="#" class="biblical-reference" data-reference="Hebreus 7:25-27">Hebreus 7:25-27</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 8:1-2">Hebreus 8:1-2</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 9:24-28">Hebreus 9:24-28</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 10:10-14">Hebreus 10:10-14</a>). O erro seria pensar que a doutrina adventista repete sacrifícios ou sugere insuficiência da cruz. Ela não faz isso.</p>
                <p>A teologia adventista distingue entre:</p>
                <p>sacrifício único, consumado na cruz;</p>
                <p>intercessão contínua, realizada no céu;</p>
                <p>fase final do juízo, ligada à purificação do santuário.</p>
                <p>Assim, Hebreus 8–10 não destrói a doutrina do santuário; é precisamente o fundamento da realidade do ministério celestial de Cristo. O debate recai mais sobre a interpretação de sua estrutura interna do que sobre a sua existência.</p>
            </div>

            <div class="doctrine-item">
                <h4>3.10 O tabernáculo e o sistema levítico podem servir de base para uma doutrina tão central como o Juízo Investigativo?</h4>
                <p>Os adventistas respondem que sim, porque o Novo Testamento trata o sistema levítico como tipológico e cristológico, não como simples ritual antiquado sem valor doutrinário. Hebreus inteiro trabalha nessa linha. O tabernáculo, o sacerdócio, os sacrifícios e o Dia da Expiação apontavam para Cristo e para diferentes aspectos de sua obra redentora.</p>
                <p>Se o Novo Testamento reconhece valor tipológico profundo no sistema levítico, então é legítimo construir doutrina a partir dele, desde que em conexão com textos proféticos e apostólicos. O Juízo Investigativo não deriva apenas de Levítico 16 isoladamente, mas da relação entre Levítico 16, Daniel 7–9, Hebreus 8–9 e Apocalipse 14. Portanto, não se trata de base frágil, mas de leitura integrada da revelação.</p>
            </div>

            <div class="doctrine-item">
                <h4>3.11 Por que outras denominações cristãs rejeitam a doutrina adventista do Juízo Investigativo?</h4>
                <p>A principal razão é hermenêutica. Muitas denominações não adotam a leitura historicista das profecias de Daniel e Apocalipse, não aplicam o princípio dia-ano da mesma maneira, ou entendem <a href="#" class="biblical-reference" data-reference="Daniel 8:14">Daniel 8:14</a> em outro contexto histórico. Outras veem em Hebreus uma entrada imediata e plena de Cristo no Santíssimo sem distinção de fases ministeriais posteriores.</p>
                <p>Além disso, a doutrina adventista está ligada à história específica do movimento milerita e ao desenvolvimento teológico pós-1844, o que leva muitos cristãos a considerá-la uma construção particular da IASD. A resposta adventista é que a verdade bíblica não depende de maioria denominacional, mas de fidelidade exegética. Portanto, a rejeição por outras igrejas é reconhecida como real, mas não considerada decisiva para a validade da doutrina.</p>
            </div>
        </div>

        <div id="estado-mortos" class="sub-tab-content">
            <h2 class="sub-section-title">4. ESTADO DOS MORTOS, ALMA E VIDA APÓS A MORTE</h2>

            <div class="doctrine-item">
                <h4>4.1 Por que os adventistas ensinam o "sono da alma" ou a inconsciência dos mortos até a ressurreição?</h4>
                <p>Os adventistas ensinam a inconsciência dos mortos porque entendem que essa é a antropologia mais coerente com a Bíblia. O ser humano não possui uma alma naturalmente imortal separada do corpo; ele é uma unidade viva formada do pó da terra mais o fôlego de vida (<a href="#" class="biblical-reference" data-reference="Gênesis 2:7">Gênesis 2:7</a>). Na morte, essa unidade se desfaz: o pó volta à terra e o fôlego retorna a Deus (<a href="#" class="biblical-reference" data-reference="Eclesiastes 12:7">Eclesiastes 12:7</a>). O resultado não é uma alma consciente vagando, mas a cessação da consciência até a ressurreição.</p>
                <p>Diversos textos apoiam essa compreensão: <a href="#" class="biblical-reference" data-reference="Eclesiastes 9:5-6, 10">Eclesiastes 9:5-6, 10</a> afirma que os mortos nada sabem; <a href="#" class="biblical-reference" data-reference="Salmo 146:4">Salmo 146:4</a> diz que perecem os seus pensamentos; <a href="#" class="biblical-reference" data-reference="João 11:11-14">João 11:11-14</a> chama a morte de sono; <a href="#" class="biblical-reference" data-reference="1 Tessalonicenses 4:13-17">1 Tessalonicenses 4:13-17</a> liga a esperança cristã não à sobrevivência consciente da alma, mas à ressurreição na volta de Cristo. Por isso, a esperança adventista é fortemente centrada na ressurreição corporal e no retorno de Jesus.</p>
            </div>

            <div class="doctrine-item">
                <h4>4.2 O que acontece com a pessoa imediatamente após a morte segundo a doutrina adventista? Ela está no céu (ou no inferno) agora (<a href="#" class="biblical-reference" data-reference="Lucas 23:43">Lucas 23:43</a>)?</h4>
                <p>Segundo a doutrina adventista, imediatamente após a morte a pessoa entra em estado de inconsciência, comparado ao sono. Ela não está desfrutando conscientemente do céu nem sofrendo conscientemente no inferno. Seu próximo momento consciente será a ressurreição: para vida, no caso dos justos, na volta de Cristo; ou para condenação, após o milênio, no caso dos ímpios (<a href="#" class="biblical-reference" data-reference="João 5:28-29">João 5:28-29</a>; <a href="#" class="biblical-reference" data-reference="1 Tessalonicenses 4:16-17">1 Tessalonicenses 4:16-17</a>; <a href="#" class="biblical-reference" data-reference="Apocalipse 20:5-6">Apocalipse 20:5-6</a>, <a href="#" class="biblical-reference" data-reference="Apocalipse 20:11-15">Apocalipse 20:11-15</a>).</p>
                <p>Quanto a <a href="#" class="biblical-reference" data-reference="Lucas 23:43">Lucas 23:43</a>, os adventistas entendem que a pontuação do texto em português reflete decisão interpretativa posterior, pois o grego antigo não tinha vírgulas como nas edições modernas. Assim, a frase pode ser entendida como: "Em verdade te digo hoje, estarás comigo no paraíso", preservando a promessa feita naquele dia, sem exigir cumprimento naquele mesmo dia. Essa leitura é vista como coerente com o restante da Bíblia e com o fato de que o próprio Jesus, após a ressurreição, disse ainda não ter subido ao Pai (<a href="#" class="biblical-reference" data-reference="João 20:17">João 20:17</a>).</p>
            </div>

            <div class="doctrine-item">
                <h4>4.3 Como os adventistas interpretam a promessa de Jesus ao ladrão na cruz: "Hoje estarás comigo no paraíso"?</h4>
                <p>Como visto, os adventistas interpretam o texto de <a href="#" class="biblical-reference" data-reference="Lucas 23:43">Lucas 23:43</a> levando em conta a ausência de pontuação original no grego. A ênfase estaria em "hoje" como momento da promessa solene, não da entrada imediata no paraíso. Em outras palavras: "Eu te afirmo hoje, neste dia de dor e aparente derrota, que estarás comigo no paraíso".</p>
                <p>Essa leitura é reforçada por dois pontos. Primeiro, Jesus permaneceu no sepulcro e só depois da ressurreição declarou: "ainda não subi para meu Pai" (<a href="#" class="biblical-reference" data-reference="João 20:17">João 20:17</a>). Segundo, a esperança predominante do Novo Testamento é a ressurreição futura, não a glorificação plena imediatamente após a morte (<a href="#" class="biblical-reference" data-reference="1 Coríntios 15:51-54">1 Coríntios 15:51-54</a>). Portanto, a promessa ao ladrão é absolutamente real, mas seu cumprimento pertence à consumação futura.</p>
            </div>

            <div class="doctrine-item">
                <h4>4.4 Como os adventistas explicam a parábola do rico e Lázaro contada por Jesus?</h4>
                <p>Os adventistas interpretam <a href="#" class="biblical-reference" data-reference="Lucas 16:19-31">Lucas 16:19-31</a> como parábola, não como descrição literal do estado intermediário dos mortos. O objetivo principal do relato é moral e espiritual: denunciar a dureza de coração, a autossuficiência e a rejeição da revelação divina. A chave do final é: "Se não ouvem a Moisés e aos Profetas, tampouco acreditarão, ainda que alguém ressuscite dentre os mortos".</p>
                <p>Se tomada literalmente, a parábola criaria vários problemas: salvos e perdidos conversando entre si, céu e inferno separados por distância visível, uma gota d'água aliviando fogo literal, e a bem-aventurança dos salvos perturbada pela observação contínua do tormento dos perdidos. Os adventistas entendem que Jesus utilizou imagens conhecidas para transmitir uma lição moral, sem endossar uma ontologia da alma imortal consciente.</p>
            </div>

            <div class="doctrine-item">
                <h4>4.5 Como os adventistas interpretam textos como <a href="#" class="biblical-reference" data-reference="Filipenses 1:23">Filipenses 1:23</a> e <a href="#" class="biblical-reference" data-reference="2 Coríntios 5:8">2 Coríntios 5:8</a> sobre estar com Cristo após a morte?</h4>
                <p>Em <a href="#" class="biblical-reference" data-reference="Filipenses 1:21-23">Filipenses 1:21-23</a>, Paulo diz ter desejo de partir e estar com Cristo. Os adventistas entendem isso do ponto de vista da experiência consciente do crente: entre a morte e a ressurreição não há percepção de passagem do tempo. Assim, ao morrer, o próximo momento consciente do fiel será estar com Cristo na ressurreição. O texto expressa certeza do destino final, não necessariamente um estado intermediário consciente.</p>
                <p>Em <a href="#" class="biblical-reference" data-reference="2 Coríntios 5:1-8">2 Coríntios 5:1-8</a>, Paulo contrasta a habitação terrena mortal com a futura habitação celestial, desejando ser revestido da imortalidade. Os adventistas observam que o clímax do argumento não é uma alma desencarnada vivendo conscientemente, mas a vitória sobre a mortalidade por meio do revestimento da vida. Em coerência com <a href="#" class="biblical-reference" data-reference="1 Coríntios 15">1 Coríntios 15</a>, o ideal paulino não é ficar "despido", mas ser revestido pelo corpo imortal na ressurreição. Assim, "ausentes do corpo e presentes com o Senhor" aponta para o destino assegurado do crente, consumado escatologicamente.</p>
            </div>

            <div class="doctrine-item">
                <h4>4.6 Como Moisés e Elias apareceram no monte da transfiguração conversando com Jesus se os mortos estão inconscientes?</h4>
                <p>Os adventistas explicam que os dois casos não contradizem a doutrina do estado dos mortos, porque representam exceções redentivas específicas. Elias foi trasladado ao céu sem ver a morte (<a href="#" class="biblical-reference" data-reference="2 Reis 2:11">2 Reis 2:11</a>). Moisés, segundo a compreensão adventista, foi ressuscitado de modo especial, em harmonia com a disputa mencionada em <a href="#" class="biblical-reference" data-reference="Judas 9">Judas 9</a> e com sua presença real na transfiguração (<a href="#" class="biblical-reference" data-reference="Mateus 17:1-3">Mateus 17:1-3</a>).</p>
                <p>Assim, a transfiguração não prova que todos os mortos estejam conscientes. Pelo contrário, ela antecipa o reino vindouro e reúne simbolicamente Cristo com representantes dos dois grupos dos salvos: os que serão ressuscitados e os que serão trasladados sem ver a morte. É uma cena excepcional e escatológica, não descrição do estado comum dos mortos.</p>
            </div>

            <div class="doctrine-item">
                <h4>4.7 Os mortos podem se comunicar com os vivos ou saber o que acontece na Terra?</h4>
                <p>A posição adventista é que os mortos não se comunicam com os vivos e não acompanham conscientemente os acontecimentos terrenos. Textos como <a href="#" class="biblical-reference" data-reference="Eclesiastes 9:5-6, 10">Eclesiastes 9:5-6, 10</a> e <a href="#" class="biblical-reference" data-reference="Salmo 146:4">Salmo 146:4</a> são entendidos como claros nesse ponto. Toda tentativa de contato com os mortos é proibida nas Escrituras (<a href="#" class="biblical-reference" data-reference="Deuteronômio 18:10-12">Deuteronômio 18:10-12</a>; <a href="#" class="biblical-reference" data-reference="Isaías 8:19-20">Isaías 8:19-20</a>).</p>
                <p>Quando há fenômenos que parecem envolver comunicação com mortos, a interpretação adventista os atribui a engano espiritual e ação demoníaca, não a reais espíritos dos falecidos. Essa compreensão se encaixa no grande conflito entre Cristo e Satanás e na advertência bíblica contra espíritos enganadores (<a href="#" class="biblical-reference" data-reference="2 Coríntios 11:14">2 Coríntios 11:14</a>; <a href="#" class="biblical-reference" data-reference="1 Timóteo 4:1">1 Timóteo 4:1</a>).</p>
            </div>

            <div class="doctrine-item">
                <h4>4.8 A doutrina adventista do estado dos mortos torna sem sentido a oração pelos mortos ou a intercessão dos santos?</h4>
                <p>Sim, na teologia adventista essa doutrina exclui a prática da oração pelos mortos e da intercessão dos santos falecidos. Se os mortos estão inconscientes, eles não recebem petições, não intercedem e não acompanham as necessidades dos vivos. Além disso, a Bíblia apresenta Cristo como o único mediador suficiente entre Deus e os homens (<a href="#" class="biblical-reference" data-reference="1 Timóteo 2:5">1 Timóteo 2:5</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 7:25">Hebreus 7:25</a>; <a href="#" class="biblical-reference" data-reference="1 João 2:1">1 João 2:1</a>).</p>
                <p>A esperança cristã, então, não se apoia em comunicação com os mortos, mas em comunhão com Cristo vivo, na intercessão sacerdotal de Jesus e na certeza da ressurreição futura. A doutrina adventista entende que isso preserva a centralidade de Cristo, evita práticas não fundamentadas na Escritura e mantém a esperança ancorada no retorno do Senhor.</p>
            </div>
        </div>

        <div id="inferno-destruicao" class="sub-tab-content">
            <h2 class="sub-section-title">5. INFERNO E DESTRUIÇÃO FINAL DOS ÍMPIOS</h2>

            <div class="doctrine-item">
                <h4>5.1 Os adventistas acreditam em inferno eterno (a Bíblia fala em "fogo eterno" e "tormento para sempre" em <a href="#" class="biblical-reference" data-reference="Apocalipse 20:10">Apocalipse 20:10</a>) ou na destruição final dos ímpios (aniquilacionismo)?</h4>
                <p>Os adventistas creem na destruição final dos ímpios, e não em um inferno de tormento consciente sem fim. Em linguagem mais técnica, trata-se da doutrina da imortalidade condicional e da aniquilação final dos perdidos.</p>
                <p>O ponto de partida dessa compreensão, no pensamento adventista exposto em Nisto Cremos, é a visão bíblica do ser humano. O homem não possui em si mesmo uma alma naturalmente imortal; a vida eterna é um dom de Deus em Cristo, não uma propriedade inerente do ser humano. Por isso, a punição final dos ímpios não é viver eternamente sofrendo, mas sofrer o juízo de Deus e finalmente perecer de modo irreversível. As bases principais são textos como <a href="#" class="biblical-reference" data-reference="Gênesis 2:7">Gênesis 2:7</a>, <a href="#" class="biblical-reference" data-reference="Ezequiel 18:4">Ezequiel 18:4</a>, <a href="#" class="biblical-reference" data-reference="Romanos 6:23">Romanos 6:23</a>, <a href="#" class="biblical-reference" data-reference="Malaquias 4:1-3">Malaquias 4:1-3</a>, <a href="#" class="biblical-reference" data-reference="Mateus 10:28">Mateus 10:28</a>, <a href="#" class="biblical-reference" data-reference="João 3:16">João 3:16</a>, <a href="#" class="biblical-reference" data-reference="2 Tessalonicenses 1:7-9">2 Tessalonicenses 1:7-9</a> e <a href="#" class="biblical-reference" data-reference="Apocalipse 20:14-15">Apocalipse 20:14-15</a>.</p>
                <p>Na leitura adventista, a expressão "segunda morte" é decisiva. Se o destino final dos ímpios fosse vida eterna em tormento, então não seria propriamente morte no sentido pleno, mas uma existência interminável em sofrimento. Porém, Apocalipse apresenta o castigo final como morte, não como uma forma alternativa de vida eterna. Assim, <a href="#" class="biblical-reference" data-reference="Apocalipse 20:14-15">Apocalipse 20:14-15</a> é entendido como a eliminação definitiva do pecado, dos pecadores impenitentes, de Satanás e de seus anjos.</p>
                <p>A objeção mais comum vem de textos como <a href="#" class="biblical-reference" data-reference="Apocalipse 20:10">Apocalipse 20:10</a>, onde se fala do diabo, da besta e do falso profeta sendo atormentados "pelos séculos dos séculos". A resposta adventista distingue entre:</p>
                <p>textos simbólicos e apocalípticos, que exigem leitura cuidadosa;</p>
                <p>personagens simbólicos, como a besta e o falso profeta;</p>
                <p>e a doutrina geral da Escritura sobre o destino dos ímpios.</p>
                <p>Ou seja: os adventistas não constroem sua doutrina do inferno a partir de uma leitura isolada e literalista de uma expressão apocalíptica, mas do conjunto da revelação bíblica. Nesse conjunto, os ímpios são descritos como os que perecem, são consumidos, viram cinzas, sofrem destruição eterna, recebem a segunda morte. Isso aparece em <a href="#" class="biblical-reference" data-reference="Salmos 37:10,20">Salmos 37:10,20</a>, <a href="#" class="biblical-reference" data-reference="Obadias 16">Obadias 16</a>, <a href="#" class="biblical-reference" data-reference="Malaquias 4:1-3">Malaquias 4:1-3</a>, <a href="#" class="biblical-reference" data-reference="Mateus 10:28">Mateus 10:28</a>, <a href="#" class="biblical-reference" data-reference="Filipenses 3:19">Filipenses 3:19</a>, <a href="#" class="biblical-reference" data-reference="2 Pedro 3:7">2 Pedro 3:7</a> e <a href="#" class="biblical-reference" data-reference="Judas 7">Judas 7</a>.</p>
                <p>Portanto, a resposta adventista é clara: não, os adventistas não creem em tormento consciente eterno dos ímpios. Eles creem na destruição final, total e irreversível dos ímpios no juízo de Deus.</p>
                <p>Do ponto de vista apologético, essa posição procura preservar ao mesmo tempo:</p>
                <p>a seriedade da justiça divina;</p>
                <p>a realidade do juízo;</p>
                <p>e a coerência moral do caráter de Deus como amor, justiça e santidade.</p>
                <p>Na visão adventista, um Deus que pune eternamente seres finitos com tormento sem fim criaria um problema moral muito maior do que o que a Bíblia de fato ensina. A destruição final, ao contrário, seria a manifestação de uma justiça verdadeira, santa e proporcional.</p>
            </div>

            <div class="doctrine-item">
                <h4>5.2 Como os adventistas interpretam textos sobre "fogo eterno", "castigo eterno" e "tormento pelos séculos dos séculos" tal como em <a href="#" class="biblical-reference" data-reference="Mateus 25:46">Mateus 25:46</a>?</h4>
                <p>Os adventistas interpretam essas expressões à luz do uso bíblico da linguagem sobre juízo, e não a partir de pressupostos filosóficos sobre a imortalidade natural da alma.</p>
                <p>No caso de "fogo eterno", o argumento central é que, na Bíblia, "eterno" muitas vezes descreve o resultado permanente de uma ação divina, e não necessariamente um processo que nunca termina. O exemplo clássico é <a href="#" class="biblical-reference" data-reference="Judas 7">Judas 7</a>, onde Sodoma e Gomorra são postas como exemplo do "fogo eterno". Evidentemente, aquelas cidades não continuam queimando hoje. O fogo foi "eterno" em seus efeitos: a destruição foi definitiva, irreversível.</p>
                <p>O mesmo raciocínio é aplicado a <a href="#" class="biblical-reference" data-reference="Mateus 25:46">Mateus 25:46</a>. Quando o texto fala em "castigo eterno", a interpretação adventista não é "castigar eternamente", mas um castigo cujos efeitos são eternos. O paralelo do versículo é importante:</p>
                <p>os justos entram na vida eterna;</p>
                <p>os ímpios recebem o castigo eterno.</p>
                <p>A vida eterna é um processo contínuo de existência;</p>
                <p>o castigo eterno é uma sentença definitiva com efeito permanente.</p>
                <p>O foco está no caráter irreversível do juízo, não na duração infinita do ato de punir.</p>
                <p>Algo semelhante ocorre com a expressão "tormento pelos séculos dos séculos" em <a href="#" class="biblical-reference" data-reference="Apocalipse 14:11">Apocalipse 14:11</a> e <a href="#" class="biblical-reference" data-reference="Apocalipse 20:10">Apocalipse 20:10</a>. Os adventistas lembram que Apocalipse é um livro cheio de símbolos. A linguagem do "fumo" subindo para sempre também ecoa o Antigo Testamento, especialmente imagens de juízo contra cidades e poderes rebeldes, como em <a href="#" class="biblical-reference" data-reference="Isaías 34:8-10">Isaías 34:8-10</a>. Ali também a ideia não é necessariamente fogo queimando infinitamente em sentido literal, mas um juízo de consequências definitivas, memoráveis e irrevogáveis.</p>
                <p>Além disso, a expressão "para sempre" ou equivalentes pode, na própria Bíblia, significar duração condicionada ao contexto. Por exemplo, em <a href="#" class="biblical-reference" data-reference="Êxodo 21:6">Êxodo 21:6</a>, o servo serviria "para sempre", o que claramente não significa eternidade metafísica, mas enquanto durasse sua vida e condição. Em <a href="#" class="biblical-reference" data-reference="1 Samuel 1:22,28">1 Samuel 1:22,28</a>, Samuel é dedicado "para sempre", isto é, por toda a sua vida. Isso mostra que o termo deve ser lido dentro da moldura literária e teológica do texto.</p>
                <p>Assim, a interpretação adventista é:</p>
                <p>fogo eterno = fogo cujo efeito é eterno;</p>
                <p>castigo eterno = punição definitiva e irreversível;</p>
                <p>tormento pelos séculos dos séculos = linguagem apocalíptica que enfatiza a totalidade, solenidade e irreversibilidade do juízo divino.</p>
                <p>Apologeticamente, os adventistas argumentam que essa leitura faz mais justiça ao conjunto da Escritura. Se textos muito claros dizem que os ímpios perecem, são destruídos, são consumidos, tornam-se cinzas, então textos figurados devem ser interpretados em harmonia com esses textos mais diretos, e não o contrário.</p>
            </div>

            <div class="doctrine-item">
                <h4>5.3 "Morte eterna" significa aniquilação ou separação eterna de Deus?</h4>
                <p>Na compreensão adventista, "morte eterna" significa primariamente a destruição definitiva e irreversível dos ímpios, e não uma separação eterna de Deus em estado consciente interminável.</p>
                <p>É verdade que a morte implica separação. A morte física já envolve separação da vida presente. Mas quando se fala da segunda morte no juízo final, o ponto principal não é mera separação relacional num estado de consciência sem fim; é o fim definitivo da existência dos ímpios como seres vivos. Esse entendimento deriva de textos como <a href="#" class="biblical-reference" data-reference="Romanos 6:23">Romanos 6:23</a>, <a href="#" class="biblical-reference" data-reference="Mateus 10:28">Mateus 10:28</a>, <a href="#" class="biblical-reference" data-reference="João 3:16">João 3:16</a>, <a href="#" class="biblical-reference" data-reference="Filipenses 3:19">Filipenses 3:19</a>, <a href="#" class="biblical-reference" data-reference="2 Tessalonicenses 1:9">2 Tessalonicenses 1:9</a> e <a href="#" class="biblical-reference" data-reference="Apocalipse 20:14-15">Apocalipse 20:14-15</a>.</p>
                <p>O texto de <a href="#" class="biblical-reference" data-reference="2 Tessalonicenses 1:9">2 Tessalonicenses 1:9</a> é importante porque fala em "pena de eterna destruição". Na leitura adventista, isso não significa um processo eterno de destruir sem nunca completar a destruição. Significa uma destruição final cujos efeitos jamais serão revertidos. Assim, "eterna" qualifica o resultado, não a duração infinita de um sofrimento consciente.</p>
                <p>A expressão "separação eterna de Deus" pode ser usada em certo sentido descritivo, mas os adventistas evitam fazer dela a definição central, porque isso pode sugerir que os ímpios continuarão existindo para sempre. E esse não é o ensino adventista. Para a IASD, o destino final do pecado é sua erradicação completa do universo. Deus não manterá eternamente um setor do cosmos em rebelião consciente e sofrimento sem fim.</p>
                <p>Do ponto de vista apologético, os adventistas sustentam que chamar o estado final dos ímpios de "vida eterna separada de Deus" enfraquece o sentido bíblico de "morte" e desloca a esperança cristã para categorias mais gregas do que hebraico-bíblicas. Na Bíblia, morte é morte; e a vida eterna pertence aos salvos, não aos perdidos.</p>
                <p>Portanto, a resposta mais precisa é: para os adventistas, "morte eterna" significa a destruição final e irreversível dos ímpios, o que inclui sua exclusão definitiva da presença favorável de Deus, mas culmina na extinção de sua vida, não em sua preservação eterna em sofrimento.</p>
            </div>

            <div class="doctrine-item">
                <h4>5.4 A destruição final dos ímpios diminui a seriedade do pecado e da justiça divina?</h4>
                <p>Na teologia adventista, não. Pelo contrário, a destruição final dos ímpios ressalta tanto a gravidade do pecado quanto a santidade da justiça divina.</p>
                <p>A ideia de que somente um tormento eterno tornaria o pecado realmente sério parte de uma noção específica de justiça, mas não necessariamente bíblica. Os adventistas respondem que a Bíblia apresenta o salário do pecado como morte, não como vida eterna em dor. <a href="#" class="biblical-reference" data-reference="Romanos 6:23">Romanos 6:23</a> é central aqui. O pecado é tão grave que leva à perda total da vida, à exclusão definitiva do reino de Deus e à destruição sem retorno. Isso já é, em si mesmo, uma punição absolutamente séria.</p>
                <p>Além disso, a justiça divina, na visão adventista, não é vingança infinita, mas juízo santo, verdadeiro e proporcional. A Bíblia ensina graus de responsabilidade e de castigo, como em <a href="#" class="biblical-reference" data-reference="Lucas 12:47-48">Lucas 12:47-48</a>, <a href="#" class="biblical-reference" data-reference="Mateus 11:20-24">Mateus 11:20-24</a> e <a href="#" class="biblical-reference" data-reference="Romanos 2:5-6">Romanos 2:5-6</a>. Isso se harmoniza melhor com a ideia de que os ímpios sofrerão o juízo de Deus de forma real e proporcional, culminando na segunda morte, do que com a noção de um sofrimento idêntico e infinito para todos.</p>
                <p>Outro ponto importante é o caráter de Deus. Em Nisto Cremos, Deus é apresentado como amor, justiça e verdade. Os adventistas entendem que o juízo final precisa ser compatível com esse caráter. A destruição final dos ímpios não suaviza o pecado; ela mostra que:</p>
                <p>Deus não banaliza o mal;</p>
                <p>Deus não perpetua o mal eternamente;</p>
                <p>Deus põe fim ao mal de modo justo, completo e definitivo.</p>
                <p>Em outras palavras, a punição final não é leve. Ela é absoluta. O pecador impenitente perde tudo: vida, futuro, herança, participação no reino e qualquer continuidade de existência. A seriedade do pecado é vista exatamente no fato de que ele conduz à ruína total fora de Cristo.</p>
                <p>Apologeticamente, os adventistas costumam inverter a objeção: a pergunta não é se a destruição final diminui a justiça divina, mas se o tormento eterno não comprometeria a revelação de Deus em Cristo. A cruz mostra que Deus leva o pecado a sério, mas também revela que Seu propósito final é vencer o mal, não preservá-lo eternamente em um inferno ativo.</p>
                <p>Assim, a resposta adventista é que a destruição final dos ímpios:</p>
                <p>não enfraquece a doutrina do pecado;</p>
                <p>não diminui a justiça de Deus;</p>
                <p>e, ao contrário, mostra a vitória completa de Deus sobre o mal.</p>
            </div>
        </div>

        <div id="salvacao-graca" class="sub-tab-content">
            <h2 class="sub-section-title">6. SALVAÇÃO, GRAÇA, LEI E PERFEIÇÃO CRISTÃ</h2>

            <div class="doctrine-item">
                <h4>6.1 Os adventistas creem na salvação somente pela graça mediante a fé (<a href="#" class="biblical-reference" data-reference="Efésios 2:8-9">Efésios 2:8-9</a>), ou a obediência aos mandamentos (especialmente o sábado) também é necessária?</h4>
                <p>Os adventistas ensinam que a salvação é somente pela graça, mediante a fé em Jesus Cristo. Nesse ponto, a posição oficial adventista, expressa em Nisto Cremos, é claramente protestante. Textos como <a href="#" class="biblical-reference" data-reference="Efésios 2:8-9">Efésios 2:8-9</a>, <a href="#" class="biblical-reference" data-reference="Romanos 3:20-28"><a href="#" class="biblical-reference" data-reference="Romanos 3:20">Romanos 3:20</a>-28</a>, <a href="#" class="biblical-reference" data-reference="Romanos 5:1">Romanos 5:1</a>, <a href="#" class="biblical-reference" data-reference="Gálatas 2:16">Gálatas 2:16</a> e <a href="#" class="biblical-reference" data-reference="Tito 3:5">Tito 3:5</a> são fundamentais.</p>
                <p>Ao mesmo tempo, os adventistas afirmam que a fé salvadora nunca permanece sozinha. Ela produz obediência, transformação e santificação. Portanto, a obediência aos mandamentos — inclusive ao sábado — não é a base da salvação, mas é vista como fruto da salvação e sinal de uma relação viva com Cristo. Aqui entram textos como <a href="#" class="biblical-reference" data-reference="Efésios 2:10">Efésios 2:10</a>, <a href="#" class="biblical-reference" data-reference="João 14:15">João 14:15</a>, <a href="#" class="biblical-reference" data-reference="1 João 2:3-6">1 João 2:3-6</a>, <a href="#" class="biblical-reference" data-reference="1 João 5:2-3">1 João 5:2-3</a>, <a href="#" class="biblical-reference" data-reference="Tiago 2:17-26">Tiago 2:17-26</a> e <a href="#" class="biblical-reference" data-reference="Apocalipse 14:12">Apocalipse 14:12</a>.</p>
                <p>A distinção é decisiva:</p>
                <p>causa da salvação: a graça de Deus em Cristo;</p>
                <p>meio de apropriação: a fé;</p>
                <p>evidência da salvação: uma vida de obediência;</p>
                <p>resultado da salvação: santificação e fidelidade.</p>
                <p>No pensamento adventista, o sábado entra nessa lógica. Ele não salva ninguém. Cristo salva. Mas, uma vez que a pessoa reconhece Cristo como Senhor e aceita a autoridade das Escrituras, ela se dispõe a obedecer ao que Deus ordenou, inclusive o quarto mandamento. Assim, o sábado não é um concorrente da graça; é parte da resposta obediente de quem foi alcançado pela graça.</p>
                <p>Apologeticamente, os adventistas rejeitam duas distorções:</p>
                <p>o legalismo, que tenta merecer salvação por obras;</p>
                <p>e o antinomismo, que diz que a graça torna a obediência irrelevante.</p>
                <p>Para a IASD, ambos são erros. A graça salva, e a mesma graça transforma. Quem foi justificado é chamado a viver em novidade de vida. Por isso, a obediência importa muito, mas como consequência da união com Cristo, não como moeda de troca para obter aceitação divina.</p>
            </div>

            <div class="doctrine-item">
                <h4>6.2 A ênfase adventista na lei e no sábado torna sua teologia legalista? O que os adventistas entendem por "graça e lei"? São complementares ou excludentes?</h4>
                <p>Na autocompreensão adventista, a ênfase na lei e no sábado não deveria tornar sua teologia legalista. O legalismo surge quando a lei é usada como meio de justificação. A posição oficial adventista é que a lei jamais salva; ela revela o caráter de Deus, define o pecado e orienta a vida do crente, mas não pode justificar o pecador. Isso se baseia em <a href="#" class="biblical-reference" data-reference="Romanos 3:20">Romanos 3:20</a>, <a href="#" class="biblical-reference" data-reference="Romanos 7:7">Romanos 7:7</a>, <a href="#" class="biblical-reference" data-reference="Gálatas 2:16">Gálatas 2:16</a> e <a href="#" class="biblical-reference" data-reference="Tiago 1:23-25">Tiago 1:23-25</a>.</p>
                <p>Em Nisto Cremos, graça e lei são vistas como complementares, não excludentes. A lei mostra a vontade de Deus e expõe o pecado; a graça oferece perdão, reconciliação e poder para obedecer. Assim:</p>
                <p>a lei revela o padrão divino;</p>
                <p>a graça restaura o pecador;</p>
                <p>o Espírito Santo escreve a lei no coração;</p>
                <p>a obediência surge como fruto de uma relação redimida com Deus.</p>
                <p>Essa lógica é muito importante no adventismo. A lei sem graça produz condenação e orgulho religioso. A graça sem lei, se mal compreendida, pode ser transformada em permissividade. Mas biblicamente, segundo a leitura adventista, a nova aliança não elimina a lei moral; ela a internaliza. Os textos principais aqui são <a href="#" class="biblical-reference" data-reference="Jeremias 31:31-33">Jeremias 31:31-33</a>, <a href="#" class="biblical-reference" data-reference="Hebreus 8:8-10">Hebreus 8:8-10</a>, <a href="#" class="biblical-reference" data-reference="Romanos 3:31">Romanos 3:31</a>, <a href="#" class="biblical-reference" data-reference="Romanos 8:3-4">Romanos 8:3-4</a> e <a href="#" class="biblical-reference" data-reference="Mateus 5:17-19">Mateus 5:17-19</a>.</p>
                <p>A acusação de legalismo surge porque os adventistas dão grande destaque ao sábado, à saúde, ao estilo de vida e à guarda dos mandamentos. A resposta apologética adventista é que uma ênfase prática em santidade não é legalismo por si só. Torna-se legalismo apenas quando essas coisas são apresentadas como base meritória da salvação. No ensino oficial, isso seria incorreto.</p>
                <p>Dito isso, os próprios adventistas reconhecem historicamente que, em certos momentos e contextos, houve tendências legalistas entre alguns membros e pregadores. Mas isso é visto como desvio da teologia adventista, não como sua essência. O adventismo entende que sua mensagem correta é cristocêntrica: a lei conduz a Cristo, Cristo justifica o pecador, e o Espírito leva o crente à obediência.</p>
                <p>Portanto, para os adventistas:</p>
                <p>graça e lei não são inimigas;</p>
                <p>a graça não cancela a lei moral;</p>
                <p>e a lei não substitui a graça.</p>
            </div>

            <div class="doctrine-item">
                <h4>6.3 A guarda do sábado como "selo de Deus" faz dela uma condição de salvação nos últimos dias?</h4>
                <p>A resposta adventista exige distinção. Em termos absolutos, a salvação continua sendo somente por Cristo, nunca pelo sábado em si. Mas, em termos escatológicos, a IASD ensina que, no conflito final, o sábado terá papel especial como sinal de lealdade a Deus, em contraste com a marca da besta.</p>
                <p>Isso quer dizer que o sábado não é apresentado como um rito salvador, mas como um ponto concreto em que a fidelidade a Deus se torna visível na crise final. A base para isso é a ligação entre:</p>
                <p>o sábado como sinal entre Deus e Seu povo em <a href="#" class="biblical-reference" data-reference="Êxodo 31:13-17">Êxodo 31:13-17</a> e <a href="#" class="biblical-reference" data-reference="Ezequiel 20:12,20">Ezequiel 20:12,20</a>;</p>
                <p>a adoração ao Criador em <a href="#" class="biblical-reference" data-reference="Apocalipse 14:6-7">Apocalipse 14:6-7</a>;</p>
                <p>e a perseverança dos santos em <a href="#" class="biblical-reference" data-reference="Apocalipse 14:12">Apocalipse 14:12</a>.</p>
                <p>Na teologia adventista, quando a verdade é claramente conhecida e a pessoa escolhe conscientemente a rebelião contra Deus, a questão deixa de ser apenas "qual dia guardar?" e passa a ser "a quem obedecer?". Nesse cenário final, o sábado seria um dos pontos centrais da controvérsia entre a autoridade de Deus e a autoridade humana usurpadora.</p>
                <p>Por isso, os adventistas dizem que a guarda do sábado não salva, mas a rejeição consciente da vontade de Deus, quando plenamente compreendida, revela uma atitude de rebelião incompatível com a fé salvadora. Isso é diferente de afirmar que pessoas sinceras, que hoje guardam o domingo sem conhecer a mensagem bíblica sobre o sábado, estejam automaticamente perdidas. A IASD não ensina isso. Pelo contrário, ensina que Deus julga cada pessoa conforme a luz recebida, como se vê em <a href="#" class="biblical-reference" data-reference="Atos 17:30">Atos 17:30</a>, <a href="#" class="biblical-reference" data-reference="Romanos 2:11-16">Romanos 2:11-16</a> e <a href="#" class="biblical-reference" data-reference="João 9:41">João 9:41</a>.</p>
                <p>Apologeticamente, os adventistas procuram evitar dois extremos:</p>
                <p>dizer que o sábado é irrelevante;</p>
                <p>ou dizer que o sábado, por si só, salva.</p>
                <p>A posição oficial é mais específica: no tempo final, em um contexto de plena luz e decisão consciente, o sábado funciona como sinal exterior de uma fidelidade interior. Assim, ele se torna parte do teste final, mas a salvação continua tendo sua base unicamente na justiça de Cristo.</p>
            </div>

            <div class="doctrine-item">
                <h4>6.4 O adventista pode ter certeza da salvação ou pode perder a salvação?</h4>
                <p>Os adventistas ensinam que o crente pode ter plena segurança em Cristo, mas rejeitam a fórmula de segurança incondicional segundo a qual, uma vez salvo, sempre salvo, independentemente da perseverança na fé.</p>
                <p>A segurança da salvação é real no presente. Quem se arrepende, crê em Cristo e permanece nEle pode ter certeza da aceitação divina. Os textos mais usados são <a href="#" class="biblical-reference" data-reference="João 3:16">João 3:16</a>, <a href="#" class="biblical-reference" data-reference="João 5:24">João 5:24</a>, <a href="#" class="biblical-reference" data-reference="Romanos 5:1">Romanos 5:1</a>, <a href="#" class="biblical-reference" data-reference="Romanos 8:1">Romanos 8:1</a>, <a href="#" class="biblical-reference" data-reference="1 João 5:11-13">1 João 5:11-13</a> e <a href="#" class="biblical-reference" data-reference="Hebreus 7:25">Hebreus 7:25</a>. O adventista fiel não precisa viver em terror constante, como se nunca pudesse saber se está em Cristo.</p>
                <p>Por outro lado, a IASD entende que a salvação pode ser abandonada por apostasia, incredulidade persistente e rejeição consciente da graça. Por isso, dá peso aos textos de advertência, como <a href="#" class="biblical-reference" data-reference="Hebreus 3:12-14">Hebreus 3:12-14</a>, <a href="#" class="biblical-reference" data-reference="Hebreus 6:4-6">Hebreus 6:4-6</a>, <a href="#" class="biblical-reference" data-reference="Hebreus 10:26-29">Hebreus 10:26-29</a>, <a href="#" class="biblical-reference" data-reference="2 Pedro 2:20-22">2 Pedro 2:20-22</a>, <a href="#" class="biblical-reference" data-reference="João 15:1-6">João 15:1-6</a> e <a href="#" class="biblical-reference" data-reference="Apocalipse 2:10">Apocalipse 2:10</a>.</p>
                <p>A lógica adventista é relacional: a salvação não é uma coisa armazenada mecanicamente; ela está em Cristo. Enquanto a pessoa permanece em Cristo pela fé, ela está segura. Se deliberadamente rompe essa união, rejeitando a graça, ela se coloca em perigo espiritual real. Portanto:</p>
                <p>há certeza da salvação presente;</p>
                <p>mas não garantia automática independente da perseverança.</p>
                <p>Apologeticamente, os adventistas dizem que essa posição evita dois erros:</p>
                <p>a presunção, que transforma a segurança em licença para negligência espiritual;</p>
                <p>e o desespero, que nega a certeza do amor e do perdão de Deus.</p>
                <p>Assim, o adventista pode dizer: "tenho certeza da salvação em Cristo hoje", sem dizer: "estou salvo de modo incondicional, mesmo que depois eu abandone a fé".</p>
            </div>

            <div class="doctrine-item">
                <h4>6.5 O que os adventistas entendem por "perfeição cristã" e é possível parar completamente de pecar antes da volta de Cristo? O ensinamento do caráter perfeito ensinado por Ellen White não coloca a salvação como dependente do esforço humano?</h4>
                <p>Na teologia adventista, "perfeição cristã" não significa impecabilidade absoluta no sentido metafísico ou igualdade moral com Cristo em natureza. Em geral, significa maturidade espiritual, plena entrega a Deus, crescimento na semelhança de Cristo e fidelidade sincera em toda a luz conhecida.</p>
                <p>Os adventistas costumam entender textos como <a href="#" class="biblical-reference" data-reference="Mateus 5:48">Mateus 5:48</a>, <a href="#" class="biblical-reference" data-reference="Filipenses 3:12-15">Filipenses 3:12-15</a>, <a href="#" class="biblical-reference" data-reference="Colossenses 1:28">Colossenses 1:28</a>, <a href="#" class="biblical-reference" data-reference="Hebreus 6:1">Hebreus 6:1</a> e <a href="#" class="biblical-reference" data-reference="1 Tessalonicenses 5:23">1 Tessalonicenses 5:23</a> dentro dessa ideia de maturidade, integridade e plenitude de consagração. A perfeição cristã, nesse sentido, é real como alvo da santificação, mas sempre dependente da graça de Deus, do ministério de Cristo e da obra do Espírito Santo.</p>
                <p>Sobre a possibilidade de parar completamente de pecar antes da volta de Cristo, há nuances internas no adventismo histórico. O ensino oficial tende a afirmar que Deus é poderoso para conceder vitória real sobre o pecado e que Seu povo é chamado a viver em obediência. Textos como <a href="#" class="biblical-reference" data-reference="Romanos 6">Romanos 6</a>, <a href="#" class="biblical-reference" data-reference="1 João 3:6-9">1 João 3:6-9</a>, <a href="#" class="biblical-reference" data-reference="Judas 24">Judas 24</a> e <a href="#" class="biblical-reference" data-reference="Apocalipse 14:5">Apocalipse 14:5</a> costumam ser citados nesse contexto. Contudo, isso não deve ser entendido como salvação por desempenho humano autônomo.</p>
                <p>A preocupação da pergunta é importante: se se fala em "caráter perfeito", isso não tornaria a salvação dependente do esforço? A resposta adventista oficial deveria ser não. A formação do caráter cristão não é mérito humano independente, mas fruto da união com Cristo. Ellen White, quando corretamente lida dentro do conjunto da teologia adventista, não coloca o pecador como salvando a si mesmo pelo próprio esforço. A ênfase é que o caráter transformado é a evidência da atuação da graça, não a sua causa.</p>
                <p>O problema aparece quando certos discursos populares ou devocionais são lidos de modo isolado e acabam soando perfeccionistas. A formulação teológica mais equilibrada no adventismo insiste em quatro pontos:</p>
                <p>a justificação é sempre pela justiça de Cristo;</p>
                <p>a santificação também é obra da graça;</p>
                <p>o cristão é chamado à vitória real sobre o pecado;</p>
                <p>mas nunca pode reivindicar mérito próprio.</p>
                <p>Em termos apologéticos, os adventistas respondem que a exigência de santidade não é invenção adventista; ela está no Novo Testamento. O erro seria pensar que essa santidade substitui Cristo. Não substitui. O crente é aceito por causa de Cristo e transformado por causa de Cristo.</p>
                <p>Portanto, a resposta mais justa é:</p>
                <p>os adventistas entendem "perfeição cristã" como maturidade, integridade e vitória crescente em Cristo;</p>
                <p>muitos creem que, pela graça, o crente pode viver em obediência real antes da volta de Jesus;</p>
                <p>mas o ensino oficial não deveria transformar isso em salvação por esforço humano;</p>
                <p>qualquer leitura que faça da perfeição uma base meritória de aceitação diante de Deus distorce o centro do evangelho.</p>
            </div>

            <div class="doctrine-item">
                <h4>6.6 Guardar a lei, o sábado e a reforma de saúde é necessário para a salvação, santificação ou apenas como fruto de obediência?</h4>
                <p>Na teologia adventista, esses elementos não são necessários como meio meritório de salvação, mas têm relação importante com a santificação e com os frutos da obediência.</p>
                <p>A lei moral, incluindo o sábado, expressa a vontade de Deus para a vida humana. A reforma de saúde, por sua vez, não é geralmente tratada como requisito para ser justificado diante de Deus, mas como parte de uma vida cristã de mordomia, santidade e cuidado com o corpo. Textos relevantes incluem <a href="#" class="biblical-reference" data-reference="Romanos 12:1-2">Romanos 12:1-2</a>, <a href="#" class="biblical-reference" data-reference="1 Coríntios 6:19-20">1 Coríntios 6:19-20</a>, <a href="#" class="biblical-reference" data-reference="1 Coríntios 10:31">1 Coríntios 10:31</a>, <a href="#" class="biblical-reference" data-reference="3 João 2">3 João 2</a>, <a href="#" class="biblical-reference" data-reference="Êxodo 20:8-11">Êxodo 20:8-11</a>, <a href="#" class="biblical-reference" data-reference="Isaías 58:13-14">Isaías 58:13-14</a> e <a href="#" class="biblical-reference" data-reference="João 14:15">João 14:15</a>.</p>
                <p>A distinção correta, para os adventistas, é esta:</p>
                <p>salvação: somente pela graça, mediante a fé;</p>
                <p>santificação: vida transformada pelo Espírito;</p>
                <p>obediência: fruto visível dessa transformação.</p>
                <p>Assim, guardar a lei e o sábado pertence claramente ao campo da obediência cristã. Já a reforma de saúde pertence ao campo da mordomia e do estilo de vida consagrado. Nenhuma dessas práticas compra perdão, remove culpa ou substitui a obra expiatória de Cristo.</p>
                <p>No entanto, os adventistas também dizem que a santificação não é opcional. Quem foi salvo é chamado a viver de modo coerente com a vontade de Deus. Isso significa que lei, sábado e princípios de saúde não são irrelevantes. Eles não são "extras sem importância"; são áreas concretas da vida em que a fé se expressa.</p>
                <p>Apologeticamente, a melhor formulação adventista é:</p>
                <p>não são a raiz da salvação;</p>
                <p>são frutos da salvação;</p>
                <p>e participam do processo de santificação como expressões práticas de fidelidade.</p>
                <p>Se alguém transformá-los em condição meritória de aceitação diante de Deus, isso é legalismo. Se alguém os tratar como irrelevantes para a vida cristã, isso seria desobediência ou graça barata. O adventismo procura, ao menos em sua formulação oficial, manter o equilíbrio entre esses dois erros.</p>
            </div>

            <div class="doctrine-item">
                <h4>6.7 Como os adventistas entendem <a href="#" class="biblical-reference" data-reference="Gálatas 3:19">Gálatas 3:19</a>, que diz que a lei foi acrescentada por causa das transgressões?</h4>
                <p>Os adventistas entendem <a href="#" class="biblical-reference" data-reference="Gálatas 3:19">Gálatas 3:19</a> dentro do argumento mais amplo de Paulo contra a justificação pelas obras da lei. O texto diz que a lei foi "acrescentada por causa das transgressões, até que viesse o Descendente". A interpretação adventista procura distinguir entre diferentes funções da lei e entre diferentes aspectos da legislação dada a Israel.</p>
                <p>Em primeiro lugar, os adventistas não entendem esse verso como prova de que a lei moral dos Dez Mandamentos tenha sido abolida. Em vez disso, veem Paulo dizendo que a lei teve papel especial de:</p>
                <p>tornar o pecado mais evidente;</p>
                <p>expor a transgressão;</p>
                <p>conduzir o pecador à necessidade de Cristo.</p>
                <p>Isso combina com <a href="#" class="biblical-reference" data-reference="Romanos 3:20">Romanos 3:20</a>, <a href="#" class="biblical-reference" data-reference="Romanos 5:20">Romanos 5:20</a>, <a href="#" class="biblical-reference" data-reference="Romanos 7:7">Romanos 7:7</a> e <a href="#" class="biblical-reference" data-reference="Gálatas 3:24">Gálatas 3:24</a>. A lei, nesse sentido, foi "acrescentada" para lidar com a condição pecaminosa do povo, revelando objetivamente a vontade divina e mostrando a gravidade do pecado.</p>
                <p>Em segundo lugar, muitos adventistas distinguem aqui entre a lei moral permanente e o sistema cerimonial associado ao santuário, sacrifícios e sombras messiânicas. Esse sistema, de fato, tinha função temporária e apontava para Cristo, como se vê em <a href="#" class="biblical-reference" data-reference="Colossenses 2:14-17">Colossenses 2:14-17</a>, Hebreus 9 e <a href="#" class="biblical-reference" data-reference="Hebreus 10:1">Hebreus 10:1</a>. Assim, o aspecto pedagógico e provisório da lei em Gálatas é frequentemente aplicado, em grande parte, à economia mosaica como administração histórica voltada para conduzir a Cristo.</p>
                <p>Ao mesmo tempo, a IASD não diria que a lei moral começou no Sinai, como se antes disso não houvesse padrão moral divino. O próprio conceito de "transgressão" pressupõe lei. Por isso, "foi acrescentada" não significa necessariamente "foi criada do nada pela primeira vez", mas que foi formalmente dada, explicitada e aplicada de modo especial na história da aliança.</p>
                <p>Apologeticamente, os adventistas respondem que Gálatas combate o uso errado da lei como caminho de justificação, não a validade da lei moral como expressão do caráter de Deus. Paulo não está ensinando que o evangelho elimina a obediência, mas que a lei não pode substituir a promessa nem a fé em Cristo.</p>
                <p>Portanto, a leitura adventista de <a href="#" class="biblical-reference" data-reference="Gálatas 3:19">Gálatas 3:19</a> é esta:</p>
                <p>a lei foi dada de forma especial por causa do pecado humano;</p>
                <p>para revelar a transgressão e conduzir a Cristo;</p>
                <p>o sistema cerimonial tinha função temporária até a vinda de Cristo;</p>
                <p>mas a lei moral não foi anulada como padrão divino de vida.</p>
            </div>
        </div>

        <div id="escatologia" class="sub-tab-content">
            <h2 class="sub-section-title">7. ESCATOLOGIA, MARCA DA BESTA E EVENTOS FINAIS</h2>

            <div class="doctrine-item">
                <h4>7.1 O que é a "marca da besta" segundo os adventistas (Apocalipse 13)? Por que a Igreja relaciona isso à observância do domingo?</h4>
                <p>Para os adventistas, a marca da besta não é um chip físico ou um código de barras, mas um símbolo de lealdade espiritual e autoridade. Segundo o Nisto Cremos, ela representa a aceitação da autoridade de um poder religioso apóstata em oposição direta à autoridade de Deus. A Igreja relaciona isso ao domingo porque identifica o sábado como o selo de Deus (o sinal bíblico de Sua autoridade como Criador).</p>
                <p>A lógica é que, no conflito final, a questão central será a adoração. O quarto mandamento da lei de Deus (o sábado) é o único que identifica o Legislador pelo Seu nome, título (Criador) e jurisdição (Céu e Terra). Quando um poder humano (a besta) tenta mudar essa lei e impõe o domingo como dia de culto obrigatório por decreto, a observância do domingo torna-se o sinal de submissão a esse poder humano, em vez de a Deus. Referências: <a href="#" class="biblical-reference" data-reference="Apocalipse 13:16-17">Apocalipse 13:16-17</a>, <a href="#" class="biblical-reference" data-reference="Apocalipse 14:9-11">Apocalipse 14:9-11</a>, <a href="#" class="biblical-reference" data-reference="Êxodo 20:8-11">Êxodo 20:8-11</a>, <a href="#" class="biblical-reference" data-reference="Ezequiel 20:12">Ezequiel 20:12</a>, <a href="#" class="biblical-reference" data-reference="Ezequiel 20:20">20</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>7.2 Se sábado e domingo não aparecem explicitamente em Apocalipse 13, como a IASD faz essa ligação? Os adventistas ensinam que a imposição do domingo como dia de culto obrigatório será a "Marca da Besta". Existe base bíblica para isso?</h4>
                <p>A ligação é feita através da exegese comparativa e da história profética. Embora os nomes "sábado" e "domingo" não estejam em Apocalipse 13, a mensagem do primeiro anjo em <a href="#" class="biblical-reference" data-reference="Apocalipse 14:7">Apocalipse 14:7</a> convida a adorar "Aquele que fez o céu, e a terra, e o mar", uma citação direta do mandamento do sábado (<a href="#" class="biblical-reference" data-reference="Êxodo 20:11">Êxodo 20:11</a>). Isso coloca o sábado no centro do conflito final sobre adoração.</p>
                <p>A base bíblica para relacionar a marca à mudança da lei está em <a href="#" class="biblical-reference" data-reference="Daniel 7:25">Daniel 7:25</a>, que profetiza um poder que cuidaria em "mudar os tempos e a lei". Como o sábado é o único mandamento que lida com o "tempo" da adoração, a IASD entende que a substituição do sábado pelo domingo é a maior evidência dessa tentativa de alteração da autoridade divina. A "marca" ocorre quando essa substituição é imposta por lei, forçando uma escolha entre a lei de Deus e a lei dos homens. Referências: <a href="#" class="biblical-reference" data-reference="Daniel 7:25">Daniel 7:25</a>, <a href="#" class="biblical-reference" data-reference="Atos 5:29">Atos 5:29</a>, <a href="#" class="biblical-reference" data-reference="Apocalipse 14:12">Apocalipse 14:12</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>7.3 A IASD ensina que haverá uma lei ou decreto dominical mundial de perseguição (civil/religiosa) dos guardadores do sábado?</h4>
                <p>Sim. Com base em <a href="#" class="biblical-reference" data-reference="Apocalipse 13:15-17">Apocalipse 13:15-17</a>, os adventistas ensinam que haverá um tempo de angústia em que os poderes da Terra se unirão para impor a observância do domingo. Aqueles que se recusarem a obedecer a esse decreto humano para permanecerem fiéis ao sábado de Deus enfrentarão sanções econômicas ("não poder comprar nem vender") e, por fim, um decreto de morte. Esse evento é visto como o clímax do Grande Conflito entre Cristo e Satanás. Referências: <a href="#" class="biblical-reference" data-reference="Apocalipse 13:15-17">Apocalipse 13:15-17</a>, <a href="#" class="biblical-reference" data-reference="Daniel 12:1">Daniel 12:1</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>7.4 Quem são a besta que sobe do mar, a besta que sobe da terra, a imagem da besta e o falso profeta na interpretação adventista?</h4>
                <p><strong>Besta que sobe do mar (Ap 13:1-10):</strong> Representa o Papado (o sistema romano), que uniu igreja e estado e dominou durante a Idade Média (os 1260 anos de <a href="#" class="biblical-reference" data-reference="Daniel 7:25">Daniel 7:25</a>).</p>
                <p><strong>Besta que sobe da terra (Ap 13:11-18):</strong> Representa os Estados Unidos da América, que surgem em um território despovoado ("da terra"), com princípios cristãos e democráticos ("dois chifres como de cordeiro"), mas que acabará falando "como dragão" ao impor decretos religiosos.</p>
                <p><strong>Imagem da besta:</strong> É a união de igrejas protestantes apóstatas com o estado para impor dogmas religiosos por força civil, replicando o modelo de perseguição da Idade Média.</p>
                <p><strong>Falso profeta:</strong> Identificado como o protestantismo apostatado que, unido ao espiritismo e ao poder civil, realiza sinais para enganar o mundo. Referências: Apocalipse 13, <a href="#" class="biblical-reference" data-reference="Apocalipse 16:13">Apocalipse 16:13</a>, <a href="#" class="biblical-reference" data-reference="2 Tessalonicenses 2:3-4">2 Tessalonicenses 2:3-4</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>7.5 A interpretação adventista de Apocalipse 13 é anticatólica? O papado é o anticristo nessa visão?</h4>
                <p>A interpretação foca em sistemas e instituições, não em indivíduos. Os adventistas identificam o Papado como o sistema do "homem do pecado" ou "anticristo" profético devido às suas pretensões de autoridade divina e mudança da lei de Deus. No entanto, o Nisto Cremos enfatiza que há cristãos sinceros em todas as denominações, incluindo a Igreja Católica, e que o julgamento final é sobre a lealdade à luz que cada um recebeu. A mensagem não é de ódio, mas de advertência profética sobre sistemas de autoridade. Referências: <a href="#" class="biblical-reference" data-reference="2 Tessalonicenses 2:3-12">2 Tessalonicenses 2:3-12</a>, <a href="#" class="biblical-reference" data-reference="Daniel 7:25">Daniel 7:25</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>7.6 O que significa o "selo de Deus" em contraste com a marca da besta?</h4>
                <p>O selo de Deus é o sinal de propriedade e proteção divina. Biblicamente, o selo contém o nome, o título e o território do soberano. O sábado é identificado como o selo da lei de Deus porque contém esses elementos (<a href="#" class="biblical-reference" data-reference="Êxodo 20:11">Êxodo 20:11</a>). Enquanto a marca da besta representa a lealdade a uma autoridade humana, o selo de Deus representa a lealdade ao Criador e a aceitação da Sua lei no coração pelo Espírito Santo. Referências: <a href="#" class="biblical-reference" data-reference="Ezequiel 20:12">Ezequiel 20:12</a>, <a href="#" class="biblical-reference" data-reference="Apocalipse 7:2-3">Apocalipse 7:2-3</a>, <a href="#" class="biblical-reference" data-reference="Efésios 4:30">Efésios 4:30</a></p>
            </div>

            <div class="doctrine-item">
                <h4>7.7 O que significa "não poder comprar nem vender" em <a href="#" class="biblical-reference" data-reference="Apocalipse 13:17">Apocalipse 13:17</a> na interpretação adventista?</h4>
                <p>Significa um boicote econômico total imposto contra aqueles que se recusarem a aceitar a marca da besta (a observância compulsória do domingo). É uma forma de pressão civil e econômica para forçar a conformidade religiosa antes que o decreto de morte final seja emitido. Referências: <a href="#" class="biblical-reference" data-reference="Apocalipse 13:17">Apocalipse 13:17</a></p>
            </div>

            <div class="doctrine-item">
                <h4>7.8 O que acontece durante o milênio segundo a escatologia adventista? Os justos estarão no céu e a Terra ficará deserta?</h4>
                <p>Sim. Durante o milênio:</p>
                <p>Os justos estarão no céu com Cristo, participando do julgamento dos ímpios e dos anjos caídos.</p>
                <p>A Terra ficará deserta, em um estado de caos e desolação (como o abismo original de <a href="#" class="biblical-reference" data-reference="Gênesis 1:2">Gênesis 1:2</a>), sem habitantes humanos vivos.</p>
                <p>Satanás e seus anjos ficarão presos na Terra por uma "corrente de circunstâncias", pois não terão ninguém para enganar. Referências: <a href="#" class="biblical-reference" data-reference="Apocalipse 20:1-6">Apocalipse 20:1-6</a>, <a href="#" class="biblical-reference" data-reference="1 Coríntios 6:2-3">1 Coríntios 6:2-3</a>, <a href="#" class="biblical-reference" data-reference="Jeremias 4:23-26">Jeremias 4:23-26</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>7.9 Qual é o papel de Satanás durante o milênio e por que ele é relacionado ao bode emissário de Levítico 16?</h4>
                <p>Satanás é "preso" na Terra desolada. Ele é relacionado ao bode emissário (Azazel) porque, no Dia da Expiação Levítico 16, após o santuário ser purificado pelo sangue do sacrifício, os pecados confessados eram colocados sobre o bode emissário, que era levado ao deserto. Da mesma forma, após Cristo completar a expiação no santuário celestial, Ele colocará a responsabilidade final por todos os pecados sobre Satanás (o originador do mal), que será banido para a Terra desolada durante o milênio. Referências: <a href="#" class="biblical-reference" data-reference="Levítico 16:8-10">Levítico 16:8-10</a>, <a href="#" class="biblical-reference" data-reference="Levítico 16:20-22">Levítico 16:20-22</a>, <a href="#" class="biblical-reference" data-reference="Apocalipse 20:1-3">Apocalipse 20:1-3</a></p>
            </div>

            <div class="doctrine-item">
                <h4>7.10 É verdade que os adventistas ensinam que o bode emissário (Azazel) no Dia da Expiação representa Satanás em Levítico 16? Isso não faria de Satanás um co-salvador que carrega nossos pecados?</h4>
                <p>Sim, os adventistas ensinam que Azazel representa Satanás. No entanto, isso não faz dele um co-salvador. O Nisto Cremos é enfático: Cristo realizou a expiação completa pelo pecado na cruz. Satanás não morre pelos nossos pecados para nos salvar; ele carrega a culpa e a punição final como o instigador do pecado. Assim como um criminoso é responsável por induzir outros ao crime, Satanás é punido por ter levado a humanidade a pecar. A salvação vem apenas do "Bode para o Senhor" (Cristo). Referências: <a href="#" class="biblical-reference" data-reference="Levítico 16:8">Levítico 16:8</a>, Hebreus 9:22-28</p>
            </div>

            <div class="doctrine-item">
                <h4>7.11 Os adventistas acreditam em uma grande tribulação antes da volta de Cristo? Qual é a sequência dos eventos finais?</h4>
                <p>Sim, baseados em <a href="#" class="biblical-reference" data-reference="Daniel 12:1">Daniel 12:1</a> e <a href="#" class="biblical-reference" data-reference="Mateus 24:21">Mateus 24:21</a>. A sequência é:</p>
                <p>Proclamação das Três Mensagens Angélicas.</p>
                <p>Fechamento da porta da graça (fim da intercessão de Cristo).</p>
                <p>As Sete Pragas (Grande Tribulação).</p>
                <p>Decreto de morte contra os fiéis.</p>
                <p>Segunda Vinda de Cristo. Referências: <a href="#" class="biblical-reference" data-reference="Apocalipse 15-16">Apocalipse 15 e 16</a>, <a href="#" class="biblical-reference" data-reference="1 Tessalonicenses 4:16-17">1 Tessalonicenses 4:16-17</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>7.12 Como os adventistas interpretam as três mensagens angélicas de <a href="#" class="biblical-reference" data-reference="Apocalipse 14:6">Apocalipse 14:6</a>–12?</h4>
                <p><strong>Primeira Mensagem:</strong> Chamado para temer a Deus, dar-Lhe glória e adorar o Criador (restauração do sábado), pois a hora do Seu juízo chegou.</p>
                <p><strong>Segunda Mensagem:</strong> Anúncio da queda de Babilônia (sistemas religiosos confusos e apóstatas).</p>
                <p><strong>Terceira Mensagem:</strong> Aviso contra a adoração da besta e o recebimento de sua marca, destacando a perseverança daqueles que guardam os mandamentos de Deus. Referências: <a href="#" class="biblical-reference" data-reference="Apocalipse 14:6">Apocalipse 14:6</a>-12</p>
            </div>

            <div class="doctrine-item">
                <h4>7.13 Quem são os 144.000 no Apocalipse? São literais ou simbólicos?</h4>
                <p>A interpretação predominante é que o número é simbólico, representando a totalidade do povo de Deus que estará vivo na Terra para enfrentar a crise final sem passar pela morte antes da vinda de Cristo. Eles são caracterizados pela fidelidade absoluta e por terem o selo de Deus. Referências: <a href="#" class="biblical-reference" data-reference="Apocalipse 7:4-8">Apocalipse 7:4-8</a>, <a href="#" class="biblical-reference" data-reference="Apocalipse 14:1-5">Apocalipse 14:1-5</a></p>
            </div>

            <div class="doctrine-item">
                <h4>7.14 Os adventistas rejeitam o arrebatamento secreto? Como entendem a segunda vinda de Cristo?</h4>
                <p>Sim, a IASD rejeita o arrebatamento secreto. A segunda vinda de Cristo será literal, pessoal, visível e audível. "Todo olho O verá". Não haverá uma vinda "invisível" em duas etapas. Referências: <a href="#" class="biblical-reference" data-reference="Apocalipse 1:7">Apocalipse 1:7</a>, <a href="#" class="biblical-reference" data-reference="Mateus 24:27">Mateus 24:27</a>, <a href="#" class="biblical-reference" data-reference="Mateus 24:30-31">Mateus 24:30-31</a>, <a href="#" class="biblical-reference" data-reference="1 Tessalonicenses 4:16-17">1 Tessalonicenses 4:16-17</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>7.15 O que acontece com salvos e perdidos na segunda vinda?</h4>
                <p><strong>Salvos mortos:</strong> Ressuscitam (primeira ressurreição).</p>
                <p><strong>Salvos vivos:</strong> São transformados e arrebatados para encontrar o Senhor nos ares.</p>
                <p><strong>Ímpios vivos:</strong> São destruídos pelo resplendor da vinda de Cristo.</p>
                <p><strong>Ímpios mortos:</strong> Permanecem na sepultura até o fim do milênio. Referências: <a href="#" class="biblical-reference" data-reference="1 Tessalonicenses 4:16-17">1 Tessalonicenses 4:16-17</a>, <a href="#" class="biblical-reference" data-reference="2 Tessalonicenses 2:8">2 Tessalonicenses 2:8</a>, <a href="#" class="biblical-reference" data-reference="Apocalipse 20:5">Apocalipse 20:5</a></p>
            </div>

            <div class="doctrine-item">
                <h4>7.16 Como os adventistas lidam com previsões de datas, se ninguém sabe o dia nem a hora?</h4>
                <p>A Igreja Adventista proíbe a marcação de datas para a volta de Cristo, baseada em <a href="#" class="biblical-reference" data-reference="Mateus 24:36">Mateus 24:36</a>. Embora estudem os "sinais dos tempos" para saber que a vinda está próxima, qualquer tentativa de marcar dia ou hora é considerada antibíblica. Referências: <a href="#" class="biblical-reference" data-reference="Marcos 13:32">Marcos 13:32</a>, <a href="#" class="biblical-reference" data-reference="Atos 1:7">Atos 1:7</a></p>
            </div>

            <div class="doctrine-item">
                <h4>7.17 Por que Jesus ainda não voltou, se tanto tempo já passou?</h4>
                <p>Os adventistas entendem que Deus é paciente, não querendo que ninguém pereça (<a href="#" class="biblical-reference" data-reference="2 Pedro 3:9">2 Pedro 3:9</a>). A demora também é relacionada à necessidade de o evangelho ser pregado em todo o mundo como testemunho (<a href="#" class="biblical-reference" data-reference="Mateus 24:14">Mateus 24:14</a>) e ao amadurecimento do caráter do Seu povo. Referências: <a href="#" class="biblical-reference" data-reference="2 Pedro 3:8-12">2 Pedro 3:8-12</a>, <a href="#" class="biblical-reference" data-reference="Apocalipse 14:14-15">Apocalipse 14:14-15</a></p>
            </div>

            <div class="doctrine-item">
                <h4>7.18 O que é a visão adventista do Grande Conflito entre Cristo e Satanás?</h4>
                <p>É a moldura teológica que explica a origem do mal como uma rebelião de Lúcifer no céu contra o governo e a lei de Deus. A Terra tornou-se o palco onde o caráter de Deus (amor e justiça) está sendo vindicado perante o universo. A história da salvação é o processo de Deus resolver esse conflito preservando o livre-arbítrio. Referências: <a href="#" class="biblical-reference" data-reference="Apocalipse 12:7-9">Apocalipse 12:7-9</a>, <a href="#" class="biblical-reference" data-reference="Isaías 14:12-14">Isaías 14:12-14</a>, <a href="#" class="biblical-reference" data-reference="Ezequiel 28:12-17">Ezequiel 28:12-17</a></p>
            </div>
        </div>

        <div id="igreja-remanescente" class="sub-tab-content">
            <h2 class="sub-section-title">8. IGREJA REMANESCENTE, IDENTIDADE E RELAÇÃO COM OUTRAS IGREJAS</h2>

            <div class="doctrine-item">
                <h4>8.1 A Igreja Adventista se considera a única igreja verdadeira ou a igreja remanescente exclusiva de <a href="#" class="biblical-reference" data-reference="Apocalipse 12:17">Apocalipse 12:17</a>?</h4>
                <p>A IASD se identifica como a igreja remanescente profética, mas isso não significa que ela seja a "única igreja verdadeira" no sentido de que apenas seus membros serão salvos. O remanescente é um movimento com uma missão específica: restaurar as verdades esquecidas e proclamar as mensagens finais. Deus tem um "povo invisível" em todas as denominações. Referências: <a href="#" class="biblical-reference" data-reference="Apocalipse 12:17">Apocalipse 12:17</a>, <a href="#" class="biblical-reference" data-reference="João 10:16">João 10:16</a></p>
            </div>

            <div class="doctrine-item">
                <h4>8.2 Pessoas de outras igrejas ou religiões (católicos, evangélicos, espíritas, etc) também podem ser salvas segundo a doutrina adventista?</h4>
                <p>Sim. Os adventistas acreditam que Deus julga as pessoas de acordo com a luz que receberam e a sinceridade de seu coração. Muitos que nunca ouviram a mensagem adventista, mas viveram fielmente segundo o que conheciam da vontade de Deus, serão salvos. Referências: <a href="#" class="biblical-reference" data-reference="Romanos 2:14-16">Romanos 2:14-16</a>, <a href="#" class="biblical-reference" data-reference="Atos 17:30">Atos 17:30</a></p>
            </div>

            <div class="doctrine-item">
                <h4>8.3 A doutrina do remanescente não é exclusivista e contrária à unidade do corpo de Cristo descrita em <a href="#" class="biblical-reference" data-reference="1 Coríntios 12">1 Coríntios 12</a>?</h4>
                <p>Não, pois a unidade bíblica é baseada na verdade e no Espírito. O remanescente é chamado para unir o corpo de Cristo em torno da "fé que uma vez foi entregue aos santos". A exclusividade é da mensagem, não das pessoas. O convite é inclusivo: "sai dela, povo meu". Referências: <a href="#" class="biblical-reference" data-reference="Efésios 4:13">Efésios 4:13</a>, <a href="#" class="biblical-reference" data-reference="Apocalipse 18:4">Apocalipse 18:4</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>8.4 Se guardar o sábado é central ao remanescente, por que outros grupos sabatistas tais como os Batistas do Sétimo Dia não são reconhecidos da mesma forma?</h4>
                <p>Porque a identidade do remanescente em Apocalipse envolve não apenas os mandamentos, mas também o "testemunho de Jesus" (identificado como o Espírito de Profecia) e a missão específica de proclamar as Três Mensagens Angélicas em um contexto de juízo. Outros grupos podem guardar o sábado, mas não compartilham da totalidade da missão profética adventista. Referências: <a href="#" class="biblical-reference" data-reference="Apocalipse 12:17">Apocalipse 12:17</a>, <a href="#" class="biblical-reference" data-reference="Apocalipse 19:10">Apocalipse 19:10</a></p>
            </div>

            <div class="doctrine-item">
                <h4>8.5 O que significa "Babilônia" para a IASD no Apocalipse 14 em relação às outras igrejas cristãs?</h4>
                <p>Babilônia representa a confusão religiosa e a apostasia. Refere-se a organizações religiosas que rejeitaram as verdades bíblicas em favor de tradições humanas (como a imortalidade da alma e a santidade do domingo). O chamado é para que os filhos de Deus saiam desses sistemas antes que as pragas caiam. Referências: <a href="#" class="biblical-reference" data-reference="Apocalipse 14:8">Apocalipse 14:8</a>, <a href="#" class="biblical-reference" data-reference="Apocalipse 18:1-4">Apocalipse 18:1-4</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>8.6 Por que a Igreja Adventista geralmente não participa do movimento ecumênico?</h4>
                <p>Porque o ecumenismo muitas vezes busca a unidade através do compromisso doutrinário (esquecendo as diferenças bíblicas em nome da paz social). Os adventistas acreditam que a verdadeira unidade deve ser baseada na obediência à Palavra de Deus, e não em alianças políticas ou religiosas que obscureçam as verdades do tempo do fim. Referências: <a href="#" class="biblical-reference" data-reference="2 Coríntios 6:14-17">2 Coríntios 6:14-17</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>8.7 A IASD é classificada por alguns teólogos de outras denominações como uma "seita"?</h4>
                <p>Embora no passado tenha sido rotulada assim por causa de doutrinas como o sábado e o santuário, a maioria dos teólogos evangélicos contemporâneos reconhece a IASD como uma denominação cristã legítima, pois ela sustenta as doutrinas fundamentais do cristianismo (Trindade, divindade de Cristo, salvação pela graça, ressurreição). Referências: Crenças Fundamentais 1-5.</p>
            </div>

            <div class="doctrine-item">
                <h4>8.8 Os adventistas são evangélicos/protestantes ou possuem identidade teológica própria? As crenças adventistas são compatíveis com o protestantismo histórico?</h4>
                <p>Os adventistas são protestantes e evangélicos, herdeiros da Reforma (Sola Scriptura, Sola Fide). No entanto, possuem uma identidade teológica própria ("o evangelho eterno" no contexto do juízo), que eles veem como a conclusão da Reforma que foi interrompida. Referências: <a href="#" class="biblical-reference" data-reference="Apocalipse 14:6">Apocalipse 14:6</a>.</p>
            </div>

            <div class="doctrine-item">
                <h4>8.9 A igreja mudou doutrinas ao longo do tempo (ex.: sobre Trindade, expiação ou natureza de Cristo)?</h4>
                <p>Houve um amadurecimento e clarificação teológica. No início, alguns pioneiros tinham visões antitrinitarianas, mas através do estudo bíblico sistemático, a igreja consolidou sua posição trinitariana e cristocêntrica plena, refletida nas 28 Crenças Fundamentais. Não foi uma "mudança de verdade", mas uma compreensão mais profunda da revelação bíblica. Referências: Introdução ao Nisto Cremos.</p>
            </div>

            <div class="doctrine-item">
                <h4>8.10 As 28 Crenças Fundamentais dos adventistas podem ser revistas por assembleias humanas sem comprometer a autoridade bíblica, ou seja, se a Bíblia é o único credo?</h4>
                <p>Sim. O preâmbulo das Crenças Fundamentais afirma que a Bíblia é o único credo e que estas declarações podem ser revisadas em assembleias da Associação Geral sempre que a igreja, sob a guia do Espírito Santo, alcançar uma compreensão mais completa da verdade bíblica. Isso demonstra que a autoridade final é sempre a Bíblia, não o documento humano. Referências: Preâmbulo das 28 Crenças Fundamentais.</p>
            </div>
        </div>

        <div id="trindade-deus" class="sub-tab-content">
            <h2 class="sub-section-title">9. TRINDADE, DEUS E CRISTOLOGIA</h2>

            <div class="doctrine-item">
                <h4>9.1 A Igreja Adventista acredita na doutrina da Trindade (Pai, Filho e Espírito Santo como um só Deus) da mesma forma que outros cristãos evangélicos?</h4>
                <p>Sim, a Igreja Adventista do Sétimo Dia crê na Trindade. A posição oficial da IASD é claramente trinitariana: há um só Deus, existente em três Pessoas coeternas — Pai, Filho e Espírito Santo. Isso aparece de forma explícita no Nisto Cremos, ao afirmar: "Há um só Deus: Pai, Filho e Espírito Santo, uma unidade de três Pessoas coeternas."</p>
                <p>Biblicamente, essa compreensão se apoia em várias passagens:</p>
                <p><a href="#" class="biblical-reference" data-reference="Mateus 28:19">Mateus 28:19</a> — Jesus manda batizar "em nome do Pai, e do Filho, e do Espírito Santo".</p>
                <p><a href="#" class="biblical-reference" data-reference="2 Coríntios 13:13">2 Coríntios 13:13</a> — Paulo reúne as três Pessoas na bênção apostólica.</p>
                <p><a href="#" class="biblical-reference" data-reference="Efésios 4:4-6">Efésios 4:4-6</a> — um Espírito, um Senhor, um Deus e Pai.</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Pedro 1:2">1 Pedro 1:2</a> — eleição segundo a presciência do Pai, santificação do Espírito, obediência a Jesus Cristo.</p>
                <p>A IASD também crê, como os cristãos evangélicos históricos, que:</p>
                <p>o Pai é Deus;</p>
                <p>o Filho é Deus;</p>
                <p>o Espírito Santo é Deus;</p>
                <p>e, ainda assim, não há três deuses, mas um só Deus.</p>
                <p>Isso não significa confusão entre as Pessoas. O Pai não é o Filho, o Filho não é o Espírito, e o Espírito não é o Pai. Eles são distintos em pessoa, mas um em essência, propósito, caráter e divindade.</p>
                <p>Onde os adventistas se aproximam fortemente dos evangélicos clássicos? Na afirmação de que:</p>
                <p>Jesus é plenamente Deus (<a href="#" class="biblical-reference" data-reference="João 1:1-3">João 1:1-3</a>, <a href="#" class="biblical-reference" data-reference="João 1:14">14</a>; <a href="#" class="biblical-reference" data-reference="Colossenses 2:9">Colossenses 2:9</a>);</p>
                <p>o Espírito Santo é Pessoa divina, não mera força impessoal (<a href="#" class="biblical-reference" data-reference="João 14:16-17">João 14:16-17</a>, <a href="#" class="biblical-reference" data-reference="João 14:26">26</a>; <a href="#" class="biblical-reference" data-reference="João 16:13-14">João 16:13-14</a>; <a href="#" class="biblical-reference" data-reference="Atos 5:3-4">Atos 5:3-4</a>).</p>
                <p>Portanto, sim, os adventistas creem na Trindade em sentido real e ortodoxo. Se houver diferença em relação a certos grupos evangélicos, normalmente não está no fato de crer ou não crer na Trindade, mas na forma de explicar alguns pontos bíblicos ou históricos. Mas, em essência, a IASD é trinitariana.</p>
            </div>

            <div class="doctrine-item">
                <h4>9.2 É verdade que a doutrina adventista da Trindade mudou ao longo da história?</h4>
                <p>Sim, é verdade que houve desenvolvimento histórico na compreensão adventista sobre esse ponto. Mas isso precisa ser explicado com cuidado.</p>
                <p>Os primeiros adventistas do século 19 vieram de ambientes protestantes variados. Alguns pioneiros tinham reservas contra formulações tradicionais da Trindade, especialmente porque viam em certas expressões pós-bíblicas um risco de confusão filosófica ou de linguagem não diretamente bíblica. Em muitos casos, a preocupação deles não era negar a divindade de Cristo, mas evitar formulações que julgavam abstratas ou ligadas a credos impostos.</p>
                <p>Com o tempo, porém, o estudo mais profundo das Escrituras levou a Igreja Adventista a formular de modo claro sua posição oficial: um só Deus em três Pessoas coeternas. O próprio Nisto Cremos mostra esse amadurecimento ao apresentar a Trindade como crença fundamental da igreja.</p>
                <p>Isso não deve ser visto, necessariamente, como uma "traição" da fé original, mas como um desenvolvimento doutrinário. Os adventistas sempre insistiram que a Bíblia é o único credo e que sua compreensão pode ser refinada à medida que a igreja estuda a Palavra de Deus com mais profundidade. O próprio livro destaca que as crenças fundamentais são a compreensão da igreja sobre o ensino das Escrituras, e não um credo imutável no sentido humano e fechado.</p>
                <p>Em termos pastorais, isso mostra duas coisas:</p>
                <p>a IASD não afirma infalibilidade histórica de todos os pioneiros em cada detalhe;</p>
                <p>a autoridade final está na Escritura, não nas opiniões iniciais de qualquer líder humano.</p>
                <p>Textos relevantes para esse princípio:</p>
                <p><a href="#" class="biblical-reference" data-reference="Provérbios 4:18">Provérbios 4:18</a> — "a vereda dos justos é como a luz da aurora".</p>
                <p><a href="#" class="biblical-reference" data-reference="João 16:13">João 16:13</a> — o Espírito guia "a toda a verdade".</p>
                <p><a href="#" class="biblical-reference" data-reference="Atos 15">Atos 15</a> — a própria igreja apostólica precisou amadurecer a compreensão de certas questões doutrinárias.</p>
                <p>Então, sim, houve mudança histórica de formulação e consolidação. Mas a resposta oficial adventista hoje é inequívoca: a IASD é trinitariana.</p>
            </div>

            <div class="doctrine-item">
                <h4>9.3 A compreensão adventista da Trindade é igual à formulação "nicena" (católicos/evangélicos) ou possui diferenças?</h4>
                <p>A resposta mais correta é: há ampla convergência, mas a ênfase adventista é mais bíblica do que credal-filosófica.</p>
                <p>A IASD concorda com o núcleo da formulação nicena no que importa essencialmente:</p>
                <p>há um só Deus;</p>
                <p>o Pai, o Filho e o Espírito Santo são plenamente divinos;</p>
                <p>o Filho não é criatura;</p>
                <p>o Espírito Santo não é mera energia;</p>
                <p>as três Pessoas são coeternas.</p>
                <p>Nesse sentido, a proximidade com o cristianismo niceno é real.</p>
                <p>Mas os adventistas, de forma geral, preferem linguagem bíblica e evitam transformar fórmulas conciliares em autoridade suprema. A autoridade final, para a IASD, não é Nicéia em si, mas a Bíblia. O Nisto Cremos deixa claro esse princípio: as crenças da igreja são aceitas porque são entendidas como ensino das Escrituras.</p>
                <p>Assim, a diferença principal não é tanto de conteúdo essencial, mas de fundamentação e ênfase:</p>
                <p>a IASD não baseia sua fé em tradição eclesiástica;</p>
                <p>a IASD procura formular a doutrina a partir do texto bíblico;</p>
                <p>a IASD evita especulações filosóficas sobre a natureza interna de Deus além do que foi revelado.</p>
                <p>Por isso, o adventista pode dizer sem problema que crê em um Deus triúno, mas normalmente desejará acrescentar: porque a Escritura revela o Pai, o Filho e o Espírito Santo como divinos e coeternos.</p>
                <p>Passagens centrais:</p>
                <p><a href="#" class="biblical-reference" data-reference="Deuteronômio 6:4">Deuteronômio 6:4</a> — um só Deus.</p>
                <p><a href="#" class="biblical-reference" data-reference="João 1:1-3">João 1:1-3</a> — o Verbo é Deus.</p>
                <p><a href="#" class="biblical-reference" data-reference="Mateus 28:19">Mateus 28:19</a> — fórmula batismal trinitária.</p>
                <p><a href="#" class="biblical-reference" data-reference="Atos 5:3-4">Atos 5:3-4</a> — o Espírito Santo identificado com Deus.</p>
                <p><a href="#" class="biblical-reference" data-reference="Hebreus 9:14">Hebreus 9:14</a> — o Espírito eterno.</p>
                <p>Portanto, sim, há forte concordância com a formulação nicena no conteúdo central, mas a IASD prefere apresentar a doutrina como ensino bíblico, não como submissão a uma tradição conciliar.</p>
            </div>

            <div class="doctrine-item">
                <h4>9.4 Por que os adventistas identificam Jesus como Miguel, o arcanjo? Isso significa que vocês acreditam que Jesus é um anjo criado (semelhante às Testemunhas de Jeová)?</h4>
                <p>Não. Os adventistas não creem que Jesus seja um anjo criado. A identificação de Cristo com Miguel não significa que Ele seja um ser criado ou da mesma natureza dos anjos.</p>
                <p>Na leitura adventista, "Miguel" é um título funcional aplicado a Cristo em certos contextos bíblicos, especialmente nos textos de conflito cósmico e liderança dos exércitos celestiais. O nome Miguel significa algo como "Quem é como Deus?", e aparece em cenas de guerra espiritual e autoridade celestial.</p>
                <p>Textos principais:</p>
                <p><a href="#" class="biblical-reference" data-reference="Daniel 10:13">Daniel 10:13</a> — Miguel, um dos primeiros príncipes.</p>
                <p><a href="#" class="biblical-reference" data-reference="Daniel 12:1">Daniel 12:1</a> — Miguel se levanta em favor do povo de Deus.</p>
                <p><a href="#" class="biblical-reference" data-reference="Judas 9">Judas 9</a> — Miguel, o arcanjo.</p>
                <p><a href="#" class="biblical-reference" data-reference="Apocalipse 12:7">Apocalipse 12:7</a> — Miguel e seus anjos pelejam contra o dragão.</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Tessalonicenses 4:16">1 Tessalonicenses 4:16</a> — a voz do arcanjo associada à volta de Cristo.</p>
                <p>A lógica adventista é esta: o ser que lidera os anjos, defende o povo de Deus, derrota Satanás e está ligado à ressurreição e à vitória final é o próprio Cristo em sua função de Comandante celestial. "Arcanjo", nessa leitura, não quer dizer "anjo criado", mas chefe dos anjos.</p>
                <p>Isso é muito diferente da posição das Testemunhas de Jeová. Elas entendem Miguel como um ser criado, o maior dos anjos. A IASD rejeita isso porque afirma claramente que:</p>
                <p>Cristo é eterno (<a href="#" class="biblical-reference" data-reference="Miquéias 5:2">Miquéias 5:2</a>; <a href="#" class="biblical-reference" data-reference="João 1:1-3">João 1:1-3</a>);</p>
                <p>Cristo é Criador, não criatura (<a href="#" class="biblical-reference" data-reference="Colossenses 1:16-17">Colossenses 1:16-17</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 1:1-8">Hebreus 1:1-8</a>);</p>
                <p>Cristo é adorado (<a href="#" class="biblical-reference" data-reference="Mateus 28:17">Mateus 28:17</a>; <a href="#" class="biblical-reference" data-reference="Hebreus 1:6">Hebreus 1:6</a>; Apocalipse 5:11-14);</p>
                <p>"nele habita corporalmente toda a plenitude da Divindade" (<a href="#" class="biblical-reference" data-reference="Colossenses 2:9">Colossenses 2:9</a>).</p>
                <p>Hebreus 1 é especialmente importante, porque distingue claramente o Filho dos anjos:</p>
                <p><a href="#" class="biblical-reference" data-reference="Hebreus 1:4-8, 13">Hebreus 1:4-8, 13</a> mostra que o Filho é superior aos anjos e recebe honra que nenhum anjo recebe.</p>
                <p>Então a posição adventista é:</p>
                <p>Jesus não é um anjo criado;</p>
                <p>Jesus é Deus Filho eterno;</p>
                <p>"Miguel" é um título que descreve sua função de líder angelical e guerreiro celestial.</p>
            </div>

            <div class="doctrine-item">
                <h4>9.5 Por que outros cristãos consideram problemática ou herética a identificação de Jesus com Miguel?</h4>
                <p>Porque, para muitos cristãos, essa identificação parece perigosa por poder sugerir que Jesus seria apenas um anjo exaltado. E essa preocupação, em si, é compreensível.</p>
                <p>Há três razões principais para a objeção:</p>
                <p>Primeira: o termo "arcanjo" soa, para muitos, como categoria criada. Então, ao ouvir "Jesus é Miguel", muitos concluem imediatamente: "logo, Jesus é criatura".</p>
                <p>Segunda: grupos efetivamente heréticos, como as Testemunhas de Jeová, usam linguagem parecida, mas com conteúdo diferente.</p>
                <p>Terceira: Hebreus 1 enfatiza a superioridade absoluta do Filho sobre os anjos, o que leva muitos a considerar inadequada qualquer identificação entre Cristo e um arcanjo.</p>
                <p>A resposta adventista a essa crítica é que a identificação não é ontológica, mas funcional e titular. Ou seja, não se diz que Cristo pertence à ordem criada dos anjos. Diz-se que Ele aparece como Comandante dos anjos.</p>
                <p>Mesmo assim, é preciso reconhecer com honestidade: muitos cristãos consideram essa linguagem ruim ou desnecessária, porque pode causar confusão. E de fato pode. Pastoralmente, o adventista precisa deixar muito claro, sempre, que:</p>
                <p>Jesus não foi criado;</p>
                <p>Jesus não é um anjo por natureza;</p>
                <p>Jesus é o Filho eterno de Deus.</p>
                <p>Quando isso não é explicado, a formulação parece herética. Quando é explicado com precisão, a posição adventista busca preservar tanto a plena divindade de Cristo quanto sua função de líder do exército celestial.</p>
            </div>

            <div class="doctrine-item">
                <h4>9.6 Na encarnação, Jesus assumiu a natureza humana antes (sem tendências pecaminosas) ou depois da queda (com tendências pecaminosas)?</h4>
                <p>A resposta oficial adventista precisa ser dada com muito cuidado: Jesus assumiu a verdadeira natureza humana decaída em suas fraquezas e limitações físicas, mas sem pecado, sem corrupção moral e sem qualquer participação em culpa ou inclinação interior ao mal como consentimento pecaminoso.</p>
                <p>A IASD afirma ao mesmo tempo duas verdades bíblicas:</p>
                <p>Cristo foi verdadeiramente humano;</p>
                <p>Cristo foi absolutamente sem pecado.</p>
                <p>Textos essenciais:</p>
                <p><a href="#" class="biblical-reference" data-reference="João 1:14">João 1:14</a> — o Verbo se fez carne.</p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 8:3">Romanos 8:3</a> — Deus enviou seu Filho "em semelhança de carne pecaminosa".</p>
                <p><a href="#" class="biblical-reference" data-reference="Hebreus 2:14, 17">Hebreus 2:14, 17</a> — participou da carne e sangue e foi semelhante aos irmãos.</p>
                <p><a href="#" class="biblical-reference" data-reference="Hebreus 4:15">Hebreus 4:15</a> — foi tentado em todas as coisas, "mas sem pecado".</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Pedro 2:22">1 Pedro 2:22</a> — não cometeu pecado.</p>
                <p><a href="#" class="biblical-reference" data-reference="2 Coríntios 5:21">2 Coríntios 5:21</a> — não conheceu pecado.</p>
                <p>No debate histórico adventista, surgiram formulações diferentes sobre "pré-queda" e "pós-queda". Mas como você pediu a posição oficial, o ponto seguro é este:</p>
                <p>Jesus assumiu a humanidade real, enfraquecida pelos efeitos de milênios de pecado;</p>
                <p>porém, Ele nunca possuiu pecado pessoal, culpa, corrupção moral ou concupiscência consentida.</p>
                <p>O Nisto Cremos enfatiza que Cristo se identificou plenamente com a raça humana, mas sem ser pecador. Essa é a linha que deve ser mantida.</p>
                <p>Em termos pastorais, isso é precioso, porque significa duas coisas:</p>
                <p>Jesus pode realmente nos representar, porque Se fez um de nós;</p>
                <p>Jesus pode realmente nos salvar, porque permaneceu santo, puro e sem pecado.</p>
                <p>Ele não foi um homem pecador tentando melhorar. Ele foi o segundo Adão, santo, obediente e vitorioso, vencendo onde o primeiro caiu (<a href="#" class="biblical-reference" data-reference="Romanos 5:12-19">Romanos 5:12-19</a>; <a href="#" class="biblical-reference" data-reference="1 Coríntios 15">1 Coríntios 15</a>:21-22, <a href="#" class="biblical-reference" data-reference="1 Coríntios 15">1 Coríntios 15</a>:45-49).</p>
            </div>
        </div>

        <div id="saude-alimentacao" class="sub-tab-content">
            <h2 class="sub-section-title">10. SAÚDE, ALIMENTAÇÃO E ESTILO DE VIDA</h2>

            <div class="doctrine-item">
                <h4>10.1 Por que os adventistas evitam carne de porco e outros animais considerados impuros (Levítico 11)? Isso não fazia parte da lei cerimonial?</h4>
                <p>Os adventistas entendem que a distinção entre animais puros e impuros não surgiu apenas com a lei cerimonial de Moisés, mas já existia antes, sendo depois reafirmada em Levítico 11</p>
                <p>Um ponto importante é <a href="#" class="biblical-reference" data-reference="Gênesis 7:2">Gênesis 7:2</a>, antes do Sinai: Noé já devia distinguir entre animais limpos e imundos. Isso mostra que a categoria não nasceu meramente com o sistema cerimonial israelita.</p>
                <p>Levítico 11 e Deuteronômio 14 organizam essa distinção para o povo de Israel. A leitura adventista é que essa diferença possui também caráter sanitário, moral e de santidade prática, não apenas ritual. O Nisto Cremos trata da saúde cristã em conexão com a santidade do corpo e da vida.</p>
                <p>Textos importantes:</p>
                <p>Levítico 11</p>
                <p>Deuteronômio 14</p>
                <p><a href="#" class="biblical-reference" data-reference="Gênesis 7:2">Gênesis 7:2</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 10:31">1 Coríntios 10:31</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 6:19-20">1 Coríntios 6:19-20</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Tessalonicenses 5:23">1 Tessalonicenses 5:23</a></p>
                <p>Por isso, a IASD não vê a abstinência de porco e de outros animais impuros como mero apego ao cerimonial abolido, mas como parte de um princípio permanente de cuidado com o corpo e distinção entre o que Deus classificou como apropriado ou não para alimento.</p>
                <p>O argumento adventista é: a lei cerimonial apontava para Cristo por meio de sacrifícios, rituais e festas típicas. Já a distinção alimentar tem um alcance amplo de ordem, saúde e santidade. O Nisto Cremos trata a reforma de saúde como expressão de mordomia cristã.</p>
                <p>Então, na posição adventista, não se trata de buscar salvação por dieta, mas de honrar a Deus no corpo.</p>
            </div>

            <div class="doctrine-item">
                <h4>10.2 Como os adventistas interpretam Atos 10 ("Não chames imundo ao que Deus purificou") e Marcos 7 ("... E, assim, considerou ele puros todos os alimentos") sobre alimentos puros e impuros?</h4>
                <p>A IASD entende que nem Atos 10 nem Marcos 7 aboliram a distinção bíblica entre animais puros e impuros.</p>
                <p>Em Atos 10, Pedro tem uma visão com animais diversos e ouve a ordem: "mata e come". Mas o próprio texto interpreta a visão. Quando Pedro entende seu significado, ele declara:</p>
                <p><a href="#" class="biblical-reference" data-reference="Atos 10:28">Atos 10:28</a> — "a mim me revelou Deus que a nenhum homem considerasse comum ou imundo".</p>
                <p>Portanto, o tema central não era dieta, mas aceitação dos gentios no povo de Deus. Deus estava quebrando a barreira étnica e ensinando a Pedro a não tratar os gentios convertidos como impuros.</p>
                <p>Quanto a Marcos 7, o Nisto Cremos é bem claro no raciocínio: a discussão ali não é sobre declarar o porco ou o camarão próprios para alimento, mas sobre a lavagem ritual das mãos. O conflito era entre Jesus e a tradição farisaica. O problema era comer com mãos "impuras" cerimonialmente, e não a espécie do alimento em si.</p>
                <p>Textos centrais:</p>
                <p><a href="#" class="biblical-reference" data-reference="Marcos 7:1-23">Marcos 7:1-23</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Mateus 15:1-20">Mateus 15:1-20</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Atos 10:9-28">Atos 10:9-28</a></p>
                <p>Na leitura adventista, Jesus ensinou que a contaminação moral vem do coração, não da falta de rito humano. Isso não equivale a abolir categorias alimentares já definidas por Deus.</p>
                <p>Portanto:</p>
                <p>Atos 10 fala principalmente sobre pessoas, não sobre cardápio;</p>
                <p>Marcos 7 fala sobre tradição ritual, não sobre revogação de Levítico 11</p>
            </div>

            <div class="doctrine-item">
                <h4>10.3 O vegetarianismo é uma doutrina obrigatória ou uma recomendação de saúde?</h4>
                <p>Não é uma doutrina obrigatória para salvação nem condição universal para membresia. A posição oficial adventista é que o vegetarianismo é fortemente recomendado como ideal de saúde, mas não é imposto como dogma salvífico.</p>
                <p>A Bíblia apresenta a dieta original em <a href="#" class="biblical-reference" data-reference="Gênesis 1:29">Gênesis 1:29</a> como vegetal. Depois do dilúvio, Deus permitiu o uso de carne em certas condições (<a href="#" class="biblical-reference" data-reference="Gênesis 9:3-4">Gênesis 9:3-4</a>). Mais tarde, em Israel, distinguiu-se entre carnes limpas e impuras (Levítico 11).</p>
                <p>A leitura adventista é:</p>
                <p>o ideal mais elevado aponta para uma alimentação mais simples e saudável;</p>
                <p>porém, a salvação está em Cristo, não no cardápio.</p>
                <p>O Nisto Cremos trata a saúde como parte da mordomia cristã. O objetivo é glorificar a Deus no corpo, na mente e nos hábitos.</p>
                <p>Textos relevantes:</p>
                <p><a href="#" class="biblical-reference" data-reference="Gênesis 1:29">Gênesis 1:29</a></p>
                <p>Daniel 1:8-20</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 6:19-20">1 Coríntios 6:19-20</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 10:31">1 Coríntios 10:31</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 14:17">Romanos 14:17</a></p>
                <p>Então, o vegetarianismo, para a IASD, é uma recomendação de saúde e um ideal de estilo de vida, não um teste absoluto de salvação.</p>
            </div>

            <div class="doctrine-item">
                <h4>10.4 A reforma de saúde é questão de salvação ou apenas de bem-estar e mordomia cristã?</h4>
                <p>A resposta oficial precisa ser equilibrada: a reforma de saúde não é o evangelho nem o meio de salvação, mas é uma área importante de santificação, obediência e mordomia cristã.</p>
                <p>A salvação é somente pela graça, mediante a fé em Cristo (<a href="#" class="biblical-reference" data-reference="Efésios 2:8-9">Efésios 2:8-9</a>). Isso a IASD afirma claramente. Porém, a vida salva produz frutos visíveis. Assim como a fé afeta a língua, a sexualidade, as finanças e o caráter, também afeta o uso do corpo.</p>
                <p>Por isso, a reforma de saúde é mais do que "bem-estar". Ela envolve:</p>
                <p>respeito ao corpo como templo do Espírito;</p>
                <p>clareza mental para a vida espiritual;</p>
                <p>testemunho cristão;</p>
                <p>obediência prática a princípios divinos.</p>
                <p>Textos principais:</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 6:19-20">1 Coríntios 6:19-20</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 10:31">1 Coríntios 10:31</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 12:1-2">Romanos 12:1-2</a></p>
                <p><a href="#" class="biblical-reference" data-reference="3 João 2">3 João 2</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Tessalonicenses 5:23">1 Tessalonicenses 5:23</a></p>
                <p>Pastoralmente, a melhor forma de dizer é:</p>
                <p>não somos salvos porque seguimos a reforma de saúde;</p>
                <p>mas quem foi alcançado pela graça deve desejar honrar a Deus também nessa área.</p>
            </div>

            <div class="doctrine-item">
                <h4>10.5 Por que café, álcool e tabaco são desaconselhados ou proibidos entre adventistas?</h4>
                <p>Porque a IASD entende que essas substâncias prejudicam o corpo, a mente e a vida espiritual, e que o cristão é chamado à sobriedade, pureza e autocontrole.</p>
                <p>Álcool: a posição adventista é de abstinência. Ainda que existam debates sobre usos antigos do vinho na Bíblia, a igreja entende que, diante dos males do álcool e do chamado à sobriedade, o caminho mais seguro e coerente é a abstinência.</p>
                <p>Textos:</p>
                <p><a href="#" class="biblical-reference" data-reference="Provérbios 20:1">Provérbios 20:1</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Provérbios 23:29-32">Provérbios 23:29-32</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Efésios 5:18">Efésios 5:18</a></p>
                <p>Tabaco: não há menção explícita porque o hábito é posterior ao texto bíblico, mas o princípio é claro: não destruir o corpo nem se tornar escravo de vícios.</p>
                <p>Textos:</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 6:12">1 Coríntios 6:12</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 6:19-20">1 Coríntios 6:19-20</a></p>
                <p><a href="#" class="biblical-reference" data-reference="2 Coríntios 7:1">2 Coríntios 7:1</a></p>
                <p>Café: tradicionalmente a IASD desaconselha o café por seu efeito estimulante, dependência e impacto sobre o sistema nervoso. O raciocínio é de temperança, domínio próprio e proteção da mente.</p>
                <p>Textos de princípio:</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 10:31">1 Coríntios 10:31</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 12:1-2">Romanos 12:1-2</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Pedro 1:13">1 Pedro 1:13</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Pedro 5:8">1 Pedro 5:8</a></p>
                <p>O ponto central não é legalismo alimentar, mas vida sóbria e consagrada.</p>
            </div>

            <div class="doctrine-item">
                <h4>10.6 Adventistas podem consumir outras bebidas com cafeína, como chá preto, mate, energéticos e refrigerantes?</h4>
                <p>Na prática adventista histórica, bebidas com cafeína costumam ser desaconselhadas, especialmente quando têm caráter estimulante forte e potencial de dependência. Isso inclui, em muitos contextos, chá preto, mate, energéticos e certos refrigerantes.</p>
                <p>A Bíblia não traz uma lista dessas bebidas, então a aplicação é feita por princípio:</p>
                <p>cuidado com o corpo;</p>
                <p>domínio próprio;</p>
                <p>evitar dependência;</p>
                <p>preservar clareza mental.</p>
                <p>Textos-base:</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 6:12">1 Coríntios 6:12</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 10:31">1 Coríntios 10:31</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 12:1-2">Romanos 12:1-2</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Pedro 5:8">1 Pedro 5:8</a></p>
                <p>É importante distinguir:</p>
                <p>a posição oficial enfatiza princípios;</p>
                <p>a prática pastoral local pode variar em rigor e aplicação.</p>
                <p>Portanto, a resposta mais fiel é: em geral, essas bebidas são desaconselhadas no ideal adventista de saúde, ainda que a disciplina eclesiástica costume se concentrar mais fortemente em álcool, tabaco e drogas.</p>
            </div>

            <div class="doctrine-item">
                <h4>10.7 É permitido o uso de jóias, piercings, maquiagens e outros adornos?</h4>
                <p>A posição adventista histórica e oficial tende à simplicidade, modéstia e ausência de ostentação. O princípio é que a beleza cristã deve ser primariamente interior, e não centrada em exibição externa.</p>
                <p>Textos frequentemente usados:</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Timóteo 2:9-10">1 Timóteo 2:9-10</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Pedro 3:3-4">1 Pedro 3:3-4</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 João 2:15-17">1 João 2:15-17</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 12:1-2">Romanos 12:1-2</a></p>
                <p>No Nisto Cremos, a conduta cristã é apresentada como chamada à modéstia, pureza e simplicidade.</p>
                <p>Quanto aos itens:</p>
                <p>jóias: tradicionalmente desaconselhadas, especialmente como ornamento;</p>
                <p>piercings: em geral vistos como incompatíveis com o ideal adventista de simplicidade e reverência ao corpo;</p>
                <p>maquiagem: a abordagem costuma ser de cautela, modéstia e rejeição da vaidade ou sensualização;</p>
                <p>adornos em geral: avaliados pelo princípio da modéstia cristã.</p>
                <p>A ideia não é dizer que qualquer uso externo seja automaticamente um pecado imperdoável, mas que o discípulo é chamado a uma vida de simplicidade santa, que contraste com a vaidade do mundo.</p>
            </div>

            <div class="doctrine-item">
                <h4>10.8 Pode-se batizar alguém que usa jóias?</h4>
                <p>Na prática pastoral adventista, o ideal é que a pessoa compreenda e aceite os princípios de modéstia cristã antes do batismo. O batismo é entrada pública em uma aliança de discipulado, e isso inclui disposição de aprender e obedecer.</p>
                <p>Por isso, normalmente a orientação pastoral é instruir o candidato a abandonar adornos incompatíveis com esse ideal. Em muitos lugares, o uso persistente de jóias como adorno é tratado como obstáculo ao preparo batismal.</p>
                <p>Mas pastoralmente é importante evitar dureza superficial. O foco não é a jóia em si como centro do evangelho, mas o coração submetido a Cristo. O batismo pressupõe arrependimento, fé e disposição de viver em conformidade com a Palavra.</p>
                <p>Textos de princípio:</p>
                <p><a href="#" class="biblical-reference" data-reference="Mateus 28:19-20">Mateus 28:19-20</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Atos 2:38">Atos 2:38</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 6">Romanos 6</a>:3-6</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Timóteo 2:9-10">1 Timóteo 2:9-10</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Pedro 3:3-4">1 Pedro 3:3-4</a></p>
                <p>Então, a resposta curta e honesta é: a igreja espera que o candidato ao batismo aceite o princípio adventista de modéstia, e isso normalmente inclui deixar jóias ornamentais.</p>
            </div>

            <div class="doctrine-item">
                <h4>10.9 Adventistas podem receber transfusão de sangue?</h4>
                <p>Sim. A Igreja Adventista do Sétimo Dia não proíbe transfusão de sangue como fazem as Testemunhas de Jeová.</p>
                <p>A IASD valoriza fortemente a vida, a saúde, o cuidado médico responsável e a liberdade de consciência em decisões clínicas. Pode haver discussão sobre tipos de tratamento, riscos, alternativas e preferências pessoais, mas não há doutrina adventista proibindo transfusão.</p>
                <p>Os textos bíblicos sobre não comer sangue, como Levítico 17 e <a href="#" class="biblical-reference" data-reference="Atos 15">Atos 15</a>, são entendidos no contexto alimentar, ritual e da reverência à vida, não como proibição absoluta de procedimento médico terapêutico moderno.</p>
                <p>Portanto, sim, adventistas podem receber transfusão de sangue.</p>
            </div>

            <div class="doctrine-item">
                <h4>10.10 Adventista pode comer camarão e mariscos?</h4>
                <p>Pela posição tradicional adventista, não deve. Camarão, mariscos e outros frutos do mar classificados como impuros em <a href="#" class="biblical-reference" data-reference="Levítico 11:9-12">Levítico 11:9-12</a> e <a href="#" class="biblical-reference" data-reference="Deuteronômio 14:9-10">Deuteronômio 14:9-10</a> não fazem parte da dieta recomendada pela IASD.</p>
                <p>A lógica é a mesma aplicada ao porco e outros animais impuros: a distinção bíblica é respeitada como parte da mordomia do corpo e da santidade prática.</p>
            </div>

            <div class="doctrine-item">
                <h4>10.11 Por que há regras sobre entretenimento, vestimenta e padrões de aparência?</h4>
                <p>Porque a fé cristã, na visão adventista, não é apenas crença intelectual; ela molda a vida inteira. O Nisto Cremos associa a conduta cristã a uma existência santa, sóbria e distinta do espírito do mundo.</p>
                <p>Textos-base:</p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 12:1-2">Romanos 12:1-2</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Filipenses 4:8">Filipenses 4:8</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 João 2:15-17">1 João 2:15-17</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 10:31">1 Coríntios 10:31</a></p>
                <p>A preocupação com entretenimento, vestimenta e aparência existe porque essas áreas afetam:</p>
                <p>a mente;</p>
                <p>os desejos;</p>
                <p>o testemunho;</p>
                <p>a identidade cristã;</p>
                <p>a sensibilidade espiritual.</p>
                <p>Em termos pastorais, o objetivo não é criar uma subcultura artificial, mas formar discípulos que vivam de maneira coerente com o Reino de Deus.</p>
            </div>
        </div>

        <div id="dizimos-ofertas" class="sub-tab-content">
            <h2 class="sub-section-title">11. DÍZIMOS, OFERTAS E FINANÇAS</h2>

            <div class="doctrine-item">
                <h4>11.1 O dízimo é uma prática do Antigo Testamento ou continua válido para os cristãos na Nova Aliança? Ele é obrigatório para a salvação?</h4>
                <p>A posição adventista é que o dízimo continua válido como princípio de mordomia, embora não seja meio de salvação.</p>
                <p>A IASD vê o dízimo como anterior ao Sinai:</p>
                <p>Abraão deu o dízimo a Melquisedeque (<a href="#" class="biblical-reference" data-reference="Gênesis 14:20">Gênesis 14:20</a>);</p>
                <p>Jacó fez voto relacionado ao dízimo (<a href="#" class="biblical-reference" data-reference="Gênesis 28:22">Gênesis 28:22</a>).</p>
                <p>Depois, o sistema foi regulado em Israel:</p>
                <p><a href="#" class="biblical-reference" data-reference="Levítico 27:30, 32">Levítico 27:30, 32</a> — o dízimo é santo ao Senhor.</p>
                <p>No Novo Testamento, Jesus não o condena:</p>
                <p><a href="#" class="biblical-reference" data-reference="Mateus 23:23">Mateus 23:23</a> — "devíeis, porém, fazer estas coisas, sem omitir aquelas".</p>
                <p>O Nisto Cremos afirma que Deus instituiu o sistema de dízimos e ofertas e que a igreja adota esse modelo para sustentar a proclamação do evangelho. O trecho recuperado do anexo diz claramente que o dízimo é "santo ao Senhor" e deve ser devolvido como propriedade divina.</p>
                <p>Mas isso precisa ser cercado do evangelho:</p>
                <p>ninguém é salvo por dizimar;</p>
                <p>ninguém compra favor divino com dinheiro;</p>
                <p>o dízimo é resposta de fidelidade, não moeda de salvação.</p>
                <p>Textos centrais:</p>
                <p><a href="#" class="biblical-reference" data-reference="Gênesis 14:20">Gênesis 14:20</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Gênesis 28:22">Gênesis 28:22</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Levítico 27:30">Levítico 27:30</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Malaquias 3:8-10">Malaquias 3:8-10</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Mateus 23:23">Mateus 23:23</a></p>
                <p>2 Coríntios 9:6-8</p>
            </div>

            <div class="doctrine-item">
                <h4>11.2 O dízimo deve ser "pago" ou "devolvido"?</h4>
                <p>Na linguagem adventista, o mais correto é dizer "devolvido", não "pago".</p>
                <p>Isso porque o dízimo não é visto como algo que pertence originalmente ao adorador e depois é oferecido a Deus. Pelo contrário: ele já é entendido como pertencente ao Senhor.</p>
                <p>O próprio Nisto Cremos enfatiza isso ao dizer que essa porção é "sua efetiva propriedade". Com base em <a href="#" class="biblical-reference" data-reference="Levítico 27:30">Levítico 27:30</a>, a igreja entende que o dízimo é santo ao Senhor.</p>
                <p>Então:</p>
                <p>pagar sugere quitação de uma dívida ou contribuição voluntária qualquer;</p>
                <p>devolver expressa melhor a ideia bíblica de mordomia.</p>
            </div>

            <div class="doctrine-item">
                <h4>11.3 Para onde vai o dinheiro do dízimo e das ofertas, e como ele é administrado na IASD?</h4>
                <p>Na prática adventista, há distinção entre dízimos e ofertas.</p>
                <p>Dízimo: destinado primariamente ao sustento do ministério evangélico e da pregação do evangelho. O anexo menciona o modelo levítico e o financiamento da obra mundial.</p>
                <p>Ofertas: usadas em várias frentes, como manutenção local, projetos missionários, expansão, assistência, construção e necessidades institucionais, conforme a organização da igreja.</p>
                <p>A IASD possui uma estrutura administrativa representativa e organizada. Em princípio:</p>
                <p>o dízimo não é tratado como verba livre local para qualquer fim;</p>
                <p>ele integra uma administração denominacional voltada à missão global;</p>
                <p>há sistemas de distribuição e auditoria dentro da estrutura eclesiástica.</p>
                <p>A ideia teológica é que os recursos devem servir à missão, não ao enriquecimento pessoal de líderes.</p>
            </div>
        </div>

        <div id="batismo-ceia" class="sub-tab-content">
            <h2 class="sub-section-title">12. BATISMO, CEIA E PRÁTICAS LITÚRGICAS</h2>

            <div class="doctrine-item">
                <h4>12.1 Por que os adventistas praticam apenas o batismo por imersão? Batismos realizados em outras denominações (por aspersão) são válidos?</h4>
                <p>A IASD pratica apenas o batismo por imersão porque entende que esse é o modelo bíblico mais fiel.</p>
                <p>Razões bíblicas:</p>
                <p>o verbo grego baptizo traz a ideia de imergir;</p>
                <p>o batismo simboliza morte, sepultamento e ressurreição com Cristo;</p>
                <p>esse simbolismo aparece plenamente na imersão.</p>
                <p>Textos-chave:</p>
                <p><a href="#" class="biblical-reference" data-reference="Mateus 3:13-17">Mateus 3:13-17</a> — o exemplo de Jesus;</p>
                <p><a href="#" class="biblical-reference" data-reference="João 3:23">João 3:23</a> — havia muita água;</p>
                <p><a href="#" class="biblical-reference" data-reference="Atos 8:38-39">Atos 8:38-39</a> — Filipe e o eunuco descem à água;</p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 6">Romanos 6</a>:3-6</p>
                <p><a href="#" class="biblical-reference" data-reference="Colossenses 2:12">Colossenses 2:12</a></p>
                <p>O Nisto Cremos também rejeita a ideia sacramental automática do batismo, como se o rito em si salvasse. O batismo é sinal de fé, arrependimento e união com Cristo.</p>
                <p>Quanto a batismos por aspersão em outras denominações, a IASD normalmente entende que não correspondem ao modelo bíblico pleno. Por isso, ao unir-se à igreja, a prática esperada é o batismo por imersão, ainda que a pessoa venha de ambiente cristão sincero.</p>
            </div>

            <div class="doctrine-item">
                <h4>12.2 A IASD aceita batismo infantil?</h4>
                <p>Não. A IASD não pratica batismo infantil, porque entende que o batismo requer:</p>
                <p>arrependimento;</p>
                <p>fé pessoal;</p>
                <p>compreensão do evangelho;</p>
                <p>decisão consciente de discipulado.</p>
                <p>Textos principais:</p>
                <p><a href="#" class="biblical-reference" data-reference="Marcos 16:16">Marcos 16:16</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Atos 2:38">Atos 2:38</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Atos 8:12">Atos 8:12</a></p>
                <p>Atos 8:36-38</p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 6">Romanos 6</a>:3-6</p>
                <p>Como um bebê ainda não pode exercer arrependimento e fé consciente, a igreja não o batiza.</p>
            </div>

            <div class="doctrine-item">
                <h4>12.3 O que é a apresentação ou dedicação de crianças e por que ela não é batismo?</h4>
                <p>A apresentação ou dedicação de crianças é um ato em que os pais consagram o filho a Deus e assumem publicamente a responsabilidade de criá-lo nos caminhos do Senhor.</p>
                <p>Ela não é batismo porque:</p>
                <p>não implica profissão pessoal de fé da criança;</p>
                <p>não simboliza morte e ressurreição com Cristo;</p>
                <p>não substitui a futura decisão individual.</p>
                <p>A base bíblica inclui:</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Samuel 1:27-28">1 Samuel 1:27-28</a> — Ana consagra Samuel;</p>
                <p><a href="#" class="biblical-reference" data-reference="Lucas 2:22">Lucas 2:22</a> — Jesus foi apresentado.</p>
                <p>Portanto, a dedicação é um ato de gratidão, compromisso familiar e oração, mas não é regeneração sacramental nem batismo substituto.</p>
            </div>

            <div class="doctrine-item">
                <h4>12.4 O que é o lava-pés praticado antes da Ceia do Senhor?</h4>
                <p>É o chamado rito da humildade. Baseia-se no gesto de Jesus em <a href="#" class="biblical-reference" data-reference="João 13:1-17">João 13:1-17</a>, quando Ele lavou os pés dos discípulos.</p>
                <p>Na IASD, esse ato tem vários significados:</p>
                <p>humildade;</p>
                <p>serviço mútuo;</p>
                <p>reconciliação;</p>
                <p>renovada purificação relacional e espiritual;</p>
                <p>preparação para a Ceia.</p>
                <p>O Nisto Cremos afirma que o lava-pés representa renovada purificação e a disposição de servir uns aos outros em humildade cristã.</p>
            </div>

            <div class="doctrine-item">
                <h4>12.5 A Ceia do Senhor é apenas um memorial simbólico ou envolve a presença real de Cristo?</h4>
                <p>A posição adventista evita dois extremos:</p>
                <p>não ensina transubstanciação;</p>
                <p>não reduz a Ceia a um ato vazio ou meramente mental.</p>
                <p>O Nisto Cremos afirma explicitamente que, na experiência da comunhão, Cristo está presente para encontrar-se com seu povo e fortalecê-lo. Isso é muito importante.</p>
                <p>Então, para a IASD:</p>
                <p>os emblemas não se transformam literalmente no corpo e sangue físicos;</p>
                <p>mas a Ceia não é mero simbolismo frio;</p>
                <p>há uma presença real de Cristo em sentido espiritual e relacional.</p>
                <p>Textos principais:</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 10:16-17">1 Coríntios 10:16-17</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 11:23-29">1 Coríntios 11:23-29</a></p>
                <p>João 6 como pano de fundo cristológico, ainda que não diretamente institucional da Ceia.</p>
            </div>

            <div class="doctrine-item">
                <h4>12.6 A ceia adventista é aberta a outros cristãos ou restrita aos membros?</h4>
                <p>A prática adventista é de comunhão aberta a cristãos que creem em Cristo e participam com reverência, exame próprio e respeito ao significado da ordenança.</p>
                <p>Ela não é restrita exclusivamente aos membros locais em sentido absoluto. O foco está na relação da pessoa com Cristo, no autoexame e na disposição espiritual adequada.</p>
                <p>Texto principal:</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 11:27-29">1 Coríntios 11:27-29</a></p>
            </div>

            <div class="doctrine-item">
                <h4>12.7 Por que usam suco de uva em vez de vinho na ceia?</h4>
                <p>A IASD usa suco de uva não fermentado por razões teológicas e simbólicas.</p>
                <p>O argumento principal é que os emblemas da Ceia devem representar a pureza de Cristo. Como a fermentação muitas vezes simboliza corrupção ou decomposição, entende-se que o símbolo mais adequado é o fruto puro da videira, sem fermentação.</p>
                <p>Além disso, isso harmoniza a Ceia com o princípio adventista de abstinência alcoólica.</p>
                <p>Textos frequentemente relacionados:</p>
                <p><a href="#" class="biblical-reference" data-reference="Mateus 26:27-29">Mateus 26:27-29</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 11:23-26">1 Coríntios 11:23-26</a></p>
                <p>referências veterotestamentárias ao fermento como símbolo negativo em certos contextos.</p>
            </div>
        </div>

        <div id="casamento-familia" class="sub-tab-content">
            <h2 class="sub-section-title">13. CASAMENTO, FAMÍLIA E QUESTÕES ÉTICAS</h2>

            <div class="doctrine-item">
                <h4>13.1 Qual é a posição da IASD sobre divórcio e novo casamento? A Igreja aceita casamentos de pessoas divorciadas?</h4>
                <p>A IASD entende que o casamento é uma instituição sagrada e, idealmente, permanente. O Nisto Cremos cita claramente o ensino de Jesus: "O que Deus ajuntou não o separe o homem" (<a href="#" class="biblical-reference" data-reference="Mateus 19:6">Mateus 19:6</a>; <a href="#" class="biblical-reference" data-reference="Marcos 10:9">Marcos 10:9</a>).</p>
                <p>A igreja reconhece, porém, que o pecado feriu profundamente a realidade humana. Por isso, trata o divórcio como tragédia, não como ideal. Em casos bíblicos específicos, especialmente imoralidade sexual (<a href="#" class="biblical-reference" data-reference="Mateus 5:32">Mateus 5:32</a>; <a href="#" class="biblical-reference" data-reference="Mateus 19:9">19:9</a>) e, em discussões pastorais, abandono destrutivo ligado a <a href="#" class="biblical-reference" data-reference="1 Coríntios 7:10-15">1 Coríntios 7:10-15</a>, a igreja admite que houve ruptura real da aliança.</p>
                <p>Quanto ao novo casamento, a IASD o trata com seriedade pastoral e análise bíblica. Em muitos casos, sim, a igreja aceita o casamento de pessoas divorciadas, desde que a situação seja tratada conforme os princípios bíblicos e as normas eclesiásticas aplicáveis.</p>
                <p>Portanto:</p>
                <p>divórcio não é o ideal de Deus;</p>
                <p>a igreja não o banaliza;</p>
                <p>mas reconhece situações de quebra grave da aliança;</p>
                <p>e trata novo casamento dentro de critérios pastorais e bíblicos.</p>
            </div>

            <div class="doctrine-item">
                <h4>13.2 Qual é a posição oficial da Igreja Adventista sobre homossexualidade e uniões homoafetivas?</h4>
                <p>A posição oficial da IASD distingue entre:</p>
                <p>inclinação/atração;</p>
                <p>prática sexual;</p>
                <p>doutrina do casamento.</p>
                <p>A igreja ensina que toda pessoa tem dignidade, valor e deve ser tratada com respeito, compaixão e amor cristão. Ao mesmo tempo, sustenta que o padrão bíblico para a prática sexual e para o casamento é a união entre um homem e uma mulher, dentro da aliança matrimonial.</p>
                <p>Textos frequentemente citados:</p>
                <p><a href="#" class="biblical-reference" data-reference="Gênesis 1:27">Gênesis 1:27</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Gênesis 2:24">Gênesis 2:24</a></p>
                <p>Mateus 19:4-6</p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 1:24-27">Romanos 1:24-27</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 6:9-11">1 Coríntios 6:9-11</a></p>
                <p>Assim, a IASD não reconhece uniões homoafetivas como casamento bíblico e não aprova prática sexual fora do modelo bíblico que ela entende ser heterossexual e conjugal.</p>
                <p>Pastoralmente, isso deve ser dito com verdade e graça: a igreja não autoriza redefinir o casamento, mas é chamada a acolher pessoas, anunciar o evangelho e chamar todos ao discipulado.</p>
            </div>

            <div class="doctrine-item">
                <h4>13.3 Adventistas podem se casar com pessoas de outras religiões ou denominações?</h4>
                <p>A orientação oficial é que o crente não deve casar-se com incrédulos ou com quem não compartilha a mesma fé de forma compatível. O anexo traz referência explícita ao cuidado contra casar com descrentes.</p>
                <p>Base bíblica:</p>
                <p><a href="#" class="biblical-reference" data-reference="2 Coríntios 6:14">2 Coríntios 6:14</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Amós 3:3">Amós 3:3</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 7:39">1 Coríntios 7:39</a> — "contanto que seja no Senhor".</p>
                <p>A preocupação é espiritual, prática e familiar. Diferenças profundas de fé costumam gerar conflito em culto doméstico, criação dos filhos, prioridades morais e missão da casa.</p>
                <p>Então, embora a igreja lidere pastoralmente com casos reais já existentes, a orientação é clara: o ideal bíblico é casar-se "no Senhor".</p>
            </div>

            <div class="doctrine-item">
                <h4>13.4 Qual é a posição oficial da IASD sobre o aborto?</h4>
                <p>A IASD tem posição fortemente pró-vida, reconhecendo o valor sagrado da vida humana. Em regra, o aborto não é tratado como método aceitável de conveniência, controle social ou liberdade irrestrita sobre o corpo.</p>
                <p>Ao mesmo tempo, a abordagem adventista costuma buscar responsabilidade pastoral em casos dramáticos e complexos. A tendência oficial é afirmar:</p>
                <p>a vida humana é dom de Deus;</p>
                <p>o nascituro merece respeito moral;</p>
                <p>o aborto não deve ser trivializado;</p>
                <p>situações extremas exigem seriedade, oração, princípio bíblico e consciência responsável.</p>
                <p>Textos de referência moral:</p>
                <p>Salmo 139:13-16</p>
                <p><a href="#" class="biblical-reference" data-reference="Jeremias 1:5">Jeremias 1:5</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Êxodo 20:13">Êxodo 20:13</a></p>
            </div>
        </div>

        <div id="criacao-evolucao" class="sub-tab-content">
            <h2 class="sub-section-title">14. CRIAÇÃO, EVOLUÇÃO E ORIGENS</h2>

            <div class="doctrine-item">
                <h4>14.1 Os adventistas creem em criação literal em seis dias?</h4>
                <p>Sim. A IASD crê em criação literal em seis dias. Isso é parte central de sua doutrina.</p>
                <p>Textos principais:</p>
                <p><a href="#" class="biblical-reference" data-reference="Gênesis 1:1-2:3">Gênesis 1:1-2:3</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Êxodo 20:8-11">Êxodo 20:8-11</a></p>
                <p>O Nisto Cremos trata a criação como fundamento indispensável para a teologia bíblica e cristã. A conexão com o sábado é decisiva: o quarto mandamento baseia o sábado no padrão da criação em seis dias.</p>
            </div>

            <div class="doctrine-item">
                <h4>14.2 Por que a IASD rejeita a macroevolução e a evolução teísta?</h4>
                <p>Porque entende que essas teorias entram em conflito com o relato bíblico da criação, da queda e da morte.</p>
                <p>Se a macroevolução for aceita como mecanismo principal da origem humana, surgem grandes tensões com:</p>
                <p>a historicidade de Adão e Eva;</p>
                <p>a entrada do pecado no mundo;</p>
                <p>a relação entre pecado e morte;</p>
                <p>a base do sábado;</p>
                <p>a necessidade de redenção como resposta a uma queda real.</p>
                <p>Textos centrais:</p>
                <p><a href="#" class="biblical-reference" data-reference="Gênesis 1:1-2:3">Gênesis 1:1-2:3</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 5:12-21">Romanos 5:12-21</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 15">1 Coríntios 15</a>:21-22, 45-49</p>
                <p><a href="#" class="biblical-reference" data-reference="Êxodo 20:8-11">Êxodo 20:8-11</a></p>
                <p>A evolução teísta é rejeitada porque, na leitura adventista, ela tenta unir dois esquemas que se chocam: um processo longo de morte antes do pecado e a narrativa bíblica que vincula morte humana ao ingresso do pecado.</p>
            </div>

            <div class="doctrine-item">
                <h4>14.3 A criação em seis dias é um pilar que afeta outras doutrinas, como sábado, queda e redenção?</h4>
                <p>Sim, profundamente. O próprio Nisto Cremos afirma que muitos conceitos bíblicos fundamentais estão enraizados na criação divina.</p>
                <p>A criação afeta:</p>
                <p>sábado — memorial da criação;</p>
                <p>natureza humana — criada à imagem de Deus;</p>
                <p>queda — queda de um casal real;</p>
                <p>redenção — Cristo como segundo Adão;</p>
                <p>escatologia — nova criação e restauração final.</p>
                <p>Sem criação literal, várias peças da teologia bíblica ficam soltas.</p>
            </div>

            <div class="doctrine-item">
                <h4>14.4 Qual é a posição adventista sobre a idade da Terra e do universo (milhares vs milhões/bilhões)?</h4>
                <p>A IASD oficialmente afirma a criação literal recente da vida na Terra em seis dias, mas historicamente nem sempre especifica com o mesmo grau dogmático cada detalhe cronológico do universo inteiro como tema confessional central.</p>
                <p>Na prática, o adventismo tende a uma leitura de Terra recente, associada à cronologia bíblica. Quanto ao universo em sua totalidade, há adventistas que discutem o tema com cautela maior, mas a posição doutrinária da igreja enfatiza sobretudo:</p>
                <p>criação divina literal;</p>
                <p>semana da criação real;</p>
                <p>historicidade de Gênesis.</p>
                <p>Portanto, se a pergunta for sobre a Terra e a vida humana, a tendência oficial adventista é rejeitar longas eras evolucionárias para a história da vida humana na Terra.</p>
            </div>

            <div class="doctrine-item">
                <h4>14.5 O dilúvio foi global e explica geologia e fósseis na leitura adventista?</h4>
                <p>Sim. Na leitura adventista tradicional, o dilúvio de Gênesis foi global, não apenas regional.</p>
                <p>Textos principais:</p>
                <p><a href="#" class="biblical-reference" data-reference="Gênesis 6:9">Gênesis 6:9</a></p>
                <p>Mateus 24:37-39</p>
                <p><a href="#" class="biblical-reference" data-reference="2 Pedro 3:5-6">2 Pedro 3:5-6</a></p>
                <p>Essa leitura serve como parte importante da explicação adventista para grande parte do registro fóssil e de formações geológicas de larga escala. A igreja vê o dilúvio como evento histórico catastrófico de alcance mundial.</p>
            </div>
        </div>

        <div id="costumes-festas" class="sub-tab-content">
            <h2 class="sub-section-title">15. COSTUMES, FESTAS E PRÁTICAS CULTURAIS</h2>

            <div class="doctrine-item">
                <h4>15.1 Adventistas celebram o Natal? É pecado ou considerado pagão?</h4>
                <p>Adventistas podem celebrar o Natal, desde que o façam de modo cristão, centrado em Cristo e sem superstição. A IASD não trata o Natal como sacramento obrigatório nem como festa proibida em si.</p>
                <p>A preocupação adventista costuma ser dupla:</p>
                <p>evitar consumismo, sentimentalismo vazio e elementos contrários ao evangelho;</p>
                <p>usar a ocasião para enfatizar a encarnação de Cristo, generosidade e missão.</p>
                <p>Então, a resposta equilibrada é:</p>
                <p>não é pecado celebrar o Natal em si;</p>
                <p>mas a igreja não o eleva a ordenança bíblica;</p>
                <p>e alerta contra excessos, paganização cultural e secularização da data.</p>
            </div>

            <div class="doctrine-item">
                <h4>15.2 Adventistas celebram a Páscoa?</h4>
                <p>Sim, podem celebrar, especialmente destacando a morte e ressurreição de Cristo. Mas, como no Natal, a IASD tende a evitar formalismo litúrgico obrigatório e elementos não bíblicos que obscureçam o evangelho.</p>
                <p>A Páscoa cristã pode ser ocasião legítima para pregação, culto, estudo bíblico e gratidão pela redenção em Cristo.</p>
            </div>
        </div>

        <div id="lideranca-feminina" class="sub-tab-content">
            <h2 class="sub-section-title">16. LIDERANÇA FEMININA E ORDENAÇÃO DE MULHERES</h2>

            <div class="doctrine-item">
                <h4>16.1 Mulheres podem pregar, ensinar, dar estudos bíblicos e exercer liderança na IASD?</h4>
                <p>Sim. A IASD reconhece amplamente o ministério feminino em várias áreas. O próprio Nisto Cremos afirma que o Espírito distribui dons à igreja para ministérios como ensino, proclamação, administração, reconciliação, compaixão e serviço. Isso não é limitado, nesse princípio, apenas aos homens.</p>
                <p>Além disso, o livro reconhece o ministério profético de Ellen G. White, cofundadora da Igreja Adventista do Sétimo Dia. Isso, por si só, mostra que a IASD não crê que Deus esteja impedido de usar mulheres em ensino, exortação e liderança espiritual.</p>
                <p>Textos relevantes:</p>
                <p><a href="#" class="biblical-reference" data-reference="Joel 2:28-29">Joel 2:28-29</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Atos 2:17-18">Atos 2:17-18</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 16:1-27">Romanos 16:1-27</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Juízes 4:1-5">Juízes 4:1-5</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Atos 18:26">Atos 18:26</a></p>
                <p>Na prática, mulheres podem:</p>
                <p>pregar;</p>
                <p>ensinar;</p>
                <p>dar estudos bíblicos;</p>
                <p>liderar ministérios;</p>
                <p>servir em muitas funções eclesiásticas.</p>
            </div>

            <div class="doctrine-item">
                <h4>16.2 Por que existe controvérsia sobre a ordenação de mulheres ao pastorado na IASD?</h4>
                <p>Porque esse tema envolve não apenas dons espirituais, mas também interpretação bíblica, prática eclesiástica mundial, unidade denominacional e compreensão do ofício pastoral ordenado.</p>
                <p>Os dois lados normalmente concordam que mulheres podem servir a Deus de forma poderosa. A controvérsia está em outra pergunta: isso inclui ordenação ao pastorado no mesmo sentido formal aplicado aos homens?</p>
                <p>As áreas de debate incluem:</p>
                <p>interpretação de 1 Timóteo 2;</p>
                <p>interpretação de 1 Coríntios 11 e 14;</p>
                <p>relação entre criação, queda e liderança;</p>
                <p>distinção entre dom ministerial e ordenação eclesiástica;</p>
                <p>unidade mundial da igreja.</p>
                <p>Como você pediu a posição oficial, o ponto importante é: a IASD mundial não estabeleceu ordenação feminina ao pastorado como prática universal obrigatória. Por isso o tema permanece controverso.</p>
            </div>

            <div class="doctrine-item">
                <h4>16.3 O que a Bíblia diz, segundo a leitura adventista, sobre mulheres como pastoras e anciãs?</h4>
                <p>A resposta adventista oficial aqui exige cuidado, porque a igreja mundial abriga discussões interpretativas internas. Mas alguns pontos são claros:</p>
                <p>a Bíblia mostra mulheres exercendo papéis espirituais importantes;</p>
                <p>a Bíblia também contém textos usados por muitos para restringir certas funções de governo e ensino autoritativo eclesial.</p>
                <p>Textos em favor de forte participação feminina:</p>
                <p><a href="#" class="biblical-reference" data-reference="Joel 2:28-29">Joel 2:28-29</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Atos 2:17-18">Atos 2:17-18</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Romanos 16:1-27">Romanos 16:1-27</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Juízes 4:1-5">Juízes 4:1-5</a></p>
                <p><a href="#" class="biblical-reference" data-reference="Atos 18:26">Atos 18:26</a></p>
                <p>Textos usados nas restrições:</p>
                <p><a href="#" class="biblical-reference" data-reference="1 Timóteo 2:11-15">1 Timóteo 2:11-15</a></p>
                <p><a href="#" class="biblical-reference" data-reference="1 Coríntios 14:34-35">1 Coríntios 14:34-35</a></p>
                <p>1 Timóteo 3</p>
                <p>Tito 1</p>
                <p>Na prática adventista:</p>
                <p>quanto a pregar, ensinar e liderar, a resposta é claramente sim;</p>
                <p>quanto a ordenação pastoral, a questão permanece debatida no âmbito mundial;</p>
                <p>quanto a anciãs, em muitas regiões e contextos a função existe, mas a aplicação pode variar conforme decisões e políticas eclesiásticas.</p>
                <p>Como posição oficial mais segura: a IASD reconhece os dons das mulheres e seu amplo ministério, mas a questão da ordenação ao pastorado não foi uniformemente resolvida para toda a igreja mundial.</p>
            </div>
        </div>

    <div class="faq-estudo-biblico">
        <h3>📖 Quer Aprofundar Seus Conhecimentos Bíblicos?</h3>
        <p>Descubra como a Bíblia pode transformar sua vida através de nossos estudos bíblicos gratuitos!</p>
        <p>Oferecemos estudos <strong>presenciais</strong> (na sua residência ou na igreja), <strong>online</strong> (por videoconferência) e <strong>remotos</strong> (por telefone ou mensagem).</p>
        <p>Solicite materiais de estudo gratuitos e comece sua jornada espiritual hoje mesmo!</p>
        <a href="{{ route('estudo-biblico') }}" class="btn-estudo-biblico">Acessar Estudo Bíblico</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.faq-tab-button');
    const tabContents = document.querySelectorAll('.faq-tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');

            // Remove active class from all buttons
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            // Hide all tab contents
            tabContents.forEach(content => content.classList.remove('active'));
            // Show selected tab content
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Sub-tabs functionality
    const subTabButtons = document.querySelectorAll('.sub-tab-button');
    const subTabContents = document.querySelectorAll('.sub-tab-content');

    subTabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const subTabId = this.getAttribute('data-subtab');

            // Remove active class from all sub-tab buttons
            subTabButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            // Hide all sub-tab contents
            subTabContents.forEach(content => content.classList.remove('active'));
            // Show selected sub-tab content
            document.getElementById(subTabId).classList.add('active');
        });
    });
});
</script>
@endpush
