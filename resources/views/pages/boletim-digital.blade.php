@extends('layouts.app')

@section('title', 'Boletim Digital - IASD Central de Brasília')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/boletim-digital.css') }}">
@endpush

@php
    $boletimBase = 'img/boletim/boletim_10_10_2026';
    $oracao365Base = $boletimBase . '/365 Dias de Oração';
    $oracaoMinBase = $boletimBase . '/M. Oração';

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

    $texto365Dias = 'Continuamos envolvidos no projeto Jornada de Oração: Frutos do Espírito. Ao longo deste mês, vamos orar pedindo a Deus que desenvolva em nossa vida o fruto: MANSIDÃO. O desafio da TERCEIRA semana de OUTUBRO é: Ore por humildade para reconhecer erros e ter coragem de pedir perdão quando necessário.';

    $boletins = [
        // Com descrição (script DOCX)
        [
            'type' => 'image',
            'src' => $boletimBase . '/One Voice.jpg',
            'alt' => 'One Voice 27 — mobilização missionária global',
            'title' => 'One Voice',
            'text' => 'O OneVoice27 é o projeto global de mobilização missionária da Igreja Adventista do Sétimo Dia, que conduzirá toda a igreja mundial a um mesmo esforço evangelístico. O lançamento oficial aconteceu no dia 5 de setembro deste ano. Lançada no segundo semestre de 2025 e planejada para ser uma grande celebração em setembro de 2027, marcando os 2.000 anos do batismo de Jesus Cristo. A Divisão Sul-Americana lidera a mobilização de todas as suas Uniões, Associações, Missões e igrejas locais para que participem em uma estratégia digital unificada. Participe conosco deste projeto.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/CAMISETAS ONVOICE27.png',
            'alt' => 'Camisetas One Voice 27',
            'title' => 'Camisetas One Voice 27',
            'text' => 'One Voice27 nos une em uma grande missão global: apresentar Jesus Cristo ao mundo com uma mensagem bíblica unificada. Faça parte deste movimento e leve essa identidade no peito! Adquira sua camiseta OneVoice27. Vamos juntos falar em uma só voz, afinal, Jesus faz tudo novo. Acesse o link https://forms.gle/4W8Q2e8YoeGQ8nKJA e encomende a sua!',
        ],
        [
            'type' => 'image',
            'src' => $oracao365Base . '/WhatsApp Image 2026-08-16 at 13.42.24 (1).jpeg',
            'alt' => '365 Dias de Oração — Jornada de Oração',
            'title' => '365 Dias de Oração',
            'text' => $texto365Dias,
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/OUTUBRO ROSA.jpg',
            'alt' => 'Outubro Rosa — conscientização e prevenção',
            'title' => 'Outubro Rosa',
            'text' => 'O amor ao próximo se manifesta em gestos de cuidado, acolhimento e partilha. No mês dedicado à conscientização e prevenção ao câncer de mama, a nossa igreja preparou uma programação especial para abraçar as mulheres e abençoar vidas. O Ministério da Mulher de nossa igreja realizará ações especiais voltadas ao Outubro Rosa, mês dedicado à conscientização e prevenção do câncer de mama. O evento tem como principal objetivo conscientizar sobre a importância do diagnóstico precoce do câncer de mama e do colo do útero, além de proporcionar um espaço de cuidado integral físico, emocional e espiritual.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Entre Elas.jpg',
            'alt' => 'Entre Elas — encontro feminino',
            'title' => 'Entre Elas',
            'text' => 'Encontro "Entre Elas": Um bate-papo abençoado, voltado especialmente para nós, mulheres. Venha fortalecer a sua fé e trocar experiências! No dia 18/10, às 17h, no Espaço Jovem. Garanta já a sua vaga acessando o link: https://forms.gle/UZfqi7LgAKFgYssq5',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/visita missionária.jpg',
            'alt' => 'Visita missionária — Outubro Rosa',
            'title' => 'Visita Missionária',
            'text' => 'E para finalizar a programação do Outubro Rosa, no dia 24/10 faremos uma visita especial a mulheres que estão enfrentando o câncer de mama, com o objetivo de levar amor, solidariedade, oração e um mimo para aquecer o coração de cada uma delas; haverá inscrições para quem deseja participar deste momento de amor ao próximo, por isso fique atenta!',
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
            'src' => $boletimBase . '/Costura.jpg',
            'alt' => 'Oficina de Costura Criativa',
            'title' => 'Oficina Criativa',
            'text' => 'Use sua criatividade para criar! Faça o curso Oficina de Costura Criativa e aprenda a confeccionar lindas peças, com duração de apenas dois meses (outubro e novembro). Não perca essa chance, acesse o link: https://wa.me/qr/ZME25VEMZLTBI1 e faça já a sua inscrição! Para iniciantes e aprendizes. Dias e horário: terças e quintas-feiras, pela manhã. Outubro (Módulo 1) — Peças: estojo, ecobag, scrunchie, mochila, viseira e chapéu para praia de tecido. Datas: 06/10 e 08/10, 13/10 e 15/10, 20/10 e 22/10, 27/10 e 29/10. Novembro (Módulo 2) — Peças: mesa posta para o Natal. Datas: 03/11 e 05/11, 10/11 e 12/11, 17/11 e 19/11, 24/11 e 26/11. Materiais necessários: tecido, manta R1 colante, tesoura, linha, agulha, viés, zíper de metro, cursor, elástico médio, fita para alças da ecobag e mochila, metal para regular alça. Investimento: R$ 120,00 por mês. Pagamento: pix.centralbsb.aplac@adventistas.org Atenção! Após o pagamento, por favor envie o comprovante no WhatsApp da Professora Annelise Tisi.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Voluntariado.jpeg',
            'alt' => 'Voluntariado nos ministérios da igreja',
            'title' => 'Voluntariado',
            'text' => 'Seja voluntário em um de nossos ministérios! Acesse o link e escolha o departamento da igreja que mais combina com você. https://forms.gle/yBKYhMg3V7Rcd8uY6',
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
            'src' => $oracaoMinBase . '/WhatsApp Image 2026-01-06 at 18.19.43 (5).jpeg',
            'alt' => 'Reunião de Oração',
            'title' => 'Reunião de Oração',
            'text' => 'Participe da nossa Reunião de Oração. Temos recebido grandes bênçãos do Senhor. Venha clamar pelo derramamento do Espírito Santo! Nossas reuniões acontecem a cada 15 dias, acompanhe e venha orar conosco.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/PESQUISA.jpg',
            'alt' => 'Pesquisa do Ministério da Família',
            'title' => 'Ministério da Família',
            'text' => 'O Ministério da Família da Igreja Central de Brasília deseja ouvir você. Queremos saber quais temas têm impactado os casamentos para que possamos abordá-los de forma relevante em nossas programações. Participe da nossa pesquisa, acesse o link e responda https://forms.gle/c8osbfRcEivGvKcZ9 , leva poucos minutos para responder. Contamos com você!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Entrega de livros.jpg',
            'alt' => 'Entrega de livros missionários',
            'title' => 'Entrega de Livros',
            'text' => 'O ritmo acelerado do ano continua e a nossa missão não pode parar! Outubro chegou trazendo um novo gás e a grande oportunidade de expandirmos o alcance do Impacto Esperança. Cada exemplar entregue representa uma semente de transformação, uma resposta às orações de alguém e um raio de luz para quem mais precisa. O momento de agir é agora e a sua presença faz toda a diferença para que essa corrente de fé alcance ainda mais corações. Prepare a sua agenda, junte-se a nós nesta grande mobilização e faça história neste mês de outubro! Dê uma passada no Centro White, retire os livros da sua semana e venha ser um instrumento ativo de esperança. Quem assume esse compromisso com a gente em outubro?',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Doutores.jpeg',
            'alt' => 'Doutores de Esperança',
            'title' => 'Doutores de Esperança',
            'text' => 'Junte-se aos Doutores de Esperança! Você já sentiu o desejo de levar um abraço, um sorriso e uma palavra de conforto para quem mais precisa? Nossos plantões estão de volta, e queremos convidar você para fazer parte dessa missão transformadora! Quer participar, mas ainda não faz parte do grupo? Esta é a sua oportunidade! Você pode começar acompanhando a nossa equipe como observador, conhecendo de perto a dinâmica do projeto antes de dar o próximo passo. Venha vivenciar essa experiência cheia de amor, empatia e alegria com a gente. Como posso me inscrever? Entre em contato agora mesmo com a Lu Mesquita pelo link https://wa.me/message/7M6UOXXMBRWRI1 e garanta as informações para participar. Venha fazer a diferença na vida de alguém e descubra a alegria de servir!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/CEVISA.jpeg',
            'alt' => 'Excursão especial de bem-estar — CEVISA',
            'title' => 'Excursão CEVISA',
            'text' => 'O Ministério do Idoso da Igreja Adventista Central de Brasília está promovendo uma oportunidade maravilhosa para cuidarmos da nossa saúde física, mental e espiritual: uma excursão inesquecível para o renomado Spa Médico Adventista (CEVISA), de 22 a 29 de novembro de 2026! Será uma semana inteira dedicada ao tratamento e renovação com o exclusivo Pacote de Bem-Estar – Linha Select. Sobre o CEVISA: Reconhecido nacional e internacionalmente como referência em estilo de vida saudável e tratamentos naturais, o CEVISA busca ajudar a renovar o corpo, a mente e o estado de espírito. Afinal, este é o caminho para uma vida plena e feliz! Inscrições e detalhes: Procure a professora Mariazinha, clique no link para obter mais informações https://forms.gle/ZNbidJmaihpgYqNY8 Venha viver essa experiência de renovação e comunhão!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Bazar.jpg',
            'alt' => 'Bazar da ASA',
            'title' => 'Bazar ASA',
            'text' => 'No dia 08/11, teremos o bazar da ASA, peças selecionadas a preços acessíveis. Programe-se para participar!',
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
            'src' => $boletimBase . '/Cópia de Projeto Maná 2027  - TelãoYoutube.jpg',
            'alt' => 'Projeto Maná — assinatura da Lição da Escola Sabatina',
            'title' => 'Projeto Maná',
            'text' => 'A vida é feita de escolhas. E uma delas é decidir o que vai ocupar um espaço na nossa rotina. Assinar a Lição da Escola Sabatina é mais do que receber um material em casa. É escolher alimentar a fé, aprofundar o conhecimento da Palavra e reservar, todos os dias, um momento para estar com Deus. No Projeto Maná, queremos incentivar uma igreja que não apenas ouve sobre a Bíblia, mas que a estuda, vive e compartilha. Faça parte desse movimento. Assine a Lição da Escola Sabatina e transforme seu estudo em um compromisso diário com Deus. Acesse: https://projetomana.cpb.com.br/ ou pelo WhatsApp: (61) 98235-0008 ou ligue: (61) 3321-2021 ou 0800 979 0666.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Tersouros do Céu.jpeg',
            'alt' => 'App Tesouros do Céu — APlaC',
            'title' => 'Tesouros do Céu',
            'text' => 'A APLaC lança APP gratuito para ensinar educação financeira para as novas gerações. "Tesouros do Céu" é uma ferramenta didática e divertida que reúne tarefas, pontos, avatares bíblicos e devocional diário para ajudar os pais a ensinar mordomia cristã desde a infância. https://noticias.adventistas.org/pt/tesouros-do-ceu/',
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
            'text' => 'Temos uma excelente notícia para os amantes de idiomas e do estudo da Palavra: a Classe de Escola Sabatina em Francês está de volta! Todo sábado, às 10h, na Igreja Adventista Internacional "BIC", espaço dedicado a adultos que desejam aprofundar seu conhecimento no idioma francês enquanto estudamos a Bíblia juntos. É uma oportunidade maravilhosa para aprender e compartilhar em comunidade. Entre no nosso grupo de WhatsApp para receber todos os detalhes e materiais. https://chat.whatsapp.com/KcWLeUItuvWEnZ27s6Xs67?mode=gi_t À bientôt!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Capas  Série Permanecer em Jesus.jpg',
            'alt' => 'Quartas Especiais — Permanecer em Jesus',
            'title' => 'Quartas Especiais',
            'text' => 'Nas quartas-feiras do mês de outubro, às 19h30, teremos a nova série Permanecer em Jesus, onde nossos jovens estarão responsáveis por toda a programação. Baseada no livro 2 da série Passos Para o Reavivamento Pessoal de Helmut Haubeil, inspirado na célebre passagem bíblica "Permaneça em mim, e Eu permanecerei em vocês", o livro mergulha nas verdades atemporais contidas nessas palavras, revelando insights transformadores sobre a conexão espiritual e a busca pela plenitude da vida. Participe com sua família!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Quartas de Poder 2026.jpg',
            'alt' => 'Quartas de Poder — O Poder do Espírito',
            'title' => 'Quartas de Poder',
            'text' => 'Convidamos toda a comunidade para os cultos especiais do projeto Quartas de Poder, que serão realizados nas últimas quartas-feiras de cada mês, sempre às 19h30, com o tema "O Poder do Espírito". A programação das últimas quartas-feiras do mês é dedicada ao fortalecimento da vida de oração da igreja local, integrando momentos de louvor, orações de agradecimento e testemunhos de respostas de oração alcançadas pela nossa comunidade. Contamos com a sua presença para juntos buscarmos ao Senhor em oração.',
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
            'src' => $boletimBase . '/Aulas CEMAB.jpg',
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
            'src' => $boletimBase . '/Canal APLAC.jpeg',
            'alt' => 'Canal Adventistas Brasília no WhatsApp',
            'title' => 'Canal APlaC',
            'text' => 'Você já segue o canal Adventistas Brasília no WhatsApp? É o jeito mais fácil de ficar por dentro de tudo o que acontece na nossa região da APlaC: eventos, programações e avisos importantes, direto no seu celular. Escaneie o QR Code e clique em “Seguir”.',
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
            'src' => $boletimBase . '/Liberdade Religiosa capa divulgação.jpeg',
            'alt' => 'Pequeno Grupo — Liberdade Religiosa no Tempo do Fim',
            'title' => 'Liberdade Religiosa e Tempo do Fim',
            'text' => 'Convidamos você para participar do Pequeno Grupo de Oração: Liberdade Religiosa no Tempo do Fim, um espaço semanal dedicado à comunhão e ao estudo da Palavra de Deus à luz das profecias bíblicas e dos acontecimentos contemporâneos. Nossos encontros ocorrem quinzenalmente às quintas-feiras, às 20h, sempre em formato online. Participe da reunião: https://teams.live.com/meet/9355849010881?p=1yeodiUPQHVWDUdvu3 Integre-se ao nosso grupo: https://chat.whatsapp.com/CYo7NkQ5jIMEpJ6BbSyl3X?mode=gi_t "Orai sem cessar." — 1 Tessalonicenses 5:17',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/318.jpeg',
            'alt' => 'Código 318 — círculo de homens',
            'title' => 'Código 318',
            'text' => 'Cansado de carregar tudo sozinho? O CÓDIGO 318 é um círculo de homens que se reúnem para serem treinados por Deus e formarem uns aos outros. Não é mais um evento. É um lugar para você ser visto, ouvido e fortalecido. No sábado, dia 10/10, às 15h30, no salão jovem, teremos mais um encontro do Código 318. Contaremos com a participação especial do Pastor Adenilton Aguiar. Traga a sua história e traga um amigo. Homens treinados por Deus. Homens que formam homens. CÓDIGO 318. O seu lugar é no círculo. Entre para o nosso grupo acesse o link: https://chat.whatsapp.com/FTLmis6gSdsKXbqCafHcrd?s=cl&p=i&mlu=0&ilr=0',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Criacionismo final.jpeg',
            'alt' => 'JA Criacionismo — Uma Viagem no Tempo',
            'title' => 'JA Criacionismo',
            'text' => 'Atenção, universitários de todo o DF! Reservem a data de 24 de outubro, às 16h: será o Sábado da Criação na Igreja Central de Brasília. O programa trará o tema “Uma Viagem no Tempo”, com o Dr. Tiago Alves de Souza, Doutor em Genética pela USP. Pré-universitários, universitários, quem já concluiu a faculdade, professores e todos os interessados no tema estão convidados para este momento especial. Um programa que você não pode perder!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/darwin.png',
            'alt' => 'Série A Caixa Preta de Darwin',
            'title' => 'A Caixa Preta de Darwin',
            'text' => 'Você já parou para pensar se a complexidade da vida ao nosso redor poderia surgir por acaso? Nos domingos especiais de outubro, a nova série de estudos A Caixa Preta de Darwin aborda a origem da vida, a complexidade do universo e a perspectiva criacionista à luz das Escrituras. Acompanhe os temas: 04/10 — A Origem da Vida (Dr. Davi Reis Lopes); 11/10 — O Universo Sobre o Fio da Navalha (Dr. Alexsander Silva); 18/10 — O Mistério da Vida (Dr. Davi Reis Lopes); 25/10 — A Grande Extinção em Massa (Dr. Nahor Neves). Ative as notificações do nosso Canal do WhatsApp para não perder nenhum episódio desta jornada sobre fé, ciência e criação! No YouTube, inscreva-se no nosso canal e compartilhe a mensagem de fé e esperança.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/SUPER SEXTA.jpg',
            'alt' => 'Super Sexta — 2ª edição',
            'title' => 'Super Sexta',
            'text' => 'A Super Sexta está de volta! Vem aí a 2ª edição! Uma noite de muito louvor, adoração, conexão e reflexão, com a participação especial do Pastor Igo Rocha e do Grupo Puro Louvor, do IABC. Convide seus amigos e venha viver essa noite com a gente! Dia 23 de outubro, sexta-feira, às 19h30, no Espaço Jovem da Igreja Adventista Central de Brasília. SUPER SEXTA, você não vai querer ficar de fora!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/Coral Jovem.jpeg',
            'alt' => 'Coral Canto Jovem do IAP',
            'title' => 'Canto Jovem',
            'text' => 'Prepare-se para uma manhã especial de louvor, música e adoração. No próximo sábado, dia 17/10, às 9h, estaremos recebendo em nossa igreja o Coral Canto Jovem diretamente do IAP (Instituto Adventista Paranaense), venha e participe deste grande louvor conosco!',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/O Semeador.jpg',
            'alt' => 'Bíblias para Semeadores',
            'title' => 'Bíblias',
            'text' => 'Queridos professores da Escola Sabatina e amigos semeadores da Palavra de Deus: aqueles que precisarem de Bíblias para seus trabalhos podem procurar o líder dos Semeadores irmão Josias Gonsioroski para obter o material.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/ZAP.jpg',
            'alt' => 'Canal Central Informa no WhatsApp',
            'title' => 'Canal WhatsApp',
            'text' => 'Perdeu algum detalhe dos nossos anúncios? Não se preocupe. O Central Informa está disponível no nosso canal oficial no WhatsApp para manter você atualizado. Siga o canal: Adventistas Central Brasília! Acesse o link https://whatsapp.com/channel/0029VaY6Z5UJJhzdkYF51D1T e faça parte desta comunidade.',
        ],
        [
            'type' => 'image',
            'src' => $boletimBase . '/ASA Aberta.jpg',
            'alt' => 'ASA Aberta — pizzas e lanches',
            'title' => 'ASA Aberta',
            'text' => 'No sábado, dia 10/10/26, após o pôr do sol, a ASA estará aberta para receber você com pizzas e lanches deliciosos a preços acessíveis, em um ambiente acolhedor e descontraído, perfeito para reencontrar amigos e fazer novas conexões. Aproveite!',
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
