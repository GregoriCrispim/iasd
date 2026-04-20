@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Estudo Bíblico')

@push('styles')
<style>

    .estudo-biblico-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .estudo-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    .estudo-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .estudo-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto;
    }

    .motivos-section {
        margin: 60px 0;
    }

    .motivos-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .motivos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .motivo-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 25px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .motivo-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border-color: rgba(211, 84, 0, 0.4);
    }

    .motivo-card .emoji {
        font-size: 3em;
        margin-bottom: 20px;
        display: block;
        color: #d35400;
    }

    .motivo-card h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3em;
        color: #003366;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .motivo-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #666;
        line-height: 1.6;
    }

    .experiencia-section {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 60px 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
        color: #fff;
    }

    .experiencia-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #fff;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .experiencia-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #f8f9fa;
        margin-bottom: 20px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .experiencia-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr));
        gap: 30px;
        margin: 40px auto;
        max-width: 900px;
        width: 100%;
    }

    .experiencia-card {
        background: rgba(255,255,255,0.1);
        padding: 30px;
        border-radius: 12px;
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
        min-width: 0;
        overflow: hidden;
        color: #fff;
        text-align: left;
    }

    .experiencia-card i {
        font-size: 3em;
        display: block;
        margin-bottom: 15px;
    }

    .experiencia-card h3 {
        color: #fff;
        font-family: 'Roboto', sans-serif;
        font-size: 1.3em;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .experiencia-card p {
        color: #f8f9fa;
        margin: 0;
        line-height: 1.7;
        max-width: none;
    }

    .experiencia-highlight {
        background: rgba(255,255,255,0.15);
        padding: 30px 40px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.22);
        max-width: 800px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .experiencia-highlight p {
        color: #fff;
        font-size: 1.2em;
        font-weight: 600;
        margin: 0;
        text-align: center;
        max-width: none;
    }

    .como-funciona-section {
        margin: 60px 0;
    }

    .como-funciona-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .steps-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .step {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 25px 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        align-items: flex-start;
        gap: 20px;
    }

    .step:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.12);
    }

    .step-number {
        background: #003366;
        color: #fff;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.3em;
        flex-shrink: 0;
        font-family: 'Bebas neue', sans-serif;
    }

    .step-content h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3em;
        color: #003366;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .step-content p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #666;
        line-height: 1.6;
    }

    .materiais-section {
        background: radial-gradient(900px circle at 10% 0%, rgba(0, 51, 102, 0.06) 0%, rgba(0, 51, 102, 0) 55%),
        #f8f9fa;
        padding: 44px 40px 34px;
        border-radius: 16px;
        margin: 50px 0 0;
        border: 1px solid rgba(0, 51, 102, 0.10);
    }

    .materiais-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .materiais-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .material-card {
        background: rgba(255,255,255,0.94);
        border: 1px solid rgba(0, 51, 102, 0.12);
        border-radius: 14px;
        padding: 18px 16px 16px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease, background 0.18s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .material-card:hover {
        transform: translateY(-3px);
        border-color: rgba(0, 51, 102, 0.22);
        background: #fff;
        box-shadow: 0 14px 40px rgba(0,0,0,0.12);
    }

    .material-card:focus-visible {
        outline: 3px solid rgba(0, 51, 102, 0.28);
        outline-offset: 3px;
    }

    .material-card .emoji {
        width: 52px;
        height: 52px;
        margin: 0 auto 12px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        background: rgba(0, 51, 102, 0.08);
        color: #003366;
        font-size: 1.55rem;
    }

    .material-card h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        color: #003366;
        margin-bottom: 0;
        font-weight: 700;
        line-height: 1.35;
    }

    .btn-material-destaque {
        display: block;
        text-align: center;
        background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%);
        color: #fff;
        padding: 18px 50px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.2em;
        margin: 0 auto 24px auto;
        max-width: 450px;
        transition: transform 0.3s, box-shadow 0.3s;
        font-family: 'Roboto', sans-serif;
        border-bottom: 3px solid #ba4a00;
    }

    .btn-material-destaque:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(211, 84, 0, 0.3);
        border-bottom-color: #9e4100;
    }

    .estudo-request {
        background: radial-gradient(900px circle at 20% 0%, rgba(255,255,255,0.85) 0%, rgba(255,255,255,0) 55%),
        linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        padding: 44px 40px;
        border-radius: 16px;
        margin: 0 0 56px;
        border: 1px solid rgba(0, 51, 102, 0.12);
        border-bottom: 3px solid rgba(211, 84, 0, 0.4);
    }

    .estudo-request__inner {
        max-width: 980px;
        margin: 0 auto;
        text-align: center;
    }

    .estudo-request__title {
        font-size: clamp(1.9rem, 3.2vw, 2.6rem);
        color: #003366;
        margin-bottom: 14px;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .estudo-request__lead {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.75;
        color: rgba(0,0,0,0.78);
        margin: 0 auto 22px;
        max-width: 860px;
    }

    .estudo-request__modes {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin: 22px auto 18px;
        max-width: 980px;
    }

    .estudo-request__mode {
        background: rgba(255,255,255,0.92);
        border: 1px solid rgba(0, 51, 102, 0.10);
        border-radius: 14px;
        padding: 18px 18px 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        text-align: left;
        min-width: 0;
    }

    .estudo-request__mode-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%);
        color: #fff;
        font-size: 1.55rem;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(211, 84, 0, 0.3);
    }

    .estudo-request__mode h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        color: #003366;
        font-weight: 700;
        margin-bottom: 6px;
        transition: color 0.18s ease;
    }

    .estudo-request__mode p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: rgba(0,0,0,0.62);
        margin: 0;
        line-height: 1.5;
    }

    .estudo-request .container_form {
        margin-top: 10px;
    }

    .estudo-request .form-open-btn {
        appearance: none;
        border: 0;
        background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%);
        color: #fff;
        padding: 14px 22px;
        margin: 6px 0 0;
        border-radius: 12px;
        font-weight: 800;
        letter-spacing: 0.01em;
        box-shadow: 0 14px 30px rgba(211, 84, 0, 0.3);
        transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
        border-bottom: 3px solid rgba(186, 74, 0, 0.6);
    }

    .estudo-request .form-open-btn:hover {
        transform: none;
        box-shadow: 0 14px 30px rgba(211, 84, 0, 0.3);
        filter: none;
        border-bottom-color: rgba(186, 74, 0, 0.6);
        background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%);
    }

    .estudo-request .form-open-btn:focus-visible {
        outline: 3px solid rgba(249, 160, 27, 0.55);
        outline-offset: 3px;
    }

    .container_form {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .container_label {
        width: 100%;
        display: flex;
        flex-direction: column;
        margin-top: 20px;
    }

    label {
        margin-bottom: 4px;
        font-family: 'Roboto';
    }

    .container_input {
        position: relative;
    }

    .container_input i {
        position: absolute;
        bottom: 6px;
        left: 6px;
    }

    input {
        width: 100%;
        padding: 6px 0;
        border: 1px solid rgb(192, 191, 191);
        text-indent: 26px;
        border-radius: 8px;
    }

    textarea {
        text-indent: 6px;
        border: 1px solid rgb(192, 191, 191);
        padding: 6px 0;
        border-radius: 8px;
    }

    button {
        background-color: #d35400;
        color: #fff;
        margin: 15px 0 25px 0;
        padding: 12px 50px;
        border-radius: 6px;
        cursor: pointer;
        transition: .4s;
        border-bottom: 3px solid #ba4a00;
    }

    button:hover {
        background-color: #ba4a00;
        border-bottom-color: #9e4100;
    }

    @media (max-width: 768px) {
        .estudo-biblico-container {
            padding: 20px 15px;
        }

        .estudo-intro {
            padding: 30px 20px;
        }

        .estudo-intro h1 {
            font-size: 2.2em;
        }

        .motivos-grid,
        .materiais-grid {
            grid-template-columns: 1fr;
        }

        .experiencia-section {
            padding: 40px 20px;
        }

        .experiencia-cards {
            gap: 16px;
            margin: 24px auto;
        }

        .experiencia-card {
            padding: 22px 18px;
            text-align: center;
        }

        .experiencia-highlight {
            padding: 22px 18px;
        }

        .step {
            flex-direction: column;
            text-align: center;
        }

        .step-number {
            margin: 0 auto;
        }

        .materiais-section {
            padding: 28px 18px 22px;
            margin: 38px 0 0;
        }

        .btn-material-destaque {
            padding: 16px 18px;
            font-size: 1.08em;
            margin: 0 auto 18px auto;
        }

        .estudo-request {
            padding: 28px 18px;
            margin: 0 0 38px;
        }

        .estudo-request__modes {
            grid-template-columns: 1fr;
            gap: 12px;
            margin: 18px auto 14px;
        }

        .estudo-request__mode {
            text-align: center;
            padding: 16px 16px 14px;
        }

        .estudo-request__mode-icon {
            margin: 0 auto 10px;
        }
    }
</style>
@endpush

@section('content')
<img src="{{ asset('img/cards/estudo_biblico/estudo_biblico_header.webp') }}" alt="Estudo Bíblico" style="width: 100%;" fetchpriority="high" decoding="async">

<div class="estudo-biblico-container">

    <!-- Seção Introdutória -->
    <div class="estudo-intro acb-fullbleed">
        <h1>Estudo Bíblico: Uma Jornada para Conectar-se com Deus</h1>
        <p>
            Procurando respostas, fortalecimento espiritual ou alívio para desafios emocionais? O Estudo Bíblico é o caminho! Seja qual for sua idade ou momento de vida, aqui você encontrará um espaço acolhedor, dinâmico e adaptado às suas necessidades. Encontros presenciais na sua residência, na igreja ou online — <strong>você escolhe como participar!</strong>
        </p>
    </div>

    <!-- Seção Por Que Estudar a Bíblia -->
    <div class="motivos-section">
        <h2 class="acb-title-serif">Por que estudar a Bíblia?</h2>
        <div class="motivos-grid">
            <div class="motivo-card">
                <i class="bi bi-book-half emoji"></i>
                <h3>Aprendizado Simples</h3>
                <p>Aprenda de forma simples como os ensinamentos de Jesus transformam vidas.</p>
            </div>

            <div class="motivo-card">
                <i class="bi bi-patch-question-fill emoji"></i>
                <h3>Respostas Reais</h3>
                <p>Descubra respostas para questões pessoais e espirituais, guiado pelo amor de Cristo.</p>
            </div>

            <div class="motivo-card">
                <i class="bi bi-people-fill emoji"></i>
                <h3>Conexão Autêntica</h3>
                <p>Conecte-se com Deus de maneira prática e autêntica, em comunidade.</p>
            </div>
        </div>
    </div>

    <!-- Seção Experiência -->
    <div class="experiencia-section acb-fullbleed">
        <h2 class="acb-title-serif">Mais que estudo, uma experiência!</h2>

        <div class="experiencia-cards">
            <div class="experiencia-card">
                <i class="bi bi-stars"></i>
                <h3>Transformação Diária</h3>
                <p>Cada lição é um passo para entender melhor a Palavra de Deus e seu propósito para você.</p>
            </div>

            <div class="experiencia-card">
                <i class="bi bi-stars"></i>
                <h3>Renovação e Esperança</h3>
                <p>Venha renovar sua esperança, encontrar apoio e caminhar mais perto dEle.</p>
            </div>

            <div class="experiencia-card">
                <i class="bi bi-heart"></i>
                <h3>Crescimento Espiritual</h3>
                <p>Venha estudar, compartilhar e crescer na graça de Deus!</p>
            </div>
        </div>

        <div class="experiencia-highlight">
            <p>
                <i class="bi bi-star-fill"></i> Sua jornada espiritual começa agora! Descubra como a Bíblia pode iluminar sua vida!
            </p>
        </div>
    </div>

    <!-- Seção Como Funciona -->
    <div class="como-funciona-section">
        <h2 class="acb-title-serif">Como funciona?</h2>
        <div class="steps-container">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>Ambiente Leve</h3>
                    <p>Materiais como Bíblia e guias são fornecidos. Suas dúvidas e experiências são sempre bem-vindas!</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>Encontros Envolventes</h3>
                    <p>Começamos com oração, exploramos passagens bíblicas e refletimos juntos.</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Transformação Real</h3>
                    <p>Ao final, você é incentivado a aplicar os aprendizados no dia a dia e crescer na fé.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção Materiais de Estudo -->
    <div class="materiais-section acb-fullbleed">
        <h2 class="acb-title-serif">Materiais de Estudo</h2>
        <p style="text-align: center; font-family: 'Roboto', sans-serif; font-size: 1.1rem; color: #666; margin-bottom: 30px;">
            Acesse nossos conteúdos e solicite materiais gratuitos.
        </p>

        <a href="https://downloads.adventistas.org/pt/evangelismo/estudos-biblicos/cursos-biblicos/" target="_blank" class="btn-material-destaque">
            <i class="bi bi-journals"></i> Materiais para Estudo Bíblico
        </a>

        <div class="materiais-grid">
            <a href="https://cursos.novotempo.com/" target="_blank" class="material-card">
                <i class="bi bi-gift emoji"></i>
                <h4>Solicite materiais impressos ou digitais gratuitamente</h4>
            </a>

            <a href="https://www.youtube.com/user/BibliaFacil" target="_blank" class="material-card">
                <i class="bi bi-camera-video emoji"></i>
                <h4>Canal Bíblia Fácil</h4>
            </a>

            <a href="https://www.youtube.com/@NaMiradaVerdadeNT" target="_blank" class="material-card">
                <i class="bi bi-search emoji"></i>
                <h4>Canal Na Mira da Verdade</h4>
            </a>
        </div>
    </div>

    <!-- Seção Solicitação de Estudo Bíblico -->
    <section class="estudo-request acb-fullbleed" aria-labelledby="estudo-request-title">
        <div class="estudo-request__inner">
            <h2 id="estudo-request-title" class="acb-title-serif estudo-request__title">
                Solicite Seu Estudo Bíblico Gratuito
            </h2>

            <p class="estudo-request__lead">
                Deseja aprofundar seu conhecimento na Palavra de Deus? Oferecemos <strong>estudos bíblicos gratuitos</strong> que podem ser realizados da forma que preferir!
            </p>

            <div class="estudo-request__modes" aria-label="Formas de participação">
                <article class="estudo-request__mode">
                    <div class="estudo-request__mode-icon" aria-hidden="true">
                        <i class="bi bi-house"></i>
                    </div>
                    <h3>Presencial</h3>
                    <p>Na sua residência ou na igreja</p>
                </article>

                <article class="estudo-request__mode">
                    <div class="estudo-request__mode-icon" aria-hidden="true">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <h3>Online</h3>
                    <p>Por videoconferência</p>
                </article>

                <article class="estudo-request__mode">
                    <div class="estudo-request__mode-icon" aria-hidden="true">
                        <i class="bi bi-phone"></i>
                    </div>
                    <h3>Remoto</h3>
                    <p>Por telefone ou mensagem</p>
                </article>
            </div>

            <!-- FORMULÁRIO -->
            <div class="container_form">
                <div class="form-cta" @if($errors->any()) data-open-on-load="1" @endif>
                    @if ($errors->any())
                        <div class="form-server-errors" style="display:none;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php
                        $formConfigEstudoBiblico = [
                            'title' => 'Preencha o formulário',
                            'subtitle' => 'Solicitação de Estudo Bíblico',
                            'action' => route('estudo-biblico.enviar'),
                            'method' => 'POST',
                            'submitLabel' => 'Enviar Mensagem',
                            'fields' => [
                                ['type' => 'text', 'name' => 'nome', 'label' => 'Nome', 'placeholder' => 'Nome', 'required' => true, 'autocomplete' => 'name'],
                                ['type' => 'email', 'name' => 'email', 'label' => 'E-mail', 'placeholder' => 'E-mail', 'required' => true, 'autocomplete' => 'email'],
                                ['type' => 'tel', 'name' => 'telefone', 'label' => 'Telefone', 'placeholder' => '(00) 00000-0000', 'required' => true, 'autocomplete' => 'tel', 'inputmode' => 'tel', 'maxlength' => 15, 'mask' => 'br-phone'],
                                ['type' => 'textarea', 'name' => 'mensagem', 'label' => 'Mensagem', 'placeholder' => 'Mensagem', 'required' => true, 'rows' => 10],
                            ],
                        ];
                    @endphp

                    <button
                        type="button"
                        class="btn-primary-solid form-open-btn"
                        data-form-config='@json($formConfigEstudoBiblico)'>
                        Preencher Formulário
                    </button>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
