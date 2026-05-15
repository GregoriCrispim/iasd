@extends('layouts.app')

@section('title', 'Boletim Digital - IASD Central de Brasília')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/boletim-digital.css') }}">
@endpush

@php
    $boletins = [
        [
            'type' => 'image',
            'src' => 'img/boletim/calendário doutores.jpeg',
            'alt' => 'Calendário dos Doutores de Esperança',
            'title' => 'Coração do Bem',
            'text' => 'No próximo domingo dia 24/05, às 9h, acontecerá mais uma Oficina do Bem, na sala dos Doutores de Esperança. Um encontro de afeto e solidariedade, onde voluntários se reúnem para confeccionar corações de feltro que serão distribuídos aos pacientes durante os plantões do projeto Doutores de Esperança. Qualquer pessoa pode participar.',
        ],
        [
            'type' => 'image',
            'src' => 'img/boletim/impacto esperanca.jpeg',
            'alt' => 'Impacto Esperança 2026',
            'title' => 'Etiquetagem',
            'text' => 'O Impacto Esperança 2026 precisa de você! Continuamos nosso mutirão de etiquetagem dos livros missionários todos os sábados, das 15h às 19h, em frente ao Centro White. Venha nos ajudar a preparar esse material evangelístico. Sua dedicação é essencial para o sucesso desta missão. Contamos com sua presença!',
        ],
        [
            'type' => 'image',
            'src' => 'img/boletim/convite_jotinha_16_9.jpg.jpeg',
            'alt' => 'Convite para programação infantil',
            'title' => null,
            'text' => null,
        ],
        [
            'type' => 'image',
            'src' => 'img/boletim/liberdade religiosa.jpeg',
            'alt' => 'Liberdade religiosa',
            'title' => null,
            'text' => null,
        ],
        [
            'type' => 'video',
            'src' => 'img/boletim/caminhando com jesus -aventureiros.mp4',
            'alt' => 'Vídeo Caminhando com Jesus - Aventureiros',
            'title' => null,
            'text' => null,
        ],
        [
            'type' => 'video',
            'src' => 'img/boletim/clube aventureiros.mp4',
            'alt' => 'Vídeo Clube de Aventureiros',
            'title' => null,
            'text' => null,
        ],
        [
            'type' => 'image',
            'src' => 'img/boletim/voluntariado.jpeg',
            'alt' => 'Voluntariado nos ministérios da igreja',
            'title' => 'Voluntariado',
            'text' => 'Seja voluntário em um de nossos ministérios! Acesse o QR Code e escolha o departamento da igreja que mais combina com você.',
        ],
    ];
@endphp

@section('content')
<section class="boletim-page">
    <div class="boletim-page__header">
        <span class="boletim-page__eyebrow">Central Informa</span>
        <h1 class="acb-title-serif">Boletim Digital</h1>
        <p>Acompanhe os anúncios e materiais desta semana da IASD Central de Brasília.</p>
    </div>

    <div class="boletim-feed" aria-label="Conteúdos do boletim digital">
        @foreach ($boletins as $boletim)
            <article class="boletim-feed__item">
                <div class="boletim-feed__media-wrap">
                    @if ($boletim['type'] === 'video')
                        <video class="boletim-feed__media" controls muted playsinline preload="metadata" aria-label="{{ $boletim['alt'] }}">
                            <source src="{{ asset($boletim['src']) }}" type="video/mp4">
                            Seu navegador não suporta a reprodução deste vídeo.
                        </video>
                    @else
                        <button type="button" class="boletim-feed__image-button boletim-lightbox-trigger" data-full="{{ asset($boletim['src']) }}" aria-label="Ampliar {{ $boletim['alt'] }}">
                            <img class="boletim-feed__media" src="{{ asset($boletim['src']) }}" alt="{{ $boletim['alt'] }}" loading="lazy" decoding="async">
                        </button>
                    @endif
                </div>

                @if ($boletim['title'] || $boletim['text'])
                    <div class="boletim-feed__caption">
                        @if ($boletim['title'])
                            <h2>{{ $boletim['title'] }}</h2>
                        @endif
                        @if ($boletim['text'])
                            <p>{{ $boletim['text'] }}</p>
                        @endif
                    </div>
                @endif
            </article>
        @endforeach
    </div>

    <div class="boletim-lightbox" id="boletim-lightbox" aria-hidden="true">
        <button type="button" class="boletim-lightbox__close" aria-label="Fechar">&times;</button>
        <img class="boletim-lightbox__content" id="boletim-lightbox-img" src="" alt="" loading="lazy" decoding="async">
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/boletim-digital.js') }}" defer></script>
@endpush
