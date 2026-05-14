@extends('layouts.app')

@section('title', 'Time de Desenvolvimento')

@section('content')
    <img class="page-header-img"
         src="{{ asset('img/time_dev/time_dev_header.webp') }}"
         alt="Time de Desenvolvimento"
         fetchpriority="high"
         decoding="async">

    <div class="page-container" style="padding-left: 16px; padding-right: 16px;">
        <div class="page-hero" style="margin-bottom: 22px;">
            <h1 class="page-title" style="margin-bottom: 10px;">Time de Desenvolvimento</h1>
            <p class="page-subtitle">Profissionais que constroem, mantêm e evoluem este site com foco em qualidade, performance e acessibilidade.</p>
        </div>

        @php
            $devs = [
                [
                    'name' => 'Maurício Marinho',
                    'linkedin' => 'https://www.linkedin.com/in/mauricio-marinho-formiga-198b9429/',
                    'image' => asset('img/time_dev/mauricio.webp'),
                    'alt' => 'Maurício Marinho',
                    'description' => 'Servidor Público Federal na área de Gestão em TI, contribuindo para o desenvolvimento de sistemas que impactam milhões de cidadãos.',
                    'formations' => [
                        'Graduação em Tecnologia da Informação',
                        'Mestrado em Tecnologia da Informação',
                        'Especialização em Inteligência Artificial',
                    ],
                ],
                [
                    'name' => 'Grégori Crispim',
                    'linkedin' => 'https://www.linkedin.com/in/gr%C3%A9gori-crispim-265954271/',
                    'image' => asset('img/time_dev/gregori.webp'),
                    'alt' => 'Grégori Crispim',
                    'description' => 'Desenvolvedor Full Stack',
                    'formations' => [
                        'Graduando Bacharelado em Engenharia de Software',
                        'Graduando Bacharelado em Sistemas de Informação',
                        'Graduando Licenciatura em Computação',
                        'Graduando Tecnólogo em Análise e Desenvolvimento de Sistemas',
                    ],
                ],
            ];

            shuffle($devs);
        @endphp

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
            @foreach ($devs as $dev)
                <article style="background: #fff; border-radius: 14px; padding: 16px; box-shadow: 0 8px 20px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.06);">
                    <a href="{{ $dev['linkedin'] }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="LinkedIn de {{ $dev['name'] }} (abre em nova aba)"
                       style="display:block;">
                        <img src="{{ $dev['image'] }}"
                             alt="{{ $dev['alt'] }}"
                             loading="lazy"
                             decoding="async"
                             style="width: 100%; aspect-ratio: 4/3; object-fit: cover; border-radius: 12px; margin-bottom: 12px;">
                    </a>
                    <h2 style="margin: 0 0 6px; font-size: 1.1rem;">{{ $dev['name'] }}</h2>
                    <p style="margin: 0 0 8px; opacity: .85; font-size: .92rem; line-height: 1.6;">
                        {{ $dev['description'] }}
                    </p>
                    <ul style="margin: 0; padding-left: 18px; opacity: .85; font-size: .92rem; line-height: 1.8;">
                        @foreach ($dev['formations'] as $formation)
                            <li style="list-style-type: disc;">{{ $formation }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ $dev['linkedin'] }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Saiba mais sobre {{ $dev['name'] }} no LinkedIn (abre em nova aba)"
                       style="display: inline-flex; align-items: center; justify-content: center; margin-top: 12px; padding: 10px 16px; border-radius: 999px; background: #0a66c2; color: #fff; text-decoration: none; font-size: .92rem; font-weight: 600;">
                        Saiba mais
                    </a>
                </article>
            @endforeach
        </div>
    </div>
@endsection

