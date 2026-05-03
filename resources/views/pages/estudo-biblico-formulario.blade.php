@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Solicitação de Estudo Bíblico')

@section('meta-description', 'Solicite seu estudo bíblico gratuito agora. Preencha o formulário e nossa equipe entrará em contato. Estudos presenciais ou online.')
@section('og-title', 'Solicitação de Estudo Bíblico - IASD Central de Brasília')
@section('og-description', 'Dê o primeiro passo! Solicite seu estudo bíblico gratuito preenchendo o formulário.')
@section('page-name', 'Solicitação de Estudo Bíblico')

@push('styles')
<style>

    .estudo-form-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .estudo-form-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    .estudo-form-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .estudo-form-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto 20px;
    }

    .form-section {
        background: #fff;
        padding: 50px 24px;
        border-radius: 15px;
        margin: 50px 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .form-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .estudo-form {
        max-width: 700px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #333;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .input-wrapper {
        position: relative;
    }

    .input-wrapper i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #003366;
        font-size: 1.2rem;
    }

    .form-group input[type="text"],
    .form-group input[type="tel"],
    .form-group input[type="email"],
    .form-group textarea {
        width: 100%;
        padding: 14px 15px 14px 50px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #333;
        transition: border-color 0.3s, box-shadow 0.3s;
        background: #f8f9fa;
    }

    .form-group textarea {
        padding-left: 15px;
        resize: vertical;
        min-height: 150px;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #003366;
        box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.1);
        background: #fff;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: #999;
    }

    .btn-submit {
        display: block;
        width: 100%;
        padding: 16px 40px;
        background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        margin-top: 30px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(211, 84, 0, 0.3);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .voltar-link {
        display: inline-block;
        margin-top: 20px;
        color: #003366;
        text-decoration: none;
        font-family: 'Roboto', sans-serif;
        font-weight: 500;
        transition: color 0.3s;
    }

    .voltar-link:hover {
        color: #d35400;
    }

    .form-section a.btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(211, 84, 0, 0.3);
    }

    @media (max-width: 768px) {
        .estudo-form-container {
            padding: 20px 15px;
        }

        .estudo-form-intro {
            padding: 30px 20px;
        }

        .estudo-form-intro h1 {
            font-size: 2.2em;
        }

        .form-section {
            padding: 28px 14px;
        }

        .form-section h2 {
            font-size: 2em;
        }

        .estudo-form {
            max-width: 100%;
        }

        .form-group input[type="text"],
        .form-group input[type="tel"],
        .form-group input[type="email"] {
            padding-left: 45px;
        }
    }
</style>
@endpush

@section('content')
<img src="{{ asset('img/cards/estudo_biblico/estudo_biblico_header.webp') }}" alt="Estudo Bíblico" style="width: 100%;" fetchpriority="high" decoding="async">

<div class="estudo-form-container">
    <!-- Seção Introdutória -->
    <div class="estudo-form-intro acb-fullbleed">
        <h1>Solicite Seu Estudo Bíblico Gratuito</h1>
        <p>
            Preencha o formulário abaixo para solicitar seu estudo bíblico gratuito. Oferecemos estudos presenciais (na sua residência ou na igreja), online por videoconferência, ou por telefone/mensagem. Entre em contato e conheça mais sobre a Palavra de Deus!
        </p>
    </div>

    <!-- Seção do Formulário -->
    <div class="form-section">
        <h2 class="acb-title-serif">Preencha o formulário</h2>

        @if ($errors->any())
            <div class="alert alert-danger" style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success" style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('estudo-biblico.enviar') }}" method="POST" class="estudo-form">
            @csrf

            <div class="form-group">
                <label for="nome">Nome</label>
                <div class="input-wrapper">
                    <i class='bx bx-user'></i>
                    <input type="text" name="nome" id="nome" placeholder="Digite seu nome completo" required value="{{ old('nome') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <div class="input-wrapper">
                    <i class='bx bx-envelope'></i>
                    <input type="email" name="email" id="email" placeholder="seu@email.com" required value="{{ old('email') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <div class="input-wrapper">
                    <i class='bx bx-phone'></i>
                    <input type="tel" name="telefone" id="telefone" placeholder="(00) 00000-0000" required value="{{ old('telefone') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="mensagem">Mensagem</label>
                <textarea name="mensagem" id="mensagem" placeholder="Compartilhe suas dúvidas, interesse ou mensagem..." required>{{ old('mensagem') }}</textarea>
            </div>

            <button type="submit" class="btn-submit">Enviar Mensagem</button>
        </form>

        <div style="text-align: center;">
            <a href="{{ route('estudo-biblico') }}" class="voltar-link">
                <i class='bx bx-arrow-back'></i> Voltar para página de Estudo Bíblico
            </a>
        </div>
    </div>

    <!-- Seção Questões sobre Doutrina -->
    <div class="form-section" style="background: linear-gradient(135deg, #003366 0%, #001531 100%); color: #fff; text-align: center; padding: 50px 24px;">
        <i class='bx bx-book-open' style="font-size: 3rem; color: #fff; margin-bottom: 20px; display: block;"></i>
        <h2 class="acb-title-serif" style="font-size: 2.5em; color: #fff; margin-bottom: 25px; font-weight: 700;">Tem perguntas sobre doutrina?</h2>
        <p style="font-family: 'Roboto', sans-serif; font-size: 1.15rem; line-height: 1.8; color: #f8f9fa; margin-bottom: 30px; max-width: 800px; margin-left: auto; margin-right: auto;">
            Explore nossa seção de Perguntas Frequentes para encontrar respostas detalhadas sobre as doutrinas bíblicas adventistas. Descubra mais sobre sábado, dom de profecia, juízo investigativo, estado dos mortos e muito mais!
        </p>
        <a href="{{ route('faq') }}#doutrina" class="btn-submit" style="display: inline-block; width: auto; padding: 16px 40px; background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%); color: #fff; border: none; border-radius: 10px; font-family: 'Roboto', sans-serif; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: transform 0.3s, box-shadow 0.3s; text-decoration: none;">
            <i class='bx bx-help-circle'></i> Ver Questões sobre Doutrina
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script>
    // Função para manter apenas dígitos
    function onlyDigits(value) {
        return String(value || '').replace(/\D+/g, '');
    }

    // Função para formatar telefone no padrão brasileiro
    function formatBrPhone(value) {
        const digits = onlyDigits(value).slice(0, 11);
        if (!digits) return '';

        const ddd = digits.slice(0, 2);
        const rest = digits.slice(2);

        if (digits.length <= 10) {
            const p1 = rest.slice(0, 4);
            const p2 = rest.slice(4, 8);
            if (!rest) return `(${ddd}`;
            if (rest.length <= 4) return `(${ddd}) ${p1}`;
            return `(${ddd}) ${p1}-${p2}`;
        }

        const p1 = rest.slice(0, 5);
        const p2 = rest.slice(5, 9);
        if (!rest) return `(${ddd}`;
        if (rest.length <= 5) return `(${ddd}) ${p1}`;
        return `(${ddd}) ${p1}-${p2}`;
    }

    // Aplicar máscara de telefone
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        const telefoneHandler = () => {
            const next = formatBrPhone(telefoneInput.value);
            if (telefoneInput.value !== next) telefoneInput.value = next;
        };

        // Inicial
        telefoneHandler();

        telefoneInput.addEventListener('input', telefoneHandler);
        telefoneInput.addEventListener('blur', telefoneHandler);
        telefoneInput.addEventListener('paste', () => setTimeout(telefoneHandler, 0));
    }

    // Bloquear números no campo nome
    const nomeInput = document.getElementById('nome');
    if (nomeInput) {
        nomeInput.addEventListener('keypress', function(e) {
            if (/\d/.test(e.key)) {
                e.preventDefault();
            }
        });

        nomeInput.addEventListener('paste', function(e) {
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            if (/\d/.test(paste)) {
                e.preventDefault();
            }
        });
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();
        const telefone = document.getElementById('telefone').value.trim();

        if (email.length === 0 || !email.includes('@')) {
            alert('Por favor, insira um e-mail válido.');
            e.preventDefault();
            return;
        }

        if (telefone.length === 0) {
            alert('Por favor, insira um telefone válido.');
            e.preventDefault();
            return;
        }
    });
</script>
@endpush
