@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Fale com a Secretaria')

@section('meta-description', 'Entre em contato com a secretaria da IASD Central de Brasília. Atualize seus dados, faça inscrições e tire suas dúvidas.')
@section('og-title', 'Secretaria - IASD Central de Brasília')
@section('og-description', 'Fale com nossa secretaria e mantenha seus dados atualizados!')
@section('page-name', 'Secretaria')

@push('styles')
<style>

    .secretaria-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .secretaria-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 0;
        text-align: center;
    }

    .secretaria-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .secretaria-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto 20px;
    }

    .missao-section {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 60px 40px;
        border-radius: 15px;
        margin: 0 0 50px;
        text-align: center;
        color: #fff;
    }

    .missao-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #fff;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .missao-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #f8f9fa;
        max-width: 900px;
        margin: 0 auto 20px;
    }

    .missao-section p:last-child {
        margin-bottom: 0;
    }

    .servicos-section {
        background: #f8f9fa;
        padding: 60px 40px;
        border-radius: 15px;
        margin: 60px 0;
        border-left: 5px solid #003366;
    }

    .servicos-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .servicos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
    }

    .servico-card {
        background: #fff;
        border-radius: 12px;
        padding: 30px 25px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .servico-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }

    .servico-card h4 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.4em;
        color: #003366;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .servico-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .servico-card li {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #555;
        line-height: 1.7;
        margin-bottom: 8px;
        position: relative;
        padding-left: 20px;
    }

    .servico-card li:before {
        content: "▸";
        position: absolute;
        left: 0;
        color: #003366;
        font-weight: bold;
    }

    .observacao-box {
        background: #fff3cd;
        border-left: 5px solid #ffc107;
        padding: 20px 25px;
        border-radius: 8px;
        margin-top: 30px;
    }

    .observacao-box p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #856404;
        margin: 0;
        font-weight: 500;
    }

    .contato-section {
        background: linear-gradient(135deg, #003366 0%, #1b4472 100%);
        padding: 70px 40px;
        border-radius: 15px;
        margin: 60px 0;
        text-align: center;
        color: #fff;
    }

    .contato-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.8em;
        color: #fff;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .contato-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        color: #f8f9fa;
        margin-bottom: 35px;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-formulario {
        display: inline-block;
        background-color: #fff;
        color: #003366;
        padding: 18px 50px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.2em;
        transition: all 0.3s;
        font-family: 'Roboto', sans-serif;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .btn-formulario:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        background-color: #f8f9fa;
    }

    .faq-section {
        margin: 70px 0;
    }

    .faq-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.8em;
        color: #003366;
        text-align: center;
        margin-bottom: 50px;
        font-weight: 500;
    }

    .faq-grid {
        display: grid;
        gap: 20px;
        max-width: 900px;
        margin: 0 auto;
    }

    .faq-item {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 30px 35px;
        transition: all 0.3s;
        cursor: pointer;
        position: relative;
    }

    .faq-item:hover {
        border-color: #003366;
        box-shadow: 0 5px 20px rgba(0,51,102,0.15);
        transform: translateX(5px);
    }

    .faq-question {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.4em;
        color: #003366;
        margin-bottom: 15px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .faq-question .icon {
        font-size: 1.2em;
        background: linear-gradient(135deg, #003366 0%, #1b4472 100%);
        color: #fff;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-weight: bold;
    }

    .faq-answer {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #555;
        line-height: 1.8;
        padding-left: 47px;
    }

    .convite-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 60px 50px;
        border-radius: 15px;
        margin: 70px 0;
        text-align: center;
        border-left: 5px solid #003366;
        border-right: 5px solid #003366;
    }

    .convite-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.2em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .convite-section .verse {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3rem;
        color: #333;
        font-style: italic;
        line-height: 1.9;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .convite-section .verse-reference {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #003366;
        font-weight: 600;
        margin-bottom: 25px;
    }

    .convite-section .mensagem {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #555;
        line-height: 1.9;
        max-width: 800px;
        margin: 0 auto;
    }

    @media (max-width: 768px) {
        .secretaria-container {
            padding: 20px 15px;
        }

        .secretaria-intro {
            padding: 30px 20px;
        }

        .secretaria-intro h1 {
            font-size: 2.2em;
        }

        .missao-section {
            padding: 40px 20px;
        }

        .missao-section h2 {
            font-size: 2em;
        }

        .servicos-grid {
            grid-template-columns: 1fr;
        }

        .servicos-section h2 {
            font-size: 2em;
        }

        .contato-section h2 {
            font-size: 2em;
        }

        .faq-section h2 {
            font-size: 2em;
        }

        .faq-item {
            padding: 25px 20px;
        }

        .convite-section {
            padding: 40px 25px;
        }

        .convite-section h3 {
            font-size: 1.8em;
        }

        .convite-section .verse {
            font-size: 1.1rem;
        }
    }
</style>
@endpush

@section('content')
<img src="{{ asset('img/cards/secretaria/secretaria_header.webp') }}" alt="IASD Central de Brasília - Secretaria" style="width: 100%;" fetchpriority="high" decoding="async">

<div class="secretaria-container">

    <!-- Seção Introdutória -->
    <div class="secretaria-intro acb-fullbleed">
        <h1 class="acb-title-serif">FALE COM A SECRETARIA</h1>
        <p>
            A Secretaria da Igreja Adventista do Sétimo Dia de Brasília está aqui para servi-lo(a) com excelência, cortesia e eficiência. Nosso compromisso é oferecer apoio administrativo e espiritual a membros e visitantes, facilitando a vida da comunidade eclesiástica.
        </p>
        <p>
            Como um ministério essencial, a secretaria funciona como o coração administrativo da igreja, conectando membros, departamentos e liderança. Cada atendimento é realizado com dedicação, refletindo os valores cristãos de acolhimento, respeito e profissionalismo.
        </p>
    </div>

    <!-- Seção Missão -->
    <div class="missao-section acb-fullbleed">
        <h2 class="acb-title-serif">MISSÃO DA SECRETARIA</h2>
        <p>
            Nossa missão é honrar a Deus através de um serviço exemplar, mantendo registros precisos, facilitar a comunicação e apoiar a liderança da igreja. Buscamos ser um canal de bênção, demonstrando o amor de Cristo em cada interação.
        </p>
        <p>
            Trabalhamos para garantir que cada membro se sinta valorizado, cuidado e conectado à vida da igreja, mantendo a organização e a eficiência que contribuem para o avanço da obra de Deus em nossa comunidade.
        </p>
    </div>

    <!-- Seção Serviços -->
    <div class="servicos-section acb-fullbleed">
        <h2 class="acb-title-serif">SERVIÇOS DISPONÍVEIS</h2>

        <div class="servicos-grid">
            <div class="servico-card">
                <h4><i class="bi bi-clipboard"></i> Transferência de Membros</h4>
                <ul>
                    <li>Solicitação de transferência para outras igrejas</li>
                    <li>Recebimento de membros transferidos</li>
                    <li>Orientações sobre o processo</li>
                    <li>Acompanhamento do pedido</li>
                </ul>
            </div>

            <div class="servico-card">
                <h4><i class="bi bi-gem"></i> Casamentos Religiosos</h4>
                <ul>
                    <li>Agendamento de cerimônias</li>
                    <li>Orientações sobre documentos necessários</li>
                    <li>Marcação de pré-matrimoniais</li>
                    <li>Certificado de casamento religioso</li>
                </ul>
            </div>

            <div class="servico-card">
                <h4><i class="bi bi-bookmark-fill"></i> Batismos e Profissão de Fé</h4>
                <ul>
                    <li>Inscrição para classe batismal</li>
                    <li>Documentação necessária</li>
                    <li>Agendamento de batismos</li>
                    <li>Certificados de batismo</li>
                </ul>
            </div>

            <div class="servico-card">
                <h4><i class="bi bi-person-heart"></i> Dedicatória de Crianças</h4>
                <ul>
                    <li>Agendamento de dedicatórias</li>
                    <li>Orientações aos pais</li>
                    <li>Certificado de dedicatória</li>
                </ul>
            </div>

            <div class="servico-card">
                <h4><i class="bi bi-pencil"></i> Cartas de Mudança</h4>
                <ul>
                    <li>Emissão de cartas recomendação</li>
                    <li>Atestados de membros</li>
                    <li>Declarações para instituições</li>
                </ul>
            </div>

            <div class="servico-card">
                <h4><i class="bi bi-journals"></i> Registros e Certificados</h4>
                <ul>
                    <li>Certidões de batismo</li>
                    <li>Certidões de casamento</li>
                    <li>Segunda via de documentos</li>
                    <li>Atestados de membresia</li>
                </ul>
            </div>

            <div class="servico-card">
                <h4><i class="bi bi-bar-chart"></i> Estatísticas e Relatórios</h4>
                <ul>
                    <li>Ficha de membros atualização</li>
                    <li>Censo anual</li>
                    <li>Relatórios para associações</li>
                </ul>
            </div>

            <div class="servico-card">
                <h4><i class="bi bi-telephone"></i> Atendimento Geral</h4>
                <ul>
                    <li>Informações sobre a igreja</li>
                    <li>Contato com departamentos</li>
                    <li>Agendamento de reuniões</li>
                    <li>Orientações diversas</li>
                </ul>
            </div>
        </div>

        <div class="observacao-box">
            <p><i class="bi bi-exclamation-triangle-fill"></i> <strong>Observação:</strong> Alguns serviços podem requerer agendamento prévio e documentos específicos. Entre em contato para verificar os requisitos.</p>
        </div>
    </div>

    <!-- Seção de Contato -->
    <div class="contato-section acb-fullbleed">
        <h2 class="acb-title-serif">ENTRE EM CONTATO</h2>
        <p>Tem alguma dúvida ou precisa de algum dos nossos serviços? Preencha o formulário abaixo e entraremos em contato com você o mais breve possível.</p>
        <a href="https://forms.gle/AcLNhK1kJh5mWHed7" target="_blank" class="btn-formulario">ACESSAR FORMULÁRIO DE CONTATO</a>
    </div>

    <!-- Seção FAQ -->
    <div class="faq-section">
        <h2 class="acb-title-serif">Perguntas Frequentes (FAQ)</h2>

        <div class="faq-grid">
            <div class="faq-item">
                <div class="faq-question">
                    <span class="icon">Q</span>
                    Por que a igreja precisa de tantos detalhes?
                </div>
                <div class="faq-answer">
                    Seus dados nos ajudam a oferecer um pastoreio mais próximo e a planejar ações que beneficiem toda a comunidade. Respeitamos sua privacidade e seguimos rigorosamente as leis de proteção de dados.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span class="icon">Q</span>
                    E se eu mudar de telefone ou e-mail?
                </div>
                <div class="faq-answer">
                    Basta nos informar! Uma simples atualização evita que você perca convites e mensagens importantes.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span class="icon">Q</span>
                    Meus dados são compartilhados com terceiros?
                </div>
                <div class="faq-answer">
                    Não. Suas informações são usadas apenas para fins internos e administrativos da igreja, conforme estabelecido em nossa política de privacidade.
                </div>
            </div>
        </div>
    </div>

    <!-- Seção Convite -->
    <div class="convite-section acb-fullbleed">
        <h3 class="acb-title-serif">Juntos Somos Mais Fortes!</h3>
        <blockquote class="acb-quote" style="max-width: 900px; margin: 0 auto 18px;">
            <p>"Conheça o estado das suas ovelhas e cuide bem dos seus rebanhos"</p>
            <span class="acb-quote__ref">Provérbios 27:23</span>
        </blockquote>
        <p class="mensagem">
            Ao manter seus dados atualizados, você fortalece os laços de comunhão e contribui para que nossa igreja cumpra sua missão com excelência. <i class="bi bi-emoji-smile"></i>
        </p>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.servico-card, .faq-item, .convite-section').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endpush
