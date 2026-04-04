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
        "text": "Temos cultos aos sábados às 09h00 e 19h00. A Escola Sabatina acontece às 08h00."
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
.faq-container {
    width: 100%;
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px;
}

.faq-header {
    text-align: center;
    margin-bottom: 50px;
}

.faq-header h1 {
    font-family: 'Bebas neue', sans-serif;
    font-size: 3em;
    color: #003366;
    margin-bottom: 15px;
}

.faq-header p {
    font-family: 'Roboto', sans-serif;
    font-size: 1.1rem;
    color: #666;
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

@media (max-width: 768px) {
    .faq-container {
        padding: 20px 15px;
    }

    .faq-header h1 {
        font-size: 2.2em;
    }

    .faq-item {
        padding: 20px;
    }
}
</style>
@endpush

@section('content')
<div class="faq-container">
    <div class="faq-header">
        <h1>Perguntas Frequentes</h1>
        <p>Encontre respostas para as dúvidas mais comuns sobre nossa igreja.</p>
    </div>

    <div class="faq-list">
        <div class="faq-item">
            <h3>Quais são os horários dos cultos?</h3>
            <p>Temos cultos aos sábados às 09h00 e 19h00. A Escola Sabatina acontece às 08h00. Você é muito bem-vindo em todos os nossos encontros!</p>
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
            <p>Oferecemos estudos bíblicos gratuitos, escola sabatina para todas as idades, corais e orquestras, ministérios para todas as faixas etárias (crianças, adolescentes, jovens, adultos e idosos), eventos especiais ao longo do ano, ações comunitárias através da ASA (Ação Solidária Adventista), suporte espiritual através do Ministério de Oração, e muito mais.</p>
        </div>

        <div class="faq-item">
            <h3>Como participar da IASD Central de Brasília?</h3>
            <p>Você pode participar dos nossos cultos aos sábados, inscrever-se gratuitamente em estudos bíblicos, juntar-se a um dos nossos ministérios, participar de nossos eventos e programações especiais, ou simplesmente visitar-nos para conhecer nossa comunidade. Todos são bem-vindos!</p>
        </div>
    </div>

    <div class="faq-contact">
        <h3>Ainda Tem Dúvidas?</h3>
        <p>Entre em contato conosco! Estamos aqui para ajudar.</p>
        <p>📞 <strong>(61) 98157-4702</strong></p>
        <p>✉️ <a href="mailto:comunicacaocentralbsb@gmail.com">comunicacaocentralbsb@gmail.com</a></p>
    </div>
</div>
@endsection
