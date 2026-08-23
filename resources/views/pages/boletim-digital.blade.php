@extends('layouts.app')

@section('title', 'Boletim Digital - IASD Central de Brasília')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/boletim-digital.css') }}">
@endpush

@php
    $boletimBase = 'img/boletim/boletim_22_08_2026';
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

    $texto365Dias = 'Continuamos envolvidos no projeto Jornada de Oração: Frutos do Espírito. Ao longo deste mês, vamos orar pedindo a Deus que desenvolva em nossa vida o fruto: O desafio da QUARTA semana de AGOSTO é BONDADE: Ore para demonstrar bondade até mesmo com quem já lhe feriu ou decepcionou.';

    $boletins = [
        // Com descrição (script DOCX)
        [
            'type' => 'image',
            'src' => $oracao365Base . '/WhatsApp Image 2026-05-30 at 22.57.52 (3).jpeg',
            'alt' => '365 Dias de Oração — Jornada de Oração',
            'title' => '365 Dias de Oração',
            'text' => $texto365Dias,
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
            'src' => $boletimBase . '/Oi Amiga!.jpg',
            'alt' => 'Oi Amiga — capacitação para estudos bíblicos',
            'title' => 'Oi Amiga',
            'text' => 'Se você tem o desejo de compartilhar a Palavra de Deus, mas nunca deu um estudo bíblico e não sabe por onde começar, este convite é para você. As reuniões serão on-line. Nosso objetivo é apresentar um material exclusivo e compartilhar dicas práticas e simples para capacitar você a iniciar estudos bíblicos com as amigas que têm participado dos eventos da nossa igreja. Não se preocupe com a falta de experiência: este será um espaço de apoio, aprendizado e encorajamento mútuo. Queremos caminhar de mãos dadas com você nessa missão. Reserve a sua agenda. Acesse o link e inscreva-se: https://docs.google.com/forms/d/e/1FAIpQLSfyTOpyHObLtKZi4doWD-2094Yoy5GAEN9cm0oSxQZK-isRZQ/viewform',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Encontro de Mulheres com Darleide.jpg',
            'alt' => 'Encontro especial de mulheres — Vida que Eleva',
            'title' => 'Encontro Especial de Mulheres',
            'text' => 'Queridas amigas, hoje teremos o encontro de mulheres "Vida que Eleva", às 16h. Aguardamos vocês para uma tarde memorável preparada com muito carinho, teremos a presença especial da apresentadora da TV Novo Tempo, Darleide Alves. Não percam! Inscrições: https://docs.google.com/forms/d/e/1FAIpQLSeF8-UFe5ARIx4UbbhkRNj3X7ccJfP0luyVo2uuTeOUQ-vOaQ/viewform?usp=publish-editor',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Quartas da Família (1).jpg',
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
            'src' => $boletimBase . '/SGI.jpg',
            'alt' => 'SGI — Sistema de Gerenciamento de Interessados',
            'title' => 'SGI',
            'text' => 'Está no ar o SGI - Sistema de Gerenciamento de Interessados. Querido membro, se você está estudando a Bíblia com alguém, a Igreja Central conta agora com um sistema de cadastro de interessados, o SGI, onde você poderá se cadastrar como Instrutor bíblico e cadastrar seus alunos. Nele você contará com o apoio do Ministério Pessoal e com estudos bíblicos especialmente preparados. Aponte seu celular para o QR CODE que está na tela e faça hoje mesmo o seu cadastro. E se você é visitante: Que bom que você veio! É uma alegria tê-lo conosco. Se você está nos visitando pela primeira vez e gostaria que orássemos por você ou tem interesse em estudar a Bíblia, acesse o nosso site https://aplac.sgi7.com.br/ ou procure nossa equipe de recepção, preencha o cartão de visitas que teremos o maior prazer em atendê-lo.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Maná.jpeg',
            'alt' => 'Projeto Maná — assinatura da Lição da Escola Sabatina',
            'title' => 'Projeto Maná',
            'text' => 'A vida é feita de escolhas. E uma delas é decidir o que vai ocupar um espaço na nossa rotina. Assinar a Lição da Escola Sabatina é mais do que receber um material em casa. É escolher alimentar a fé, aprofundar o conhecimento da Palavra e reservar, todos os dias, um momento para estar com Deus. No Projeto Maná, queremos incentivar uma igreja que não apenas ouve sobre a Bíblia, mas que a estuda, vive e compartilha. Faça parte desse movimento. Assine a Lição da Escola Sabatina e transforme seu estudo em um compromisso diário com Deus. Acesse: https://projetomana.cpb.com.br/ ou pelo WhatsApp: (61) 98235-0008 ou ligue: (61) 3321-2021 ou 0800 979 0666.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/On Voice.jpeg',
            'alt' => 'One Voice 27 — uma só voz para anunciar Jesus',
            'title' => 'One Voice',
            'text' => 'Uma só voz para anunciar Jesus e Sua breve volta! Dia 5 de setembro. Participe deste movimento!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Classe pós-batismo.jpg',
            'alt' => 'Classe Pós-Batismo — Programa de Discipulado 2026',
            'title' => 'Classe Pós-Batismo',
            'text' => 'Queridos membros, aos sábados, às 10h45, na Sala do Ministério de Oração, acontece o Programa de Discipulado da Classe Pós-Batismal de 2026. Este projeto é voltado para quem se batizou entre 2023 e 2026 ou para membros que ainda não participaram desse programa e desejam fortalecer a fé, conhecendo a fundo a história, a organização, a missão global e o estilo de vida da Igreja Adventista. Para garantir sua vaga, você deverá acessar o link https://wa.me/qr/WFZC7FAE4POAK1 para se inscrever ou falar diretamente com o líder do Ministério Pessoal, irmão Alexandre Tinoco. Participe!',
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
            'text' => 'Relatos de casas assombradas, experiências mediúnicas e fenômenos considerados paranormais acompanham a história humana, desafiando nossa compreensão sobre a realidade. Entre explicações psicológicas, manifestações espirituais e interpretações culturais, surge uma questão fundamental: como avaliar aquilo que parece ultrapassar os limites do natural? À luz das Escrituras, este tema convida a uma reflexão profunda sobre o mundo invisível, o discernimento espiritual e a diferença entre mistério, engano e verdade. Assista a nova série dos domingos especiais de agosto e setembro intitulada COISAS ESTRANHAS, sempre às 19h.',
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
            'text' => 'É com muita alegria que anunciamos a chegada da Rádio Novo Tempo à capital federal, levando fé, música e mensagens que renovam as forças a cada dia. O lançamento oficial ocorreu no dia 24 de julho. Prepare o seu rádio, sintonize a 92.9 FM e compartilhe essa novidade com quem você ama.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Sementes Musicais.jpg',
            'alt' => 'Sementes Musicais — flautas doces no CEMAB',
            'title' => 'Sementes Musicais',
            'text' => 'O projeto de musicalização através das flautas doces denominado Sementes Musicais está de volta, oferecendo aulas totalmente gratuitas e abertas ao público. Voltada para quem deseja aprender a tocar um instrumento ou aperfeiçoar sua técnica, a iniciativa ocorre semanalmente, todos os sábados, às 15h30, sendo necessário apenas levar a própria flauta para participar. Os encontros acontecem na sala da Saúde, as inscrições são feitas presencialmente. Venha tocar conosco!',
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
            'src' => $boletimBase . '/Orquestra CEMAB.jpg',
            'alt' => 'Orquestra CEMAB — ensaios semanais',
            'title' => 'Orquestra CEMAB',
            'text' => 'A Orquestra CEMAB retoma suas atividades. Os ensaios acontecem semanalmente e são totalmente gratuitos, nossos encontros acontecem na Sala da Orquestra do CEMAB. Voltada para instrumentistas de todos os níveis que desejam integrar o grupo musical, a iniciativa ocorre todos os sábados, às 15h30, com inscrições realizadas presencialmente no próprio local, sendo necessário apenas levar o instrumento para participar.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Desbravadores.jpg',
            'alt' => 'Apoie um Desbravador — Campori DSA 2027',
            'title' => 'Rumo ao Campori da DSA 2027',
            'text' => 'O Clube de Desbravadores Cruzeiro do Sul já está se preparando para participar do Campori da Divisão Sul-Americana, o maior encontro de Desbravadores, que será realizado de 5 a 10 de janeiro de 2027, em Barretos/SP. Mais do que uma viagem, o Campori representa uma oportunidade de crescimento espiritual, desenvolvimento de valores cristãos, fortalecimento da fé e formação do caráter de nossas crianças e adolescentes. Por isso, convidamos toda a igreja a fazer parte desse projeto! Adote um Desbravador e apoie a campanha "Rumo ao Campori da DSA 2027". Sua contribuição ajudará nossos desbravadores a viver essa experiência transformadora. Se você deseja colaborar ou saber mais sobre como participar, entre em contato com a Direção do Clube de Desbravadores Cruzeiro do Sul. Celular: (61) 99117-4444 – Rozi Manzi.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Criacionismo.jpg',
            'alt' => 'Sábado da Criação — Ciência, Fé e Verdade',
            'title' => 'Sábado da Criação',
            'text' => 'Ciência, Fé e Verdade - Será que ciência e religião precisam estar em lados opostos? No Sábado da Criação, duas palestras vão provocar reflexões profundas sobre aquilo que ouvimos, aquilo em que acreditamos e a maneira como compreendemos alguns aspetos da criação. Às 10h ocorrerá a palestra com o tema: “Quem tem ouvidos, ouça!” e às 16h a palestra intitulada “Ciência versus religião: Um falso dilema.” Com Tiago Alves Jorge Souza, Mestre e Doutor em Genética pela USP. Uma programação para quem gosta de pensar, questionar e ir além das respostas prontas. Com o encontro da ciência e da fé, talvez você descubra que algumas questões são mais complexas e mais fascinantes do que parecem. Acontecerá na Igreja Adventista do Lago Sul, no sábado, dia 29 de agosto de 2026. Participe!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Clube do Livro.jpg',
            'alt' => 'Clube do Livro Cristão',
            'title' => 'Clube do Livro',
            'text' => 'Descubra novas perspectivas e aprofunde sua fé em nossa comunidade literária. O Clube do Livro Cristão é um espaço dedicado à leitura reflexiva, à comunhão e ao debate de obras que edificam a mente e o espírito. Nossos encontros ocorrem quinzenalmente às terças-feiras, às 19h30, em formato online, permitindo a participação de qualquer lugar, complementados por uma reunião presencial mensal para estreitar os laços de fraternidade. Venha caminhar conosco nessa jornada de aprendizado e crescimento espiritual; faça parte do nosso grupo e acompanhe as próximas leituras através do link: https://chat.whatsapp.com/JISpyFzQWaz5FflxofhNfs',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/PG Jovem.jpg',
            'alt' => 'Pequeno Grupo Jovem',
            'title' => 'Pequeno Grupo Jovem',
            'text' => 'Um espaço de comunhão, amizade e fé feito sob medida para você. O Pequeno Grupo Jovem é o lugar ideal para jovens de 15 a 25 anos compartilharem experiências, fortalecerem os laços e aprofundarem o conhecimento espiritual de forma leve e relevante. Nossos encontros acontecem quinzenalmente às quintas-feiras, às 19h30, em formato online, contando também com um encontro presencial especial por mês para estarmos juntos. Venha fazer parte desta jornada e caminhar conosco; saiba mais e integre-se ao grupo através do link: https://chat.whatsapp.com/JWDD88eG3rOBXUG7iqvfv1?s=sw&p=a&ilr=0',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/PG Liberdade Religiosa .jpeg',
            'alt' => 'Pequeno Grupo — Liberdade Religiosa no Tempo do Fim',
            'title' => 'Liberdade Religiosa e Tempo do Fim',
            'text' => 'Convidamos você para participar do Pequeno Grupo de Oração: Liberdade Religiosa no Tempo do Fim, um espaço semanal dedicado à comunhão e ao estudo da Palavra de Deus à luz das profecias bíblicas e dos acontecimentos contemporâneos. Nossos encontros ocorrem quinzenalmente às quintas-feiras, às 20h, sempre em formato online. O próximo encontro será no dia 27 de agosto, via Microsoft Teams. Contaremos com a ilustre presença do Pr. Hélio Carnassale — conferencista e uma das principais referências da Igreja Adventista do Sétimo Dia na área de Liberdade Religiosa —, que ministrará sobre o instigante tema: "Haverá liberdade religiosa após o decreto dominical?" Participe da reunião: https://teams.live.com/meet/9355849010881?p=1yeodiUPQHVWDUdvu3 Integre-se ao nosso grupo: https://chat.whatsapp.com/CYo7NkQ5jIMEpJ6BbSyl3X?mode=gi_t "Orai sem cessar." — 1 Tessalonicenses 5:17',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/ASA Aberta.jpg',
            'alt' => 'ASA Aberta — pizzas e lanches',
            'title' => 'ASA Aberta',
            'text' => 'Sábado, 22/08/26, após o pôr do sol, a ASA estará aberta para receber você com pizzas e lanches deliciosos a preços acessíveis, em um ambiente acolhedor e descontraído, perfeito para reencontrar amigos e fazer novas conexões. Aproveite!',
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
