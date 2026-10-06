@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Estudo Bíblico')

@section('meta-description', 'Solicite seu estudo bíblico gratuito na IASD Central de Brasília. Estudos presenciais, online ou por telefone. Conecte-se com Deus por meio da Palavra.')
@section('og-title', 'Estudo Bíblico - IASD Central de Brasília')
@section('og-description', 'Procurando respostas, fortalecimento espiritual ou alívio para desafios emocionais? O estudo bíblico é o caminho!')
@section('page-name', 'Estudo Bíblico')

@push('styles')
<style>
    .eb {
        --eb-navy: #003366;
        --eb-navy-deep: #001531;
        --eb-accent: #d35400;
        --eb-accent-deep: #ba4a00;
        --eb-ink: #1a2332;
        --eb-muted: #5a6577;
        --eb-line: rgba(0, 51, 102, 0.12);
        --eb-surface: #f5f7fa;
        --eb-max: 1080px;
        color: var(--eb-ink);
    }

    .eb * { box-sizing: border-box; }

    .eb-wrap {
        width: 100%;
        max-width: var(--eb-max);
        margin: 0 auto;
        padding: 0 20px 64px;
    }

    /* —— CTA principal (logo após o header) —— */
    .eb-cta {
        margin: 28px auto 48px;
        max-width: var(--eb-max);
        padding: 0 20px;
        position: relative;
        z-index: 2;
    }

    .eb-cta__panel {
        background: #fff;
        border: 1px solid var(--eb-line);
        border-radius: 18px;
        box-shadow: 0 18px 50px rgba(0, 21, 49, 0.12);
        padding: clamp(28px, 4vw, 40px);
        display: grid;
        grid-template-columns: 1.35fr 0.9fr;
        gap: clamp(24px, 4vw, 40px);
        align-items: center;
    }

    .eb-cta__kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'Roboto', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--eb-accent);
        margin-bottom: 10px;
    }

    .eb-cta__kicker i { font-size: 1rem; }

    .eb-cta h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: clamp(2.1rem, 4.2vw, 3rem);
        line-height: 1.05;
        color: var(--eb-navy);
        font-weight: 500;
        margin: 0 0 14px;
        letter-spacing: 0.01em;
    }

    .eb-cta__lead {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.65;
        color: var(--eb-muted);
        margin: 0 0 22px;
        max-width: 36em;
    }

    .eb-cta__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .eb-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-family: 'Roboto', sans-serif;
        font-weight: 800;
        font-size: 1.05rem;
        text-decoration: none;
        border-radius: 12px;
        padding: 15px 26px;
        transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        border: 0;
        cursor: pointer;
    }

    .eb-btn--primary {
        background: linear-gradient(135deg, var(--eb-accent) 0%, var(--eb-accent-deep) 100%);
        color: #fff;
        box-shadow: 0 12px 28px rgba(211, 84, 0, 0.28);
    }

    .eb-btn--primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 34px rgba(211, 84, 0, 0.34);
        color: #fff;
        text-decoration: none;
    }

    .eb-btn--ghost {
        background: transparent;
        color: var(--eb-navy);
        border: 1.5px solid var(--eb-line);
        padding: 13px 20px;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .eb-btn--ghost:hover {
        background: var(--eb-surface);
        color: var(--eb-navy);
        text-decoration: none;
        transform: translateY(-1px);
    }

    .eb-cta__modes {
        display: grid;
        gap: 12px;
    }

    .eb-mode {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        padding: 14px 16px;
        border-radius: 12px;
        background: var(--eb-surface);
        border: 1px solid transparent;
        transition: border-color 0.18s ease, background 0.18s ease;
    }

    .eb-mode:hover {
        border-color: rgba(0, 51, 102, 0.16);
        background: #fff;
    }

    .eb-mode__icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        background: rgba(0, 51, 102, 0.08);
        color: var(--eb-navy);
        font-size: 1.25rem;
    }

    .eb-mode h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 0.98rem;
        font-weight: 700;
        color: var(--eb-navy);
        margin: 0 0 2px;
    }

    .eb-mode p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.88rem;
        color: var(--eb-muted);
        margin: 0;
        line-height: 1.4;
    }

    /* —— Seções —— */
    .eb-section {
        margin: 0 0 52px;
    }

    .eb-section__head {
        text-align: center;
        max-width: 640px;
        margin: 0 auto 28px;
    }

    .eb-section__head h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: clamp(1.85rem, 3vw, 2.35rem);
        color: var(--eb-navy);
        font-weight: 500;
        margin: 0 0 10px;
    }

    .eb-section__head p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.02rem;
        line-height: 1.6;
        color: var(--eb-muted);
        margin: 0;
    }

    .eb-reasons {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .eb-reason {
        padding: 22px 20px;
        border-radius: 14px;
        border: 1px solid var(--eb-line);
        background: #fff;
        text-align: left;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .eb-reason:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(0, 21, 49, 0.08);
    }

    .eb-reason__icon {
        width: 44px;
        height: 44px;
        border-radius: 11px;
        display: grid;
        place-items: center;
        background: rgba(211, 84, 0, 0.1);
        color: var(--eb-accent);
        font-size: 1.35rem;
        margin-bottom: 14px;
    }

    .eb-reason h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.08rem;
        font-weight: 700;
        color: var(--eb-navy);
        margin: 0 0 8px;
    }

    .eb-reason p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        line-height: 1.55;
        color: var(--eb-muted);
        margin: 0;
    }

    /* Como funciona */
    .eb-steps {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0;
        counter-reset: eb-step;
        border: 1px solid var(--eb-line);
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
    }

    .eb-step {
        padding: 26px 22px;
        position: relative;
        counter-increment: eb-step;
    }

    .eb-step:not(:last-child) {
        border-right: 1px solid var(--eb-line);
    }

    .eb-step::before {
        content: counter(eb-step);
        display: grid;
        place-items: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--eb-navy);
        color: #fff;
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.15rem;
        margin-bottom: 14px;
    }

    .eb-step h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--eb-navy);
        margin: 0 0 8px;
    }

    .eb-step p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.94rem;
        line-height: 1.55;
        color: var(--eb-muted);
        margin: 0;
    }

    /* Experiência */
    .eb-experience {
        background: linear-gradient(145deg, var(--eb-navy) 0%, var(--eb-navy-deep) 100%);
        border-radius: 18px;
        padding: clamp(28px, 4vw, 40px);
        color: #fff;
    }

    .eb-experience__head {
        max-width: 560px;
        margin-bottom: 24px;
    }

    .eb-experience__head h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: clamp(1.85rem, 3vw, 2.35rem);
        font-weight: 500;
        color: #fff;
        margin: 0 0 10px;
    }

    .eb-experience__head p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.02rem;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.82);
        margin: 0;
    }

    .eb-experience__list {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .eb-experience__list li {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 18px 16px;
    }

    .eb-experience__list i {
        display: block;
        font-size: 1.4rem;
        color: #f9a01b;
        margin-bottom: 10px;
    }

    .eb-experience__list strong {
        display: block;
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .eb-experience__list span {
        font-family: 'Roboto', sans-serif;
        font-size: 0.9rem;
        line-height: 1.5;
        color: rgba(255, 255, 255, 0.78);
    }

    /* Materiais */
    .eb-materials {
        background: var(--eb-surface);
        border: 1px solid var(--eb-line);
        border-radius: 18px;
        padding: clamp(24px, 3.5vw, 36px);
    }

    .eb-materials__featured {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--eb-line);
    }

    .eb-materials__featured h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--eb-navy);
        margin: 0 0 4px;
    }

    .eb-materials__featured p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: var(--eb-muted);
        margin: 0;
    }

    .eb-materials__grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .eb-material {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 12px;
        background: #fff;
        border: 1px solid var(--eb-line);
        text-decoration: none;
        color: inherit;
        transition: border-color 0.18s ease, transform 0.18s ease;
    }

    .eb-material:hover {
        border-color: rgba(0, 51, 102, 0.28);
        transform: translateY(-2px);
        text-decoration: none;
        color: inherit;
    }

    .eb-material i {
        font-size: 1.35rem;
        color: var(--eb-navy);
        flex-shrink: 0;
    }

    .eb-material span {
        font-family: 'Roboto', sans-serif;
        font-size: 0.92rem;
        font-weight: 600;
        color: var(--eb-navy);
        line-height: 1.35;
    }

    /* FAQ doutrina */
    .eb-faq {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 28px 30px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--eb-navy) 0%, var(--eb-navy-deep) 100%);
        color: #fff;
    }

    .eb-faq__copy {
        flex: 1;
        min-width: min(100%, 280px);
    }

    .eb-faq__copy h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: clamp(1.6rem, 2.5vw, 2rem);
        font-weight: 500;
        color: #fff;
        margin: 0 0 8px;
    }

    .eb-faq__copy p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.98rem;
        line-height: 1.55;
        color: rgba(255, 255, 255, 0.82);
        margin: 0;
        max-width: 42em;
    }

    .eb-btn--on-dark {
        background: #fff;
        color: var(--eb-navy);
        box-shadow: none;
    }

    .eb-btn--on-dark:hover {
        background: #f0f4f8;
        color: var(--eb-navy);
        text-decoration: none;
        transform: translateY(-2px);
    }

    /* CTA final */
    .eb-final {
        text-align: center;
        padding: 36px 24px;
        border-radius: 18px;
        border: 1px solid var(--eb-line);
        background:
            radial-gradient(600px circle at 50% 0%, rgba(211, 84, 0, 0.08) 0%, transparent 60%),
            #fff;
    }

    .eb-final h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: clamp(1.85rem, 3vw, 2.35rem);
        color: var(--eb-navy);
        font-weight: 500;
        margin: 0 0 10px;
    }

    .eb-final p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.6;
        color: var(--eb-muted);
        margin: 0 auto 22px;
        max-width: 34em;
    }

    /* Motion */
    @media (prefers-reduced-motion: no-preference) {
        .eb-reveal {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity 0.55s ease, transform 0.55s ease;
        }

        .eb-reveal.is-visible {
            opacity: 1;
            transform: none;
        }

        .eb-cta__panel {
            animation: eb-rise 0.55s ease both;
        }
    }

    @keyframes eb-rise {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: none; }
    }

    @media (max-width: 860px) {
        .eb-cta {
            margin-top: 20px;
            margin-bottom: 36px;
        }

        .eb-cta__panel {
            grid-template-columns: 1fr;
            gap: 22px;
        }

        .eb-reasons,
        .eb-steps,
        .eb-experience__list,
        .eb-materials__grid {
            grid-template-columns: 1fr;
        }

        .eb-step:not(:last-child) {
            border-right: 0;
            border-bottom: 1px solid var(--eb-line);
        }

        .eb-faq {
            flex-direction: column;
            text-align: center;
            padding: 26px 22px;
        }

        .eb-faq__copy p {
            margin-left: auto;
            margin-right: auto;
        }

        .eb-materials__featured {
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }
    }

    @media (max-width: 480px) {
        .eb-cta__actions {
            flex-direction: column;
            align-items: stretch;
        }

        .eb-btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<img src="{{ asset('img/cards/estudo_biblico/estudo_biblico_header.webp') }}" alt="Estudo Bíblico" style="width: 100%;" fetchpriority="high" decoding="async">

<div class="eb">
    {{-- Ação principal: solicitar estudo — acima de todo o conteúdo explicativo --}}
    <div class="eb-cta">
        <div class="eb-cta__panel">
            <div>
                <div class="eb-cta__kicker">
                    <i class="bi bi-book" aria-hidden="true"></i>
                    Gratuito e sem compromisso
                </div>
                <h1>Estudo bíblico: uma jornada para se conectar com Deus</h1>
                <p class="eb-cta__lead">
                    Procurando respostas, fortalecimento espiritual ou alívio para desafios emocionais?
                    Oferecemos encontros presenciais, na igreja ou online. <strong>Você escolhe como participar.</strong>
                </p>
                <div class="eb-cta__actions">
                    <a href="{{ route('estudo-biblico.formulario') }}" class="eb-btn eb-btn--primary">
                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                        Solicitar estudo bíblico
                    </a>
                    <a href="#como-funciona" class="eb-btn eb-btn--ghost">
                        Como funciona
                    </a>
                </div>
            </div>

            <div class="eb-cta__modes" aria-label="Formas de participação">
                <article class="eb-mode">
                    <div class="eb-mode__icon" aria-hidden="true"><i class="bi bi-house"></i></div>
                    <div>
                        <h3>Presencial</h3>
                        <p>Na sua residência ou na igreja</p>
                    </div>
                </article>
                <article class="eb-mode">
                    <div class="eb-mode__icon" aria-hidden="true"><i class="bi bi-laptop"></i></div>
                    <div>
                        <h3>Online</h3>
                        <p>Por videoconferência</p>
                    </div>
                </article>
                <article class="eb-mode">
                    <div class="eb-mode__icon" aria-hidden="true"><i class="bi bi-phone"></i></div>
                    <div>
                        <h3>Remoto</h3>
                        <p>Por telefone ou mensagem</p>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <div class="eb-wrap">
        <section class="eb-section eb-reveal" aria-labelledby="eb-motivos-title">
            <div class="eb-section__head">
                <h2 id="eb-motivos-title" class="acb-title-serif">Por que estudar a Bíblia?</h2>
                <p>Seja qual for a sua idade ou o momento de vida, aqui você encontra um espaço acolhedor e adaptado às suas necessidades.</p>
            </div>
            <div class="eb-reasons">
                <article class="eb-reason">
                    <div class="eb-reason__icon" aria-hidden="true"><i class="bi bi-book-half"></i></div>
                    <h3>Aprendizado simples</h3>
                    <p>Aprenda de forma simples como os ensinamentos de Jesus transformam vidas.</p>
                </article>
                <article class="eb-reason">
                    <div class="eb-reason__icon" aria-hidden="true"><i class="bi bi-patch-question-fill"></i></div>
                    <h3>Respostas reais</h3>
                    <p>Descubra respostas para questões pessoais e espirituais, com a orientação do amor de Cristo.</p>
                </article>
                <article class="eb-reason">
                    <div class="eb-reason__icon" aria-hidden="true"><i class="bi bi-people-fill"></i></div>
                    <h3>Conexão autêntica</h3>
                    <p>Conecte-se com Deus de maneira prática e autêntica, em comunidade.</p>
                </article>
            </div>
        </section>

        <section class="eb-section eb-reveal" id="como-funciona" aria-labelledby="eb-como-title">
            <div class="eb-section__head">
                <h2 id="eb-como-title" class="acb-title-serif">Como funciona?</h2>
                <p>Um caminho claro, acolhedor e sem pressão, do primeiro contato à aplicação no dia a dia.</p>
            </div>
            <div class="eb-steps">
                <article class="eb-step">
                    <h3>Ambiente leve</h3>
                    <p>Materiais como a Bíblia e os guias são fornecidos. Suas dúvidas e experiências são sempre bem-vindas!</p>
                </article>
                <article class="eb-step">
                    <h3>Encontros envolventes</h3>
                    <p>Começamos com oração, exploramos passagens bíblicas e refletimos juntos.</p>
                </article>
                <article class="eb-step">
                    <h3>Transformação real</h3>
                    <p>Ao final, você é incentivado a aplicar os aprendizados no dia a dia e a crescer na fé.</p>
                </article>
            </div>
        </section>

        <section class="eb-section eb-reveal" aria-labelledby="eb-exp-title">
            <div class="eb-experience">
                <div class="eb-experience__head">
                    <h2 id="eb-exp-title" class="acb-title-serif">Mais que estudo, uma experiência</h2>
                    <p>Sua jornada espiritual começa agora. Descubra como a Bíblia pode iluminar a sua vida.</p>
                </div>
                <ul class="eb-experience__list">
                    <li>
                        <i class="bi bi-stars" aria-hidden="true"></i>
                        <strong>Transformação diária</strong>
                        <span>Cada lição é um passo para entender melhor a Palavra de Deus e o propósito Dele para você.</span>
                    </li>
                    <li>
                        <i class="bi bi-brightness-high" aria-hidden="true"></i>
                        <strong>Renovação e esperança</strong>
                        <span>Venha renovar a sua esperança, encontrar apoio e caminhar mais perto Dele.</span>
                    </li>
                    <li>
                        <i class="bi bi-heart" aria-hidden="true"></i>
                        <strong>Crescimento espiritual</strong>
                        <span>Venha estudar, compartilhar e crescer na graça de Deus.</span>
                    </li>
                </ul>
            </div>
        </section>

        <section class="eb-section eb-reveal" aria-labelledby="eb-mat-title">
            <div class="eb-section__head">
                <h2 id="eb-mat-title" class="acb-title-serif">Materiais de estudo</h2>
                <p>Acesse os nossos conteúdos e solicite materiais gratuitos.</p>
            </div>
            <div class="eb-materials">
                <div class="eb-materials__featured">
                    <div>
                        <h3>Cursos e guias bíblicos</h3>
                        <p>Acervo oficial de estudos para evangelismo e crescimento espiritual.</p>
                    </div>
                    <a href="https://downloads.adventistas.org/pt/evangelismo/estudos-biblicos/cursos-biblicos/" target="_blank" rel="noopener" class="eb-btn eb-btn--primary">
                        <i class="bi bi-journals" aria-hidden="true"></i>
                        Ver materiais
                    </a>
                </div>
                <div class="eb-materials__grid">
                    <a href="https://cursos.novotempo.com/" target="_blank" rel="noopener" class="eb-material">
                        <i class="bi bi-gift" aria-hidden="true"></i>
                        <span>Materiais impressos ou digitais gratuitos</span>
                    </a>
                    <a href="https://www.youtube.com/user/BibliaFacil" target="_blank" rel="noopener" class="eb-material">
                        <i class="bi bi-camera-video" aria-hidden="true"></i>
                        <span>Canal Bíblia Fácil</span>
                    </a>
                    <a href="https://www.youtube.com/@NaMiradaVerdadeNT" target="_blank" rel="noopener" class="eb-material">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <span>Canal Na Mira da Verdade</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="eb-section eb-reveal" aria-labelledby="eb-faq-title">
            <div class="eb-faq">
                <div class="eb-faq__copy">
                    <h2 id="eb-faq-title" class="acb-title-serif">Tem perguntas sobre doutrina?</h2>
                    <p>
                        Explore a nossa seção de perguntas frequentes: sábado, dom de profecia, juízo investigativo, estado dos mortos e muito mais.
                    </p>
                </div>
                <a href="{{ route('faq') }}#doutrina" class="eb-btn eb-btn--on-dark">
                    <i class="bi bi-question-circle" aria-hidden="true"></i>
                    Ver questões sobre doutrina
                </a>
            </div>
        </section>

        <section class="eb-final eb-reveal" aria-labelledby="eb-final-title">
            <h2 id="eb-final-title" class="acb-title-serif">Pronto para começar?</h2>
            <p>
                Solicite o seu estudo bíblico gratuito. A nossa equipe entrará em contato e você escolhe a forma que preferir.
            </p>
            <a href="{{ route('estudo-biblico.formulario') }}" class="eb-btn eb-btn--primary">
                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                Preencher formulário
            </a>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll('.eb-reveal').forEach(function (el) {
            el.classList.add('is-visible');
        });
        return;
    }

    var nodes = document.querySelectorAll('.eb-reveal');
    if (!('IntersectionObserver' in window) || !nodes.length) {
        nodes.forEach(function (el) { el.classList.add('is-visible'); });
        return;
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    nodes.forEach(function (el) { observer.observe(el); });
})();
</script>
@endpush
