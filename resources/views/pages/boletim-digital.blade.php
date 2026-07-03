@extends('layouts.app')

@section('title', 'Boletim Digital - IASD Central de Brasília')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/boletim-digital.css') }}">
@endpush

@php
    $boletimBase = 'img/boletim/boletim_04_07_2026';
    $oracao365Base = $boletimBase . '/365 Dias de Oração';
    $oracaoBase = $boletimBase . '/M. Oração';
    $heroesBase = $boletimBase . '/HEROES';

    $texto365Dias = 'Continuamos envolvidos no projeto Jornada de Oração: Frutos do Espírito. Ao longo deste mês, vamos orar pedindo a Deus que desenvolva em nossa vida o fruto: BENIGNIDADE - GENTILEZA. O desafio da SEGUNDA semana de JULHO é: Ore para praticar a bondade de forma discreta, ajudando alguém sem buscar reconhecimento.';
    $textoReuniaoOracao = 'Participe da nossa Reunião de Oração. Temos recebido grandes bênçãos do Senhor. Venha clamar pelo derramamento do Espírito Santo! Nossas reuniões acontecem a cada 15 dias, acompanhe e venha orar conosco.';
    $textoEmDefesaLiberdade = 'Perspectivas Jurídicas, Históricas e Teológicas sobre os Direitos Fundamentais. O alicerce de toda e qualquer sociedade democrática reside no respeito irrestrito e no exercício seguro dos direitos e das garantias fundamentais. Contudo, a contemporaneidade nos impõe uma reflexão crítica: observamos, globalmente, movimentos estruturados que tangenciam o enfraquecimento dessas prerrogativas jurídicas, com o nítido escopo de fragmentá-las e mitigá-las no horizonte social. Para debater a complexidade desse cenário, convidamos a comunidade acadêmica, juristas, pesquisadores e estudantes para um ciclo de palestras de alto nível técnico e intelectual. O evento contará com a exposição de Mestres e Doutores, além de renomados juristas e advogados, que cruzarão a análise dogmática do Direito Constitucional com as profundas advertências históricas e teológicas contidas no texto bíblico.<br><br>A Temática Central: Tomando como objeto de análise a exegese de Apocalipse 13:11 que descreve metodicamente a transição de um poder de aparência pacífica (cordeiro) para uma postura autocrática (dragão), o corpo de palestrantes debaterá as reais implicações jurídicas, geopolíticas e institucionais desse cenário em um futuro iminente.<br><br><strong>Programação e cronograma</strong><br>05 de julho (domingo): Sessão de Abertura às 20h<br>Dias 12, 19 e 26 de julho (domingos): Painéis temáticos às 19h<br>Local: Igreja Adventista Central de Brasília – SGAS 611, Módulo 75 – Brasília/DF<br><br><strong>Informações sobre ingressos</strong><br>Gratuito mediante retirada de ingresso no Sympla (evento de caráter acadêmico-cultural). Inscrições imprescindíveis para controle de capacidade do auditório. Vagas limitadas à lotação do espaço.<br><br>Nota aos acadêmicos: Uma oportunidade única para o desenvolvimento do pensamento crítico, debate hermenêutico e networking com especialistas e referências das áreas jurídica e teológica. Garanta a sua participação!';

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
            'src' => $oracao365Base . '/samana2.jpeg',
            'alt' => '365 Dias de Oração — Jornada de Oração',
            'title' => '365 Dias de Oração',
            'text' => $texto365Dias,
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Entre Elas.jpg',
            'alt' => 'Entre Elas — encontro adiado',
            'title' => 'Entre Elas',
            'text' => 'Atenção, mulheres! O encontro do Entre Elas, que contaria com a presença da Dra. Elisabete falando sobre o tema "Como se preparar hoje para viver bem amanhã", precisou ser adiado. O evento não acontecerá mais neste domingo (dia 5 de julho). Estamos alinhando uma nova data para que possamos desfrutar dessa tarde enriquecedora de cuidado integral e saúde feminina com toda a atenção que vocês merecem. Assim que a nova data for definida, avisaremos todas vocês. Contamos com a compreensão de cada uma e, desde já, garantimos que a espera vai valer a pena.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Cópia de Evangelismo feminino 2026 - by Allan Franco  (Apresentação).jpg',
            'alt' => 'Encontro especial de mulheres — Vida que Eleva',
            'title' => 'Encontro Especial de Mulheres',
            'text' => 'Queridas mulheres, vocês são nossas convidadas especiais para uma tarde de renovação, comunhão e inspiração! No sábado, dia 08/08/26, às 16h, aqui na Igreja Central de Brasília, teremos um encontro especial de mulheres com o tema "Vida que Eleva". Teremos a alegria de receber Darleide Alves, apresentadora da TV Novo Tempo! Uma oportunidade imperdível para ouvirmos uma mensagem poderosa para os nossos corações. Tudo foi preparado com muito carinho para vocês. Não venham sozinhas: tragam uma amiga e venham compartilhar desse momento tão especial com a gente! Inscrições: https://docs.google.com/forms/d/e/1FAIpQLSeF8-UFe5ARIx4UbbhkRNj3X7ccJfP0luyVo2uuTeOUQ-vOaQ/viewform?usp=publish-editor',
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
            'src' => $oracaoBase . '/04_07_2026.jpeg',
            'alt' => 'Reunião de oração',
            'title' => 'Reunião de Oração',
            'text' => $textoReuniaoOracao,
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Ceia.jpg',
            'alt' => 'Ceia do Senhor',
            'title' => 'Ceia',
            'text' => 'Atenção! A nossa Santa Ceia precisou ser remarcada e não acontecerá mais neste sábado (04/07). Anote a nova data e programe-se: Sábado, 11 de julho, a partir das 08h30 aqui na Igreja Adventista Central de Brasília. Que possamos aproveitar esta semana para preparar nossos corações em oração e consagração para este momento tão especial de comunhão e gratidão. Compartilhe este aviso para que todos fiquem sabendo!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Oficina do Bem.png',
            'alt' => 'Oficina do Bem — Doutores de Esperança',
            'title' => 'Coração do Bem',
            'text' => 'Participe da Oficina do Bem, às 9h, na sala dos Doutores de Esperança. Onde voluntários se reúnem para confeccionar corações de feltro que serão distribuídos aos pacientes durante os Plantões dos Doutores de Esperança. Qualquer pessoa pode participar. Venha! Nossa oficina acontece a cada 15 dias, siga nosso calendário e venha ser um voluntário.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Entrega de livros.jpg',
            'alt' => 'Entrega de livros missionários',
            'title' => 'Entrega de Livros',
            'text' => 'Nós temos uma missão grandiosa e cada um de nós faz a diferença! Chegou a hora de acelerar o passo e incentivar a todos na entrega de dois livros por semana. Não é apenas uma distribuição, é uma entrega intencional, com propósito, oração e foco em alcançar corações. Imagine o impacto de cada página compartilhada na vida de alguém neste momento! Vamos juntos, com dedicação e amor, fazer essa mensagem ir mais longe. Procure a equipe de publicações, retire os seus livros e faça parte desse movimento de esperança!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/WhatsApp Image 2026-07-02 at 21.34.53.jpeg',
            'alt' => 'Classe de Saúde — O impacto do exercício físico na prevenção cardiorespiratória e cerebral',
            'title' => 'Classe de Saúde',
            'text' => 'Participe da Classe Vida & Saúde da Igreja Adventista Central de Brasília, aos sábados, sempre às 11h, e aprenda com profissionais renomados sobre temas vitais e ultra relevantes para o seu bem-estar físico, espiritual, corporal e material, a cada semana uma oportunidade única e gratuita para transformar sua vida por inteiro! Vem com a gente!',
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
            'src' => $boletimBase . '/SGI.jpg',
            'alt' => 'SGI — Sistema de Gerenciamento de Interessados',
            'title' => 'SGI',
            'text' => 'Está no ar o SGI - Sistema de Gerenciamento de Interessados. Querido membro, se você está estudando a Bíblia com alguém, a Igreja Central conta agora com um sistema de cadastro de interessados, o SGI, onde você poderá se cadastrar como Instrutor bíblico e cadastrar seus alunos. Nele você contará com o apoio do Ministério Pessoal e com estudos bíblicos especialmente preparados. Aponte seu celular para o QR CODE que está na tela e faça hoje mesmo o seu cadastro. E se você é visitante: Que bom que você veio! É uma alegria tê-lo conosco. Se você está nos visitando pela primeira vez e gostaria que orássemos por você ou tem interesse em estudar a Bíblia, acesse o nosso site pelo QR CODE que está aparecendo na tela ou procure nossa equipe de recepção, preencha o cartão de visitas que teremos o maior prazer em atendê-lo.',
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
            'src' => $boletimBase . '/calebe.jpg',
            'alt' => 'Culto Jovem Regional — Missão Calebe',
            'title' => 'Calebes',
            'text' => 'É com grande alegria que convidamos você para o grande Culto Jovem Regional! Sábado, 04/07/26, às 17h, aqui na Igreja Adventista Central de Brasília. Será um momento incrível de muita adoração, louvor e comunhão! Teremos a participação especial dos nossos amigos das igrejas do Sudoeste, Lago Sul e International Church. Teremos também a grande convocação para a Missão Calebe! Vamos nos reunir para refletir sobre o nosso papel como jovens na obra de Deus e descobrir como podemos impactar o mundo ao nosso redor. Venha preparado para ouvir testemunhos poderosos e estudar a Bíblia com o Pr. Paulo Prazeres. Não fique de fora dessa! Convide um amigo e venha fazer parte deste momento especial!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/cientistas adventistas.jpg',
            'alt' => 'VII Congresso Internacional de Cientistas Adventistas',
            'title' => 'Congressos de Cientistas Adventistas',
            'text' => 'Ciência, Fé e Redenção: interpretando o mundo à luz do grande conflito. A sétima edição do Congresso Internacional Multidisciplinar dos Cientistas Adventistas será realizada de 11 a 13 de setembro de 2026 em Cachoeira–BA, e promete ser tão grandiosa quanto suas edições anteriores. Sob o tema geral "Ciência, Fé e Redenção: interpretando o mundo à luz do grande conflito", o congresso convida especialistas de várias disciplinas para debater os mais recentes avanços e desafios. Com um leque diversificado de palestras, o evento será uma plataforma excepcional para troca de ideias pioneiras e fomento de parcerias produtivas. Representa uma chance única para aqueles que desejam ampliar seus horizontes intelectuais e espirituais, sejam profissionais, estudantes ou entusiastas. Faça sua inscrição pelo link: https://www.even3.com.br/vii-congresso-internacional-de-cientistas-adventistas-715277/',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/ASA Aberta.jpg',
            'alt' => 'ASA Aberta — pizzas e lanches',
            'title' => 'ASA Aberta',
            'text' => 'A ASA estará aberta, 04/07/26, após o pôr do sol, para receber você com pizzas e lanches deliciosos a preços acessíveis, em um ambiente acolhedor e descontraído, perfeito para reencontrar amigos e fazer novas conexões. Aproveite!',
        ],
        // Sem descrição no script
        [
            'type' => 'image',
            'src' => $heroesBase . '/WhatsApp Image 2026-05-27 at 08.08.06.jpeg',
            'alt' => 'Série evangelística Heroes',
            'title' => null,
            'text' => null,
        ],
    ];

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
