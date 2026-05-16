@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Oração e Visita')

@section('meta-description', 'Precisando de oração ou uma visita? Deixe seu pedido de oração ou solicite uma visita pastoral da equipe da IASD Central de Brasília. Estamos aqui para você!')
@section('og-title', 'Oração e Visita - IASD Central de Brasília')
@section('og-description', 'Estamos aqui para orar por você e visitar você em momentos difíceis. Deixe seu pedido!')
@section('page-name', 'Oração e Visita')

@push('styles')
<style>

    .oracao-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .oracao-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 30px;
        text-align: center;
    }

    .oracao-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .oracao-intro p {
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

    .oracao-form {
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

    .checkbox-group {
        display: flex;
        align-items: center;
        margin: 25px 0;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        border: 2px solid #e0e0e0;
    }

    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin: 0;
        margin-right: 12px;
        cursor: pointer;
        accent-color: #003366;
    }

    .checkbox-group label {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #333;
        line-height: 1.5;
        margin: 0;
        cursor: pointer;
        user-select: none;
    }

    .btn-submit {
        display: block;
        width: 100%;
        padding: 16px 40px;
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
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
        box-shadow: 0 8px 25px rgba(0, 51, 102, 0.3);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    @media (max-width: 768px) {
        .oracao-container {
            padding: 20px 15px;
        }

        .oracao-intro {
            padding: 30px 20px;
        }

        .oracao-intro h1 {
            font-size: 2.2em;
        }

        .form-section {
            padding: 28px 14px;
        }

        .form-section h2 {
            font-size: 2em;
        }

        .oracao-form {
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
<img src="{{ asset('img/cards/oracao/oracao_header.webp') }}" alt="Oração e Visita" style="width: 100%;" fetchpriority="high" decoding="async">

<div class="oracao-container">
    <!-- Seção Introdutória -->
    <div class="oracao-intro acb-fullbleed">
        <h1>Precisa de Oração ou Visita?<br>Estamos Aqui para Interceder por Você!</h1>
        <p>
            No Ministério de Oração, nossa missão é apoiar você em meio às dificuldades da vida. Oramos por seus pedidos, confiando no poder transformador da oração. Não carregue suas lutas sozinho(a). Deus ouve cada oração e, através da nossa comunidade, queremos ser um canal de esperança para sua vida.
        </p>
    </div>

    <!-- Informações sobre Oração -->
    <div class="oracao-info acb-fullbleed" style="background: #ffffff; padding: 50px 40px; border-radius: 15px; margin-bottom: 50px; text-align: center; border: 2px solid #e9ecef; box-shadow: 0 4px 20px rgba(0, 51, 102, 0.08);">
        <i class='bx bx-pray' style="font-size: 3rem; color: #003366; margin-bottom: 15px; margin-top: -20px; display: block;"></i>
        <h3 class="acb-title-serif" style="font-size: 2.2em; color: #003366; margin-bottom: 30px; margin-top: 10px; font-weight: 700;">Deixe aqui os seus pedidos de oração</h3>
        <p style="font-family: 'Roboto', sans-serif; font-size: 1.1rem; line-height: 1.8; color: #333; text-align: justify; max-width: 900px; margin: 0 auto 25px;">
            Todos os dias teremos alguém intercedendo por eles e, nos dias de culto, seus pedidos estarão na caixa de oração para podermos com a congregação orar por eles. Se preferir participar do nosso Grupo de Oração Presencial é só comparecer a cada 15 dias na Sala de Oração.
        </p>
        <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 900px; margin: 0 auto; text-align: left;">
            <p style="font-family: 'Roboto', sans-serif; font-size: 1.05rem; line-height: 1.8; color: #333; margin-bottom: 20px; display: flex; align-items: flex-start;">
                <span style="color: #003366; font-size: 1.5rem; margin-right: 15px; flex-shrink: 0;">📌</span>
                <span>Cada pedido enviado aqui é tratado com carinho e fé. Ele é impresso e cuidadosamente depositado em nossa Caixa de Oração, onde recebe nossas intercessões semanais.</span>
            </p>
            <p style="font-family: 'Roboto', sans-serif; font-size: 1.05rem; line-height: 1.8; color: #333; margin-bottom: 25px; display: flex; align-items: flex-start;">
                <span style="color: #003366; font-size: 1.5rem; margin-right: 15px; flex-shrink: 0;">🙏</span>
                <span>Oramos com esperança para que Deus atenda ao seu clamor e traga paz ao seu coração.</span>
            </p>
            <blockquote style="background: linear-gradient(135deg, #003366 0%, #001531 100%); padding: 20px 30px; border-radius: 10px; color: #fff; font-family: 'Roboto', sans-serif; font-size: 1.1rem; line-height: 1.6; text-align: center; font-style: italic; margin: 0;">
                "E tudo o que pedirem em oração, se crerem, vocês receberão"
                <span style="display: block; margin-top: 10px; font-size: 0.9em; font-weight: 600; font-style: normal;">Mateus 21:22</span>
            </blockquote>
        </div>
    </div>

    <!-- Seção do Formulário -->
    <div class="form-section">
        <h2 class="acb-title-serif" style="margin-top: -50px;">Preencha o formulário</h2>

        <form action="{{ route('oracao-visita.enviar') }}" method="POST" class="oracao-form">
            @csrf

            <div class="form-group">
                <label for="nome">Nome</label>
                <div class="input-wrapper">
                    <i class='bx bx-user'></i>
                    <input type="text" name="nome" id="nome" placeholder="Digite seu nome completo" required>
                </div>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <div class="input-wrapper">
                    <i class='bx bx-phone'></i>
                    <input type="tel" name="telefone" id="telefone" placeholder="(00) 00000-0000" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class='bx bx-envelope'></i>
                    <input type="email" name="email" id="email" placeholder="seu@email.com" required>
                </div>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" name="contato_pastor" value="1" id="contato_pastor">
                <label for="contato_pastor">Deseja ser contatado por um dos nossos pastores</label>
            </div>

            <div class="form-group">
                <label for="pedido">Pedido de oração</label>
                <textarea name="pedido" id="pedido" placeholder="Compartilhe seu pedido de oração... Oraremos por você." required></textarea>
            </div>

            <button type="submit" class="btn-submit">Enviar Mensagem</button>
        </form>
    </div>

    <!-- Versículos Bíblicos -->
    <div class="acb-fullbleed" style="background: linear-gradient(135deg, #003366 0%, #001531 100%); padding: 50px 40px; border-radius: 15px; text-align: center; margin-top: 50px; box-shadow: 0 8px 30px rgba(0, 51, 102, 0.3);">
        <i class='bx bx-book-open' style="font-size: 3rem; color: #fff; margin-bottom: 20px; display: block;"></i>
        <h3 class="acb-title-serif" style="font-size: 2.2em; color: #fff; margin-bottom: 25px; font-weight: 700;">O Poder da Oração Intercessória</h3>
        <blockquote class="acb-quote acb-quote--glass" style="max-width: 900px; margin: 0 auto 22px;">
            <p>
                "Confessai, pois, os vossos pecados uns aos outros e orai uns pelos outros, para serdes curados. A oração de um justo pode muito em seus efeitos."
            </p>
            <span class="acb-quote__ref">TIAGO 5:16</span>
        </blockquote>
        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.3); max-width: 600px; margin: 30px auto;">
        <blockquote class="acb-quote acb-quote--glass" style="max-width: 900px; margin: 0 auto;">
            <p>
                "Antes de tudo, pois, exorto que se façam súplicas, orações, intercessões e ações de graças por todos os homens."
            </p>
            <span class="acb-quote__ref">1 TIMÓTEO 2:1</span>
        </blockquote>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();

        if (email.length === 0 || !email.includes('@')) {
            alert('Por favor, insira um e-mail válido.');
            e.preventDefault();
        }
    });
</script>
@endpush
