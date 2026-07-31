@extends('layouts.app')

@section('title', 'Boletim Digital - IASD Central de Brasília')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/boletim-digital.css') }}">
@endpush

@php
    $boletimBase = 'img/boletim/boletim_01_08_2026';
    $oracao365Base = $boletimBase . '/365 Dias de Oração';
    $oracaoBase = $boletimBase . '/M. Oração';

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

    $texto365Dias = 'Continuamos envolvidos no projeto Jornada de Oração: Frutos do Espírito. Ao longo deste mês, vamos orar pedindo a Deus que desenvolva em nossa vida o fruto: O desafio da PRIMEIRA semana de AGOSTO é BONDADE: Ore para praticar o bem dentro de casa, através de atitudes simples, serviço e palavras que edificam.';
    $textoReuniaoOracao = 'Participe da nossa Reunião de Oração. Temos recebido grandes bênçãos do Senhor. Venha clamar pelo derramamento do Espírito Santo! Nossas reuniões acontecem a cada 15 dias, acompanhe e venha orar conosco.';

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
            'src' => $oracao365Base . '/WhatsApp Image 2026-05-30 at 22.57.53.jpeg',
            'alt' => '365 Dias de Oração — Jornada de Oração',
            'title' => '365 Dias de Oração',
            'text' => $texto365Dias,
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/WhatsApp Image 2026-07-28 at 11.02.57.jpeg',
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
            'text' => 'Se você tem o desejo de compartilhar a Palavra de Deus, mas nunca deu um estudo bíblico e não sabe por onde começar, este convite é para você. As reuniões serão on-line. Nosso objetivo é apresentar um material exclusivo e compartilhar dicas práticas e simples para capacitar você a iniciar estudos bíblicos com as amigas que têm participado dos eventos da nossa igreja. Não se preocupe com a falta de experiência: este será um espaço de apoio, aprendizado e encorajamento mútuo. Queremos caminhar de mãos dadas com você nessa missão. Reserve a sua agenda. Acesse o link e inscreva-se: https://docs.google.com/forms/d/e/1FAIpQLSfyTOpyHObLtKZi4doWD-2094Yoy5GAEN9cm0oSxQZK-isRZQ/viewform',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Quebrando o Silêncio.jpg',
            'alt' => 'Quebrando o Silêncio — Idosos em Risco',
            'title' => 'Quebrando o Silêncio',
            'text' => 'Idosos em Risco — A violência que atinge quem mais precisa de cuidado. Desde 2002, a Igreja Adventista do Sétimo Dia atua na prevenção contra o abuso e a violência por meio do projeto Quebrando o Silêncio. Neste ano, o tema será "Idosos em Risco". No dia 22 de agosto, às 15h, realizaremos uma ação especial de amor e cuidado com uma visita à pousada da Casa do Ceará. Você pode fazer a diferença participando conosco como voluntário ou contribuindo com doações de fraldas geriátricas (o item de maior necessidade), lenços umedecidos e produtos de higiene pessoal, tais como sabonete, xampu, creme hidratante, creme dental e escova de dente. Inscreva-se para participar através do link: https://forms.gle/DUjiLok9vspJXrvF7',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Darleide, Encontro de Mulheres.jpg',
            'alt' => 'Encontro especial de mulheres — Vida que Eleva',
            'title' => 'Encontro Especial de Mulheres',
            'text' => 'Queridas mulheres, vocês são nossas convidadas especiais para uma tarde de renovação, comunhão e inspiração! No sábado, dia 08/08/26, às 16h, aqui na Igreja Central de Brasília, teremos um encontro especial de mulheres com o tema "Vida que Eleva". Teremos a alegria de receber Darleide Alves, apresentadora da TV Novo Tempo! Uma oportunidade imperdível para ouvirmos uma mensagem poderosa para os nossos corações. Tudo foi preparado com muito carinho para vocês. Não venham sozinhas: tragam uma amiga e venham compartilhar desse momento tão especial com a gente! Inscrições: https://docs.google.com/forms/d/e/1FAIpQLSeF8-UFe5ARIx4UbbhkRNj3X7ccJfP0luyVo2uuTeOUQ-vOaQ/viewform?usp=publish-editor',
        ],
        [
            'type' => 'image',
            'src' => $oracaoBase . '/01_08_2026.jpeg',
            'alt' => 'Reunião de oração',
            'title' => 'Reunião de Oração',
            'text' => $textoReuniaoOracao,
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Quartas da Família.jpg',
            'alt' => 'Quartas da Família — Chaves da Felicidade Familiar',
            'title' => 'Quartas da Família',
            'text' => 'Queridos irmãos, o Ministério da Família da Igreja Central de Brasília convida você e sua família para a série "Chaves da Felicidade Familiar", que acontecerá de 05 de agosto a 23 de setembro; serão 8 quartas-feiras especiais dedicadas à consagração, ao fortalecimento espiritual e ao aprendizado da Palavra de Deus. Venha interceder por sua família e traga convidados para buscarem juntos essa bênção, teremos presentes especiais para todos os visitantes não adventistas! Participem!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Oficina do Bem.jpg',
            'alt' => 'Oficina do Bem — Doutores de Esperança',
            'title' => 'Coração do Bem',
            'text' => 'Participe da Oficina do Bem, às 9h, na sala dos Doutores de Esperança. Onde voluntários se reúnem para confeccionar corações de feltro que serão distribuídos aos pacientes durante os Plantões dos Doutores de Esperança. Qualquer pessoa pode participar. Venha! Nossa oficina acontece a cada 15 dias, siga nosso calendário e venha ser um voluntário.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Entrega de livros.jpg',
            'alt' => 'Entrega de livros missionários',
            'title' => 'Entrega de Livros',
            'text' => 'O descanso acabou, mas a nossa missão só está começando! Agosto chegou e, com ele, renovamos nossas energias para o maior compromisso do nosso ano. As férias ficaram para trás, e agora o convite é direto para você: liderar e continuar o trabalho transformador de levar esperança às pessoas através do Impacto Esperança. Cada livro entregue é uma semente de transformação, uma resposta a uma oração e uma mensagem que atravessa vidas. A contagem regressiva já começou! Não deixe para depois. Ajuste sua agenda e junte-se a nós nessa corrente de fé e ação. A mensagem não pode parar, e a sua voz e suas mãos fazem toda a diferença nessa missão! Quem está pronto para fazer a diferença neste mês? Passe no Centro White e retire os livros que pretende entregar durante a semana.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Classe de Saúde - Divulgação.jpg',
            'alt' => 'Classe de Saúde — retorno em agosto',
            'title' => 'Classe de Saúde',
            'text' => 'No mês de julho, a Classe Vida e Saúde da Igreja Adventista Central de Brasília esteve de férias. Retornaremos às nossas atividades no dia 08 de agosto.',
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
            'src' => $boletimBase . '/Classe pós-batismo.jpg',
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
            'text' => 'Estão abertas as inscrições para o Curso de Noivos, que acontecerá no dia 8 de agosto, no auditório da APLaC, a partir das 8h45. Atenção! Restam poucas vagas! Realize sua inscrição pelo link. https://eventos.adventistasbrasilia.org.br/event/cadastro_individual/381',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Quartas de Poder 2026.jpg',
            'alt' => 'Quartas de Poder — O Mover do Espírito',
            'title' => 'Quartas de Poder',
            'text' => 'Convidamos toda a comunidade para os cultos especiais do projeto Quartas de Poder, que serão realizados nas últimas quartas-feiras de cada mês, sempre às 19h30, com o tema "O Mover do Espírito". A programação das últimas quartas-feiras do mês é dedicada ao fortalecimento da vida de oração da igreja local, integrando momentos de louvor, orações de agradecimento e testemunhos de respostas de oração alcançadas pela nossa comunidade. Contamos com a sua presença para juntos buscarmos ao Senhor em oração.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Série Coisas Estranhas.png',
            'alt' => 'Série Coisas Estranhas — Domingos Especiais',
            'title' => 'Coisas Estranhas',
            'text' => 'Fenômenos estranhos no espaço têm despertado a atenção de autoridades globais e alimentado intensas investigações oficiais, dividindo opiniões entre tecnologia humana desconhecida, inteligência extraterrestre ou fenômenos de origem ainda mais profunda. Antes de aceitar qualquer teoria, a nova série dos Domingos Especiais investiga o tema sob a perspectiva das Escrituras Sagradas para revelar o verdadeiro significado dos sinais nos céus e o impacto direto desses acontecimentos no seu cotidiano. Acompanhe a série especial intitulada COISAS ESTRANHAS, transmitida às 19h, todos os domingos durante os meses de agosto e setembro. Participe!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/cientistas adventistas.jpg',
            'alt' => 'VII Congresso Internacional de Cientistas Adventistas',
            'title' => 'Congresso de Cientistas Adventistas',
            'text' => 'Ciência, Fé e Redenção: interpretando o mundo à luz do grande conflito. A sétima edição do Congresso Internacional Multidisciplinar dos Cientistas Adventistas será realizada de 11 a 13 de setembro de 2026 em Cachoeira–BA, e promete ser tão grandiosa quanto suas edições anteriores. Sob o tema geral "Ciência, Fé e Redenção: interpretando o mundo à luz do grande conflito", o congresso convida especialistas de várias disciplinas para debater os mais recentes avanços e desafios. Com um leque diversificado de palestras, o evento será uma plataforma excepcional para troca de ideias pioneiras e fomento de parcerias produtivas. Representa uma chance única para aqueles que desejam ampliar seus horizontes intelectuais e espirituais, sejam profissionais, estudantes ou entusiastas. Faça sua inscrição pelo link: https://www.even3.com.br/vii-congresso-internacional-de-cientistas-adventistas-715277/',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Rádio NT.jpeg',
            'alt' => 'Rádio Novo Tempo em Brasília — 92.9 FM',
            'title' => 'Rádio Novo Tempo',
            'text' => 'É com muita alegria que anunciamos a chegada da Rádio Novo Tempo à capital federal, levando fé, música e mensagens que renovam as forças a cada dia. O lançamento oficial será no dia 24 de julho, às 18h. Prepare o seu rádio, sintonize a 92.9 FM e compartilhe essa novidade com quem você ama.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/CEMAB.jpg',
            'alt' => 'CEMAB — matrículas abertas',
            'title' => 'CEMAB',
            'text' => 'O Centro Musical Adventista de Brasília está com matrículas abertas. O futuro musical do seu filho começa aqui! Queridos pais e responsáveis, as matrículas para o 2º módulo de 2026 do CEMAB já estão abertas! Sabemos como a música é fundamental para o desenvolvimento do foco, da criatividade e da disciplina das crianças e jovens. Por isso, preparamos um semestre com muita prática e aprendizado! Vagas limitadas: Garanta o melhor horário para a rotina do seu filho. Clique no link e garanta agora mesmo sua vaga: https://forms.cloud.microsoft/Pages/ResponsePage.aspx?id=DQSIkWdsW0yxEjajBLZtrQAAAAAAAAAAAAMAAbJLm9tUNzdTWjRUSzZPNzFUUVlOODRYOFdGRVRRNC4u',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/ZAP.jpg',
            'alt' => 'Canal Central Informa no WhatsApp',
            'title' => 'Canal WhatsApp',
            'text' => 'Perdeu algum detalhe dos nossos anúncios? Não se preocupe. O Central Informa está disponível no nosso canal oficial no WhatsApp para manter você atualizado. Siga o canal: Adventistas Central Brasília! Acesse o link https://whatsapp.com/channel/0029VaY6Z5UJJhzdkYF51D1T e faça parte desta comunidade.',
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
