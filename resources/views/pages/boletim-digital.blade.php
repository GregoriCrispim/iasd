@extends('layouts.app')

@section('title', 'Boletim Digital - IASD Central de Brasília')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/boletim-digital.css') }}">
@endpush

@php
    $boletimBase = 'img/boletim/boletim_18_07_2026';
    $oracao365Base = $boletimBase . '/365 Dias de Oração';

    $linkify = static function (?string $text): ?string {
        if ($text === null || $text === '') {
            return $text;
        }

        return preg_replace(
            '~(https?://[^\s<]+)~',
            '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
            $text
        );
    };

    $texto365Dias = 'Continuamos envolvidos no projeto Jornada de Oração: Frutos do Espírito. Ao longo deste mês, vamos orar pedindo a Deus que desenvolva em nossa vida o fruto: BENIGNIDADE - GENTILEZA. O desafio da QUARTA semana de JULHO é: Ore por gentileza até quando estiver cansada.';
    $textoEmDefesaLiberdade = 'Perspectivas Jurídicas, Históricas e Teológicas sobre os Direitos Fundamentais. O alicerce de toda e qualquer sociedade democrática reside no respeito irrestrito e no exercício seguro dos direitos e das garantias fundamentais. Contudo, a contemporaneidade nos impõe uma reflexão crítica: observamos, globalmente, movimentos estruturados que tangenciam o enfraquecimento dessas prerrogativas jurídicas, com o nítido escopo de fragmentá-las e mitigá-las no horizonte social. Para debater a complexidade desse cenário, convidamos a comunidade acadêmica, juristas, pesquisadores e estudantes para um ciclo de palestras de alto nível técnico e intelectual. O evento contará com a exposição de Mestres e Doutores, além de renomados juristas e advogados, que cruzarão a análise dogmática do Direito Constitucional com as profundas advertências históricas e teológicas contidas no texto bíblico.<br><br>A Temática Central: Tomando como objeto de análise a exegese de Apocalipse 13:11 que descreve metodicamente a transição de um poder de aparência pacífica (cordeiro) para uma postura autocrática (dragão), o corpo de palestrantes debaterá as reais implicações jurídicas, geopolíticas e institucionais desse cenário em um futuro iminente.<br><br><strong>Programação e cronograma</strong><br>Dias 19 e 26 de julho (domingos): Painéis temáticos às 19h<br>Local: Igreja Adventista Central de Brasília – SGAS 611, Módulo 75 – Brasília/DF<br><br><strong>Informações sobre ingressos</strong><br>Gratuito mediante retirada de ingresso no Sympla pelo link: https://www.sympla.com.br/evento/palestra-em-defesa-da-liberdade/3491192 (evento de caráter acadêmico-cultural).<br>Inscrições: Imprescindíveis para controle de capacidade do auditório. Vagas limitadas à lotação do espaço.<br><br>Nota aos acadêmicos: Uma oportunidade única para o desenvolvimento do pensamento crítico, debate hermenêutico e networking com especialistas e referências das áreas jurídica e teológica. Garanta a sua participação!';

    $boletins = [
        // Com descrição (script DOCX)
        [
            'type' => 'image',
            'src' => $boletimBase . '/Culto Permanente.jpg',
            'alt' => 'Culto Permanente',
            'title' => 'Culto Permanente',
            'text' => 'Participe do Culto Permanente coordenado pela Igreja Adventista Central de Brasília. Um momento especial de paz, oração e fortalecimento espiritual para todos. Todo 3º sábado de cada mês, às no Hospital Brasília Lago Sul, Espaço Energia. O convite é aberto a pacientes, familiares, profissionais, colaboradores e toda a comunidade. Participe e venha viver este tempo de esperança!',
        ],
        [
            'type' => 'image',
            'src' => $oracao365Base . '/WhatsApp Image 2026-05-30 at 22.57.55.jpeg',
            'alt' => '365 Dias de Oração — Jornada de Oração',
            'title' => '365 Dias de Oração',
            'text' => $texto365Dias,
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Agasalho.jpg',
            'alt' => 'Campanha do agasalho',
            'title' => 'Campanha do Agasalho',
            'text' => 'A ASA está arrecadando agasalhos, cobertores e roupas de frio em geral. Colabore doando itens limpos e em bom estado de conservação; o que não lhe serve mais será de grande valia para famílias que enfrentam o rigor deste inverno. Deixe sua doação na caixa da ASA, localizada na recepção da igreja.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Bordado.jpg',
            'alt' => 'Curso de bordado ASA',
            'title' => 'Curso de Bordado',
            'text' => 'Oportunidade Imperdível: Curso de Bordado ASA! Venha aprender técnicas exclusivas para confeccionar lindas peças. Aprender a bordar é uma excelente oportunidade para empreender e conquistar uma renda extra. Todos os domingos às 9h. Invista no seu talento e transforme o seu domingo em um momento de aprendizado e crescimento. Esperamos por você!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Voluntariado.jpeg',
            'alt' => 'Voluntariado nos ministérios da igreja',
            'title' => 'Voluntariado',
            'text' => 'Seja voluntário em um de nossos ministérios! Acesse o link/QR Code e escolha o departamento da igreja que mais combina com você. https://forms.gle/yBKYhMg3V7Rcd8uY6',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/PG FEm.jpeg',
            'alt' => 'Pequeno Grupo Feminino',
            'title' => 'PG Feminino',
            'text' => 'Atenção mulheres! Temos um encontro especial a cada 15 dias no nosso PG Feminino, um espaço de acolhimento, amizade e fé. Fale com a líder do Ministério da Mulher Cristiane Barreto, para participar. Nos reunimos às quintas-feiras a cada 15 dias. Participe conosco!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Oi Amiga.jpg',
            'alt' => 'Oi Amiga — capacitação para estudos bíblicos',
            'title' => 'Oi Amiga',
            'text' => 'Se você tem o desejo de compartilhar a Palavra de Deus, mas nunca deu um estudo bíblico e não sabe por onde começar, este convite é para você. No dia 25 de julho, às 16h30, teremos uma reunião on-line muito especial. Nosso objetivo é apresentar um material exclusivo e compartilhar dicas práticas e simples para capacitar você a iniciar estudos bíblicos com as amigas que têm participado dos eventos da nossa igreja. Não se preocupe com a falta de experiência: este será um espaço de apoio, aprendizado e encorajamento mútuo. Queremos caminhar de mãos dadas com você nessa missão. Reserve essa data na sua agenda. Escaneie o QR Code e inscreva-se! Contamos com a sua presença!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/encontro_mulheres.jpeg',
            'alt' => 'Encontro especial de mulheres — Vida que Eleva',
            'title' => 'Encontro Especial de Mulheres',
            'text' => 'Queridas mulheres, vocês são nossas convidadas especiais para uma tarde de renovação, comunhão e inspiração! No sábado, dia 08/08/26, às 16h, aqui na Igreja Central de Brasília, teremos um encontro especial de mulheres com o tema "Vida que Eleva". Teremos a alegria de receber Darleide Alves, apresentadora da TV Novo Tempo! Uma oportunidade imperdível para ouvirmos uma mensagem poderosa para os nossos corações. Tudo foi preparado com muito carinho para vocês. Não venham sozinhas: tragam uma amiga e venham compartilhar desse momento tão especial com a gente! Inscrições: https://docs.google.com/forms/d/e/1FAIpQLSeF8-UFe5ARIx4UbbhkRNj3X7ccJfP0luyVo2uuTeOUQ-vOaQ/viewform?usp=publish-editor',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Ceia.jpg',
            'alt' => 'Ceia do Senhor',
            'title' => 'Ceia',
            'text' => 'Atenção! A nossa Santa Ceia precisou ser remarcada. Anote a nova data e programe-se: Sábado, 25 de julho, a partir das 08h30, com a cerimônia do lava-pés, aqui na Igreja Adventista Central de Brasília. Que possamos aproveitar esta semana para preparar nossos corações em oração e consagração para este momento tão especial de comunhão e gratidão. Compartilhe este aviso para que todos fiquem sabendo!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Oficina do Bem.jpg',
            'alt' => 'Oficina do Bem — Doutores de Esperança',
            'title' => 'Coração do Bem',
            'text' => 'No mês de julho estaremos de férias, nossa oficina retornará em agosto, nas datas programadas.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Entrega de livros.jpg',
            'alt' => 'Entrega de livros missionários',
            'title' => 'Entrega de Livros',
            'text' => 'Malas prontas? Não esqueça o essencial! As férias chegaram e o descanso é merecido, mas a nossa missão não tira folga. Onde quer que você vá neste período, a sua fé vai com você. Aproveite cada parada, cada reencontro e cada novo lugar para espalhar esperança. O livro "Contagem Regressiva" é o seu companheiro de viagem perfeito para alcançar corações por onde você passar. Antes de sair, passe no Centro White de publicações, pegue os seus exemplares e distribua esperança por onde os seus pés pisarem. Transforme seus dias de descanso em uma oportunidade eterna para a vida de alguém.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Classe de Saúde - Divulgação.jpg',
            'alt' => 'Classe de Saúde — férias em julho',
            'title' => 'Classe de Saúde',
            'text' => 'No mês de julho, a Classe Vida e Saúde da Igreja Adventista Central de Brasília estará de férias. Retornaremos nossas atividades em agosto.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/SGI.jpg',
            'alt' => 'SGI — Sistema de Gerenciamento de Interessados',
            'title' => 'SGI',
            'text' => 'Está no ar o SGI - Sistema de Gerenciamento de Interessados. Querido membro, se você está estudando a Bíblia com alguém, a Igreja Central conta agora com um sistema de cadastro de interessados, o SGI, onde você poderá se cadastrar como Instrutor bíblico e cadastrar seus alunos. Nele você contará com o apoio do Ministério Pessoal e com estudos bíblicos especialmente preparados. Aponte seu celular para o QR CODE que está na tela e faça hoje mesmo o seu cadastro. E se você é visitante: Que bom que você veio! É uma alegria tê-lo conosco. Se você está nos visitando pela primeira vez e gostaria que orássemos por você ou tem interesse em estudar a Bíblia, acesse o nosso site https://aplac.sgi7.com.br/ ou procure nossa equipe de recepção, preencha o cartão de visitas que teremos o maior prazer em atendê-lo.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/classe pós batismo (1).jpg',
            'alt' => 'Classe Pós-Batismo — Programa de Discipulado 2026',
            'title' => 'Classe Pós-Batismo',
            'text' => 'Queridos membros, no primeiro sábado de agosto, 01/08/2026, às 10h45, na Sala do Ministério de Oração, acontecerá o novo Programa de Discipulado da Classe Pós-Batismal de 2026. Este projeto é voltado para quem se batizou entre 2023 e 2026 ou para membros que ainda não participaram desse programa e desejam fortalecer a fé, conhecendo a fundo a história, a organização, a missão global e o estilo de vida da Igreja Adventista. Para garantir sua vaga, você deverá acessar o link https://wa.me/qr/WFZC7FAE4POAK1 para se inscrever ou falar diretamente com o líder do Ministério Pessoal, irmão Alexandre Tinoco. Participe!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Francês.jpeg',
            'alt' => 'Classe de Escola Sabatina em Francês',
            'title' => 'Classe de Francês',
            'text' => 'Temos uma excelente notícia para os amantes de idiomas e do estudo da Palavra: a Classe de Escola Sabatina em Francês está de volta! No sábado, 04/07/26, às 10h, na Igreja Adventista Internacional "BIC", retomaremos esse espaço dedicado a adultos que desejam aprofundar seu conhecimento no idioma francês enquanto estudamos a Bíblia juntos. É uma oportunidade maravilhosa para aprender e compartilhar em comunidade. Entre no nosso grupo de WhatsApp para receber todos os detalhes e materiais. https://chat.whatsapp.com/KcWLeUItuvWEnZ27s6Xs67?mode=gi_t À bientôt!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/curso de noivos.jpg',
            'alt' => 'Curso de Noivos',
            'title' => 'Curso de Noivos',
            'text' => 'Estão abertas as inscrições para o Curso de Noivos, que acontecerá no dia 8 de agosto, no auditório da APLaC, a partir das 8h45. Realize sua inscrição pelo link. https://eventos.adventistasbrasilia.org.br/event/cadastro_individual/381',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Encontro SDV (2).jpg',
            'alt' => 'Palestra SDV 40+ — Relacionamento',
            'title' => 'Palestra SDV',
            'text' => 'Em um NOVO relacionamento amoroso, homens e mulheres buscam bases semelhantes, como respeito, confiança e amor. No entanto, a forma de expressar e priorizar essas necessidades costuma ser diferente devido a fatores biológicos, sociais e culturais. Para entender como conciliar esses interesses e descobrir novas perspectivas para fortalecer a convivência, convidamos os Solteiros, Divorciados e Viúvos acima de 40 anos para uma palestra imperdível do projeto SDV 40+, com o tema: Relacionamento: o que as mulheres querem e o que os homens querem? A palestra será ministrada por Flávia Perrotta, especialista em Neurociência, Comunicação e Desenvolvimento. O evento acontecerá no dia 18 de julho, às 17h, aqui na Igreja, na Sala Novo Tempo. Não perca essa oportunidade de aprendizado e crescimento pessoal. Participe!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Em-defesa-da-liberdade.jpg',
            'alt' => 'Série Em Defesa da Liberdade',
            'title' => 'Em Defesa da Liberdade',
            'text' => $textoEmDefesaLiberdade,
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/cientistas adventistas.jpg',
            'alt' => 'VII Congresso Internacional de Cientistas Adventistas',
            'title' => 'Congresso de Cientistas Adventistas',
            'text' => 'Ciência, Fé e Redenção: interpretando o mundo à luz do grande conflito. A sétima edição do Congresso Internacional Multidisciplinar dos Cientistas Adventistas será realizada de 11 a 13 de setembro de 2026 em Cachoeira–BA, e promete ser tão grandiosa quanto suas edições anteriores. Sob o tema geral "Ciência, Fé e Redenção: interpretando o mundo à luz do grande conflito", o congresso convida especialistas de várias disciplinas para debater os mais recentes avanços e desafios. Com um leque diversificado de palestras, o evento será uma plataforma excepcional para troca de ideias pioneiras e fomento de parcerias produtivas. Representa uma chance única para aqueles que desejam ampliar seus horizontes intelectuais e espirituais, sejam profissionais, estudantes ou entusiastas. Faça sua inscrição pelo link: https://www.even3.com.br/vii-congresso-internacional-de-cientistas-adventistas-715277/',
        ],
    ];

    foreach ($boletins as &$boletim) {
        if (!empty($boletim['text'])) {
            $boletim['text'] = $linkify($boletim['text']);
        }
    }
    unset($boletim);

    $boletimColumns = [[], []];
    foreach ($boletins as $index => $boletim) {
        $boletimColumns[$index % 2][] = $boletim;
    }
@endphp

@section('content')
<section class="boletim-page">
    <div class="boletim-page__header">
        <span class="boletim-page__eyebrow">Central Informa</span>
        <h1 class="acb-title-serif">Boletim Digital</h1>
        <p>Acompanhe as programações e eventos da IASD Central de Brasília.</p>
    </div>

    <div class="boletim-feed" aria-label="Conteúdos do boletim digital">
        @foreach ($boletimColumns as $columnIndex => $column)
            <div class="boletim-feed__column">
                @foreach ($column as $rowIndex => $boletim)
                    @php($hasCaption = !empty($boletim['title']) || !empty($boletim['text']))
                    <article class="boletim-feed__item{{ $hasCaption ? '' : ' boletim-feed__item--media-only' }}" style="--boletim-order: {{ $rowIndex * 2 + $columnIndex }}">
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
                                    <div class="boletim-feed__text-wrap">
                                        <div class="boletim-feed__text">
                                            <p>{!! $boletim['text'] !!}</p>
                                        </div>
                                        <button type="button" class="boletim-feed__toggle" hidden aria-expanded="false">
                                            <span class="boletim-feed__toggle-label">Mostrar mais</span>
                                            <span class="boletim-feed__toggle-icon" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
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
