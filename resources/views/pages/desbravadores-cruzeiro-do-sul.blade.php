@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Desbravadores Cruzeiro do Sul')

@section('meta-description', 'Clube de Desbravadores Cruzeiro do Sul da IASD Central de Brasília. Formando jovens líderes com fé, serviço e espírito de aventura desde 1972.')
@section('og-title', 'Desbravadores Cruzeiro do Sul - IASD Central de Brasília')
@section('og-description', 'Formando jovens líderes com fé, serviço e espírito de aventura desde 1972. Participe do nosso clube!')
@section('page-name', 'Desbravadores')

@push('styles')
<style>
    .desbravadores-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 40px;
        box-sizing: border-box;
    }

    .desbravadores-header-wrap {
        width: 100%;
        overflow: hidden;
        aspect-ratio: 1920 / 300;
    }

    .desbravadores-page-header-img {
        width: 100%;
        max-width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    .hero-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    .hero-content {
        max-width: 900px;
        margin: 0 auto;
    }

    .hero-section h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .hero-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: center;
        max-width: 900px;
        margin: 0 auto;
    }

    .content-section {
        background: #fff;
        padding: 40px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .content-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.2em;
        color: #8B4513;
        margin-bottom: 25px;
        font-weight: 500;
        position: relative;
        padding-bottom: 10px;
    }

    .content-section h2::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #D2691E, #CD853F);
    }

    .content-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 15px;
        text-align: justify;
    }

    .atividades-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .atividade-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 25px;
        border-radius: 10px;
        border-left: 4px solid #D2691E;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .atividade-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(139, 69, 19, 0.2);
    }

    .atividade-card h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.4em;
        color: #8B4513;
        margin-bottom: 10px;
    }

    .atividade-card p {
        font-size: 0.95rem;
        color: #666;
        text-align: left;
        margin: 0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin: 30px 0;
    }

    .info-card {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        padding: 25px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        text-align: center;
    }

    .info-card h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
        color: #8B4513;
        margin-bottom: 15px;
    }

    .info-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #666;
        text-align: center;
        margin: 0;
    }

    .social-media-section {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 40px;
        border-radius: 15px;
        margin: 30px 0;
        text-align: center;
    }

    .social-media-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.2em;
        color: #fff;
        margin-bottom: 30px;
    }

    .social-link {
        display: inline-flex;
        align-items: center;
        gap: 15px;
        background: rgba(255,255,255,0.1);
        padding: 15px 30px;
        border-radius: 50px;
        margin: 10px;
        text-decoration: none;
        color: #fff;
        transition: background 0.3s, transform 0.3s;
    }

    .social-link:hover {
        background: rgba(255,255,255,0.2);
        transform: scale(1.05);
    }

    .social-link svg {
        width: 24px;
        height: 24px;
        fill: #fff;
    }

    .social-link span {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
    }

    .steps-container {
        counter-reset: step-counter;
    }

    .step-item {
        position: relative;
        padding-left: 70px;
        margin-bottom: 30px;
    }

    .step-item::before {
        counter-increment: step-counter;
        content: counter(step-counter);
        position: absolute;
        left: 0;
        top: 0;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #D2691E, #CD853F);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
        color: #fff;
        font-weight: bold;
    }

    .step-item h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.3em;
        color: #8B4513;
        margin-bottom: 10px;
    }

    .step-item p {
        font-size: 1rem;
        color: #666;
        margin: 0;
    }

    .step-item a {
        color: #D2691E;
        text-decoration: none;
        font-weight: 500;
    }

    .step-item a:hover {
        text-decoration: underline;
    }

    .contact-info {
        background: linear-gradient(135deg, #fff8e7 0%, #fff 100%);
        padding: 30px;
        border-radius: 15px;
        margin-top: 30px;
        border-left: 5px solid #D2691E;
    }

    .contact-info h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
        color: #8B4513;
        margin-bottom: 15px;
    }

    .contact-info p {
        font-size: 1rem;
        color: #666;
        margin: 5px 0;
    }

    .contact-info a {
        color: #D2691E;
        text-decoration: none;
        font-weight: 500;
    }

    .contact-info a:hover {
        text-decoration: underline;
    }

    .video-section {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 15px;
        margin: 30px 0;
        text-align: center;
    }

    .video-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #8B4513;
        margin-bottom: 25px;
    }

    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 10px;
        margin: 20px 0;
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    .warning-box {
        background: linear-gradient(135deg, #fff3cd 0%, #ffe9a1 100%);
        padding: 25px;
        border-radius: 10px;
        margin: 30px 0;
        border-left: 5px solid #ffc107;
    }

    .warning-box h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.4em;
        color: #856404;
        margin-bottom: 15px;
    }

    .warning-box p {
        font-size: 1rem;
        color: #856404;
        margin: 5px 0;
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 30px 20px;
        }

        .hero-section h1 {
            font-size: 2.2em;
        }

        .atividades-grid,
        .info-grid {
            grid-template-columns: 1fr;
        }

        .step-item {
            padding-left: 60px;
        }

        .step-item::before {
            width: 45px;
            height: 45px;
            font-size: 1.3em;
        }
    }

    /* Seção de Notícias */
    .noticias-section {
        width: 100%;
        background: #f8f9fa;
        padding: clamp(30px, 5vw, 60px) 0;
        margin: 0;
    }

    .noticias-container {
        width: min(100%, 1200px);
        margin: 0 auto;
        padding: 0 clamp(20px, 6vw, 72px);
    }

    .noticias-header {
        margin-bottom: clamp(30px, 5vw, 50px);
        text-align: center;
    }

    .noticias-eyebrow {
        display: inline-block;
        margin-bottom: 8px;
        font-family: 'Roboto', sans-serif;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #003366;
    }

    .noticias-header h2 {
        margin: 0;
        font-family: 'Bebas neue', 'Arial Narrow', sans-serif;
        font-size: clamp(2rem, 4vw, 2.8rem);
        line-height: 1;
        font-weight: 500;
        letter-spacing: .03em;
        color: #151d27;
    }

    /* Notícia Card */
    .noticia-card {
        display: block;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 51, 102, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
        color: inherit;
        max-width: 900px;
        margin: 0 auto;
    }

    .noticia-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(0, 51, 102, 0.2);
    }

    .noticia-card:focus-visible {
        outline: 3px solid #003366;
        outline-offset: 4px;
    }

    .noticia-card__image {
        width: 100%;
        aspect-ratio: 16/9;
        overflow: hidden;
    }

    .noticia-card__image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }

    .noticia-card:hover .noticia-card__image img {
        transform: scale(1.05);
    }

    .noticia-card__content {
        padding: clamp(24px, 4vw, 36px);
    }

    .noticia-card__meta {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .noticia-card__categoria {
        display: inline-block;
        padding: 6px 12px;
        background: linear-gradient(135deg, #003366 0%, #1b4472 100%);
        color: white;
        font-family: 'Roboto', sans-serif;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-radius: 4px;
    }

    .noticia-card__data {
        font-family: 'Roboto', sans-serif;
        font-size: 0.85rem;
        color: #666;
        font-weight: 500;
    }

    .noticia-card__title {
        margin: 0 0 12px;
        font-family: 'Bebas neue', 'Arial Narrow', sans-serif;
        font-size: clamp(1.5rem, 3vw, 2rem);
        line-height: 1.2;
        font-weight: 500;
        letter-spacing: .02em;
        color: #151d27;
    }

    .noticia-card__excerpt {
        margin: 0 0 16px;
        font-family: 'Roboto', sans-serif;
        font-size: clamp(0.95rem, 1.4vw, 1.05rem);
        line-height: 1.6;
        color: #555;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .noticia-card__cta {
        display: inline-block;
        font-family: 'Roboto', sans-serif;
        font-size: 0.9rem;
        font-weight: 700;
        color: #003366;
        text-transform: uppercase;
        letter-spacing: .03em;
        position: relative;
    }

    .noticia-card__cta::after {
        content: '→';
        margin-left: 6px;
        transition: transform 0.2s ease;
    }

    .noticia-card:hover .noticia-card__cta::after {
        transform: translateX(4px);
    }

    .noticia-card__cta:hover {
        text-decoration: underline;
    }

    button.noticia-card__cta:hover {
        text-decoration: underline;
    }

    /* Conteúdo expansível da notícia */
    .noticia-full-content {
        max-width: 900px;
        margin: 30px auto 0;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 51, 102, 0.15);
        display: none;
        animation: slideDown 0.5s ease;
    }

    .noticia-full-content.active {
        display: block;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .noticia-full-header {
        position: relative;
        padding: 20px;
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .noticia-full-header h3 {
        margin: 0;
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
    }

    .noticia-full-body {
        padding: 40px;
        max-width: 800px;
        margin: 0 auto;
    }

    .noticia-full-body p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 20px;
        text-align: justify;
    }

    .noticia-full-body h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #003366;
        margin: 30px 0 15px;
        font-weight: 500;
    }

    .noticia-full-body blockquote {
        background: #f8f9fa;
        border-left: 4px solid #003366;
        padding: 20px;
        margin: 30px 0;
        border-radius: 8px;
        font-style: italic;
    }

    .noticia-full-body blockquote p {
        margin: 0;
        color: #555;
        font-size: 1rem;
    }

    .noticia-full-body blockquote cite {
        display: block;
        margin-top: 15px;
        font-weight: 600;
        color: #003366;
        font-style: normal;
    }

    .noticia-full-body figure {
        margin: 30px 0;
        text-align: center;
    }

    .noticia-full-body figure img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 51, 102, 0.15);
    }

    .noticia-full-body figcaption {
        margin-top: 15px;
        font-size: 0.9rem;
        color: #666;
        font-style: italic;
    }

    .noticia-full-author {
        text-align: center;
        padding: 20px 40px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .noticia-full-author span {
        font-family: 'Roboto', sans-serif;
        font-size: 0.9rem;
        color: #666;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .noticias-section {
            padding: 24px 0;
        }

        .noticias-container {
            padding: 0 20px;
        }

        .noticias-header {
            margin-bottom: 24px;
            text-align: left;
        }

        .noticia-card__content {
            padding: 20px;
        }

        .noticia-card__title {
            font-size: 1.4rem;
        }

        .noticia-card__excerpt {
            font-size: 0.9rem;
            -webkit-line-clamp: 2;
        }

        .noticia-card__meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
    }

    /* Seção de História Dinâmica */
    .history-section {
        background: linear-gradient(135deg, #fff8e7 0%, #fff 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 30px;
        border-left: 5px solid #D2691E;
    }

    .history-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.2em;
        color: #8B4513;
        margin-bottom: 30px;
        font-weight: 500;
        position: relative;
        padding-bottom: 10px;
    }

    .history-section h2::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #D2691E, #CD853F);
    }

    .history-timeline {
        position: relative;
        padding-left: 40px;
        margin-bottom: 30px;
    }

    .history-timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #D2691E 0%, #CD853F 100%);
    }

    .history-item {
        position: relative;
        margin-bottom: 25px;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .history-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(210, 105, 30, 0.2);
    }

    .history-item::before {
        content: '';
        position: absolute;
        left: -49px;
        top: 25px;
        width: 16px;
        height: 16px;
        background: #D2691E;
        border: 3px solid #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 3px #D2691E;
    }

    .history-item__year {
        display: inline-block;
        background: linear-gradient(135deg, #D2691E 0%, #CD853F 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.1em;
        margin-bottom: 10px;
    }

    .history-item__title {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.4em;
        color: #8B4513;
        margin: 10px 0;
    }

    .history-item__text {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        line-height: 1.6;
        color: #555;
        margin: 0;
    }

    .founder-card {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin: 30px 0;
        position: relative;
        overflow: hidden;
    }

    .founder-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: shine 15s infinite linear;
    }

    @keyframes shine {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .founder-card__content {
        position: relative;
        z-index: 1;
    }

    .founder-card__icon {
        font-size: 3em;
        margin-bottom: 15px;
    }

    .founder-card__title {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #fff;
        margin-bottom: 20px;
    }

    .founder-card__text {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.7;
        margin-bottom: 15px;
    }

    .founder-card__highlight {
        background: rgba(255, 255, 255, 0.2);
        padding: 15px;
        border-radius: 10px;
        border-left: 4px solid #D4AF37;
        font-style: italic;
    }

    .history-cta {
        text-align: center;
        margin-top: 30px;
    }

    .history-cta__link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #D2691E 0%, #CD853F 100%);
        color: white;
        padding: 15px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .history-cta__link:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(210, 105, 30, 0.4);
    }

    .history-cta__link::after {
        content: '→';
        font-size: 1.2em;
        transition: transform 0.3s;
    }

    .history-cta__link:hover::after {
        transform: translateX(5px);
    }

    @media (max-width: 768px) {
        .history-section {
            padding: 30px 20px;
        }

        .history-timeline {
            padding-left: 30px;
        }

        .history-item::before {
            left: -39px;
        }

        .founder-card {
            padding: 20px;
        }
    }

    /* Seção de Missão e Valores */
    .mission-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 60px 40px;
        border-radius: 20px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .mission-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(210, 105, 30, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .mission-header {
        text-align: center;
        margin-bottom: 50px;
        position: relative;
        z-index: 1;
    }

    .mission-icon {
        font-size: 3.5em;
        margin-bottom: 20px;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .mission-header h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #8B4513;
        margin-bottom: 15px;
        font-weight: 500;
        position: relative;
        display: inline-block;
    }

    .mission-header h2::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, #D2691E, #CD853F);
        border-radius: 2px;
    }

    .mission-tagline {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3rem;
        color: #666;
        font-weight: 500;
        margin-top: 20px;
        letter-spacing: 0.5px;
    }

    .mission-statement {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 50px;
        align-items: center;
    }

    .mission-text {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 1;
    }

    .mission-text p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 2;
        color: #333;
        margin: 0;
    }

    .mission-text strong {
        color: #D2691E;
        font-weight: 700;
    }

    .mission-text em {
        color: #8B4513;
        font-style: normal;
        font-weight: 600;
    }

    .mission-highlight {
        display: flex;
        justify-content: center;
    }

    .highlight-card {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        color: white;
        padding: 35px;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 51, 102, 0.3);
        position: relative;
        overflow: hidden;
        max-width: 450px;
        width: 100%;
    }

    .highlight-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: shine 15s infinite linear;
    }

    .highlight-icon {
        font-size: 2.5em;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }

    .highlight-card h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }

    .highlight-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .highlight-card strong {
        color: #D4AF37;
        font-weight: 700;
    }

    .values-showcase {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        margin-bottom: 50px;
        position: relative;
        z-index: 1;
    }

    .value-card {
        background: white;
        padding: 35px 25px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: transform 0.4s ease, box-shadow 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .value-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #D2691E, #CD853F);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }

    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(210, 105, 30, 0.25);
    }

    .value-card:hover::before {
        transform: scaleX(1);
    }

    .value-icon-wrapper {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        position: relative;
        transition: transform 0.4s ease, background 0.4s ease;
    }

    .value-card:hover .value-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
        background: linear-gradient(135deg, #D2691E 0%, #CD853F 100%);
    }

    .value-icon {
        font-size: 2.2em;
        transition: transform 0.4s ease;
    }

    .value-card:hover .value-icon {
        transform: scale(1.1);
    }

    .value-card h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.6em;
        color: #8B4513;
        margin-bottom: 12px;
        font-weight: 500;
    }

    .value-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #666;
        line-height: 1.6;
        margin: 0;
    }

    .foundation-banner {
        background: linear-gradient(135deg, #fff8e7 0%, #fff 100%);
        padding: 35px 40px;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
        z-index: 1;
        border-left: 5px solid #D2691E;
    }

    .foundation-content {
        display: flex;
        align-items: center;
        gap: 30px;
    }

    .foundation-badge {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #D2691E 0%, #CD853F 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5em;
        flex-shrink: 0;
        box-shadow: 0 8px 25px rgba(210, 105, 30, 0.3);
        position: relative;
        z-index: 1;
    }

    .foundation-text h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #8B4513;
        margin-bottom: 10px;
        font-weight: 500;
    }

    .foundation-text p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #666;
        line-height: 1.7;
        margin: 0;
    }

    @media (max-width: 1024px) {
        .mission-statement {
            grid-template-columns: 1fr;
        }

        .values-showcase {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .mission-section {
            padding: 40px 25px;
        }

        .mission-header h2 {
            font-size: 2.2em;
        }

        .mission-tagline {
            font-size: 1.1rem;
        }

        .mission-text {
            padding: 30px 25px;
        }

        .highlight-card {
            padding: 30px 25px;
        }

        .values-showcase {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .foundation-content {
            flex-direction: column;
            text-align: center;
        }

        .foundation-badge {
            width: 70px;
            height: 70px;
            font-size: 2em;
        }

        .foundation-text h3 {
            font-size: 1.5em;
        }
    }
</style>
@endpush

@section('content')
<!-- Header Image -->
<div class="desbravadores-header-wrap">
    <img src="{{ asset('img/noticias/desbravadores-1-header.jpeg') }}" alt="Desbravadores Cruzeiro do Sul" class="desbravadores-page-header-img">
</div>

<!-- Hero Section -->
<div class="hero-section acb-fullbleed">
    <div class="hero-content">
        <h1>Clube de Desbravadores Cruzeiro do Sul</h1>
        <p>Formando jovens líderes com fé, serviço e espírito de aventura desde 1972.</p>
    </div>
</div>

<div class="desbravadores-container">
    <!-- História Section -->
    <div class="history-section">
        <h2>Nossa História</h2>

        <div class="history-timeline">
            <div class="history-item">
                <span class="history-item__year">1972</span>
                <h3 class="history-item__title">Fundação do Clube</h3>
                <p class="history-item__text">O Clube de Desbravadores Cruzeiro do Sul nasceu na IASD Central de Brasília no início da década de 1970, quando líderes locais organizaram o primeiro grupo de jovens para formação física, mental e espiritual.</p>
            </div>

            <div class="history-item">
                <span class="history-item__year">Décadas de Atuação</span>
                <h3 class="history-item__title">Crescimento e Desenvolvimento</h3>
                <p class="history-item__text">Desde então o clube cresceu, participou de campos, formou bandas marciais e líderes, e mantém tradição de serviço comunitário e evangelismo entre os adolescentes de Brasília.</p>
            </div>

            <div class="history-item">
                <span class="history-item__title" style="color: #8B4513; margin-top: 0;">Legado de Liderança</span>
                <p class="history-item__text">Ao longo dos anos, temos formado gerações de jovens comprometidos com os princípios cristãos, preparando-os para serem bons cidadãos e líderes com princípios cristãos em suas comunidades.</p>
            </div>
        </div>

        <div class="founder-card">
            <div class="founder-card__content">
                <div class="founder-card__icon">⭐</div>
                <h3 class="founder-card__title">Nosso Fundador</h3>
                <p class="founder-card__text">O Pastor José Maria é o fundador do Clube de Desbravadores Cruzeiro do Sul e foi diretor deste clube nos anos de 1972 e 1973, estabelecendo as bases da nossa tradição de excelência na formação de jovens.</p>
                <div class="founder-card__highlight">
                    "A visão do Pastor José Maria em 1972 plantou a semente que cresceria para se tornar mais de 50 anos de transformação de vidas através do ministério de desbravadores."
                </div>
            </div>
        </div>

        <div class="history-cta">
            <a href="/historia-desbravadores" class="history-cta__link">
                Conheça Nossa História Completa
            </a>
        </div>
    </div>

    <!-- Missão e Valores Section -->
    <div class="mission-section">
        <div class="mission-header">
            <div class="mission-icon">🎯</div>
            <h2>Missão e Valores</h2>
            <p class="mission-tagline">Transformando jovens em líderes de propósito</p>
        </div>

        <div class="mission-statement">
            <div class="mission-text">
                <p>Nossa missão é desenvolver juvenis e adolescentes <strong>(10–15 anos)</strong> e jovens líderes <em>integralmente</em> — físico, mental e espiritual — por meio de atividades ao ar livre, serviço cristão e liderança.</p>
            </div>
            <div class="mission-highlight">
                <div class="highlight-card">
                    <div class="highlight-icon">💫</div>
                    <h3>Nosso Propósito</h3>
                    <p>Acreditamos que cada jovem tem <strong>potencial único</strong> para fazer a diferença no mundo através de fé ativa, disciplina, amizade e preparo para a vida.</p>
                </div>
            </div>
        </div>

        <div class="values-showcase">
            <div class="value-card">
                <div class="value-icon-wrapper">
                    <span class="value-icon">✝️</span>
                </div>
                <h3>Fé</h3>
                <p>Desenvolvimento espiritual e conexão profunda com Deus</p>
            </div>
            <div class="value-card">
                <div class="value-icon-wrapper">
                    <span class="value-icon">🤝</span>
                </div>
                <h3>Serviço</h3>
                <p>Compromisso inabalável com a comunidade e o próximo</p>
            </div>
            <div class="value-card">
                <div class="value-icon-wrapper">
                    <span class="value-icon">⛰️</span>
                </div>
                <h3>Aventura</h3>
                <p>Atividades ao ar livre e superação de limites</p>
            </div>
            <div class="value-card">
                <div class="value-icon-wrapper">
                    <span class="value-icon">👑</span>
                </div>
                <h3>Liderança</h3>
                <p>Formação de jovens líderes cristãos para impactar o mundo</p>
            </div>
        </div>

        <div class="foundation-banner">
            <div class="foundation-content">
                <div class="foundation-badge">
                    <span>🏛️</span>
                </div>
                <div class="foundation-text">
                    <h3>Fundamentos Cristãos</h3>
                    <p>Nossos valores promovem o desenvolvimento harmonioso de todas as faculdades humanas — preparando jovens para serem bons cidadãos e líderes em sua comunidade.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Atividades Section -->
    <div class="content-section">
        <h2>Atividades e Programas</h2>
        <p>Nossos desbravadores participam de uma variedade de atividades enriquecedoras:</p>

        <div class="atividades-grid">
            <div class="atividade-card">
                <h3>🏕️ Campori e Acampamentos</h3>
                <p>Eventos nacionais e internacionais com jovens de todo o Brasil e América do Sul</p>
            </div>
            <div class="atividade-card">
                <h3>🎖️ Investiduras</h3>
                <p>Prog. de honra e conquistas anuais</p>
            </div>
            <div class="atividade-card">
                <h3>🎵 Banda Marcial</h3>
                <p>Formação musical e apresentações</p>
            </div>
            <div class="atividade-card">
                <h3>🤝 Trabalhos Comunitários</h3>
                <p>Serviço cristão e assistência social</p>
            </div>
            <div class="atividade-card">
                <h3>🥾 Trilhas e Primeiros Socorros</h3>
                <p>Atividades ao ar livre e sobrevivência</p>
            </div>
            <div class="atividade-card">
                <h3>📖 Reuniões Semanais</h3>
                <p>Encontros regulares de formação</p>
            </div>
        </div>

        <!-- Video Section -->
        <div class="video-section">
            <h2>Conheça Nosso Trabalho</h2>
            <div class="video-container">
                <iframe src="https://www.youtube.com/embed/gbshEXCS15U" title="Desbravadores Cruzeiro do Sul" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <!-- Horários e Local Section -->
    <div class="content-section">
        <h2>Horários e Local</h2>
        <p>Reuniões semanais aos Domingos de 9 a 12h na sede do Clube de Desbravadores localizada no prédio da IASD Central de Brasília; reuniões especiais e acampamentos conforme calendário anual.</p>
        <p>Para confirmar horários e entradas, consulte as redes sociais do clube ou entre em contato com a diretoria.</p>

        <div class="info-grid">
            <div class="info-card">
                <h3>📍 Local</h3>
                <p>Sede do Clube na IASD Central de Brasília</p>
            </div>
            <div class="info-card">
                <h3>🕐 Reuniões</h3>
                <p>Conforme calendário anual</p>
            </div>
            <div class="info-card">
                <h3>📅 Eventos</h3>
                <p>Acampamentos e atividades especiais</p>
            </div>
        </div>
    </div>

    <!-- Redes Sociais Section -->
    <div class="social-media-section">
        <h2>Siga-nos nas Redes Sociais</h2>
        <p style="color: #fff; margin-bottom: 25px; font-size: 1.1rem;">Acompanhe nossas atividades, fotos e novidades!</p>

        <a href="https://www.instagram.com/cruzeirodosuldbv/reels/" target="_blank" class="social-link">
            <svg viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/>
                <path d="M12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
            </svg>
            <span>@cruzeirodosuldbv</span>
        </a>

        <a href="https://www.youtube.com/c/desbravadorescruzeirodosul" target="_blank" class="social-link">
            <svg viewBox="0 0 24 24">
                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
            </svg>
            <span>YouTube</span>
        </a>
    </div>

    <!-- Como Participar Section -->
    <div class="content-section">
        <h2>Como Participar</h2>
        <p>Para fazer parte do Clube de Desbravadores Cruzeiro do Sul, siga as 3 etapas abaixo:</p>

        <div class="steps-container">
            <div class="step-item">
                <h3>Pré-inscrição</h3>
                <p>Preencher a pré-inscrição utilizando o código <strong>3ZK5E</strong> no link: <a href="https://clubes.adventistas.org/br/aplac/13634/cruzeiro-do-sul/" target="_blank">clubes.adventistas.org/br/aplac/13634/cruzeiro-do-sul/</a></p>
            </div>

            <div class="step-item">
                <h3>Acertos Financeiros</h3>
                <p>Fazer os acertos financeiros presencialmente na Tesouraria do Clube ou entrando em contato pelo E-mail: <a href="mailto:cruzeirodosul.tesouraria.bsb@gmail.com">cruzeirodosul.tesouraria.bsb@gmail.com</a></p>
            </div>

            <div class="step-item">
                <h3>Ativação da Inscrição</h3>
                <p>Comparecer presencialmente à Secretaria do Clube para ativar a inscrição do seu filho</p>
            </div>
        </div>

        <div class="warning-box">
            <h3>⚠️ Importante</h3>
            <p><strong>Lembre-se de realizar todas as 3 etapas.</strong> Caso contrário, o desbravador não estará regularmente inscrito e não poderá participar das atividades do Clube.</p>
            <p>Cada desbravador inscrito possui um seguro que o permite participar das atividades com a segurança necessária.</p>
            <p>Além disso, os desbravadores não inscritos no Clube não poderão ser inscritos no <strong>Campori da Aplac (Jun/26)</strong> e nem no <strong>Campori Sul-Americano (Jan/27)</strong>.</p>
        </div>

        <div class="contact-info">
            <h3>📞 Contato</h3>
            <p><strong>Email Tesouraria:</strong> <a href="mailto:cruzeirodosul.tesouraria.bsb@gmail.com">cruzeirodosul.tesouraria.bsb@gmail.com</a></p>
            <p style="margin-top: 15px;"><em>Qualquer dúvida, estamos à disposição.</em></p>
            <p><strong>Diretoria do Clube de Desbravadores Cruzeiro do Sul</strong></p>
        </div>
    </div>

    <!-- Seção de Notícias -->
    <section class="noticias-section">
        <div class="noticias-container">
            <div class="noticias-header">
                <span class="noticias-eyebrow">Notícias</span>
                <h2 class="acb-title-serif">Últimas Notícias da Nossa Comunidade</h2>
            </div>

            <div class="noticia-card" onclick="expandNews()" style="cursor: pointer;" aria-label="Ler notícia completa sobre o Clube de Desbravadores">
                <div class="noticia-card__image">
                    <img src="{{ asset('img/noticias/desbravadores-1.jpeg') }}" alt="Clube de Desbravadores Cruzeiro do Sul em Campori APLaC 2026" loading="lazy" decoding="async" width="600" height="338">
                </div>
                <div class="noticia-card__content">
                    <div class="noticia-card__meta">
                        <span class="noticia-card__categoria">Religião &amp; Comunidade</span>
                        <span class="noticia-card__data">10/06/2026</span>
                    </div>
                    <h3 class="noticia-card__title">Clube de Desbravadores Cruzeiro do Sul celebra participação marcante em Campori APLaC 2026</h3>
                    <p class="noticia-card__excerpt">Evento de quatro dias reuniu jovens para atividades de desenvolvimento pessoal, espiritual e fortalecimento comunitário; foco agora se volta para a edição sul-americana de 2027.</p>
                    <span class="noticia-card__cta">Ler notícia completa</span>
                </div>
            </div>

            <!-- Conteúdo Completo da Notícia (Expansível) -->
            <div class="noticia-full-content" id="fullNewsContent">
                <div class="noticia-full-header">
                    <h3>📰 Notícia Completa</h3>
                </div>

                <div class="noticia-full-author">
                    <span>Por Redação</span>
                </div>

                <div class="noticia-full-body">
                    <p><strong>Brasília —</strong> O Clube de Desbravadores Cruzeiro do Sul consolidou, na última semana, mais um marco em sua trajetória com a conclusão de sua participação no Campori. O evento, que se estendeu por quatro dias, promoveu uma imersão focada no desenvolvimento de talentos, fortalecimento de laços de amizade e, fundamentalmente, no crescimento espiritual dos jovens participantes.</p>

                    <p>Segundo a coordenação do Cruzeiro do Sul, as atividades proporcionaram momentos de reflexão e experiências práticas que devem gerar impactos duradouros na formação dos Desbravadores. O encontro é apontado pela liderança como um espaço essencial para a comunhão e o exercício da cidadão cristã.</p>

                    <h2>Apoio e Fortalecimento Institucional</h2>
                    <p>O sucesso da expedição foi creditado ao envolvimento direto da comunidade e do corpo eclesiástico. A diretoria do Clube emitiu um agradecimento público à liderança da igreja local pelo suporte contínuo e logístico, destacando o papel dos pastores e voluntários que atuaram na linha de frente das operações diárias.</p>

                    <blockquote>
                        <p>"Temos um clube forte porque contamos com o apoio, a confiança e o envolvimento da liderança e dos pastores da nossa igreja. Isso faz toda a diferença"</p>
                        <cite>— Rozilene Manzi, líder do Clube Cruzeiro do Sul</cite>
                    </blockquote>

                    <p>Entre os colaboradores mencionados pelo suporte direto nas diversas atividades necessárias durante o acampamento estão os pastores Lucas Alves e Hugo Rodrigues, além de membros da equipe de apoio como Karin Gorski (com o suporte da ASA - Ação Solidária Adventista), Elisangela Terto Rosa "Rosinha", José Bullón, Luigi Braga, Silóe Almeida Júnior, Alexandre Tinoco, Fábio Costa, Diego Elesbão, Luciana Marques e Mateus Castanho.</p>

                    <h2>Próximos Passos: Rumo a 2027</h2>
                    <p>Com o encerramento das atividades desta edição, o Clube Cruzeiro do Sul já projeta suas atenções para o próximo grande desafio do calendário oficial. A organização confirmou o início do planejamento e da preparação para o Campori da DSA (Divisão Sul-Americana), agendado para janeiro de 2027, que reunirá clubes de diversos países da América do Sul.</p>

                    <figure>
                        <img src="{{ asset('img/noticias/desbravadores-2.jpeg') }}" alt="Atividades do Campori APLaC 2026" loading="lazy" decoding="async" width="800" height="450">
                        <figcaption>Atividades do Campori APLaC 2026</figcaption>
                    </figure>

                    <div style="text-align: center; margin-top: 30px;">
                        <button class="noticia-card__cta" onclick="closeNews(event)" aria-label="Fechar notícia" style="background: none; border: none; cursor: pointer; font-family: 'Roboto', sans-serif; font-size: 0.9rem; font-weight: 700; color: #003366; text-transform: uppercase; letter-spacing: .03em; padding: 0;">
                            <span>← Fechar notícia completa</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function expandNews() {
    const fullContent = document.getElementById('fullNewsContent');
    fullContent.classList.add('active');

    // Scroll suave até o conteúdo expandido
    setTimeout(() => {
        fullContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 100);
}

function closeNews(event) {
    event.preventDefault(); // Evitar comportamento padrão do botão

    const fullContent = document.getElementById('fullNewsContent');
    fullContent.classList.remove('active');

    // Scroll suave de volta ao card da notícia
    const newsCard = document.querySelector('.noticia-card');
    setTimeout(() => {
        newsCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 100);
}

// Permitir fechar com a tecla Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const fullContent = document.getElementById('fullNewsContent');
        if (fullContent.classList.contains('active')) {
            closeNews(event);
        }
    }
});
</script>
@endsection