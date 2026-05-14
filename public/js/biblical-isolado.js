/**
 * SISTEMA DE LINKS BÍBLICOS - ISOLADO E SEGURO
 * Não modifica layout, não afeta DOM desnecessariamente
 */

(function() {
    'use strict';

    // Textos bíblicos NVI - SEÇÃO 7 (SÁBADO E LEI DE DEUS)
    const biblicalPassages = {
        "Gênesis 2:1-3": "Assim foram acabados os céus e a terra, e todos os seus exércitos. No sétimo dia Deus já havia concluído a obra que realizara, e nesse dia descansou. Deus abençoou o sétimo dia e o santificou, porque nele descansou de toda a obra que realizara na criação.",

        "Êxodo 16:22-23": "No sexto dia eles recolheram comida em dobro, dois ômeres para cada pessoa, e todos os líderes da comunidade vieram e contaram isso a Moisés. Ele lhes disse: 'Façam isso, porque o Senhor lhes deu o sábado. Por isso, no sexto dia ele lhes dá pão para dois dias. Cada um de vocês fique no seu lugar; ninguém saia do seu local no sétimo dia'.",

        "Êxodo 20:2": "Eu sou o Senhor, o teu Deus, que te tirou do Egito, da terra da escravidão.",

        "Êxodo 20:8-11": "Lembre-se do dia de sábado, para santificá-lo. Trabalhe seis dias e neles realize toda a sua obra, mas o sétimo dia é o sábado dedicado ao Senhor, o seu Deus. Naquele dia você não deve fazer nenhum trabalho, nem você, nem seus filhos ou filhas, nem seus servos ou servas, nem seus animais, nem os estrangeiros que vivem nas suas cidades. Pois em seis dias o Senhor fez os céus e a terra, o mar e tudo o que neles existe, mas descansou no sétimo dia. Por isso o Senhor abençoou o dia de sábado e o santificou.",

        "Êxodo 31:14-15": "Guardem o sábado, pois é santo para vocês. Aquele que o profanar terá que ser executado. Quem fizer algum trabalho nesse dia será eliminado do meio do seu povo. Durante seis dias trabalhar-se-á, mas o sétimo dia será sábado de descanso consagrado ao Senhor. Quem fizer algum trabalho no dia de sábado terá que ser executado.",

        "Êxodo 31:18": "Quando o Senhor terminou de falar com Moisés no monte Sinai, deu-lhe as duas tábuas da aliança, tábuas de pedra escritas pelo dedo de Deus.",

        "Êxodo 35:2-3": "Durante seis dias trabalhar-se-á, mas o sétimo dia será para vocês santo, dia de descanso, consagrado ao Senhor. Quem fizer algum trabalho nesse dia terá que ser executado. Não acendam fogo em nenhuma das suas habitações no dia de sábado",

        "Êxodo 35:3": "Não acendam fogo em nenhuma das suas habitações no dia de sábado",

        "Levítico 23:32": "Será para vocês dia de descanso completo e vocês se humilharão. Desde a véspera do nono dia do mês, desde o pôr do sol até o pôr do sol do dia seguinte, vocês deverão descansar.",

        "Deuteronômio 5:15": "Lembre-se de que você foi escravo no Egito e que o Senhor, o seu Deus, o tirou de lá com mão poderosa e com braço forte. Por isso o Senhor, o seu Deus, lhe ordenou que guardasse o dia de sábado.",

        "Deuteronômio 10:1-5": "Naquele tempo o Senhor me disse: 'Corte duas tábuas de pedra, como as primeiras, e suba ao monte para encontrar-se comigo. Também faça uma arca de madeira. E nas tábuas escreverei as palavras que estavam nas primeiras, que você quebrou, e você as colocará na arca'. Então fiz uma arca de madeira de acácia, cortei duas tábuas de pedra, como as primeiras, e subi ao monte com as duas tábuas nas mãos. O Senhor escreveu nas tábuas, como tinha escrito anteriormente, os Dez Mandamentos que ele proclamou a vocês no monte, do meio do fogo, no dia da assembleia. Então o Senhor as deu a mim, e eu desci do monte e as coloquei na arca que tinha feito, conforme o Senhor tinha ordenado, e onde elas permanecem até hoje.",

        "Deuteronômio 10:5": "Assim, no sétimo dia Deus já havia concluído a obra que realizara, e nesse dia descansou. Deus abençoou o sétimo dia e o santificou, porque nele descansou de toda a obra que realizara na criação.",

        "Deuteronômio 31:24-26": "Depois que Moisés terminou de escrever num livro as palavras desta Lei, até o fim, deu esta ordem aos levitas que transportavam a arca da aliança do Senhor: 'Levem este livro da Lei e coloquem-no ao lado da arca da aliança do Senhor, do seu Deus. Ali ele servirá de testemunha contra vocês.'",

        "Neemias 13:15-22": "Naqueles dias vi em Judá alguns que estavam pisando uvas em lagares e recolhendo cargas de vinho, de uvas e de figos, e trazendo-as para Jerusalém no sábado. Adverti-os então que não vendessem comida. Alguns homens de Tiro e que moravam em Jerusalém traziam peixe e todas as tipos de mercadorias e as vendiam aos habitantes de Judá em Jerusalém, no sábado. Eu repreendi os nobres de Judá e lhes disse: 'Que maldade é essa que vocês estão praticando, profanando o sábado? Não foi isso que os seus antepassados fizeram, fazendo com que o nosso Deus trouxesse toda essa calamidade sobre nós e sobre esta cidade? E vocês ainda trazem mais ira sobre Israel profanando o sábado!' Quando as sombras começaram a estender sobre os portões de Jerusalém, antes do sábado, ordenei que as portas fossem fechadas e não abertas até depois do sábado. Coloquei alguns dos meus homens guardando os portões, para que não carregassem nenhuma carga em dia de sábado. Uma ou duas vezes os mercadores e vendedores de todo tipo de mercadorias passaram a dormir fora de Jerusalém. Mas eu os advirti: 'Por que vocês passam a noite perto da muralha? Se fizerem isso de novo, os mandarei prender!' Depois disso eles não voltaram mais no sábado. Então ordenei aos levitas que se purificassem e viessem guardar os portões para santificar o dia de sábado.",

        "1 Crônicas 23:31": "E para oferecer todos os holocaustos ao Senhor, nos sábados, nas luas novas e nas festas fixas, conforme o número estabelecido.",

        "2 Crônicas 2:4": "Agora pretendo construir um templo para o Nome do Senhor, o meu Deus, e dedicá-lo a ele, para queimar perante ele incenso aromático, apresentar os pães consagrados continuamente e fazer os holocaustos da manhã e da tarde, nos sábados, nas luas novas e nas festas fixas do Senhor, o nosso Deus. Isso tem que ser feito para sempre em Israel.",

        "2 Crônicas 31:3": "O rei contribuiu com sua própria bolsa para os holocaustos da manhã e da tarde, e para os holocaustos dos sábados, das luas novas e das festas fixas, como está escrito na Lei do Senhor.",

        "Ezequiel 45:17": "Caberá ao príncipe a responsabilidade de oferecer os holocaustos, as ofertas de cereal e as ofertas derramadas, nas festas, nas luas novas e nos sábados, em todas as festas fixas da casa de Israel. Ele será responsável pela oferta pelo pecado, pela oferta de cereal, pelo holocausto e pela oferta de paz, para fazer propiciação pela casa de Israel.",

        "Isaías 58:13-14": "Se vocês chamarem o sábado deleitoso e honroso ao santificado do Senhor, e se o honrarem, não seguindo os seus próprios caminhos, nem se divertindo nem falando palavras vãs, então vocês encontrarão alegria no Senhor, e eu os farei cavalgar sobre as alturas da terra e fazê-los comer da herança de Jacó, seu pai. A boca do Senhor falou.",

        "Mateus 12:1-12": "Naquela ocasião, Jesus passou pelas lavouras num sábado. Seus discípulos estavam famintos e começaram a colher espigas de cereal e a comê-las. Quando os fariseus viram isso, disseram a Jesus: 'Olha! Os teus discípulos estão fazendo o que é ilegal fazer em sábado'. Ele respondeu: 'Vocês não leram o que fez Davi quando ele e seus companheiros estavam famintos? Ele entrou na casa de Deus, e ele e os seus companheiros comeram os pães da consagração, que não lhes era permitido comer, mas apenas aos sacerdotes. Ou não leram na Lei que, no sábado, os sacerdotes no templo profanam o sábado e ainda assim ficam inocentes? Pois eu lhes digo que aqui está quem é maior que o templo. Se vocês tivessem compreendido o que significa: 'Desejo misericórdia, não sacrifícios', não teriam condenado inocentes. Pois o Filho do homem é Senhor do sábado'. Saindo dali, ele foi para a sinagoga deles, e estava ali um homem com a mão mirrada. Procurando um motivo para acusar Jesus, perguntaram-lhe: 'É permitido curar no sábado?' Ele lhes respondeu: 'Supondo que um de vocês tenha uma ovelha e, num sábado, esta caia num buraco, não irá agarrá-la e tirá-la de lá? Quanto mais vale um homem do que uma ovelha! Portanto, é permitido fazer o bem no sábado'.",

        "Mateus 12:10-13": "E estava ali um homem com a mão mirrada. Procurando um motivo para acusar Jesus, perguntaram-lhe: 'É permitido curar no sábado?' Ele lhes respondeu: 'Supondo que um de vocês tenha uma ovelha e, num sábado, esta caia num buraco, não irá agarrá-la e tirá-la de lá? Quanto mais vale um homem do que uma ovelha! Portanto, é permitido fazer o bem no sábado'. Então disse ao homem: 'Estenda a sua mão'. Ele a estendeu, e ela foi restaurada, ficando tão sã como a outra.",

        "Mateus 12:12": "Pois quanto mais vale um homem do que uma ovelha! Portanto, é permitido fazer o bem no sábado",

        "Mateus 22:37-40": "Respondeu Jesus: 'Ame o Senhor, o seu Deus, de todo o coração, de toda a sua alma e de todo o seu entendimento'. Este é o primeiro e maior mandamento. E o segundo é semelhante a ele: 'Ame o seu próximo como a si mesmo'. Destes dois mandamentos dependem toda a Lei e os Profetas.",

        "Marcos 2:27": "Então lhes disse: 'O sábado foi feito por causa do homem, e não o homem por causa do sábado'.",

        "Marcos 2:27-28": "Então lhes disse: 'O sábado foi feito por causa do homem, e não o homem por causa do sábado. Assim, o Filho do homem é Senhor até mesmo do sábado'.",

        "Lucas 4:16": "Ele foi para Nazaré, onde havia sido criado, e no dia de sábado entrou na sinagoga, como era seu costume, e levantou-se para ler.",

        "Lucas 13:15-16": "O Senhor lhe respondeu: 'Hipócritas! Cada um de vocês não desamarra o seu boi ou jumento do estábulo e o leva para beber água no sábado? Não deveria esta mulher, descendente de Abraão, a quem Satanás mantinha presa por dezoito longos anos, ser libertada no sábado daquilo que a prendia?'",

        "Lucas 14:3-5": "Então Jesus perguntou aos fariseus e aos peritos na Lei: 'É permitido ou não curar no sábado?' Mas eles ficaram em silêncio. Então Jesus tomou o homem, curou-o e mandou-o embora. Depois lhes perguntou: 'Se algum de vocês tiver um filho ou um boi que cair num poço num sábado, não o tirará imediatamente?'",

        "João 14:15": "Se vocês me amam, obedecerão aos meus mandamentos.",

        "Atos 5:29": "Pedro e os outros apóstolos responderam: 'É preciso obedecer a Deus e não aos homens!'",

        "Atos 13:14": "De Perge, Paulo e seus companheiros viajaram para Antioquia da Pisídia, e, no sábado, entraram na sinagoga e se assentaram.",

        "Atos 13:42-44": "Quando Paulo e Barnabé saíram da sinagoga, o povo os convidou para falar sobre isso no sábado seguinte. Assim, quando a congregação se dispersou, muitos judeus e gentios convertidos ao judaísmo seguiram Paulo e Barnabé, que conversavam com eles e os persuadiam a continuar na graça de Deus. No sábado seguinte, quase toda a cidade se reuniu para ouvir a palavra do Senhor.",

        "Atos 15:1, 5": "Alguns homens desceram da Judeia para Antioquia e passaram a ensinar os irmãos: 'Se vocês não forem circuncidados conforme o costume ensinado por Moisés, não podem ser salvos'. [...] Então alguns dos que pertenciam à seita dos fariseus se levantaram e disseram: 'É necessário circuncidar os gentios e exigir que eles obedeçam à Lei de Moisés'.",

        "Atos 15:1-11": "Alguns homens desceram da Judeia para Antioquia e passaram a ensinar os irmãos: 'Se vocês não forem circuncidados conforme o costume ensinado por Moisés, não podem ser salvos'. Isso levou Paulo e Barnabé a um grande debate e discussão com eles. Assim, Paulo e Barnabé e alguns outros foram enviados a Jerusalém para tratar dessa questão com os apóstolos e os presbíteros. [...] Deus, que conhece o coração, demonstrou que os aceitou, dando-lhes o Espírito Santo, tal como deu a nós. Não fez distinção alguma entre nós e eles, pois purificou-lhes o coração pela fé. Agora, então, por que vocês estão pondo a prova a Deus, impondo aos discípulos um jugo que nem nós nem nossos antepassados conseguimos suportar? Nós cremos que somos salvos pela graça de nosso Senhor Jesus, da mesma forma que eles também são.",

        "Atos 15:21": "Pois Moisés tem, em cada cidade, desde os tempos antigos, quem o pregue nas sinagogas, onde é lido todos os sábados.",

        "Atos 15:1-29": "Alguns homens desceram da Judeia para Antioquia e passaram a ensinar os irmãos: 'Se vocês não forem circuncidados conforme o costume ensinado por Moisés, não podem ser salvos'. Isso levou Paulo e Barnabé a um grande debate e discussão com eles. Assim, Paulo e Barnabé e alguns outros foram enviados a Jerusalém para tratar dessa questão com os apóstolos e os presbíteros. [...] Os apóstolos e os presbíteros, com toda a igreja, decidiram escolher alguns de entre eles e enviá-los a Antioquia com Paulo e Barnabé. Enviaram Judas, chamado Barsabás, e Silas, homens líderes entre os irmãos, com a seguinte carta: 'Os apóstolos e os presbíteros, irmãos, aos irmãos gentios em Antioquia, na Síria e na Cilícia: Saudações. Sabemos que alguns dentre nós saíram sem nossa autorização e os inquietaram, perturbando as suas mentes com o que disseram. Por isso decidimos, de comum acordo, escolher alguns representantes e enviá-los a vocês com nossos queridos Barnabé e Paulo, homens que têm arriscado a vida pelo nome de nosso Senhor Jesus Cristo. Portanto, pareceu bem ao Espírito Santo e a nós não impor a vocês nada além das seguintes exigências necessárias: Abster-se de comida sacrificada a ídolos, de sangue, da carne de animais estrangulados e de imoralidade sexual. Vocês farão bem em evitar essas coisas. Farem o bem, eis o seu dever.'",

        "Atos 16:13": "No sábado nós saímos da cidade e fomos para a margem do rio, onde esperávamos encontrar um lugar de oração.",

        "Atos 17:2": "Conforme o seu costume, Paulo foi à sinagoga, e por três sábados discutiu com eles as Escrituras,",

        "Atos 18:4": "Cada sábado ele debatia na sinagoga e convencia judeus e gregos.",

        "Atos 20:7": "No primeiro dia da semana, est nós reunidos para partir o pão, Paulo conversava com eles e, pretendendo viajar no dia seguinte, continuou falando até meia-noite.",

        "Romanos 3:31": "Anulamos, pois, a lei pela fé? De maneira nenhuma! Pelo contrário, confirmamos a lei.",

        "Romanos 7:4": "Assim, meus irmãos, vocês também morreram para a Lei, por meio do corpo de Cristo, para pertencerem a outro, àquele que ressuscitou dos mortos, a fim de que frutifiquemos para Deus.",

        "Romanos 7:12": "Assim, a lei é santa; o mandamento é santo, justo e bom.",

        "Romanos 13:8-10": "Não devam nada a ninguém, a não ser o amor de uns pelos outros, pois quem ama o próximo tem cumprido a Lei. Pois estes mandamentos: 'Não cometa adultério', 'Não mate', 'Não furte', 'Não cobiçe', e qualquer outro mandamento, todos se resumem neste preceito: 'Ame o seu próximo como a si mesmo'. O amor não pratica o mal contra o próximo. Portanto, o amor é o cumprimento da Lei.",

        "Romanos 14:1-6": "Aceitem o que é fraco na fé, sem discutir assuntos controvertidos. Um crê que pode comer de tudo; outro, cuja fé é fraca, come apenas vegetais. Aquele que come de tudo não deve menosprezar o que não come, e aquele que não come de tudo não deve condenar o que come, pois Deus o aceitou. Quem é você para julgar o servo alheio? É para o seu senhor que ele está ou não de pé. E ele ficará de pé, pois o Senhor é capaz de sustentá-lo. Um considera certo certos dias; outro considera todos os dias iguais. Cada um deve estar plenamente convicto em sua própria mente. Aquele que dá importância especial a certos dias, fá-lo para o Senhor.",

        "Romanos 14:5": "Um faz diferença entre dia e dia; outro julga iguais todos os dias. Cada um deve estar plenamente convicto em sua própria mente.",

        "Romanos 6:3-6": "Ou não sabem que todos nós, que fomos batizados em Cristo Jesus, fomos batizados em sua morte? Portanto, fomos sepultados com ele na morte por meio do batismo, a fim de que, assim como Cristo foi ressuscitado dos mortos mediante a glória do Pai, também nós vivamos uma vida nova. Se fomos unidos a ele na morte como esta, certamente seremos também unidos a ele na ressurreição. Pois sabemos que o nosso velho homem foi crucificado com ele, para que o corpo do pecado seja destruído, e não mais sejamos escravos do pecado.",

        "1 Coríntios 16:2": "No primeiro dia da semana, cada um de vocês deve separar uma quantia, em conformidade com a sua renda, reservando-a para que não sejam feitas coletas quando eu chegar.",

        "2 Coríntios 3:3, 6": "Vocês são reconhecidos como uma carta de Cristo, resultado do nosso ministério, escrita não com tinta, mas pelo Espírito do Deus vivo, não em tábuas de pedra, mas em tábuas de carne, isto é, nos corações humanos. [...] Ele nos capacitou para sermos ministros de uma nova aliança, não da letra, mas do Espírito; pois a letra mata, mas o Espírito vivifica.",

        "2 Coríntios 3:7": "E se o ministério que trouxe morte, gravado com letras em pedras, veio com glória, de modo que os israelitas não podiam fixar os olhos na face de Moisés por causa da glória do seu rosto, glória essa que estava se desvanecendo,",

        "Gálatas 4:9-11": "Mas agora que conhecemos a Deus, ou melhor, sendo conhecidos por Deus, como é que estão voltando àqueles mesmos princípios fracos e miseráveis? Querem escravizar-se a eles de novo. Vocês estão guardando dias especiais, meses, meses e estações! Receio que tenha trabalhado inutilmente para vocês.",

        "Gálatas 5:1-4": "Para a liberdade foi que Cristo nos libertou. Permaneçam firmes, pois, e não se deixem submeter novamente a um jugo de escravidão. Eu, Paulo, lhes digo que, se deixarem-se circuncidar, Cristo de nada lhes servirá. Novamente declaro a todo homem que se deixa circuncidar que está obrigado a cumprir toda a Lei. Vocês, que procuram ser justificados pela Lei, separaram-se de Cristo; caíram da graça.",

        "Gálatas 5:2-6": "Eu, Paulo, lhes digo que, se deixarem-se circuncidar, Cristo de nada lhes servirá. Novamente declaro a todo homem que se deixa circuncidar que está obrigado a cumprir toda a Lei. Vocês, que procuram ser justificados pela Lei, separaram-se de Cristo; caíram da graça. Mas, pelo Espírito, aguardamos a esperança da justiça que provém da fé. Pois, em Cristo Jesus, nem a circuncisão nem a incircuncisão têm efeito, mas sim a fé que atua pelo amor.",

        "Efésios 2:8-9": "Pois vocês são salvos pela graça, por meio da fé, e isto não vem de vocês, é dom de Deus; não por obras, para que ninguém se glorie.",

        "Efésios 2:10": "Porque somos criação de Deus, realizada em Cristo Jesus para fazermos boas obras, as quais Deus preparou de antemão para que as pratiquéssemos.",

        "Colossenses 2:12": "tendo sido sepultados com ele no batismo, nele também foram ressuscitados mediante a fé no poder de Deus que ressuscitou a Cristo dentre os mortos.",

        "Colossenses 2:16-17": "Portanto, não permitam que ninguém os julgue pelo que comem ou bebem, ou com relação a alguma festa religiosa ou à celebração da lua nova ou aos dias de sábado. Estas coisas são sombras do que haveria de vir; a realidade, porém, encontra-se em Cristo.",

        "1 Crônicas 23:31": "E para oferecer todos os holocaustos ao Senhor, nos sábados, nas luas novas e nas festas fixas, conforme o número estabelecido.",

        "Hebreus 8:10": "'Esta é a aliança que farei com a comunidade de Israel depois daqueles dias', declara o Senhor. 'Porei as minhas leis em sua mente e as escreverei em seus corações. Serei o Deus deles, e eles serão o meu povo.'",

        "Hebreus 9:9-10": "Isso é uma ilustração para os nossos tempos, indicando que as ofertas e sacrifícios oferecidos não eram capazes de tornar perfeita a consciência do adorador. Eles são apenas questões de comida, de bebida e de várias cerimônias de purificação externas, impostas até o tempo da nova ordem.",

        "Hebreus 10:1": "A Lei tem apenas uma sombra dos bens futuros, e não a sua realidade. Por isso, ela é incapaz de aperfeiçoar, mediante a repetição dos mesmos sacrifícios oferecidos ano após ano, aqueles que vêm para adorar a Deus.",

        "Tiago 2:10-12": "Pois quem obedece a toda a Lei, mas tropeça em apenas um ponto, torna-se culpado de quebrá-la inteiramente. Pois aquele que disse: 'Não cometa adultério', também disse: 'Não mate'. Se você não comete adultério, porém mata, tornou-se transgressor da Lei. Falem e ajam como quem vai ser julgado pela lei da liberdade.",

        "Jeremias 31:33": "'Esta é a aliança que farei com a comunidade de Israel depois daqueles dias', declara o Senhor. 'Porei a minha lei no seu interior e a escreverei nos seus corações. Serei o Deus deles, e eles serão o meu povo.'",

        // ============ SEÇÃO 2: ELLEN G. WHITE, BÍBLIA E DOM DE PROFECIA ============

        "Joel 2:28-29": "E depois derramarei do meu Espírito sobre todos os povos. Os seus filhos e as suas filhas profetizarão, os velhos terão sonhos, os jovens terão visões. Até sobre os servos e as servas derramarei do meu Espírito naqueles dias.",

        "Atos 2:17-18": "Nos últimos dias, diz Deus, derramarei do meu Espírito sobre todos os povos. Os seus filhos e as suas filhas profetizarão, os jovens terão visões, os velhos terão sonhos. Até sobre os meus servos e as minhas servas derramarei do meu Espírito naqueles dias, e eles profetizarão.",

        "1 Coríntios 12:28": "Assim, na igreja, Deus estabeleceu primeiramente apóstolos; em segundo lugar, profetas; em terceiro lugar, mestres; depois, operadores de milagres; depois, dons de curar, de socorrer, de governar, de falar em línguas.",

        "Efésios 4:11-13": "E ele designou alguns para apóstolos, outros para profetas, outros para evangelistas e outros para pastores e mestres, com o fim de preparar os santos para a obra do ministério, para que o corpo de Cristo seja edificado, até que todos alcancemos a unidade da fé e do conhecimento do Filho de Deus, e nos tornemos maduros, atingindo a medida da plenitude de Cristo.",

        "Apocalipse 12:17": "Então o dragão irou-se contra a mulher e foi guerrear contra o resto dos descendentes dela, os que obedecem aos mandamentos de Deus e se apegam ao testemunho de Jesus.",

        "Apocalipse 19:10": "Então caí aos pés do anjo para adorá-lo, mas ele me disse: 'Não faça isso! Sou servo como você e os seus irmãos que mantêm o testemunho de Jesus. Adore a Deus! Pois o testemunho de Jesus é o espírito de profecia'.",

        "1 Tessalonicenses 5:19-21": "Não apaguem o Espírito. Não desprezem as profecias, mas submetem-nas todas à prova. Retenham o que é bom e rejeitem o que é mal.",

        "1 Coríntios 14:1": "Sigam o amor e busquem com dedicação os dons espirituais, principalmente o de profecia.",

        "Isaías 8:20": "À Lei e ao Testemunho! Se eles não falarem conforme esta palavra, nunca verão a luz.",

        "1 João 4:1": "Amados, não creiam em qualquer espírito, mas examinem os espíritos para ver se eles procedem de Deus, porque muitos falsos profetas têm saído pelo mundo.",

        "1 João 4:1-3": "Amados, não creiam em qualquer espírito, mas examinem os espíritos para ver se eles procedem de Deus, porque muitos falsos profetas têm saído pelo mundo. Todo espírito que confessa que Jesus Cristo veio em carne é de Deus, mas todo espírito que não confessa Jesus não é de Deus. Esse é o espírito do anticristo, sobre o qual vocês ouviram que está vindo, e que agora já está no mundo.",

        "Atos 4:12": "Não há salvação em nenhum outro, pois, debaixo do céu não há nenhum outro nome dado aos homens pelo qual devamos ser salvos.",

        "João 3:16": "Porque Deus amou o mundo de tal maneira que deu o seu Filho unigênito, para que todo aquele que nele crê não pereça, mas tenha a vida eterna.",

        "1 Tessalonicenses 5:20-21": "Não desprezem as profecias. Examinem todas as coisas e fiquem com o que é bom. Rejeitem toda espécie de mal.",

        "Lucas 1:1-4": "Muitos já se dedicaram a elaborar um relato ordenado dos fatos que se cumpriram entre nós, conforme nos foram transmitidos por aqueles que desde o início foram testemunhas oculares e servos da palavra. Eu mesmo investigue tudo cuidadosamente, desde o começo, e decidi escrever-te um relato ordenado e excelentíssimo, ó excelentíssimo Teófilo, para que tenhas a certeza das coisas que te foram ensinadas.",

        "Atos 17:28": "Pois nele vivemos, nos movemos e existimos, como até disseram alguns dos seus poetas: 'Também somos sua raça'.",

        "Tito 1:12": "Um deles, seu próprio profeta, disse: 'Os cretenses são sempre mentirosos, bestas ferozes e preguiçosos glutões'.",

        "Mateus 24:42-44": "Portanto, vigiem, porque vocês não sabem em que dia virá o seu Senhor. Mas entendam isto: se o dono da casa soubesse em que hora da noite viria o ladrão, ele estaria vigilante e não deixaria que a sua casa fosse arrombada. Por isso, vocês também precisam estar preparados, porque o Filho do homem virá numa hora em que vocês não esperam.",

        "Tiago 5:8-9": "Você também deve ser paciente e fortalecer o coração, porque a vinda do Senhor está próxima. Irmãos, não se queixem uns dos outros, para que não sejam julgados.",

        "1 Coríntios 6:19-20": "Vocês não sabem que o corpo de vocês é santuário do Espírito Santo, que está em vocês, o qual receberam de Deus? Vocês não pertencem a si mesmos, mas a Deus; e, portanto, glorifiquem a Deus com o corpo de vocês.",

        "1 Coríntios 10:31": "Portanto, quer vocês comam, bebam ou façam qualquer outra coisa, façam tudo para a glória de Deus.",

        "3 João 2": "Amado, oro para que você vá bem em todas as coisas e tenha boa saúde, assim como vai bem a sua alma.",

        "Jeremias 18:7-10": "Mas, se fizer o mal aos meus olhos, e não me ouvir, eu mudarei de opinião sobre o bem que tinha pretendido fazer-lhe, e isso se tornará em mal. E, num instante, falarei contra um povo ou nação, para o arrancar, destruir e transtornar. Mas, se essa nação, da qual falei e adverti, converter-se da sua maldade, eu me arrependerei do mal que tinha planado infligir-lhe.",

        "Jonas 3:4-10": "Jonas começou a percorrer a cidade. Um dia era suficiente para percorrê-la, e ele proclamava: 'Daqui a quarenta dias Nínive será destruída'. Os ninivitas creram em Deus. Proclamaram um jejum, e vestiram-se de pano de saco, desde o maior até o menor. Quando Deus viu o que eles fizeram e como se converteram do seu mau caminho, ele teve compaixão e não os destruiu como tinha ameaçado.",

        "1 João 2:3-6": "Sabemos que o conhecemos, se obedecemos aos seus mandamentos. Aquele que diz: 'Eu o conheço', mas não obedece aos seus mandamentos, é mentiroso, e a verdade não está nele. Mas, se alguém obedece à sua palavra, nele o amor de Deus está verdadeiramente aperfeiçoado. Este é o modo como sabemos que estamos nele: aquele que diz que permanece nele, deve andar como Jesus andou.",

        "1 João 5:2-3": "Sabemos que amamos os filhos de Deus, se amamos a Deus e obedecemos aos seus mandamentos. Pois este é o amor de Deus: que obedeçam aos seus mandamentos. E os seus mandamentos não são pesados.",

        "Tiago 2:17-26": "Assim também a fé, por si só, se não for acompanhada de obras, está morta. Mas alguém poderá dizer: 'Você tem fé, e eu tenho obras'. Mostre-me a sua fé sem as obras, e eu lhe mostrarei a minha fé pelas obras. Você crê que existe um só Deus? Muito bem! Até os demônios crêem - e tremem! Você, insensato, quer provar que a fé sem as obras é inútil? Acaso não foi pelas obras que o nosso pai Abraão foi justificado, quando ofereceu seu filho Isaque sobre o altar? Veja como a fé trabalhava junto com as suas obras, e pelas obras a fé foi aperfeiçoada. E cumpriu-se a Escritura que diz: 'Abraão creu em Deus, e isso lhe foi creditado como justiça', e ele foi chamado amigo de Deus.",

        "Romanos 3:20": "Portanto, ninguém será declarado justo diante dele pelas obras da lei, pois através da lei nos tomamos conscientes do pecado.",

        "Romanos 7:7": "Que diremos então? A lei é pecado? De maneira nenhuma! Mas eu não teria conhecido o pecado, exceto por meio da lei. Pois eu não teria conhecido a cobiça, se a lei não tivesse dito: 'Não cobiçar'.",

        "Gálatas 2:16": "Sabemos que ninguém é justificado pela prática da lei, mas pela fé em Jesus Cristo. Assim, nós também cremos em Cristo Jesus para sermos justificados pela fé em Cristo, e não pela prática da lei, pois pela prática da lei ninguém será justificado.",

        "Tiago 1:23-25": "Porque, se alguém ouve a palavra e não a pratica, é como um homem que olha o seu rosto num espelho e, logo depois de se olhar, vai embora e logo esquece a sua aparência. Mas quem examina atentamente a lei perfeita da liberdade e persevera nela, não sendo ouvinte esquecido, mas praticante ativo, será abençoado no que fizer.",

        "Hebreus 8:8-10": "Mas Deus achou falha com o povo e disse: 'Está chegando o tempo', diz o Senhor, 'em que farei uma nova aliança com a comunidade de Israel e com a comunidade de Judá. Esta é a aliança que farei com a comunidade de Israel depois daqueles dias', declara o Senhor. 'Porei as minhas leis em sua mente e as escreverei em seus corações. Serei o Deus deles, e eles serão o meu povo.'",

        "Romanos 8:3-4": "Porque, aquilo que a lei fora incapaz de fazer, por estar enfraquecida pela carne, Deus o fez, condenando o pecado na carne, a fim de que as justas exigências da lei fossem plenamente satisfeitas em nós, que não vivemos segundo a carne, mas segundo o Espírito.",

        "Mateus 5:17-19": "Não pensem que vim abolir a Lei ou os Profetas; não vim abolir, mas cumprir. Digo-lhes a verdade: Enquanto o céu e a terra existirem, nem uma letra ou um traço da Lei desaparecerá até que tudo se cumpra. Qualquer que desobedecer a um desses mandamentos, mesmo que dos menores, e ensinar os outros a fazerem, será chamado menor no Reino dos céus; mas aquele que praticar e ensinar estes mandamentos será chamado grande no Reino dos céus.",

        "Êxodo 31:13-17": "Digam aos israelitas: Vocês devem observar os meus sábados. Esta será uma señal entre mim e vocês, para as suas gerações, a fim de que saibam que eu sou o Senhor que os santifica. Observem o sábado, pois será santo para vocês. Aquele que o profanar será executado; quem fizer algum trabalho nesse dia será eliminado do meio do seu povo. Durante seis dias trabalhar-se-á, mas o sétimo dia é sábado de descanso consagrado ao Senhor. Quem fizer algum trabalho no dia de sábado terá que ser executado. Os israelitas observarão o sábado, celebrando-o nas suas gerações como uma aliança perpétua.",

        "Ezequiel 20:12": "Além disso, lhes dei também os meus sábados como sinal entre mim e vocês, para que soubéssem que eu sou o Senhor que os santifica.",

        "Ezequiel 20:20": "Santifiquem os meus sábados, e eles serão um sinal entre mim e vocês, para que saibam que eu sou o Senhor, o seu Deus.",

        "Apocalipse 14:6-7": "Então vi outro anjo voando pelo meio do céu, tendo um evangelho eterno para pregar aos que habitam na terra, a toda nação, tribo, língua e povo. Ele disse em alta voz: 'Temam a Deus e glorifiquem-no, pois chegou a hora do seu juízo. Adorem aquele que fez os céus, a terra, o mar e as fontes das águas'.",

        "Atos 17:30": "No passado Deus não levou em conta essa ignorância, mas agora ordena que todos, em todos os lugares, se arrependam.",

        "Romanos 2:11-16": "Pois Deus não faz acepção de pessoas. Todos os que pecaram sem a lei perecerão sem a lei, e todos os que pecaram sob a lei serão julgados pela lei. Pois não são os que ouvem a lei que são justos aos olhos de Deus, mas os que obedecem à lei serão declarados justos. Isso acontecerá no dia em que Deus julgar os segredos dos homens, por meio de Jesus Cristo, conforme o meu evangelho.",

        "João 9:41": "Jesus disse: 'Se vocês fossem cegos, não seriam culpados de pecado. Mas agora que dizem que vêem, a culpa de pecado de vocês permanece'.",

        "2 Coríntios 6:14-17": "Não se ponham em jugo desigual com descrentes. Pois o que têm em comum a justiça com a injustiça? E que comunhão pode ter a luz com as trevas? Que harmonia entre Cristo e Belial? Que há de comum entre o crente e o não crente? Que acordo há entre o templo de Deus e os ídolos? Pois nós somos o templo do Deus vivo. Como disse Deus: 'Habitarei com eles e andarei entre eles, e serei o Deus deles, e eles serão o meu povo'. Portanto, 'saiam do meio deles e separem-se', diz o Senhor. 'Não toquem em nada impuro'.",

        "Hebreus 7:25": "Por isso ele é capaz de salvar totalmente os que, por meio dele, aproximam-se de Deus, visto que vive sempre para interceder por eles.",

        "Hebreus 3:12-14": "Cuidado, irmãos, para que nenhum de vocês tenha coração incrédulo e se afaste do Deus vivo. Pelo contrário, encorajem-se uns aos outros todos os dias, durante o tempo que se chama 'hoje', a fim de que nenhum de vocês se endureça pelo engano do pecado.",

        "Hebreus 6:4-6": "É impossível que sejam outra vez renovados para arrependimento, visto que estão uma vez por todas iluminados, que provaram o dom celestial, que se tornaram participantes do Espírito Santo, que provaram a boa palavra de Deus e os poderes da era que há de vir, e caíram, para serem novamente renovados para arrependimento, visto que estão crucificando de novo o Filho de Deus e sujeitando-o à desonra pública.",

        "Hebreus 10:26-29": "Se continuarmos a pecar deliberadamente depois de termos recebido o conhecimento da verdade, já não resta sacrifício pelos pecados, mas apenas uma expectativa terrível de juízo e de fogo voraz que consumirá os adversários de Deus. Alguém que rejeitou a lei de Moisés morre sem misericórdia pelo depoimento de duas ou três testemunhas. Quanto mais severo castigo pensam vocês que merece aquele que pisou o Filho de Deus, que profanou o sangue da aliança pelo qual havia sido santificado, e que insultou o Espírito da graça?",

        "2 Pedro 2:20-22": "Se, depois de terem escapado das corrupções do mundo por meio do conhecimento do Senhor e Salvador Jesus Cristo, ficam novamente enredados nelas e são vencidos, o seu estado final é pior do que o primeiro. Teria sido melhor não terem conhecido o caminho da justiça do que, depois de o terem conhecido, voltarem atrás e se desviarem do santo mandamento que lhes foi transmitido. Aconteceu-lhes o que diz o provérbio verdadeiro: 'O cão volta ao seu vômito', e 'a porca lavada volta a revolver-se na lama'.",

        "João 15:1-6": "Eu sou a videira verdadeira, e meu Pai é o agricultor. Todo ramo que em mim não dá fruto, ele o corta; e todo ramo que dá fruto, ele o poda, para que dê ainda mais fruto. Vocês já estão limpos pela palavra que lhes falei. Permaneçam em mim, e eu permanecerei em vocês. Nenhum ramo pode dar fruto por si mesmo, se não permanecer na videira; vocês também não podem dar fruto, se não permanecerem em mim. Eu sou a videira; vocês são os ramos. Se alguém permanecer em mim e eu nele, esse dá muito fruto; pois sem mim vocês não podem fazer coisa alguma. Se alguém não permanecer em mim, será como o ramo que é jogado fora e seca. Tais ramos são recolhidos, lançados ao fogo e queimados.",

        "Apocalipse 2:10": "Não tenha medo do que você está para sofrer. Eis que o diabo está para lançar alguns de vocês na prisão para prová-los. Você sofrerá tribulação por dez dias. Seja fiel até a morte, e eu lhe darei a coroa da vida.",

        "Mateus 5:48": "Portanto, sejam perfeitos como perfeito é o Pai celestial de vocês.",

        "Filipenses 3:12-15": "Não que já tenha obtido tudo isso ou que já tenha sido aperfeiçoado, mas prossigo para tomar posse daquilo para o que também fui tomado por Cristo Jesus. Irmãos, não penso que eu mesmo já o tenha obtido. Mas uma coisa faço: esquecendo-me do que fica para trás e avançando para o que está adiante, prossigo para o alvo, a fim de ganhar o prêmio do chamado celestial de Deus em Cristo Jesus. Todos nós que somos maduros na fé devemos ter essa atitude; e se, em alguma coisa, vocês pensam de modo diferente, Deus também lhes esclarecerá isso.",

        "Colossenses 1:28": "Nós o proclamamos, advertindo e ensinando a cada pessoa toda a sabedoria, para que apresentemos todo homem perfeito em Cristo.",

        "Hebreus 6:1": "Portanto, deixemos os ensinos elementares sobre Cristo e avancemos para a maturidade, não lançando novamente o fundamento do arrependimento de atos que levam à morte, da fé em Deus,",

        "1 Tessalonicenses 5:23": "Que o próprio Deus da paz os santifique inteiramente. Que todo o espírito, a alma e o corpo de vocês sejam preservados íntegros e irrepreensíveis na vinda de nosso Senhor Jesus Cristo.",

        "Judas 24": "Àquele que é capaz de impedi-los de cair e de apresentá-los irrepreensíveis e com grande alegria diante da sua glória.",

        "Apocalipse 14:5": "Ninguém podia aprender aquele cântico, exceto os cento e quarenta e quatro mil que haviam sido comprados da terra.",

        "Mateus 7:15-20": "Cuidado com os falsos profetas. Eles vêm a vocês disfarçados de ovelhas, mas por dentro são lobos devoradores. Pelos seus frutos vocês os conhecerão. Colhem-se uvas de espinheiros ou figos de cardos? Da mesma forma, toda árvore boa dá frutos bons, mas a árvore ruim dá frutos ruins. A árvore boa não pode dar frutos ruins, e a árvore ruim não pode dar frutos bons. Toda árvore que não dá bons frutos é cortada e lançada ao fogo. Assim, pelos seus frutos vocês os conhecerão.",

        "Jeremias 28:9": "O profeta que profetiza paz só será reconhecido como verdadeiramente enviado pelo Senhor quando a sua predição se cumprir.",

        "1 Coríntios 14:3": "Mas quem profetiza fala aos homens para edificação, exortação e consolo.",

        "Lucas 2:52": "E Jesus crescia em sabedoria, estatura e graça diante de Deus e dos homens.",

        "Daniel 10:13": "Mas o príncipe do reino da Pérsia me resistiu por vinte e um dias. Então Miguel, um dos príncipes principais, veio em minha ajuda.",

        "Daniel 12:1": "Naquele tempo se levantará Miguel, o grande príncipe que protege o seu povo. Haverá um tempo de angústia, como nunca houve desde que nações existiram até aquele tempo. Mas naquele tempo o seu povo será salvo, todo aquele cujo nome está escrito no livro.",

        "Judas 9": "Mas o arcanjo Miguel, quando contendia com o diabo e disputava a respeito do corpo de Moisés, não ousou proferir juízo insultante contra ele, mas disse: 'O Senhor te repreenda'.",

        "João 1:1-3": "No princípio era aquele que é a Palavra. Ele estava com Deus no princípio. Todas as coisas foram feitas por intermédio dele, e sem ele nada do que foi feito se fez.",

        "Colossenses 1:15-17": "Ele é a imagem do Deus invisível, o primogênito de toda a criação. Pois nele foram criadas todas as coisas nos céus e na terra, as visíveis e as invisíveis, sejam tronos ou soberanias, poderes ou autoridades; todas as coisas foram criadas por ele e para ele. Ele é antes de todas as coisas, e nele tudo subsiste.",

        "Hebreus 1:1-8": "Havendo Deus falado muitas vezes e de muitas maneiras, aos pais pelos profetas, nestes últimos dias nos falou pelo Filho. Ele nos designou herdeiro de todas as coisas, e por meio dele também fez o universo. O Filho é o resplendor da glória de Deus e a representação exata do seu ser, sustentando todas as coisas por sua palavra poderosa. Depois de ter proporcionado purificação dos pecados, ele se assentou à direita da Majestade nas alturas, tornando-se tão superior aos anjos quanto o nome que herdou é superior ao deles.",

        "1 Tessalonicenses 4:16": "Pois, segundo a palavra do Senhor, nós declaram que vocês que estão vivos e os que dormem, nós seremos arrebatados juntamente com eles nas nuvens, para encontrar o Senhor nos ares. E assim estaremos com o Senhor para sempre.",

        "Apocalipse 22:18-19": "Eu advirto a todos os que ouvem as palavras da profecia deste livro: Se alguém lhe acrescentar algo, Deus lhe acrescentará as pragas descritas neste livro. Se alguém tirar alguma palavra deste livro de profecia, Deus tirará dele a sua parte na árvore da vida e na cidade santa, que são descritas neste livro.",

        "Efésios 4:11-13": "E ele designou alguns para apóstolos, outros para profetas, outros para evangelistas, e outros para pastores e mestres, com o fim de preparar os santos para a obra do ministério, para que o corpo de Cristo seja edificado, até que todos alcancemos a unidade da fé e do conhecimento do Filho de Deus, e cheguemos à maturidade, atingindo a medida da plenitude de Cristo.",

        "Joel 2:28-29": "E depois disso derramarei o meu Espírito sobre todos os povos. Os seus filhos e as suas filhas profetizarão, os velhos terão sonhos, os jovens terão visões. Até sobre os servos e as servas derramarei o meu Espírito naqueles dias.",

        "Atos 2:17-18": "Nos últimos dias, diz Deus, derramarei o meu Espírito sobre todos os povos. Os seus filhos e as suas filhas profetizarão, os jovens terão visões, os velhos terão sonhos. Sobre os meus servos e as minhas servas derramarei do meu Espírito naqueles dias, e eles profetizarão.",

        "Apocalipse 19:10": "Então caí aos seus pés para adorá-lo, mas ele me disse: 'Não faça isso! Sou servo como você e como os seus irmãos que se mantêm firmes no testemunho de Jesus. Adore a Deus! Pois o testemunho de Jesus é o espírito de profecia'.",

        "1 Coríntios 10:31": "Assim, quer vocês comam, bebam ou façam qualquer outra coisa, façam tudo para a glória de Deus.",

        "Romanos 12:1-2": "Portanto, irmãos, rogo-lhes pelas misericórdias de Deus que se ofereçam em sacrifício vivo, santo e agradável a Deus; este é o culto racional de vocês. Não se amoldem ao padrão deste mundo, mas transformem-se pela renovação da sua mente, para que sejam capazes de experimentar e comprovar a boa, agradável e perfeita vontade de Deus.",

        "Lucas 2:52": "E Jesus crescia em sabedoria, estatura e graça diante de Deus e dos homens.",

        "Daniel 10:13": "Mas o príncipe do reino da Pérsia me resistiu por vinte e um dias. Então Miguel, um dos príncipes principais, veio em minha ajuda.",

        "Daniel 12:1": "Naquele tempo se levantará Miguel, o grande príncipe que protege o seu povo. Haverá um tempo de angústia, como nunca houve desde que nações existiram até aquele tempo. Mas naquele tempo o seu povo será salvo, todo aquele cujo nome está escrito no livro.",

        "Judas 9": "Mas o arcanjo Miguel, quando contendia com o diabo e disputava a respeito do corpo de Moisés, não ousou proferir juízo insultante contra ele, mas disse: 'O Senhor te repreenda'.",

        "João 1:1-3": "No princípio era aquele que é a Palavra. Ele estava com Deus no princípio. Todas as coisas foram feitas por intermédio dele, e sem ele nada do que foi feito se fez.",

        "Colossenses 1:15-17": "Ele é a imagem do Deus invisível, o primogênito de toda a criação. Pois nele foram criadas todas as coisas nos céus e na terra, as visíveis e as invisíveis, sejam tronos ou soberanias, poderes ou autoridades; todas as coisas foram criadas por ele e para ele. Ele é antes de todas as coisas, e nele tudo subsiste.",

        "Hebreus 1:1-8": "Havendo Deus falado muitas vezes e de muitas maneiras, aos pais pelos profetas, nestes últimos dias nos falou pelo Filho. Ele nos designou herdeiro de todas as coisas, e por meio dele também fez o universo. O Filho é o resplendor da glória de Deus e a representação exata do seu ser, sustentando todas as coisas por sua palavra poderosa. Depois de ter proporcionado purificação dos pecados, ele se assentou à direita da Majestade nas alturas, tornando-se tão superior aos anjos quanto o nome que herdou é superior ao deles.",

        // SEÇÃO 3: JUÍZO INVESTIGATIVO, SANTUÁRIO CELESTIAL E 1844

        "Daniel 7:9-10": "Continuei olhando, até que foram postos uns tronos, e um ancião de dias se assentou; a sua veste era branca como a neve, e o cabelo da sua cabeça como a pura lã; o seu trono era de chamas de fogo, e as suas rodas de fogo ardente. Um rio de fogo manava e saía de diante dele; milhares de milhares o serviam, e milhões de milhões assistiam diante dele; assentou-se o juízo, e os livros foram abertos.",

        "Daniel 8:14": "Ele me disse: 'Até duas mil e trezentas tardes e manhãs; e o santuário será purificado'.",

        "Daniel 9:24-27": "Setenta semanas estão determinadas sobre o teu povo e sobre a tua santa cidade, para extinguir a transgressão, e para acabar com os pecados, e para expiar a iniquidade, e para trazer a justiça eterna, e para selar a visão e a profecia, e para ungir o santíssimo. Sabe e entende: desde a saída da ordem para restaurar e para edificar Jerusalém, até ao Messias, o Príncipe, haverá sete semanas, e sessenta e duas semanas; as ruas e as tranqueiras se reedificarão, mas em tempos angustiosos. E depois das sessenta e duas semanas será cortado o Messias, mas não para si mesmo; e o povo do príncipe, que há de vir, destruirá a cidade e o santuário, e o seu fim será com uma inundação; e até ao fim haverá guerra; estão determinadas assolações. E ele firmará um concerto com muitos por uma semana; e na metade da semana fará cessar o sacrifício e a oblação; e sobre a asa das abominações virá o assolador, e isso até à consumação; e o que está determinado será derramado sobre o assolador.",

        "Apocalipse 14:6-7": "E vi outro anjo voar pelo meio do céu, e tinha o evangelho eterno, para o proclamar aos que habitam sobre a terra, e a toda nação, e tribo, e língua, e povo, dizendo com grande voz: Temei a Deus e dai-lhe glória; porque é chegada a hora do seu juízo. E adorai aquele que fez o céu, e a terra, e o mar, e as fontes das águas.",

        "Apocalipse 22:12": "Eis que venho sem demora, e comigo está o galardão com que recompenso a cada um segundo as suas obras.",

        "Números 14:34": "Segundo o número dos dias em que espiastes a terra, quarenta dias, cada dia representando um ano, levareis sobre vós as vossas iniquidades quarenta anos, e conhecereis o meu afastamento.",

        "Ezequiel 4:6": "E, quando tiveres cumprido these, deitar-te-ás sobre o teu lado direito, e levarás a iniquidade da casa de Judá quarenta dias; cada dia te dei um ano.",

        "Esdras 7:11-26": "Esta é a cópia da carta que o rei Artaxerxes deu ao sacerdote Esdras, o escriba das palavras dos mandamentos do Senhor e dos seus estatutos sobre Israel: Artaxerxes, rei dos reis, ao sacerdote Esdras, escriba da lei do Deus do céu, paz perfeita. Por mim se decreta que todo aquele do meu povo que estiver disposto, no meu reino, suba a Jerusalém. Tu és enviado pelo rei e pelos seus sete conselheiros para inquirires a respeito de Judá e de Jerusalém, conforme a lei do teu Deus, que está na tua mão. E para levares a prata e o ouro que o rei e os seus conselheiros voluntariamente deram ao Deus de Israel, cuja habitação está em Jerusalém. E toda a prata e o ouro que achares em toda a província da Babilônia, com as ofertas voluntárias do povo e dos sacerdotes, que voluntariamente as oferecerem para a casa do seu Deus, que está em Jerusalém. Portanto, comprarás com este dinheiro novilhos, carneiros, e cordeiros, com as suas ofertas de alimentos, e as suas libações, e os oferecerás sobre o altar da casa do vosso Deus, que está em Jerusalém. E também o que parecer bem a ti e a teus irmãos fazer com o resto da prata e do ouro, o fareis conforme a vontade do vosso Deus. E os vasos que te foram dados para o serviço da casa do teu Deus, os porás perante o Deus de Jerusalém. E todo mais que for necessário para a casa do teu Deus, que te convier dar, o darás da casa dos tesouros do rei. E eu, o rei Artaxerxes, decretei a todos os tesouros que estão além do rio, que tudo o que vos pedir o sacerdote Esdras, escriba da lei do Deus do céu, logo se faça sem demora. Até cem talentos de prata, e até cem coros de trigo, e até cem batos de vinho, e até cem batos de azeite, e sal sem medida. Tudo o que for da lei do Deus do céu, com diligência se faça para a casa do Deus do céu; pois por que seria ira sobre o reino do rei e de seus filhos? Também vos notificamos acerca de todos os sacerdotes e levitas, cantores, porteiros, netinins e ministros desta casa de Deus, que não se lha imponha tributo, nem imposto, nem pedágio. E tu, Esdras, conforme a sabedoria do teu Deus, que está na tua mão, nomeia magistrados e juízes, que julguem a todo o povo que está além do rio, a todos que sabem as leis do teu Deus; e ao que não as sabe, as ensinareis. E todo aquele que não observar a lei do teu Deus e a lei do rei, logo se faça dele justiça, seja para morte, seja para degredo, seja para perda de bens, seja para prisão.",

        "Lucas 24:25-27": "Então ele lhes disse: 'Ó néscios e tardos de coração para crer tudo o que os profetas disseram! Porventura não importava que o Cristo padecesse essas coisas e entrasse na sua glória? E, começando por Moisés, e por todos os profetas, explicava-lhes o que dele se achava em todas as Escrituras.'",

        "João 19:30": "E, quando Jesus tomou o vinagre, disse: 'Está consumado'. E, inclinando a cabeça, entregou o espírito.",

        "Hebreus 10:10-14": "Na vontade dele somos santificados, pela oferta do corpo de Jesus Cristo, feita uma vez para sempre. E assim todo sacerdote aparece cada dia, ministrando e oferecendo muitas vezes os mesmos sacrifícios, que nunca podem tirar os pecados; mas este, havendo oferecido um único sacrifício pelos pecados, assentou-se à destra de Deus, daqui em diante esperando até que os seus inimigos sejam postos por escabelo de seus pés. Porque com uma única oferta aperfeiçoou para sempre os que são santificados.",

        "Hebreus 7:25": "Portanto, pode também salvar perfeitamente os que por ele se chegam a Deus, vivendo sempre para interceder por eles.",

        "Hebreus 8:1-2": "O ponto principal do que estamos dizendo é este: temos um sumo sacerdote tal, que se assentou à destra da Majestade nos céus, ministro do santuário e do verdadeiro tabernáculo, que o Senhor fundou, não o homem.",

        "Hebreus 9:24": "Porque Cristo não entrou num santuário feito por mãos, figura do verdadeiro, porém no mesmo céu, para agora comparecer por nós perante a face de Deus.",

        "Salmo 139:1-4": "Senhor, tu me sondaste e me conheces. Tu sabes o meu assentar e o meu levantar; de longe entendes o meu pensamento. esquadrinhas o meu andar e o meu deitar, e conheces todos os meus caminhos. Sem que haja uma palavra na minha língua, eis que, ó Senhor, tudo conheces.",

        "Hebreus 4:13": "E não há criatura alguma encoberta diante dele; antes todas as coisas estão nuas e patentes aos olhos daquele a quem temos de prestar contas.",

        "1 Coríntios 4:5": "Portanto, nada julgueis antes do tempo, até que venha o Senhor, que não somente trará à luz as coisas ocultas das trevas, mas também manifestará os desígnios dos corações; e, então, cada um receberá de Deus o louvor.",

        "João 5:24": "Na verdade, na verdade vos digo que quem ouve a minha palavra e crê naquele que me enviou tem a vida eterna e não entrará em condenação, mas passou da morte para a vida.",

        "Romanos 8:1": "Portanto, agora nenhuma condenação há para os que estão em Cristo Jesus, que não andam segundo a carne, mas segundo o espírito.",

        "1 João 5:11-13": "E este é o testemunho: que Deus nos deu a vida eterna, e esta vida está em seu Filho. Quem tem o Filho tem a vida; quem não tem o Filho de Deus não tem a vida. Estas coisas vos escrevi, a vós que credes no nome do Filho de Deus, para que saibais que tendes a vida eterna e para que creiais no nome do Filho de Deus.",

        "Mateus 24:13": "Mas aquele que perseverar até ao fim será salvo.",

        "Hebreus 3:14": "Porque nos tornamos participantes de Cristo, se apenas guardarmos firme o princípio da nossa confiança até ao fim.",

        "Apocalipse 14:12": "Aqui está a paciência dos santos; aqui estão os que guardam os mandamentos de Deus e a fé em Jesus.",

        "Hebreus 9:23-24": "De sorte que era bem necessário que as figuras das coisas que estão no céu assim se purificassem; mas as próprias coisas celestiais com sacrifícios melhores do que estes. Porque Cristo não entrou num santuário feito por mãos, figura do verdadeiro, porém no mesmo céu, para agora comparecer por nós perante a face de Deus.",

        "Hebreus 8:5": "E servem de exemplo e sombra das coisas celestiais, como Moisés divinamente foi avisado, estando para acabar o tabernáculo; porque foi dito: Olha, faze tudo conforme o modelo que no monte se te mostrou.",

        "Hebreus 9:12": "Nem por sangue de bodes e bezerros, mas por seu próprio sangue, entrou uma vez no santuário, havendo efetuado uma eterna redenção.",

        "Hebreus 7:25-27": "Portanto, pode também salvar perfeitamente os que por ele se chegam a Deus, vivendo sempre para interceder por eles. Porque nos convinha tal sumo sacerdote, santo, inocente, imaculado, separado dos pecadores e feito mais sublime do que os céus, que não necessitasse, como os sumos sacerdotes, de oferecer cada dia sacrifícios, primeiramente por seus próprios pecados e depois pelos do povo; porque isso fez ele, uma vez, oferecendo-se a si mesmo.",

        "Hebreus 9:24-28": "Porque Cristo não entrou num santuário feito por mãos, figura do verdadeiro, porém no mesmo céu, para agora comparecer por nós perante a face de Deus; nem também para se oferecer muitas vezes a si mesmo, como o sumo sacerdote cada ano entra no santuário com sangue alheio. Doutra maneira, necessário lhe fora padecer muitas vezes desde a fundação do mundo; mas agora, na consumação dos séculos, uma vez se manifestou, para aniquilar o pecado pelo sacrifício de si mesmo. E, como aos homens está ordenado morrerem uma vez vindo depois disso o juízo, assim também Cristo, oferecendo-se uma vez, para tirar os pecados de muitos, aparecerá segunda vez, sem pecado, aos que o esperam para salvação.",

        // SEÇÃO 4: ESTADO DOS MORTOS, ALMA E VIDA APÓS A MORTE

        "Gênesis 2:7": "E formou o Senhor Deus o homem do pó da terra, e soprou-lhe nas narinas o fôlego da vida; e o homem foi feito alma vivente.",

        "Eclesiastes 12:7": "E o pó volte à terra, como o era, e o espírito volte a Deus, que o deu.",

        "Eclesiastes 9:5": "Porque os vivos sabem que hão de morrer, mas os mortos não sabem coisa nenhuma, nem tampouco terão eles recompensa, mas a sua memória ficou entregue às esquecimentos.",

        "Eclesiastes 9:6": "Também o seu amor, o seu ódio, e a sua inveja já pereceram, e já não têm parte alguma para sempre, em nenhuma das todas as obras que se fazem debaixo do sol.",

        "Eclesiastes 9:10": "Tudo quanto te vier à mão para fazer, faze-o conforme as tuas forças, porque na sepultura, para onde tu vais, não há obra, nem indústria, nem ciência, nem sabedoria alguma.",

        "Salmo 146:4": "O seu espírito sai, e ele torna para a sua terra; naquele mesmo dia perecem os seus pensamentos.",

        "João 11:11-14": "E assim falou; e depois disse-lhes: Lázaro, o nosso amigo, dorme, mas vou despertá-lo do sono. Disseram, pois, os seus discípulos: Senhor, se dorme, estará salvo. Mas Jesus dizia isto da sua morte; eles, porém, cuidavam que falava do repouso do sono. Então Jesus disse-lhes claramente: Lázaro está morto.",

        "1 Tessalonicenses 4:13-17": "Não quero, porém, irmãos, que sejais ignorantes acerca dos que já dormem, para que não vos entristeçais, como os demais, que não têm esperança. Porque, se cremos que Jesus morreu e ressuscitou, assim também aos que em Jesus dormem Deus os tornará a trazer com ele. Dizemo-vos, pois, isto pela palavra do Senhor: que nós, os que ficarmos vivos para a vinda do Senhor, não precederemos os que dormem. Porque o mesmo Senhor descerá do céu com alarido, e com voz de arcanjo, e com a trombeta de Deus; e os que morreram em Cristo ressuscitarão primeiro. Depois, nós, os que ficarmos vivos, seremos arrebatados juntamente com eles nas nuvens, a encontrar o Senhor nos ares, e assim estaremos sempre com o Senhor.",

        "Lucas 23:43": "E disse-lhe Jesus: Em verdade te digo hoje que estarás comigo no Paraíso.",

        "João 5:28-29": "Não vos admireis disso, porque vem a hora em que todos os que estão nos sepulcros ouvirão a sua voz. E os que fizeram o bem sairão para a ressurreição da vida; e os que fizeram o mal para a ressurreição da condenação.",

        "Apocalipse 20:5-6": "Mas os outros mortos não reviveram, até que os mil anos se acabaram. Esta é a primeira ressurreição. Bem-aventurado e santo aquele que tem parte na primeira ressurreição; sobre estes não tem poder a segunda morte, mas serão sacerdotes de Deus e de Cristo, e reinarão com ele mil anos.",

        "Apocalipse 20:11-15": "E vi um grande trono branco e o que estava assentado sobre ele, de cuja presença fugiu a terra e o céu; e não se achou lugar para eles. E vi os mortos, grandes e pequenos, que estavam diante do trono, e abriram-se os livros; e abriu-se outro livro, que é o da vida; e os mortos foram julgados pelas coisas que estavam escritas nos livros, segundo as suas obras. E deu o mar os mortos que nele havia; e a morte e o inferno deram os mortos que neles havia; e foram julgados cada um segundo as suas obras. E a morte e o inferno foram lançados no lago de fogo; esta é a segunda morte. E aquele que não foi achado escrito no livro da vida foi lançado no lago de fogo.",

        "João 20:17": "Disse-lhe Jesus: Não me detenhas, porque ainda não subi para meu Pai, mas vai para meus irmãos e dize-lhes que eu subo para meu Pai e vosso Pai, meu Deus e vosso Deus.",

        "Lucas 16:19-31": "Ora, havia um homem rico, e vestia-se de púrpura e de linho finíssimo, e vivia todos os dias regalada e esplendidamente. Havia também um certo mendigo, chamado Lázaro, que estava deitado à porta daquele, cheio de chagas. E desejava alimentar-se com as migalhas que caíam da mesa do rico; e os próprios cães vinham lamber-lhe as chagas. E aconteceu que o mendigo morreu e foi levado pelos anjos para o seio de Abraão; e morreu também o rico e foi sepultado. E no inferno, ergueu os olhos, estando em tormentos, e viu ao longe Abraão e Lázaro no seu seio. E, clamando, disse: Pai Abraão, tem misericórdia de mim, e manda a Lázaro que molhe na água a ponta do seu dedo e me refresque a língua, porque estou atormentado nesta chama. Disse, porém, Abraão: Filho, lembra-te de que recebeste os teus bens em a tua vida, e Lázaro somente males; e agora este é consolado e tu atormentado. E, além disso, está posto um grande abismo entre nós e vós, de sorte que os que quisessem passar daqui para vós não poderiam, nem tampouco os de lá passar para cá. E disse ele: Rogo-te, pois, ó pai, que o mandes à casa de meu pai, pois tenho cinco irmãos, para que lhes dê testemunho, a fim de que não venham também para este lugar de tormento. Disse-lhe Abraão: Têm Moisés e os Profetas; ouçam-nos. E disse ele: Não, pai Abraão; mas, se algum dos mortos fosse ter com eles, arrepender-se-iam. Porém, Abraão lhe disse: Se não ouvem a Moisés e aos Profetas, tampouco acreditarão, ainda que algum dos mortos ressuscite.",

        "Filipenses 1:21-23": "Porque para mim o viver é Cristo, e o morrer é ganho. Mas, se o viver na carne me der fruto da minha obra, não sei em que devo escolher. Mas de ambos os lados estou em aperto, tendo desejo de partir e estar com Cristo, porque isto é ainda muito melhor.",

        "Filipenses 1:23": "Mas de ambos os lados estou em aperto, tendo desejo de partir e estar com Cristo, porque isto é ainda muito melhor.",

        "2 Coríntios 5:1-8": "Porque sabemos que, se a nossa casa terrestre deste tabernáculo se desfizer, temos da parte de Deus um edifício, uma casa não feita por mãos, eterna, nos céus. E por isso também gememos, desejando ser revestidos da nossa habitação que é do céu, se, quanto a estar vestidos, não formos achados nus. Porque também nós, os que estamos neste tabernáculo, gememos oprimidos, porque não queremos ser despidos, mas revestidos, para que o mortal seja absorvido pela vida. Ora, quem para isto nos preparou foi Deus, o qual nos deu também o penhor do Espírito. Pelo que estamos sempre de bom ânimo, sabendo que, enquanto estamos no corpo, vivemos ausentes do Senhor. Porque andamos por fé e não por vista. Mas temos confiança e desejamos antes deixar este corpo, para habitar com o Senhor.",

        "2 Coríntios 5:8": "Mas temos confiança e desejamos antes deixar este corpo, para habitar com o Senhor.",

        "1 Coríntios 15:51-54": "Eis aqui vos digo um mistério: Na verdade, nem todos dormiremos, mas todos seremos transformados, num momento, num abrir e fechar de olhos, ante a última trombeta; porque a trombeta soará, e os mortos ressuscitarão incorruptíveis, e nós seremos transformados. Porque convém que isto que é corruptível se revista da incorruptibilidade e que isto que é mortal se revista da imortalidade. E, quando isto que é corruptível se revestir da incorruptibilidade, e isto que é mortal se revestir da imortalidade, então cumprir-se-á a palavra que está escrita: Tragada foi a morte na vitória.",

        "2 Reis 2:11": "E sucedeu que, indo eles andando e falando, eis que um carro de fogo, com cavalos de fogo, separou os dois; e Elias subiu ao céu num redemoinho.",

        "Mateus 17:1-3": "Seis dias depois, Jesus tomou consigo a Pedro, e a Tiago, e a João, seu irmão, e os conduziu em particular a um alto monte, e transfigurou-se diante deles; e o seu rosto resplandeceu como o sol, e as suas vestes se tornaram brancas como a luz. E eis que lhes apareceram Moisés e Elias, falando com ele.",

        "Deuteronômio 18:10-12": "Não se achará no meio de ti quem faça passar pelo fogo o seu filho ou a sua filha, nem adivinhador, nem prognosticador, nem agoureiro, nem feiticeiro, nem encantador, nem quem consulte um espírito adivinhador, nem mágico, nem quem consulte os mortos; pois todo aquele que faz tal coisa é abominação ao Senhor.",

        "Isaías 8:19-20": "Quando, pois, vos disserem: Consultai os que têm espíritos familiares e os adivinhos, que chilreiam e murmuram entre dentes; não recorrerá um povo ao seu Deus? A favor dos vivos consultar-se-á aos mortos? À lei e ao testemunho! Se eles não falarem segundo esta palavra, nunca verão a alva.",

        "2 Coríntios 11:14": "E não é maravilha, porque o próprio Satanás se transfigura em anjo de luz.",

        "1 Timóteo 4:1": "Mas o Espírito expressamente diz que, nos últimos tempos, apostatarão alguns da fé, dando ouvidos a espíritos enganadores e a doutrinas de demônios.",

        "1 Timóteo 2:5": "Porque há um só Deus e um só mediador entre Deus e os homens, Jesus Cristo, homem.",

        "1 João 2:1": "Meus filhinhos, estas coisas vos escrevo para que não pequeis; e, se alguém pecar, temos um advogado para com o Pai, Jesus Cristo, o justo.",

        // SEÇÃO 5: INFERNO E DESTRUIÇÃO FINAL DOS ÍMPIOS

        "Ezequiel 18:4": "Eis que todas as almas são minhas; como o é a alma do pai, assim também a alma do filho é minha; a alma que pecar, essa morrerá.",

        "Malaquias 4:1-3": "Porque eis que aquele dia vem ardendo como forno; todos os soberbos e todos os que cometem impiedade serão como restolho; e o dia que está para vir os abrasará, diz o Senhor dos Exércitos, de sorte que lhes não deixará nem raiz nem ramo. Mas para vós que temeis o meu nome nascerá o sol da justiça e cura nas suas asas; e saireis e saltareis como bezerros da estrebaria. E pisareis os ímpios, pois se farão em cinza debaixo das plantas de vossos pés, naquele dia que estou preparando, diz o Senhor dos Exércitos.",

        "Apocalipse 20:10": "E o diabo, que os enganava, foi lançado no lago de fogo e enxofre, onde está a besta e o falso profeta; e de dia e de noite serão atormentados para todo o sempre.",

        "Salmos 37:10": "Pois ainda um pouco, e o ímpio não existirá; olharás para o seu lugar, e não aparecerá.",

        "Salmos 37:20": "Mas os ímpios perecerão, e os inimigos do Senhor serão como a gordura dos cordeiros; desaparecerão em fumaça.",

        "Obadias 16": "Porque, como bebestes no monte da minha santidade, assim beberão de contínuo todos os gentios; beberão e engolirão, e serão como se nunca tivessem sido.",

        "Filipenses 3:19": "O fim deles é a perdição, o deus deles é o ventre, e a glória deles é para confusão deles, que só pensam nas coisas terrenas.",

        "2 Pedro 3:7": "Mas os céus e a terra que agora existem, pela mesma palavra, se guardam para o fogo, até o dia do juízo e da perdição dos homens ímpios.",

        "Judas 7": "Assim como Sodoma e Gomorra, e as cidades circunvizinhas, que se prostituíram e seguiram após outra carne, foram postas como exemplo, sofrendo a pena do fogo eterno.",

        "Mateus 25:46": "E irão estes para o tormento eterno, mas os justos para a vida eterna.",

        "Apocalipse 14:11": "E a fumaça do seu tormento sobe para todo o sempre; e não têm repouso nem de dia nem de noite os que adoram a besta e a sua imagem, e aquele que receber o sinal do seu nome.",

        "Isaías 34:8-10": "Porque é o dia da vingança do Senhor, ano de retribuições pela contenda de Sião. E os seus ribeiros se transformarão em pez, e o seu pó, em enxofre, e a sua terra, em pez ardente. Nem de noite nem de dia se apagará; a sua fumaça subirá para sempre; de geração em geração será assolada, e pelos séculos dos séculos ninguém passará por ela.",

        "Êxodo 21:6": "Então o seu senhor o levará aos juízes, e o fará chegar à porta, ou ao umbral da porta, e o seu senhor lhe furará a orelha com uma sovela; e ele o servirá para sempre.",

        "1 Samuel 1:22": "Porém, quando ela ficou grávida, depois de ter circuncidado o menino, subiu com o marido a oferecer os sacrifícios anuais e o seu voto.",

        "1 Samuel 1:28": "Eu, pois, o consagrei ao Senhor, todos os dias que viver; pois ao Senhor foi consagrado.",

        "2 Tessalonicenses 1:7-9": "E a vós, que sois atribulados, descanso conosco, quando se manifestar o Senhor Jesus desde o céu, com os anjos do seu poder, como labareda de fogo, tomando vingança dos que não conhecem a Deus e dos que não obedecem ao evangelho de nosso Senhor Jesus Cristo; os quais, por castigo, padecerão eterna perdição, ante a face do Senhor e a glória do seu poder.",

        "Lucas 12:47-48": "E aquele servo que soube a vontade do seu senhor e não se preparou, nem fez conforme a sua vontade, será castigado com muitos açoites. Mas o que a não soube e fez coisas dignas de açoites com poucos açoites será castigado. E a qualquer que muito for dado, muito se lhe pedirá, e ao que muito se lhe confiou, muito mais se lhe pedirá.",

        "Mateus 11:20-24": "Então começou ele a lançar em rosto às cidades onde se operou a maior parte dos seus prodígios o não se haverem arrependido, dizendo: Ai de ti, Corazim! Ai de ti, Betsaida! Porque, se em Tiro e em Sidom fossem feitos os prodígios que em vós foram feitos, há muito elas se teriam arrependido, com saco e cinza. Por isso eu vos digo que haverá menos rigor para Tiro e Sidom, no dia do juízo, do que para vós outras. E tu, Cafarnaum, que te ergues até aos céus, serás abatida até aos infernos; porque, se em Sodoma tivessem sido feitos os prodígios que em ti foram feitos, teria ela permanecido até hoje. Por isso eu vos digo que haverá menos rigor para a terra de Sodoma, no dia do juízo, do que para ti.",

        "Romanos 2:5-6": "Mas, segundo a tua dureza e coração impenitente, acumulas contra ti mesmo ira para o dia da ira e da revelação do juízo de Deus, o qual recompensará a cada um segundo as suas obras.",

        // SEÇÃO 6: SALVAÇÃO, GRAÇA, LEI E PERFEIÇÃO CRISTÃ

        "Efésios 2:8-9": "Porque pela graça sois salvos, por meio da fé; e isso não vem de vós; é dom de Deus. Não vem das obras, para que ninguém se glorie.",

        "Romanos 3:20-28": "Por isso, nenhuma carne será justificada diante dele pelas obras da lei, porque pela lei vem o conhecimento do pecado. Mas agora, sem lei, se manifestou a justiça de Deus testificada pela lei e pelos profetas; justiça de Deus em Jesus Cristo, para todos e sobre todos os que creem; porque não há diferença. Pois todos pecaram e destituídos estão da glória de Deus, sendo justificados gratuitamente pela sua graça, pela redenção que há em Cristo Jesus, ao qual Deus propôs para propiciação pela fé no seu sangue, para demonstrar a sua justiça pela remissão dos pecados dantes cometidos, sob a paciência de Deus; para demonstração da sua justiça neste tempo presente, para que ele seja justo e justificador daquele que tem fé em Jesus. Onde está, então, a jactância? Foi excluída. Por qual lei? Das obras? Não! Mas pela lei da fé. Concluímos, pois, que o homem é justificado pela fé, sem as obras da lei.",

        "Romanos 5:1": "Sendo, pois, justificados pela fé, temos paz com Deus por nosso Senhor Jesus Cristo.",

        "Gálatas 2:16": "Sabendo que o homem não é justificado pelas obras da lei, mas pela fé em Jesus Cristo, temos também crido em Jesus Cristo, para sermos justificados pela fé de Cristo e não pelas obras da lei, pois por elas nenhuma carne será justificada.",

        "Tito 3:5": "Não pelas obras de justiça que houvéssemos feito, mas segundo a sua misericórdia, nos salvou pela lavagem da regeneração e da renovação do Espírito Santo.",

        "Efésios 2:10": "Porque somos feitura sua, criados em Cristo Jesus para as boas obras, as quais Deus preparou para que andássemos nelas.",

        "João 14:15": "Se me amardes, guardareis os meus mandamentos.",

        "1 João 2:3-6": "E nisto sabemos que o conhecemos: se guardarmos os seus mandamentos. Aquele que diz: Eu conheço-o e não guarda os seus mandamentos é mentiroso, e nele não está a verdade. Mas qualquer que guarda a sua palavra, nele verdadeiramente o amor de Deus está aperfeiçoado. Nisto sabemos que estamos nele. Aquele que diz que está nele também deve andar como ele andou.",

        "1 João 5:2-3": "Nisto conhecemos que amamos os filhos de Deus: quando amamos a Deus e guardamos os seus mandamentos. Porque este é o amor de Deus: que guardemos os seus mandamentos; e os seus mandamentos não são pesados.",

        "Tiago 2:17-26": "Assim também a fé, se não tiver as obras, é morta em si mesma. Mas dirá alguém: Tu tens a fé, e eu tenho as obras; mostra-me a tua fé sem as tuas obras, e eu te mostrarei as minhas obras pela fé. Tu crês que há um só Deus; faz bem; também os demônios o creem e estremecem. Mas queres tu saber, ó homem vão, que a fé sem as obras é morta? Porventura o nosso pai Abraão não foi justificado pelas obras, quando ofereceu sobre o altar o seu filho Isaque? Bem vês que a fé cooperou com as suas obras e que, pelas obras, a fé foi aperfeiçoada. E cumpriu-se a Escritura, que diz: E creu Abraão em Deus, e foi-lhe isso imputado como justiça, e foi chamado o amigo de Deus. Vedes, então, que o homem é justificado pelas obras e não somente pela fé. E de igual modo Raabe, a meretriz, não foi ela justificada pelas obras, quando recolheu os emissários e os despediu por outro caminho? Porque, assim como o corpo sem o espírito está morto, assim também a fé sem obras está morta.",

        "Romanos 7:7": "Que diremos, pois? É a lei pecado? De modo nenhum. Mas eu não conheci o pecado senão pela lei; porque eu não conheceria a concupiscência, se a lei não dissesse: Não cobiçarás.",

        "Tiago 1:23-25": "Porque, se alguém é ouvinte da palavra e não cumpridor, é semelhante ao homem que contempla ao seu rosto num espelho; porque se contempla a si mesmo, e vai-se, e logo se esquece de que era tal. Aquele, porém, que atenta bem para a lei perfeita da liberdade, e nisso persevera, não sendo ouvinte esquecedor, mas fazedor da obra, este tal será bem-aventurado nos seus feitos.",

        "Jeremias 31:31-33": "Eis que dias vêm, diz o Senhor, em que farei um concerto novo com a casa de Israel e com a casa de Judá. Não conforme o concerto que fiz com seus pais, no dia em que os tomei pela mão para os tirar da terra do Egito, porquanto eles invalidaram o meu concerto, apesar de eu os haver desposado, diz o Senhor. Mas este é o concerto que farei com a casa de Israel depois daqueles dias, diz o Senhor: Porei a minha lei no seu interior, e a escreverei nos seus corações; e eu serei o seu Deus, e eles serão o meu povo.",

        "Hebreus 8:8-10": "Achando ele fault neles, diz: Eis que virão dias, diz o Senhor, em que com a casa de Israel e com a casa de Judá estabelecerei um novo concerto. Não segundo o concerto que fiz com seus pais, no dia em que os tomei pela mão, para os tirar da terra do Egito, porque eles não permaneceram naquele meu concerto, e eu para eles não atentei, diz o Senhor. Porque este é o concerto que depois daqueles dias farei com a casa de Israel, diz o Senhor: Porei as minhas leis no seu entendimento, e em seus corações as escreverei; e eu lhes serei por Deus, e eles me serão por povo.",

        "Romanos 3:31": "Anulamos, pois, a lei pela fé? De maneira nenhuma! Antes, estabelecemos a lei.",

        "Romanos 8:3-4": "Porquanto o que era impossível à lei, visto como estava enferma pela carne, Deus, enviando o seu Filho em semelhança da carne do pecado, pelo pecado condenou o pecado na carne, para que a justiça da lei se cumprisse em nós, que não andamos segundo a carne, mas segundo o espírito.",

        "Mateus 5:17-19": "Não cuideis que vim destruir a lei ou os profetas; não vim ab-rogar, mas cumprir. Porque em verdade vos digo que, até que o céu e a terra passem, nem um jota ou um til se omitirá da lei, sem que tudo seja cumprido. Qualquer, pois, que violar um destes menores mandamentos e assim ensinar aos homens será chamado o menor no Reino dos céus; aquele, porém, que os cumprir e ensinar será chamado grande no Reino dos céus.",

        "Êxodo 31:13-17": "Tu, pois, fala aos filhos de Israel, dizendo: Certamente guardareis meus sábados, porquanto isso é um sinal entre mim e vós nas vossas gerações; para que saibais que eu sou o Senhor, que vos santifica. Portanto, guardareis o sábado, porque santo é para vós; aquele que o profanar certamente morrerá; porque qualquer que nele fizer alguma obra, aquela alma será eliminada do meio do seu povo. Seis dias se trabalhará, porém, o sétimo dia é o sábado do descanso, santificado ao Senhor; qualquer que no dia do sábado fizer alguma obra, certamente morrerá. Pelo que os filhos de Israel guardarão o sábado, celebrando o sábado nas suas gerações como concerto perpétuo. Entre mim e os filhos de Israel será um sinal para sempre; porque em seis dias o Senhor fez o céu e a terra, e ao sétimo dia descansou, e restaurou-se.",

        "Ezequiel 20:12": "E também lhes dei os meus sábados, para que fossem um sinal entre mim e eles, para que soubessem que eu sou o Senhor que os santifica.",

        "Ezequiel 20:20": "E santificai os meus sábados, e servirão de sinal entre mim e vós, para que saibais que eu sou o Senhor, vosso Deus.",

        "Atos 17:30": "Mas Deus, não tendo em conta os tempos da ignorância, anuncia agora a todos os homens, em todo o lugar, que se arrependam.",

        "Romanos 2:11-16": "Porque, para com Deus, não há acepção de pessoas. Porque todos os que sem lei pecaram, sem lei também perecerão; e todos os que debaixo da lei pecaram, pela lei serão julgados. Porque os simples ouvintes da lei não são justos diante de Deus, mas os que praticam a lei hão de ser justificados. Porque, quando os gentios, que não têm lei, fazem naturalmente as coisas que são da lei, não tendo lei, para si mesmos são lei. Estes mostram a obra da lei escrita em seus corações, testificando juntamente a sua consciência e os seus pensamentos, quer acusando-os, quer defendendo-os) no dia em que Deus há de julgar os segredos dos homens, por Jesus Cristo, segundo o meu evangelho.",

        "João 9:41": "Disse-lhes Jesus: Se fósseis cegos, não tereis pecado; mas como agora dizeis: Vemos, o vosso pecado permanece.",

        "Hebreus 3:12-14": "Vede, irmãos, que nunca haja em qualquer de vós um coração mau e infiel, para se apartar do Deus vivo. Antes, exortai-vos uns aos outros todos os dias, durante o tempo que se chama Hoje, para que nenhum de vós se endureça pelo engano do pecado. Porque nos tornamos participantes de Cristo, se apenas guardarmos firme o princípio da nossa confiança até ao fim.",

        "Hebreus 6:4-6": "Porque é impossível que os que já uma vez foram iluminados, e provaram o dom celestial, e se fizeram participantes do Espírito Santo, e provaram a boa palavra de Deus e as virtudes do século futuro, e recaíram, sejam outra vez renovados para arrependimento; visto que, quanto a eles, de novo crucificam o Filho de Deus e o expõem ao vitupério.",

        "Hebrews 10:26-29": "Porque se vivermos deliberadamente em pecado, depois de termos recebido o conhecimento da verdade, já não resta mais sacrifício pelos pecados, mas uma certa expectação horrível de juízo e ardor de fogo, que há de devorar os adversários. Quebrantando alguém a lei de Moisés, morre sem misericórdia, só pela palavra de duas ou três testemunhas. De quanto maior castigo cuidais vós será julgado merecedor aquele que tiver pisado o Filho de Deus, e tido por profano o sangue do concerto, com que foi santificado, e fizer agravo ao Espírito da graça?",

        "2 Pedro 2:20-22": "Porquanto, se, depois de terem escapado das corrupções do mundo, pelo conhecimento do Senhor e Salvador Jesus Cristo, forem outra vez envolvidos nelas e vencidos, tornou-se-lhes o último estado pior do que o primeiro. Porque melhor lhes fora não conhecerem o caminho da justiça do que, conhecendo-o, desviarem-se do santo mandamento que lhes fora dado. Deste modo aconteceu-lhes o que diz um verdadeiro provérbio: O cão voltou ao seu próprio vômito; e: A porca lavada voltou a revolver-se no lamaçal.",

        "João 15:1-6": "Eu sou a videira verdadeira, e meu Pai é o lavrador. Toda a vara em mim que não dá fruto, ele a tira; e limpa toda aquela que dá fruto, para que dê mais fruto. Vós já estais limpos pela palavra que vos tenho falado. Estai em mim, e eu, em vós; como a vara de si mesma não pode dar fruto, se não estiver na videira, assim também vós, se não estiverdes em mim. Eu sou a videira, vós, as varas; quem está em mim, e eu nele, esse dá muito fruto; porque sem mim nada podereis fazer. Se alguém não estiver em mim, será lançado fora, como a vara, e secará; e os colhem e lançam no fogo, e ardem.",

        "Apocalipse 2:10": "Nada temas das coisas que hás de padecer. Eis que o diabo lançará alguns de vós na prisão, para que sejais tentados; e tereis uma tribulação de dez dias. Sê fiel até à morte, e dar-te-ei a coroa da vida.",

        "Mateus 5:48": "Sede vós, pois, perfeitos, como é perfeito o vosso Pai, que está nos céus.",

        "Filipenses 3:12-15": "Não que já a tenha alcançado, ou que seja perfeito; mas prossigo para alcançar aquilo para o que fui também preso por Cristo Jesus. Irmãos, quanto a mim, não julgo que o haja alcançado; mas uma coisa faço, e é que, esquecendo-me das coisas que atrás ficam, avançando para as que estão diante de mim, prossigo para o alvo, pelo prêmio da soberana vocação de Deus em Cristo Jesus. Assim, pois, todos quantos somos perfeitos, se sentimos isto mesmo, e, se sentimos outra coisa, também Deus vo-lo revelará.",

        "Colossenses 1:28": "A quem anunciamos, admoestando a todo homem e ensinando a todo homem em toda a sabedoria; para que apresentemos todo homem perfeito em Cristo Jesus.",

        "Hebreus 6:1": "Por isso, deixando os rudimentos da doutrina de Cristo, prossigamos até à perfeição, não lançando novamente o fundamento de arrependimento de obras mortas e de fé em Deus.",

        "1 Tessalonicenses 5:23": "E o mesmo Deus de paz vos santifique em tudo; e todo o vosso espírito, e alma, e corpo sejam plenamente conservados irrepreensíveis para a vinda de nosso Senhor Jesus Cristo.",

        "1 João 3:6-9": "Qualquer que permanece nele não peca; qualquer que peca não o viu nem o conheceu. Filhinhos, ninguém vos engane; quem pratica justiça é justo, como ele é justo. Quem comete o pecado é do diabo; porque o diabo peca desde o princípio. Para isto o Filho de Deus se manifestou: para desfarz as obras do diabo. Qualquer que é nascido de Deus não comete pecado; porque a sua semente permanece nele; e não pode pecar, porque é nascido de Deus.",

        "Judas 24": "Ora, aquele que é poderoso para vos guardar de tropeçar e apresentar-vos irrepreensíveis, com alegria, perante a sua glória.",

        "Apocalipse 14:5": "E na sua boca não se achou engano; porque são irrepreensíveis diante do trono de Deus.",

        "3 João 2": "Amado, desejo que te vá bem em todas as coisas e que tenhas saúde, assim como bem vai a tua alma.",

        "Isaías 58:13-14": "Se desviares o teu pé do sábado, de fazer a tua vontade no meu santo dia, e chamares ao sábado deleitoso e santo dia do Senhor digno de honra, e o honrares não seguindo os teus caminhos, nem pretendendo fazer a tua própria vontade, nem falares as tuas próprias palavras, então te deleitarás no Senhor, e te farei cavalgar sobre as alturas da terra e te sustentarei com a herança de Jacó, teu pai; porque a boca do Senhor o disse.",

        "Gálatas 3:19": "Logo, para que é a lei? Foi ordenada por causa das transgressões, até que viesse a descendência a quem a promessa tinha sido feita, e foi posta pelos anjos na mão de um medianário.",

        "Romanos 5:20": "Sobreveio a lei para que a ofensa abundasse; mas, onde o pecado abundou, superabundou a graça.",

        "Gálatas 3:24": "De maneira que a lei nos serviu de aio, para nos conduzir a Cristo, para que pela fé fôssemos justificados.",

        "Colossenses 2:14-17": "Havendo riscado a cédula que era contra nós nas suas ordenanças, a qual de alguma maneira nos era contrária, e a tirou do meio de nós, cravando-a na cruz. E, despojando os principados e potestades, os expôs publicamente e deles triunfou em si mesmo. Portanto, ninguém vos julgue pelo comer, ou pelo beber, ou por causa dos dias de festa, ou da lua nova, ou dos sábados, que são sombras das coisas futuras, mas o corpo é de Christ.",

        "Hebrews 10:1": "Porque, tendo a lei a sombra dos bens futuros e não a imagem exata das coisas, nunca, pelos mesmos sacrifícios que continuamente se oferecem cada ano, pode aperfeiçoar os que a eles se chegam.",

        // SEÇÃO 7: ESCATOLOGIA, MARCA DA BESTA E EVENTOS FINAIS

        "Apocalipse 13:1-10": "E eu pus-me sobre a areia do mar e vi subir do mar uma besta que tinha sete cabeças e dez chifres, e sobre os chifres, dez diademas, e sobre as cabeças, nomes de blasfêmia. E a besta que vi era semelhante a um leopardo, e os seus pés, como os de urso, e a sua boca, como a de leão; e o dragão deu-lhe o seu poder, e o seu trono, e grande poderio. E vi uma das suas cabeças como ferida de morte, e a sua chaga mortal foi curada; e toda a terra se maravilhou após a besta. E adoraram o dragão que deu à besta o seu poder; e adoraram a besta, dizendo: Quem é semelhante à besta? Quem poderá batalhar contra ela? E foi-lhe dada uma boca para proferir grandes coisas e blasfêmias; e deu-se-lhe poder para continuar por quarenta e dois meses. E abriu a boca em blasfêmias contra Deus, para blasfemar do nome de Deus, e do seu tabernáculo, e dos que habitam no céu. E foi-lhe permitido fazer guerra aos santos e vencê-los; e deu-se-lhe poder sobre toda tribo, língua e nação. E todos os que habitam sobre a terra adoraram a besta, esses cujos nomes não estão escritos no livro da vida do Cordeiro que foi morto desde a fundação do mundo. Se alguém tem ouvidos, ouça. Se alguém leva em cativeiro, em cativeiro irá; se alguém matar à espada, necessário é que à espada seja morto. Aqui está a paciência e a fé dos santos.",

        "Apocalipse 13:11-18": "E vi subir da terra outra besta, e tinha dois chifres semelhantes aos de um cordeiro, e falava como o dragão. E exerce todo o poder da primeira besta na sua presença e faz que a terra e os que nela habitam adorem a primeira besta, cuja chaga mortal fora curada. E opera grandes sinais, de maneira que até fogo faz descer do céu à terra, aos olhos dos homens. E engana os que habitam na terra com sinais que lhe foi permitido fazer na presença da besta, dizendo aos que habitam na terra que fizessem uma imagem da besta que recebera a ferida da espada e vivia. E foi-lhe concedido que desse espírito à imagem da besta, para que também a imagem da beast falasse, e fizesse que fossem mortos todos os que não adorassem a imagem da besta. E a todos os pequenos e grandes, ricos e pobres, livres e servos, impõe um sinal na mão direita ou na testa, para que ninguém possa comprar ou vender senão aquele que tiver o sinal, ou o nome da besta, ou o número do seu nome. Aqui há sabedoria. Aquele que tem entendimento calcule o número da besta, pois é o número de um homem. O seu número é 666.",

        "Apocalipse 13:16-17": "E faz que a todos, pequenos e grandes, ricos e pobres, livres e servos, lhes seja posto um sinal na mão direita ou na testa, para que ninguém possa comprar ou vender, senão aquele que tiver o sinal, ou o nome da besta, ou o número do seu nome.",

        "Apocalipse 14:9-11": "E seguiu-os o terceiro anjo, dizendo com grande voz: Se alguém adorar a besta e a sua imagem, e receber o sinal na sua testa ou na sua mão, também o tal beberá do vinho da ira de Deus, que se deitou, não misturado, no cálice da sua ira, e será atormentado com fogo e enxofre diante dos santos anjos e diante do Cordeiro. E a fumaça do seu tormento sobe para todo o sempre; e não têm repouso nem de dia nem de noite os que adoram a besta e a sua imagem, e aquele que receber o sinal do seu nome.",

        "Ezequiel 20:12": "E também lhes dei os meus sábados, para que fossem um sinal entre mim e eles, para que soubessem que eu sou o Senhor que os santifica.",

        "Daniel 7:25": "E proferirá palavras contra o Altíssimo, e destruirá os santos do Altíssimo, e cuidará em mudar os tempos e a lei; e eles serão entregues na sua mão, por um tempo, e tempos, e metade de um tempo.",

        "Atos 5:29": "Então Pedro e os apóstolos, respondendo, disseram: Importa antes obedecer a Deus do que aos homens.",

        "Apocalipse 13:15-17": "E foi-lhe concedido que desse espírito à imagem da besta, para que também a imagem da besta falasse, e fizesse que fossem mortos todos os que não adorassem a imagem da besta. E faz que a todos, pequenos e grandes, ricos e pobres, livres e servos, lhes seja posto um sinal na mão direita ou na testa, para que ninguém possa comprar ou vender, senão aquele que tiver o sinal, ou o nome da besta, ou o número do seu nome.",

        "Daniel 12:1": "Naquele tempo se levantará Miguel, o grande príncipe que protege o seu povo. Haverá um tempo de angústia, como nunca houve desde que nações existiram até aquele tempo. Mas naquele tempo o seu povo será salvo, todo aquele cujo nome está escrito no livro.",

        "Apocalipse 16:13": "E vi sair da boca do dragão, e da boca da besta, e da boca do falso profeta, três espíritos imundos, semelhantes a rãs.",

        "2 Tessalonicenses 2:3-4": "Ninguém, de maneira nenhuma, vos engane; porque isso não será sem que antes venha a apostasia, e se revele o homem do pecado, o filho da perdição, o qual se opõe e se levanta contra tudo o que se chama Deus ou se adora, de sorte que se assentará no templo de Deus, como Deus, pretendendo ser Deus.",

        "Efésios 4:30": "E não entristeçais o Espírito Santo de Deus, no qual estais selados para o dia da redenção.",

        "Apocalipse 20:1-3": "E vi descer do céu um anjo que tinha a chave do abismo e uma grande cadeia na sua mão. Ele prendeu o dragão, a antiga serpente, que é o diabo e Satanás, e amarrou-o por mil anos; e lançou-o no abismo, e fechou-o, e pôs selo sobre ele, para que não mais engane as nações, até que os mil anos se cumprissem; e depois importa que seja solto por um pouco de tempo.",

        "1 Coríntios 6:2-3": "Ou não sabeis que os santos hão de julgar o mundo?... Não sabeis que havemos de julgar os anjos?",

        "Jeremias 4:23-26": "Olhei para a terra, e eis que era sem forma e vazia; também para os céus, e não tinham luz. Olhei para os montes, e eis que estavam tremendo; e todos os outeiros foram abatidos. Olhei, e eis que não havia homem algum, e todas as aves do céu haviam fugido. Olhei ainda, e eis que a terra frutífera era um deserto, e as suas cidades todas estavam derribadas diante do Senhor, diante da furor da sua ira.",

        "Levítico 16:8-10": "E Arão lançará sortes sobre os dois bodes: uma sorte pelo Senhor, e a outra sorte pelo bode emissário. Então Arão fará chegar o bode sobre o qual cair a sorte pelo Senhor, e o oferecerá por oferta pelo pecado. Mas o bode sobre que cair a sorte para bode emissário apresentar-se-á vivo perante o Senhor, para fazer expiação por ele, a fim de enviá-lo ao deserto como bode emissário.",

        "Levítico 16:20-22": "E, tendo acabado de expiar o santuário, e a tenda da congregação, Arão fará chegar o bode vivo; e Arão porá as suas mãos sobre a cabeça do bode vivo, e confessará sobre ele todas as iniqüidades dos filhos de Israel, e todas as suas transgressões em todos os seus pecados; e os porá sobre a cabeça do bode, e enviá-lo-á ao deserto pela mão de um homem idôneo. Assim aquele bode levará sobre si todas as iniqüidades deles à terra solitária; e ele deixará ir o bode ao deserto.",

        "Hebreus 9:22-28": "E quase todas as coisas, segundo a lei, se purificam com sangue; e sem derramamento de sangue não há remissão. De sorte que era bem necessário que as figuras das coisas que estão nos céus assim se purificassem; mas as próprias coisas celestiais com sacrifícios melhores do que estes. Porque Cristo não entrou num santuário feito por mãos, figura do verdadeiro, porém no mesmo céu, para agora comparecer por nós perante a face de Deus; nem também para se oferecer muitas vezes a si mesmo, como o sumo sacerdote cada ano entra no santuário com sangue alheio. Doutra maneira, necessário lhe fora padecer muitas vezes desde a fundação do mundo; mas agora, na consumação dos séculos, uma vez se manifestou, para aniquilar o pecado pelo sacrifício de si mesmo. E, como aos homens está ordenado morrerem uma vez vindo depois disso o juízo, assim também Cristo, oferecendo-se uma vez, para tirar os pecados de muitos, aparecerá segunda vez, sem pecado, aos que o esperam para salvação.",

        "Mateus 24:21": "Porque haverá então grande aflição, como nunca houve desde o princípio do mundo até agora, nem tampouco há de haver.",

        "Mateus 24:14": "E este evangelho do reino será pregado em todo o mundo, em testemunho a todas as nações, e então virá o fim.",

        "Mateus 24:36": "Mas daquele dia e hora ninguém sabe, nem os anjos do céu, nem o Filho, mas unicamente meu Pai.",

        "Marcos 13:32": "Mas daquele dia e hora ninguém sabe, nem os anjos que estão no céu, nem o Filho, senão o Pai.",

        "Atos 1:7": "E disse-lhes: Não vos pertence saber os tempos ou as épocas que o Pai estabeleceu pelo seu próprio poder.",

        "2 Pedro 3:8-12": "Mas, amados, não ignoreis uma coisa: que para o Senhor um dia é como mil anos, e mil anos como um dia. O Senhor não retarda a sua promessa, ainda que alguns a têm por tardia; mas é longânimo para conosco, não querendo que alguns se percam, senão que todos venham ao arrependimento. Virá, pois, como ladrão o dia do Senhor, no qual os céus passarão com grande estrondo, e os elementos, ardendo, se desfarão, e a terra e as obras que nela há se queimarão. Havendo, pois, de todas essas coisas de haver de se desfazer, assim convém que vós em santidade e piedade espereis e apresseis a vinda do dia de Deus, na qual os céus, em fogo, se desfarão, e os elementos, ardendo, se fundirão.",

        "Apocalipse 14:14-15": "E olhei, e eis uma nuvem branca, e sobre a nuvem um assentado semelhante ao Filho do homem, que tinha sobre a cabeça uma coroa de ouro, e na mão uma foice aguda. E outro anjo saiu do templo que está no céu, clamando com grande voz ao que estava assentado sobre a nuvem: Lança a tua foice e sega; a hora de segar, porque já a seara da terra está seca.",

        "Apocalipse 12:7-9": "E houve batalha no céu: Miguel e os seus anjos batalhavam contra o dragão; e batalhavam o dragão e os seus anjos; mas não prevaleceram, nem mais o seu lugar se achou nos céus. E foi precipitado o grande dragão, a antiga serpente, chamada o diabo e Satanás, que engana todo o mundo; ele foi precipitado na terra, e os seus anjos foram lançados com ele.",

        "Isaías 14:12-14": "Como caíste desde o céu, ó estrela da manhã, filha da alva! Como foste lançado por terra, tu que debilitavas as nações! E tu dizias no teu coração: Eu subirei ao céu, acima das estrelas de Deus exaltarei o meu trono, e no monte da congregação me assentarei, da extremidade norte. Subirei sobre as alturas das nuvens, e serei semelhante ao Altíssimo.",

        "Ezequiel 28:12-17": "Filho do homem, levanta uma lamentação sobre o rei de Tiro e dize-lhe: Assim diz o Senhor Deus: Tu és o aferidor da medida, cheio de sabedoria e perfeito em formosura. Estiveste no Éden, jardim de Deus; toda pedra preciosa era a tua cobertura: sardônica, topázio, diamante, berilo, ônix, jaspe, safira, carbúnculo, esmeralda e ouro; a obra dos teus tambores e dos teus povares em ti se preparavam no dia em que foste criado. Tu eras querubim ungido para cobrir, e te estabeleci; no monte santo de Deus estavas, no meio das pedras afogueadas andavas. Perfeito eras nos teus caminhos desde o dia em que foste criado, até que se achou iniqüidade em ti. Na multidão do teu comércio se encheu o teu interior de iniqüidade, e pecaste; pelo que te lançarei profano do monte de Deus, e te destruirei, ó querubim cobridor, do meio das pedras afogueadas. Elevou-se o teu coração por causa da tua formosura, corrompeste a tua sabedoria por causa do teu resplendor; por terra te lancei, diante dos reis, para que te contemplem.",

        "Apocalipse 7:2-3": "E vi outro anjo subir do lado do sol nascente, que tinha o selo do Deus vivo; e clamou com grande voz aos quatro anjos, a quem fora dado o poder de danificar a terra e o mar, dizendo: Não danifiqueis a terra, nem o mar, nem as árvores, até que assinemos nas testas dos seus servos as suas testas.",

        "Apocalipse 7:4-8": "E ouvi o número dos assinalados: cento e quarenta e quatro mil assinalados de todas as tribos dos filhos de Israel.",

        "Apocalipse 14:1-5": "E olhei, e eis o Cordeiro em pé sobre o monte Sião, e com ele cento e quarenta e quatro mil, que tinham o nome de seu Pai escrito em suas testas. E ouvi uma voz do céu, como a voz de muitas águas, e como a voz de um grande trovão; e ouvi uma voz de harpistas tocando com as suas harpas. E cantavam como que um cântico novo diante do trono, e diante dos quatro animais e dos anciãos; e ninguém podia aprender aquele cântico, senão os cento e quarenta e quatro mil que foram comprados da terra. Estes são os que não estão contaminados com mulheres; porque são virgens. Estes são os que seguem o Cordeiro para onde quer que vá. Estes são os que dentre os homens foram remidos como primícias para Deus e para o Cordeiro; e na sua boca não se achou engano; porque são irrepreensíveis diante do trono de Deus.",

        "Apocalipse 1:7": "Eis que vem com as nuvens, e todo olho o verá, até os mesmos que o traspassaram; e todas as tribos da terra se lamentarão sobre ele. Sim. Amém.",

        "Mateus 24:27": "Porque, assim como o relâmpago sai do oriente e se mostra até ao ocidente, assim será também a vinda do Filho do homem.",

        "Mateus 24:30-31": "Então aparecerá no céu o sinal do Filho do homem; e todas as tribos da terra se lamentarão, e verão o Filho do homem vindo sobre as nuvens do céu, com poder e grande glória. E ele enviará os seus anjos com rido som de trombeta, e ajuntarão os seus escolhidos desde os quatro ventos, de uma à outra extremidade dos céus.",

        "2 Tessalonicenses 2:8": "E então será revelado o iníquo, a quem o Senhor desfará pelo espírito da sua boca e aniquilará pelo aparecimento da sua vinda.",

        // SEÇÃO 8: IGREJA REMANESCENTE, IDENTIDADE E RELAÇÃO COM OUTRAS IGREJAS

        "João 10:16": "Ainda tenho outras ovelhas que não são deste aprisco; também a essas me convém trazer, e elas ouvirão a minha voz, e haverá um rebanho e um pastor.",

        "Romanos 2:14-16": "Porque, quando os gentios, que não têm lei, fazem naturalmente as coisas que são da lei, não tendo lei, para si mesmos são lei; os quais mostram a obra da lei escrita em seus corações, testificando juntamente a sua consciência e os seus pensamentos, quer acusando-os, quer defendendo-os) no dia em que Deus há de julgar os segredos dos homens, por Jesus Cristo, segundo o meu evangelho.",

        "Efésios 4:13": "Até que todos cheguemos à unidade da fé e do conhecimento do Filho de Deus, a varão perfeito, à medida da estatura completa de Cristo.",

        "Apocalipse 18:4": "E ouvi outra voz do céu, que dizia: Sai dela, povo meu, para que não sejas participante dos seus pecados, e para que não incorras nas suas pragas.",

        "Apocalipse 14:8": "E outro anjo seguiu, dizendo: Caiu! Caiu Babilônia, aquela grande cidade, que a todas as nações deu a beber do vinho da ira da sua fornicação!",

        "2 Coríntios 6:14-17": "Não vos prendais a um jugo desigual com os infiéis; porque que sociedade tem a justiça com a injustiça? E que comunhão tem a luz com as trevas? E que concórdia há entre Cristo e Belial? Ou que parte tem o fiel com o infiel? E que consenso tem o templo de Deus com os ídolos? Porque vós sois o templo do Deus vivente, como Deus disse: Neles habitarei, e entre eles andarei; e eu serei o vosso Deus, e vós sereis o meu povo. Pelo que, saí do meio deles, e apartai-vos, diz o Senhor; e não toqueis nada imundo, e eu vos receberei.",

        // SEÇÃO 9: TRINDADE, DEUS E CRISTOLOGIA

        "2 Coríntios 13:13": "A graça do Senhor Jesus Cristo, e o amor de Deus, e a comunhão do Espírito Santo sejam com todos vós. Amém.",

        "Efésios 4:4-6": "Há um só corpo e um só Espírito, como também fostes chamados em uma só esperança da vossa vocação; um só Senhor, uma só fé, um só batismo; um só Deus e Pai de todos, o qual é sobre todos, age por todos e está em todos.",

        "1 Pedro 1:2": "Eleitos segundo a presciência de Deus Pai, em santificação do Espírito, para a obediência e aspersão do sangue de Jesus Cristo: Graça e paz vos sejam multiplicadas.",

        "João 1:1-3": "No princípio era aquele que é a Palavra. Ele estava com Deus no princípio. Todas as coisas foram feitas por intermédio dele, e sem ele nada do que foi feito se fez.",

        "João 1:14": "E o Verbo se fez carne, e habitou entre nós, e vimos a sua glória, como a glória do unigênito do Pai, cheio de graça e de verdade.",

        "João 14:16-17": "E eu rogarei ao Pai, e ele vos dará outro Consolador, para que fique convosco para sempre, o Espírito da verdade, que o mundo não pode receber, porque não o vê nem o conhece; mas vós o conheceis, porque habita convosco e estará em vós.",

        "João 14:26": "Mas o Consolador, o Espírito Santo, a quem o Pai enviará em meu nome, esse vos ensinará todas as coisas e vos fará lembrar de tudo quanto eu vos tenho dito.",

        "João 16:13-14": "Mas, quando vier aquele Espírito de verdade, ele vos guiará em toda a verdade, porque não falará de si mesmo, mas dirá tudo o que tiver ouvido e vos anunciará as coisas que hão de vir. Ele me glorificará, porque há de receber do que é meu e vo-lo há de anunciar.",

        "Atos 5:3-4": "Disse, então, Pedro: Ananias, por que encheu Satanás o teu coração, para que mentisses ao Espírito Santo e retivesses parte do preço da herdade?... Não mentiste aos homens, mas a Deus.",

        "Colossenses 2:9": "Porque nele habita corporalmente toda a plenitude da divindade.",

        "Hebreus 1:6": "E outra vez, quando introduz no mundo o Primogênito, diz: E todos os anjos de Deus o adorem.",

        "Apocalipse 5:11-14": "E olhei, e ouvi a voz de muitos anjos ao redor do trono, e dos animais, e dos anciãos; e era o número deles milhões de milhares e milhares de milhares, dizendo com grande voz: Digno é o Cordeiro, que foi morto, de receber o poder, e riqueza, e sabedoria, e força, e honra, e glória, e bênção. E ouvi a toda criatura que está no céu, e na terra, e debaixo da terra, e no mar, e a todos os que neles há, que diziam: Ao que está assentado sobre o trono, e ao Cordeiro, sejam dadas ações de graças, e honra, e glória, e poder para todo o sempre. E os quatro animais diziam: Amém. E os vinte e quatro anciãos prostraram-se e adoraram ao que vive para todo o sempre.",

        "Provérbios 4:18": "Mas a vereda dos justos é como a luz da aurora, que vai brilhando mais e mais até ser dia perfeito.",

        "Miquéias 5:2": "E tu, Belém Efrata, pequena para estar entre os milhares de Judá, de ti me sairá o que será governador em Israel, e cujas origens são desde os tempos antigos, desde os dias da eternidade.",

        "Colossenses 1:16-17": "Porque nele foram criadas todas as coisas que há nos céus e na terra, visíveis e invisíveis, sejam tronos, sejam dominações, sejam principados, sejam potestades; tudo foi criado por ele e para ele. Ele é antes de todas as coisas, e nele tudo subsiste.",

        "Hebreus 1:4-8": "Feito tanto mais excelente do que os anjos, quanto herdou mais excelente nome do que eles. Pois a qual dos anjos disse jamais: Tu és meu Filho, eu hoje te gerei? E outra vez: Eu lhe serei por Pai? E, novamente, quando introduz o Primogênito no mundo, diz: E todos os anjos de Deus o adorem. E, quanto aos anjos, diz: O que faz dos seus anjos espíritos e seus ministros labaredas de fogo. Mas, do Filho, diz: O teu trono, ó Deus, é eterno e perpétuo cetro de eqüidade. Tu amas a justiça e odeias a iniqüidade; por isso, Deus, o teu Deus, te ungiu com óleo de alegria, mais do que a teus companheiros.",

        "Romanos 8:3": "Porquanto o que era impossível à lei, visto como estava enferma pela carne, Deus, enviando o seu Filho em semelhança de carne de pecado, pelo pecado condenou o pecado na carne.",

        "Hebreus 2:14": "E, assim como aos filhos participaram da carne e do sangue, também ele participou das mesmas coisas, para que, pela morte, destruísse aquele que tinha o império da morte, isto é, o diabo.",

        "Hebreus 2:17": "Por isso convinha que, em tudo, fosse semelhante aos irmãos, para ser misericordioso e fiel sumo sacerdote naquilo que é de Deus e para fazer expiação pelos pecados do povo.",

        "Hebreus 4:15": "Porque não temos um sumo sacerdote que não possa compadecer-se das nossas fraquezas; antes, foi ele tentado em todas as coisas, à nossa semelhança, mas sem pecado.",

        "1 Pedro 2:22": "O qual não cometeu pecado, nem na sua boca se achou engano.",

        "2 Coríntios 5:21": "Àquele que não conheceu pecado, ele o fez pecado por nós; para que nele fôssemos feitos justiça de Deus.",

        "Hebreus 9:14": "Quanto mais o sangue de Cristo, que pelo Espírito eterno se ofereceu a si mesmo imaculado a Deus, purificará a vossa consciência das obras mortas, para servirdes ao Deus vivo?",

        "Romanos 5:12-19": "Pelo que, como por um homem entrou o pecado no mundo, e pelo pecado, a morte, assim também a morte passou a todos os homens, por isso que todos pecaram.",

        "1 Coríntios 15:21-22": "Porque, assim como a morte veio por um homem, também a ressurreição dos mortos veio por um homem.",

        "1 Coríntios 15:45-49": "Assim está também escrito: O primeiro homem, Adam, foi feito em alma vivente; o último Adam, em espírito vivificante. Mas não é primeiro o espiritual, senão o animal; depois o espiritual. O primeiro homem, da terra, é terreno; o segundo homem, o Senhor, é do céu. Qual o terreno, tais são também os terrenos; e, qual o celestial, tais também os celestiais.",

        "Deuteronômio 6:4": "Ouve, Israel, o Senhor, nosso Deus, é o único Senhor.",

        "Hebreus 1:1-8": "Havendo Deus, outrora, falado muitas vezes e de muitas maneiras, aos pais, pelos profetas, a nós falou-nos, nestes últimos dias, pelo Filho, a quem constituiu herdeiro de tudo, por quem fez também o mundo. O qual, sendo o resplendor da sua glória, e a expressa imagem da sua pessoa, e sustentando todas as coisas com a sua palavra, havendo ele mesmo feito a purificação dos nossos pecados, assentou-se à destra da Majestade, nas alturas, feito tanto mais excelente do que os anjos, quanto herdou mais excelente nome do que eles. Pois a qual dos anjos disse jamais: Tu és meu Filho, eu hoje te gerei? E outra vez: Eu lhe serei por Pai? E, novamente, quando introduz o Primogênito no mundo, diz: E todos os anjos de Deus o adorem. E, quanto aos anjos, diz: O que faz dos seus anjos espíritos e seus ministros labaredas de fogo. Mas, do Filho, diz: O teu trono, ó Deus, é eterno e perpétuo cetro de eqüidade. Tu amas a justiça e odeias a iniqüidade; por isso, Deus, o teu Deus, te ungiu com óleo de alegria, mais do que a teus companheiros.",

        "Mateus 28:17": "E, quando o viram, o adoraram; mas alguns duvidaram.",

        // SEÇÃO 10: SAÚDE, ALIMENTAÇÃO E ESTILO DE VIDA

        "Gênesis 7:2": "De todos os animais limpos tomarás sete e sete, macho e fêmea; mas dos animais que não são limpos, dois, macho e fêmea.",

        "Levítico 11": "E falou o Senhor a Moisés e a Arão, dizendo-lhes: Fala aos filhos de Israel, dizendo: Estes são os animais que comereis de todos os animais que há sobre a terra: Todo animal que tem unha fendida, dividida em duas unhas, e que rumina, esse comereis. Destes, porém, não comereis: dos que ruminam ou os que têm unha fendida, como o camelo, e a lebre, e o coelho, porque ruminam, mas não têm a unha fendida; imundos vos serão. Também o porco, porque tem unha fendida, mas não rumina; imundo vos será; da sua carne não comereis, e no seu cadavere não tocareis. Estes vos serão imundos entre os animais.",

        "Deuteronômio 14": "Estes são os animais que comereis: a vaca, a ovelha, a cabra, o veado, a gazela, o corço, o cabrito montês, o antílope, o órix e a ovelha montês. E todo animal que tem unhas fendidas, divididas em duas, e que rumina, entre todos os animais, esse comereis. Porém, destes não comereis: os que chew ou ruminam ou têm unha fendida, como o camelo, e a lebre, e o coelho; porque ruminam, mas não têm a unha fendida; imundos vos serão. Também o porco, porque tem unha fendida, mas não rumina; imundo vos será; da sua carne não comereis, e no seu cadavere não tocareis. Aqui da água coisas viventes comerá, peixes com barbatanas e escamas comerá; mas tudo o que não tem barbatanas e escamas abstere-vos de comer, imundo vos será.",

        "Atos 10:28": "E disse-lhes: Vós bem sabeis como é abominável para um homem judeu se ajuntar ou mesmo vir a um estrangeiro; mas Deus me revelou que a nenhum homem considerasse comum ou imundo.",

        "Marcos 7:1-23": "E foram ter com ele os fariseus e alguns dos escribas, que tinham vindo de Jerusalém. E, vendo que alguns dos seus discípulos comiam pão com as mãos impuras, isto é, por lavar, os criticavam. Porque os fariseus e todos os judeus, conservando a tradição dos anciãos, não comem sem lavar as mãos até os cotovelos. Depois, indo do mercado, se não se lavarem, não comem. E muitas outras coisas há que receberam para observar, como lavar os copos, e as taças, e as cadeiras e as camas. E perguntavam-lhe os fariseus e os escribas: Por que não andam os teus discípulos conforme a tradição dos anciãos, mas comem pão com as mãos impuras? Ele, respondendo, disse-lhes: Bem profetizou Isaías acerca de vós, hipócritas, como está escrito: Este povo honra-me com os lábios, mas o seu coração está longe de mim. Em vão, porém, me honram, ensinando doutrinas que são mandamentos de homens. Porquanto, deixando o mandamento de Deus, retendes a tradição dos homens, como o lavar dos jarros e dos copos; e fazeis muitas outras coisas semelhantes a essas. E dizia-lhes: Bem invalidais o mandamento de Deus, para guardardes a vossa tradição. Porque Moisés disse: Honra a teu pai e a tua mãe; e: Quem maldisser o pai ou a mãe, certamente morrerá. Mas vós dizeis: Se um homem disser ao pai ou à mãe: Seja corbã, isso é porém mais do que o dedication; e vós o deixais impedir de dizer: Honra a teu pai ou a tua mãe. E, chamando a si a multidão, disse-lhes: Ouvi-me todos e entendei: Não há nada fora do homem que, entrando nele, o possa contaminar; mas o que sai dele, isso é o que contamina o homem. Porque do interior do coração dos homens saem os maus pensamentos, os adultérios, a prostituição, os furtos, os homicídios, os adultérios, a avareza, as maldades, o engano, a dissimulação, a inveja, a blasfêmia, a soberba, a loucura. Todos estes males procedem de dentro e contaminam o homem.",

        "Mateus 15:1-20": "Então chegaram ao pé de Jesus uns escribas e fariseus, que eram de Jerusalém, dizendo: Por que transgridem os teus discípulos a tradição dos anciãos? pois não lavam as mãos quando comem pão. Ele, porém, respondendo, disse-lhes: E vós, por que transgredis o mandamento de Deus por causa da vossa tradição? Pois Moisés disse: Honra a teu pai e à tua mãe; e: Quem maldisser ao pai ou à mãe, certamente morrerá. Mas vós dizeis: Qualquer que disser ao pai ou à mãe: Seja corbã; esse não precisa honrar de forma alguma a seu pai ou a sua mãe. E assim invalidastes o mandamento de Deus por causa da vossa tradição. Hipócritas, bem profetizou Isaías a vosso respeito, dizendo: Este povo se aproxima de mim com a boca e com os lábios me honra, mas o seu coração está longe de mim. Em vão, porém, me honram, ensinando doutrinas que são mandamentos de homens. E, chamando a si a multidão, disse-lhes: Ouvi e entendei: O que contamina o homem não é o que entra na boca, mas o que sai da boca, isso é o que contamina o homem.",

        "Atos 10:9-28": "E, no dia seguinte, indo eles seu caminho e chegando perto da cidade, subiu Pedro ao terraço para orar, quase à hora sexta. E tendo muita fome, quis comer; e, enquanto lhe preparavam, caiu em êxtase. E viu o céu aberto e um vaso, que descia como um grande lençal atado pelas quatro pontas, e lowered à terra; no qual havia de todos os animais quadrúpedes, e feras, e répteis da terra. E lhe foi dito: Levanta-te, Pedro! Mata e come. Mas Pedro disse: De maneira nenhuma, Senhor; porque nunca comi coisa alguma comum e imunda. E a voz disse-lhe segunda vez: O que Deus purificou, não tu chames comum. E isso aconteceu três vezes; e o vaso tornou a recolher-se ao céu. E estando Pedro perplexo dentro de si mesmo sobre o que seria aquela visão que tinha, eis que lhe disseram: Os homens enviados por Cornélio te perguntaram: E foram à casa de Cornélio um centurião, justo e temente a Deus, e que tem bom testemunho de toda a nação dos judeus, e foi avisado por um santo anjo para te chamar à sua casa e ouvir as tuas palavras. Pedro, pois, descendo, disse: Mas eu sou apenas um homem, e não sou digno de impedir que alguém se aproxime de mim; no entanto, a mensagem que me enviou é que eu não devesse considerar nenhum homem comum ou imundo. Por isso vim sem objeção quando fui chamado. Pergunto, pois, por que razão viestes ter me chamado?",

        "Daniel 1:8-20": "E Daniel propôs no seu coração não se contaminar com a porção das iguarias do rei, nem com o vinho que ele bebia; pediu, pois, ao chefe dos eunucos que lhe concedesse não se contaminar. Ora, Deus concedeu a Daniel misericórdia e benevolência perante o chefe dos eunucos. E disse o chefe dos eunucos a Daniel: Tenho medo de que o meu senhor, o rei, que determinou a vossa comida e a vossa bebida, veja os vossos rostos mais tristes do que os dos jovens que são da vossa idade; entãoareis a minha cara por causa de vós. E disse Daniel ao mordomo, a quem o chefe dos eunucos havia posto sobre Daniel, Hananias, Misael e Azarias: Eu te peço, experimenta os teus servos dez dias; e que se nos dêem legumes a comer e água a beber. Então se vejam os vossos rostos mais favoráveis do que os dos jovens que comem a porção das iguarias do rei; e assim se tratará os vossos servos. E ele consentiu nisso e os experimentou dez dias. E, ao fim dos dez dias, os seus semblantes pareciam mais belos e mais robustos do que todos os jovens que comiam das iguarias do rei. E o mordomo retirava a porção das iguarias deles e o vinho que deviam beber, e lhes dava legumes. E a estes quatro jovens Deus deu o conhecimento e a inteligência em todas as letras e sabedoria; mas Daniel tinha entendimento em toda visão e sonhos. E, ao fim dos dias, depois que o rei mandara que fossem trazidos, o chefe dos eunucos os trouxe diante de Nabucodonosor. E o rei falou com eles; e entre todos eles não foram achados tais como Daniel, Hananias, Misael e Azarias; e por isso ficaram na sua presença. E em toda matéria de sabedoria e inteligência, sobre a qual o rei os inquiriu, os achou dez vezes mais do que todos os magos e encantadores que havia em todo o seu reino.",

        "Romanos 14:17": "Porque o reino de Deus não é comida nem bebida, mas justiça, e paz, e alegria no Espírito Santo.",

        "3 João 2": "Amado, desejo que te vá bem em todas as coisas, e que tenhas saúde, assim como bem vai a tua alma.",

        "Provérbios 20:1": "O vinho é escarnecedor, e a bebida forte, alvoroçadora; e todo aquele que por eles é vencido não é sábio.",

        "Provérbios 23:29-32": "Para quem são os ais? Para quem são os ais? Para quem andam procurando a vinha misturada. Para quem são os ais? Para os que se demoram em buscar o vinho misturado. Quando olhares para o vinho, como se vermelho, quando no copo se deixa brilhar, entra suavemente; mas ao fim morderá como a cobra e picará como a basilisco.",

        "1 Pedro 1:13": "Portanto, cingindo os lombos do vosso entendimento, sede sóbrios e esperai inteiramente na graça que vos está sendo trazida na revelação de Jesus Cristo.",

        "1 Pedro 5:8": "Sede sóbrios e vigiai; porque o vosso adversário, o diabo, anda em derredor, bramando como leão, buscando a quem possa tragar.",

        "1 Timóteo 2:9-10": "Que do mesmo modo as mulheres se ataviem em traje honesto, com pudor e modéstia, não com tranças, ou ouro, ou pérolas, ou vestidos preciosos, mas (como convém a mulheres que fazem profissão de servir a Deus) com boas obras.",

        "1 Pedro 3:3-4": "O enfeite delas não seja o exterior, com o frisado dos cabelos, com o uso de jóias de ouro, ou a compostura de vestes, mas o homem encoberto no coração, no incorruptível traje de um espírito manso e quieto, que é precioso diante de Deus.",

        "1 João 2:15-17": "Não ameis o mundo, nem o que há no mundo. Se alguém ama o mundo, o amor do Pai não está nele. Porque tudo o que há no mundo, a concupiscência da carne, a concupiscência dos olhos e a soberba da vida, não é do Pai, mas é do mundo.",

        "Mateus 28:19-20": "Portanto, ide e ensinai todas as nações, batizando-as em nome do Pai, e do Filho, e do Espírito Santo; ensinando-as a guardar todas as coisas que eu vos tenho mandado; e eis que eu estou convosco todos os dias, até a consumação dos séculos. Amém.",

        "Atos 2:38": "E disse-lhes Pedro: Arrependei-vos, e cada um de vós seja batizado em nome de Jesus Cristo para perdão dos pecados, e recebereis o dom do Espírito Santo.",

        "Romanos 6:3-6": "Ou ignorais que todos quantos fomos batizados em Jesus Cristo fomos batizados na sua morte? De sorte que fomos sepultados com ele pelo batismo na morte; para que, como Cristo ressuscou dos mortos pela glória do Pai, assim andemos nós também em novidade de vida. Porque, se fomos plantados juntamente com ele na semelhança da sua morte, também o seremos na da sua ressurreição.",

        "1 Coríntios 6:12": "Todas as coisas me são lícitas, mas nem todas me convêm. Todas as coisas me são lícitas, mas eu não me deixareis dominar por nenhuma delas.",

        "2 Coríntios 7:1": "Ora, amados, pois que temos tais promessas, purifiquemo-nos de toda imundícia da carne e do espírito, aperfeiçoando a santificação no temor de Deus.",

        // SEÇÃO 11: DÍZIMOS, OFERTAS E FINANÇAS
        "Gênesis 14:20": "E bendito seja o Deus Altíssimo, que entregou os teus inimigos nas tuas mãos. E Abrão lhe deu o dízimo de tudo.",
        "Gênesis 28:22": "E esta pedra que tenho posto por coluna será casa de Deus; e de tudo quanto me deres, certamente te darei o dízimo.",
        "Levítico 27:30": " Também todas as dízimas do campo, da semente do campo, do fruto das árvores, são do Senhor; santas são ao Senhor.",
        "Malaquias 3:8-10": "Roubará o homem a Deus? Todavia vós me roubais, e dizeis: Em que te roubamos? Nos dízimos e nas ofertas. Com maldição sois amaldiçoados, porque a mim me roubais, sim, toda esta nação. Trazei todos os dízimos à casa do tesouro, para que haja mantimento na minha casa, e depois fazei prova de mim nisto, diz o Senhor dos Exércitos, se eu não vos abrir as janelas do céu e não derramar sobre vós uma bênção tal até que não haja lugar suficiente para a recolherdes.",
        "Mateus 23:23": "Ai de vós, escribas e fariseus, hipócritas! pois que dizimais a hortelã, o endro e o cominho, e desprezais o mais importante da lei, o juízo, a misericórdia e a fé; deveis, porém, fazer estas coisas, e não omitir aquelas.",
        "2 Coríntios 9:6-8": "E isto afirmo: que o que semeia pouco, pouco também ceifará; e o que semeia em abundância, em abundância também ceifará. Cada um contribua segundo propôs no seu coração; não com tristeza, nem por constrangimento; porque Deus ama ao que dá com alegria. E Deus é poderoso para fazer abundar em vós toda graça, para que, tendo sempre, em tudo, toda suficiência, abundeis em toda boa obra.",

        // SEÇÃO 12: BATISMO, CEIA E PRÁTICAS LITÚRGICAS
        "Mateus 3:13-17": "Então veio Jesus da Galileia ao Jordão ter com João, para ser batizado por ele. Mas João opunha-se-lhe, dizendo: Eu careço de ser batizado por ti, e vens tu a mim? Jesus, porém, respondendo, disse-lhe: Deixa por agora, porque assim nos convém cumprir toda a justiça. Então ele o permitiu. E, sendo Jesus batizado, saiu logo da água, e eis que se lhe abriram os céus, e viu o Espírito de Deus descendo como pomba e vindo sobre ele; E eis que uma voz dos céus dizia: Este é o meu Filho amado, em quem me comprazo.",
        "João 3:23": "Ora, João batizava também em Enom, junto a Salim, porque havia ali muitas águas; e ali vinham, e eram batizados.",
        "Atos 8:12": "Mas, como cressem em Filipe, que lhes pregava acerca do reino de Deus, e do nome de Jesus Cristo, se batizavam, tanto homens como mulheres.",
        "Atos 8:36-39": "E, indo eles caminhando, chegaram ao pé de alguma água, e disse o eunuco: Eis aqui água; que impede que eu seja batizado? E disse Filipe: É lícito, se crês de todo o coração. E, respondendo ele, disse: Creio que Jesus Cristo é o Filho de Deus. E mandou parar o carro, e desceram ambos à água, tanto Filipe como o eunuco, e o batizou. E, quando saíram da água, o Espírito do Senhor arrebatou a Filipe, e não o viu mais o eunuco; e jubiloso, seguiu o seu caminho.",
        "Atos 8:38-39": "E mandou parar o carro, e desceram ambos à água, tanto Filipe como o eunuco, e o batizou. E, quando saíram da água, o Espírito do Senhor arrebatou a Filipe, e não o viu mais o eunuco; e jubiloso, seguiu o seu caminho.",
        "Colossenses 2:12": "Sepultados com ele no batismo, nele também ressuscitastes pela fé no poder de Deus, que o ressuscitou dentre os mortos.",
        "Marcos 16:16": "Quem crer e for batizado será salvo; mas quem não crer será condenado.",
        "1 Samuel 1:27-28": "Orei por este menino, e o Senhor me concedeu a minha petição que lhe lhe pedi. Eu também o concedo ao Senhor; por todos os dias que ele viver, pertencerá ao Senhor.",
        "Lucas 2:22": "E, cumprindo-se os dias da purificação dela, segundo a lei de Moisés, o levaram a Jerusalém, para o apresentarem ao Senhor.",
        "João 13:1-17": "Antes da festa da páscoa, sabendo Jesus que já era chegada a sua hora de passar deste mundo para o Pai, como havia amado os seus, que estavam no mundo, amou-os até o fim. E, havendo acabado a ceia, quando o diabo já tinha posto no coração de Judas Iscariotes, filho de Simão, que o traísse, Jesus, sabendo que o Pai tinha depositado nas suas mãos todas as coisas, e que havia saído de Deus e ia para Deus, Levantou-se da ceia, tirou as vestes, e, tomando uma toalha, cingiu-se. Depois deitou água na bacia, e começou a lavar os pés aos discípulos, e a enxugar-lhos com a toalha com que estava cingido. Aproximou-se, pois, de Simão Pedro, que lhe disse: Senhor, tu lavas-me os pés a mim? Respondeu Jesus, e disse-lhe: O que eu faço não o sabes tu agora, mas tu o entenderás depois. Disse-lhe Pedro: Nunca me lavarás os pés. Respondeu-lhe Jesus: Se eu te não lavar, não tens parte comigo. Disse-lhe Simão Pedro: Senhor, não somente os meus pés, mas também as mãos e a cabeça. Disse-lhe Jesus: Aquele que já está banhado não necessita de lavar senão os pés, pois no mais todo está limpo. E vós estais limpos, mas não todos. Porque bem sabia ele quem o havia de trair; por isso disse: Nem todos estais limpos. Depois que lhes lavou os pés, e tomou as suas vestes, se assentou outra vez à mesa, e disse-lhes: Entendeis o que vos tenho feito? Vós me chamais Mestre e Senhor, e dizeis bem, porque eu o sou. Ora, se eu, Senhor e Mestre, vos lavei os pés, vós deveis também lavar os pés uns aos outros. Porque eu vos dei o exemplo, para que, como eu vos fiz, façais vós também. Na verdade, na verdade vos digo que não é o servo maior do que o seu senhor, nem o enviado maior do que aquele que o enviou. Se sabeis isto, bem-aventurados sois se o fizerdes.",
        "1 Coríntios 10:16-17": "Porventura o cálice de bênção que abençoamos, não é a comunhão do sangue de Cristo? O pão que partimos, não é a comunhão do corpo de Cristo? Porque nós, sendo muitos, somos um só pão e um só corpo; porque todos participamos do mesmo pão.",
        "1 Coríntios 11:23-29": "Porque eu recebi do Senhor o que também vos ensinei: que o Senhor Jesus, na noite em que foi traído, tomou o pão; E, tendo dado graças, o partiu e disse: Tomai, comei; isto é o meu corpo que é partido por vós; fazei isto em memória de mim. Semelhantemente também, depois de cear, tomou o cálice, dizendo: Este cálice é o novo testamento no meu sangue; fazei isto, todas as vezes que beberdes, em memória de mim. Porque todas as vezes que comerdes este pão e beberdes este cálice, anunciais a morte do Senhor, até que venha. Portanto, qualquer que comer este pão, ou beber o cálice do Senhor indignamente, será culpado do corpo e do sangue do Senhor. Examine-se, pois, o homem a si mesmo, e assim coma deste pão e beba deste cálice. Porque o que come e bebe indignamente, come e bebe para sua própria condenação, não discernindo o corpo do Senhor.",
        "1 Coríntios 11:27-29": "Portanto, qualquer que comer este pão, ou beber o cálice do Senhor indignamente, será culpado do corpo e do sangue do Senhor. Examine-se, pois, o homem a si mesmo, e assim coma deste pão e beba deste cálice. Porque o que come e bebe indignamente, come e bebe para sua própria condenação, não discernindo o corpo do Senhor.",
        "Mateus 26:27-29": "E, tomando o cálice, e dando graças, deu-lho, dizendo: Bebei dele todos, Porque isto é o meu sangue; o sangue do novo testamento, que é derramado por muitos, para remissão dos pecados. E digo-vos que, desde agora, não beberei deste fruto da vide, até aquele dia em que o beba novo convosco no reino de meu Pai.",
        "1 Coríntios 11:23-26": "Porque eu recebi do Senhor o que também vos ensinei: que o Senhor Jesus, na noite em que foi traído, tomou o pão; E, tendo dado graças, o partiu e disse: Tomai, comei; isto é o meu corpo que é partido por vós; fazei isto em memória de mim. Semelhantemente também, depois de cear, tomou o cálice, dizendo: Este cálice é o novo testamento no meu sangue; fazei isto, todas as vezes que beberdes, em memória de mim. Porque todas as vezes que comerdes este pão e beberdes este cálice, anunciais a morte do Senhor, até que venha.",

        // SEÇÃO 13: CASAMENTO, FAMÍLIA E QUESTÕES ÉTICAS
        "Mateus 19:6": "Assim não são mais dois, mas uma só carne. Portanto, o que Deus ajuntou não o separe o homem.",
        "Marcos 10:9": "Portanto, o que Deus ajuntou, não o separe o homem.",
        "Mateus 5:32": "Eu, porém, vos digo que qualquer que repudiar sua mulher, a não ser por causa de prostituição, faz que ela cometa adultério, e qualquer que casar com a repudiada comete adultério.",
        "Mateus 19:9": "Eu vos digo, porém, que qualquer que repudiar sua mulher, não sendo por causa de prostituição, e casar com outra, comete adultério; e o que casar com a repudiada também comete adultério.",
        "1 Coríntios 7:10-15": "Todavia, aos casados, mando, não eu, mas o Senhor, que a mulher não se aparte do marido. Se, porém, se apartar, que fique sem casar, ou que se reconcilie com o marido; e que o marido não deixe a mulher. Mas aos outros digo eu, não o Senhor: Se algum irmão tem mulher descrente, e ela consente em habitar com ele, não a deixe. E se alguma mulher tem marido descrente, e ele consente em habitar com ela, não o deixe. Porque o marido descrente é santificado pela mulher; e a mulher descrente é santificada pelo marido; de outra sorte, os vossos filhos seriam imundos; mas agora são santos. Mas, se o descrente se apartar, aparte-se; porque neste caso o irmão, ou irmã, não está sujeito à servidão; pois Deus chamou-nos em paz.",
        "Gênesis 1:27": "E criou Deus o homem à sua imagem; à imagem de Deus o criou; homem e mulher os criou.",
        "Gênesis 2:24": "Portanto deixará o homem o seu pai e a sua mãe, e apegar-se-á à sua mulher, e serão ambos uma carne.",
        "Mateus 19:4-6": "Ele, porém, respondendo, disse-lhes: Não tendes lido que, no princípio, o Criador os fez homem e mulher, E disse: Portanto, deixará o homem pai e mãe, e se unirá a sua mulher, e serão dois numa só carne? Assim não são mais dois, mas uma só carne. Portanto, o que Deus ajuntou não o separe o homem.",
        "Romanos 1:24-27": "Pelo qual também Deus os entregou às concupiscências de seus corações, à imundícia, para desonrarem os seus corpos entre si; Pois mudaram a verdade de Deus em mentira, e honraram e serviram mais a criatura do que o Criador, que é bendito eternamente. Amém. Por isso Deus os abandonou às paixões infames; porque até as suas mulheres mudaram o uso natural, no contrário à natureza. E, semelhantemente, também os homens, deixando o uso natural da mulher, se inflamaram em sua sensualidade uns para com os outros, homens com homens, cometendo torpeza e recebendo em si mesmos a recompensa que convinha ao seu erro.",
        "1 Coríntios 6:9-11": "Não sabeis que os injustos não hão de herdar o reino de Deus? Não erreis: nem os devassos, nem os idólatras, nem os adúlteros, nem os efeminados, nem os sodomitas, nem os ladrões, nem os avarentos, nem os bêbados, nem os maldizentes, nem os roubadores herdarão o reino de Deus. E é o que alguns têm sido; mas haveis sido lavados, mas haveis sido santificados, mas haveis sido justificados em nome do Senhor Jesus, e pelo Espírito do nosso Deus.",
        "Amós 3:3": "Andarão dois juntos, se não estiverem de acordo?",
        "1 Coríntios 7:39": "A mulher casada está ligada pela lei todo o tempo que o marido vive; mas, se ele falecer, fica livre para casar com quem quiser, contanto que seja no Senhor.",
        "Salmo 139:13-16": "Pois possuíste o meu interior; entreteceste-me no ventre de minha mãe. Eu te louvarei, porque de um modo terrível e tão maravilhoso fui formado; maravilhosas são as tuas obras, e a minha alma o sabe muito bem. Os meus ossos não te foram encobertos, quando no oculto fui feito, e entretecido nas profundezas da terra. Os teus olhos viram o meu corpo ainda informe; e no teu livro todos os meus membros foram escritos; os dias em que me formaste, e nenhum deles havia.",
        "Jeremias 1:5": "Antes que te formasse no ventre da tua mãe, eu te conheci; e antes que saísses da madre, te santifiquei, e às nações te dei por profeta.",

        // SEÇÃO 14: CRIAÇÃO, EVOLUÇÃO E ORIGENS
        "Mateus 24:37-39": "E, como foi nos dias de Noé, assim será também a vinda do Filho do homem. Porquanto, assim como nos dias anteriores ao dilúvio comiam, bebiam, casavam e davam-se em casamento, até ao dia em que Noé entrou na arca, E não o perceberam, até que veio o dilúvio, e os levou a todos, assim será também a vinda do Filho do homem.",
        "2 Pedro 3:5-6": "Eles voluntariamente ignoram isto: que pela palavra de Deus já desde a antiguidade existiram os céus, e a terra, que foi tirada da água e no meio da água subsiste. Pelas quais coisas pereceu o mundo de então, coberto com as águas do dilúvio.",

        // SEÇÃO 16: LIDERANÇA FEMININA
        "Joel 2:28-29": "E há de ser que, depois derramarei o meu Espírito sobre toda a carne, e vossos filhos e vossas filhas profetizarão, os vossos velhos terão sonhos, os vossos jovens terão visões. E também sobre os servos e sobre as servas naqueles dias derramarei o meu Espírito.",
        "Atos 2:17-18": "E nos últimos dias acontecerá, diz Deus, Que do meu Espírito derramarei sobre toda a carne; E os vossos filhos e as vossas filhas profetizarão, Os vossos jovens terão visões, E os vossos velhos terão sonhos; E também do meu Espírito derramarei sobre os meus servos e sobre as minhas servas naqueles dias, e profetizarão.",
        "Atos 18:26": "E ele começou a falar ousadamente na sinagoga. Quando o ouviram Priscila e Áqüila, o levaram consigo e lhe declararam mais precisamente o caminho de Deus.",
        "1 Timóteo 2:11-15": "A mulher aprenda em silêncio, com toda a sujeição. Não permito, porém, que a mulher ensine, nem use de autoridade sobre o marido, mas que esteja em silêncio. Porque primeiro foi formado Adão, depois Eva. E Adão não foi enganado, mas a mulher, sendo enganada, caiu em transgressão. Salvar-se-á, porém, dando à luz filhos, se permanecer com modéstia na fé, no amor e na santificação.",
        "1 Coríntios 14:34-35": "As mulheres estejam caladas nas igrejas, porque lhes não é permitido falar; mas estejam sujeitas, como também ordena a lei. E, se querem aprender alguma coisa, interroguem em casa a seus próprios maridos; porque é vergonhoso que as mulheres falem na igreja.",

        // SEÇÃO 3 - TEXTOS FALTANTES
        "Lucas 24:25-27, 44-47": "E ele lhes disse: Ó néscios e tardios de coração para crer tudo o que os profetas disseram! Porventura não era necessário que o Cristo padecesse essas coisas e entrasse na sua glória? E, começando por Moisés, e por todos os profetas, explicava-lhes o que dele se achava em todas as Escrituras. E disse-lhes: Assim está escrito que o Cristo padeceria e ressuscitaria dentre os mortos ao terceiro dia; e que em seu nome se pregaria o arrependimento e a remissão dos pecados, em todas as nações, começando por Jerusalém.",
        "Êxodo 25:40": "Vê, pois, e faze tudo conforme o modelo que te foi mostrado no monte.",
        "Êxodo 25:8-9": "Me farão um santuário, e eu habitarei no meio deles. Conforme tudo o que eu te mostrar para o modelo do tabernáculo e para a forma de todos os seus utensílios, assim o farão.",

        // SEÇÕES 4, 5, 6 - TEXTOS FALTANTES
        "1 Coríntios 15": "E, agora, quero lembrar a vocês, irmãos, o evangelho que lhes preguei, o qual receberam e no qual estão firmes. Por meio deste evangelho vocês são salvos, se de fato o conservam como eu lhes preguei; caso contrário, vocês têm crido em vão. Pois o que primeiramente lhes transmiti foi o que recebi: que Cristo morreu pelos nossos pecados, segundo as Escrituras, que foi sepultado e que ressuscitou ao terceiro dia, segundo as Escrituras.",

        "1 Tessalonicenses 4:16-17": "Pois, segundo a palavra do Senhor, nós declaramos vocês que estão vivos e os que dormem, seremos arrebatados juntamente com eles nas nuvens, a encontrar o Senhor nos ares. E assim estaremos sempre com o Senhor.",

        "Eclesiastes 9:5-6, 10": "Porque os vivos sabem que hão de morrer, mas os mortos não sabem coisa nenhuma, nem tampouco terão eles recompensa, mas a sua memória ficou entregue às esquecimentos. Também o seu amor, o seu ódio, e a sua inveja já pereceram, e já não têm parte alguma para sempre, em nenhuma das todas as obras que se fazem debaixo do sol. Tudo quanto te vier à mão para fazer, faze-o conforme as tuas forças, porque na sepultura, para onde tu vais, não há obra, nem indústria, nem ciência, nem sabedoria alguma.",

        "1 Samuel 1:22,28": "Porém, quando ela ficou grávida, depois de ter circuncidado o menino, subiu com o marido a oferecer os sacrifícios anuais e o seu voto. Eu, pois, o consagrei ao Senhor, todos os dias que viver; pois ao Senhor foi consagrado.",

        "2 Tessalonicenses 1:9": "Os quais, por castigo, padecerão eterna perdição, ante a face do Senhor e a glória do seu poder.",

        "Apocalipse 20:14-15": "E a morte e o inferno foram lançados no lago de fogo; esta é a segunda morte. E aquele que não foi achado escrito no livro da vida foi lançado no lago de fogo.",

        "Mateus 10:28": "Não temais os que matam o corpo e não podem matar a alma; temei antes aquele que pode fazer perecer no inferno tanto a alma como o corpo.",

        "Romanos 6:23": "Pois o salário do pecado é a morte, mas o dom gratuito de Deus é a vida eterna em Cristo Jesus, nosso Senhor.",

        "Salmos 37:10,20": "Pois ainda um pouco, e o ímpio não existirá; olharás para o seu lugar, e não aparecerá. Mas os ímpios perecerão, e os inimigos do Senhor serão como a gordura dos cordeiros; desaparecerão em fumaça.",

        "Ezequiel 20:12,20": "Além disso, lhes dei também os meus sábados como sinal entre mim e vocês, para que soubéssem que eu sou o Senhor que os santifica. Santifiquem os meus sábados, e eles serão um sinal entre mim e vocês, para que saibam que eu sou o Senhor, o seu Deus.",

        "Romanos 6": "Portanto, não reine o pecado em seus corpos mortais, de modo que vocês obedeçam aos seus desejos pecaminosos. Nem ofereçam os membros de vocês ao pecado como instrumentos de injustiça; ao contrário, ofereçam-se a Deus como quem voltou da morte para a vida, e ofereçam os membros de vocês a ele como instrumentos de justiça.",

        // SEÇÕES 7, 8, 9, 10 - TEXTOS FALTANTES
        "2 Pedro 3:9": "O Senhor não retarda a sua promessa, ainda que alguns a têm por tardia; mas é longânimo para conosco, não querendo que alguns se percam, senão que todos venham a arrepender-se.",

        "2 Tessalonicenses 2:3-12": "Ninguém, de maneira nenhuma, vos engane; porque não será assim sem que antes venha a apostasia, e se manifeste o homem do pecado, o filho da perdição, o qual se opõe, e se levanta contra tudo o que se chama Deus, ou se adora, de sorte que se assentará, como Deus, no templo de Deus, querendo parecer Deus.",

        "Apocalipse 12:7": "E houve batalha no céu: Miguel e os seus anjos batalhavam contra o dragão, e batalhava o dragão e os seus anjos.",

        "Apocalipse 13:17": "E que ninguém pudesse comprar ou vender, senão aquele que tivesse o sinal, ou o nome da besta, ou o número do seu nome.",

        "Apocalipse 14:6": "E vi outro anjo voar pelo meio do céu, e tinha o evangelho eterno, para o proclamar aos que habitam sobre a terra, e a toda a nação, e tribo, e língua, e povo.",

        "Apocalipse 14:7": "Dizendo com grande voz: Temei a Deus, e dai-lhe glória; porque é vinda a hora do seu juízo. E adorai aquele que fez o céu, e a terra, e o mar, e as fontes das águas.",

        "Apocalipse 15-16": "E vi outro grande e admirável sinal no céu: sete anjos, que tinham as sete últimas pragas; porque nelas é consumada a ira de Deus. E vi um como mar de vidro misturado com fogo, e também os que saíram vitoriosos da besta.",

        "Apocalipse 18:1-4": "Depois destas coisas vi descer do céu outro anjo que tinha grande autoridade, e a terra se iluminou com a sua glória. E clamou fortemente com grande voz, dizendo: Caiu, caiu a grande Babilônia, e se tornou morada de demônios. E ouvi outra voz do céu, que dizia: Sai dela, povo meu, para que não sejas participante dos seus pecados.",

        "Apocalipse 20:1-6": "E vi descer do céu um anjo, que tinha a chave do abismo, e uma grande cadeia na sua mão. Ele prendeu o dragão, a antiga serpente, que é o diabo e Satanás, e amarrou-o por mil anos.",

        "Apocalipse 20:5": "Mas os outros mortos não reviveram, até que os mil anos se acabaram. Esta é a primeira ressurreição.",

        "Atos 15": "Então alguns que tinham descido da Judeia ensinavam aos irmãos: Se não vos circuncidardes conforme o uso de Moisés, não podeis ser salvos.",

        "Deuteronômio 14:9-10": "Isto comereis de todo o que há nas águas: tudo o que tem barbatanas e escamas comereis; Mas tudo o que não tiver barbatanas nem escamas não comereis; imundo vos será.",

        "Efésios 5:18": "E não vos embriagueis com vinho, em que há contenda, mas enchei-vos do Espírito.",

        "Filipenses 4:8": "Finalmente, irmãos, tudo o que é verdadeiro, tudo o que é honesto, tudo o que é justo, tudo o que é puro, tudo o que é amável, tudo o que é de boa fama, se há alguma virtude, e se há algum louvor, nisso pensai.",

        "Gênesis 1:2": "E a terra era sem forma e vazia; e havia trevas sobre a face do abismo; e o Espírito de Deus se movia sobre a face das águas.",

        "Gênesis 1:29": "E disse Deus: Eis que vos tenho dado toda a erva que dá semente, que está sobre a face de toda a terra; e toda a árvore em que há fruto de árvore que dá semente, ser-vos-á para mantença.",

        "Gênesis 9:3-4": "Tudo o que se move, que é vivente, será para vosso mantença; tudo vo-lo tenho dado como a erva verde. A carne, porém, com a sua vida, isto é, com o seu sangue, não comereis.",

        "Hebreus 1:4-8, 13": "Feito tanto mais excelente do que os anjos, quanto herdou mais excelente nome do que eles. Porque, a qual dos anjos disse jamais: Tu és meu Filho, hoje te gerei eu? E outra vez: Eu lhe serei por Pai, e ele me será por Filho?",

        "Hebreus 2:14, 17": "E, visto como os filhos participam da carne e do sangue, também ele participou das mesmas coisas, para que pela morte aniquilasse o que tinha o império da morte, isto é, o diabo.",

        "João 16:13": "Mas, quando vier aquele Espírito de verdade, ele vos guiará em toda a verdade; porque não falará de si mesmo, mas dirá tudo o que tiver ouvido, e vos anunciará o que há de vir.",

        "Levítico 11:9-12": "Isto comereis de tudo o que há nas águas: tudo o que tem barbatanas e escamas nas águas, nos mares e nos rios, isso comereis. Mas todo o que não tem barbatanas nem escamas, nos mares e nos rios, todo o réptil das águas, e toda a alma vivente que há nas águas, estes serão para vós em abominação.",

        "Levítico 16:8": "E Arão lançará sortes sobre os dois bodes; uma pelo Senhor, e a outra pelo bode expiatório.",

        "Mateus 28:19": "Portanto ide, fazei discípulos de todas as nações, batizando-os em nome do Pai, e do Filho, e do Espírito Santo.",

        "Êxodo 20:11": "Porque em seis dias fez o Senhor os céus e a terra, o mar e tudo que neles há, e ao sétimo dia descansou; por isso o Senhor abençoou o dia de sábado e o santificou.",

        "Êxodo 20:1-6": "Então, falou Deus todas estas palavras: Eu sou o Senhor, teu Deus, que te tirei da terra do Egito, da casa da servidão. Não terás outros deuses diante de mim. Não farás para ti imagem de escultura, nem alguma semelhança do que há em cima nos céus, nem em baixo na terra, nem nas águas debaixo da terra. Não te encurvarás a elas nem as servirás; porque eu, o Senhor, teu Deus, sou Deus zeloso, que visito a maldade dos pais nos filhos até a terceira e quarta geração daqueles que me aborrecem e faço misericórdia em milhares aos que me amam e guardam os meus mandamentos.",

        "2 Coríntios 3:3": "Manifestando-vos que sois a carta de Cristo, ministrada por nós, e escrita, não com tinta, mas com o Espírito do Deus vivo, não em tábuas de pedra, mas em tábuas de carne do coração.",

        "2 Coríntios 3:6": "O qual nos fez também capazes de ser ministros de um novo testamento, não da letra, mas do espírito; porque a letra mata e o espírito vivifica.",

        "Atos 15:1": "Então, alguns que tinham descido da Judeia ensinavam aos irmãos: Se não vos circuncidardes conforme o uso de Moisés, não podeis ser salvos.",

        "Atos 15:5": "Então, se levantaram alguns da seita dos fariseus, que tinham crido, dizendo: É necessário circuncidá-los e mandar-lhes que guardem a lei de Moisés.",

        "1 Coríntios 12": "Porque a respeito dos dons espirituais, não quero, irmãos, que sejais ignorantes. Vós bem sabeis que éreis gentios, e conduzidos aos ídolos mudos, conforme éreis guiados.",

        // SEÇÕES 11-16 - TEXTOS FALTANTES
        "Levítico 27:30, 32": "Todos os dízimos da terra, das sementes do campo e do fruto das árvores, são do Senhor; são santos ao Senhor. Mas, se alguém de qualquer modo resgatar alguma coisa dos seus dízimos, acrescentará a sua quinta parte sobre ela.",

        "2 Coríntios 6:14": "Não vos prendais a um jugo desigual com os infiéis. Pois que sociedade tem a justiça com a injustiça? E que comunhão tem a luz com as trevas?",

        "Êxodo 20:13": "Não matarás.",

        "Gênesis 1:1-2:3": "No princípio, Deus criou os céus e a terra. E a terra era sem forma e vazia; e havia trevas sobre a face do abismo; e o Espírito de Deus se movia sobre a face das águas. E disse Deus: Haja luz. E houve luz. E viu Deus que era boa a luz; e fez Deus separação entre a luz e as trevas. E Deus chamou à luz Dia, e às trevas chamou Noite. E foi a tarde e a manhã: o dia primeiro. E disse Deus: Haja uma expansão no meio das águas, e haja separação entre águas e águas. E fez Deus a expansão, e fez separação entre as águas que estavam debaixo da expansão e as águas que estavam sobre a expansão. E assim foi. E chamou Deus à expansão Céus. E foi a tarde e a manhã: o dia segundo. E disse Deus: Ajuntem-se as águas debaixo dos céus num lugar; e apareça a porção seca. E assim foi. E chamou Deus à porção seca Terra, e ao ajuntamento das águas chamou Mares. E viu Deus que era bom. E disse Deus: Produza a terra erva verde, erva que dê semente, árvore frutífera que dê fruto segundo a sua espécie, cuja semente está nela sobre a terra. E assim foi. E a terra produziu erva, erva dando semente conforme a sua espécie, e árvore frutífera, cuja semente está nela conforme a sua espécie. E viu Deus que era bom. E foi a tarde e a manhã: o dia terceiro. E disse Deus: Haja luminares na expansão dos céus, para separar o dia da noite; e sejam eles para sinais e para tempos determinados e para dias e anos. E sejam para luminares na expansão dos céus, para iluminar a terra. E assim foi. E fez Deus os dois grandes luminares: o luminar maior para governar o dia, e o luminar menor para governar a noite; e fez as estrelas. E Deus os pôs na expansão dos céus para iluminar a terra, e para governar o dia e a noite, e para fazer separação entre a luz e as trevas. E viu Deus que era bom. E foi a tarde e a manhã: o dia quarto. E disse Deus: Produzam as águas abundantemente répteis de alma vivente; e voem as aves acima da terra na expansão dos céus. E Deus criou as grandes baleias, e todo réptil de alma vivente que as águas abundantemente produziram conforme as suas espécies, e toda ave de penas segundo a sua espécie. E viu Deus que era bom. E Deus os abençoou, dizendo: Frutificai e multiplicai-vos, e enchei as águas nos mares; e as aves se multipliquem na terra. E foi a tarde e a manhã: o dia quinto. E disse Deus: Produza a terra alma vivente conforme a sua espécie: gado, e répteis, e bestas-feras da terra conforme a sua espécie. E assim foi. E Deus fez as bestas-feras da terra conforme a sua espécie, e o gado conforme a sua espécie, e todo o réptil da terra conforme a sua espécie. E viu Deus que era bom. E disse Deus: Façamos o homem à nossa imagem, conforme a nossa semelhança; e domine sobre os peixes do mar, e sobre as aves dos céus, e sobre o gado, e sobre toda a terra, e sobre todo réptil que se move sobre a terra. E criou Deus o homem à sua imagem; à imagem de Deus o criou; macho e fêmea os criou. E Deus os abençoou e Deus lhes disse: Frutificai e multiplicai-vos, e enchei a terra, e sujeitai-a; e dominai sobre os peixes do mar, e sobre as aves dos céus, e sobre todo animal que se move sobre a terra. E disse Deus: Eis que vos tenho dado toda a erva que dá semente, que está sobre a face de toda a terra; e toda árvore em que há fruto de árvore que dá semente, ser-vos-á para mantença. E a todo animal da terra, e a toda ave dos céus, e a todo réptil da terra, em que há alma vivente, toda a erva verde lhes será para mantença. E assim foi. E viu Deus tudo quanto tinha feito, e eis que era muito bom. E foi a tarde e a manhã: o dia sexto. E assim foram os céus e a terra e todo o seu exército. E, havendo Deus acabado no dia sétimo a sua obra, que tinha feito, descansou no sétimo dia de toda a sua obra, que tinha feito. E abençoou Deus o dia sétimo e o santificou; porque nele descansou de toda a sua obra, que Deus criara e fizera.",

        "Gênesis 6:9": "Estas são as gerações de Noé. Noé era homem justo e perfeito em suas gerações; Noé andava com Deus.",

        "Romanos 5:12-21": "Pelo que, como por um homem entrou o pecado no mundo, e pelo pecado a morte, assim também a morte passou a todos os homens, por isso que todos pecaram. Porque até à lei estava o pecado no mundo, mas o pecado não é imputado, não havendo lei. A morte reinou desde Adão até Moisés, até sobre aqueles que não tinham pecado a transgressão de uma lei, à semelhança da transgressão de Adão, o qual é a figura daquele que havia de vir. Mas não é assim o dom gratuito como a ofensa; porque, se pela ofensa de um morreram muitos, muito mais a graça de Deus e o dom pela graça, que é de um só homem, Jesus Cristo, abundou sobre muitos. E não é assim o dom como a ofensa, que veio por um só que pecou; porque o juízo veio de uma só ofensa para condenação, mas o dom gratuito veio de muitas ofensas para justificação. Porque, se pela ofensa de um só, a morte reinou por esse, muito mais os que recebem a abundância da graça e do dom da justiça reinarão em vida por um só, Jesus Cristo. Pois assim como por uma só ofensa veio o juízo sobre todos os homens para condenação, assim também por um só ato de justiça veio a graça sobre todos os homens para justificação de vida. Porque, como pela desobediência de um só homem, muitos foram feitos pecadores, assim pela obediência de um, muitos serão feitos justos. Veio, porém, a lei para que a ofensa abundasse; mas, onde o pecado abundou, superabundou a graça; para que, assim como o pecado reinou na morte, assim também a graça reinasse pela justiça para a vida eterna, por Jesus Cristo, nosso Senhor.",

        "Juízes 4:1-5": "E os filhos de Israel tornaram a fazer o que era mal aos olhos do Senhor, depois da morte de Eúde. E o Senhor os vendeu na mão de Jabim, rei de Canaã, que reinou em Hazor; e Sísera era o capitão do seu exército, o qual então habitava em Harosete dos Gentios. E os filhos de Israel clamaram ao Senhor, porquanto Jabim tinha novecentos carros de ferro, e por vinte anos oprimia violentamente os filhos de Israel. E Débora, mulher profetisa, mulher de Lapidote, julgava a Israel naquele tempo. Ela assentava-se sob a palmeira de Débora, entre Ramá e Betel, na montanha de Efraim; e os filhos de Israel subiam a ela a juízo.",

        "Romanos 16:1-27": "Recomendo-vos, pois, nossa irmã Febe, que é serva da igreja que está em Cencréia, para que a recebais no Senhor, como convém aos santos, e que a ajudeis em qualquer coisa que de vós necessitar; porque ela tem ajudado a muitos, e também a mim mesmo. Saudai a Priscila e a Áqüila, meus cooperadores em Cristo Jesus, os quais pela minha vida expuseram as suas cabeças; o que não só eu lhes agradeço, mas também todas as igrejas dos gentios. Saudai também a igreja que está em sua casa. Saudai a Epêneto, meu amado, que é as primícias da Acaia em Cristo. Saudai a Maria, que trabalhou muito por nós. Saudai a Andrônico e a Júnias, meus parentes e meus companheiros na prisão, os quais são ilustres entre os apóstolos e foram antes de mim em Cristo. Saudai a Amplias, meu amado no Senhor. Saudai a Urbano, nosso cooperador em Cristo, e a Estáquis, meu amado. Saudai a Apeles, aprovado em Cristo. Saudai aos da família de Aristóbulo. Saudai a Herodião, meu parente. Saudai aos da família de Narciso, os que estão no Senhor. Saudai a Trifena e a Trifosa, as quais trabalham no Senhor. Saudai à amada Pérside, a qual trabalhou muito no Senhor. Saudai a Rufo, eleito no Senhor, e a sua mãe e minha. Saudai a Asíncrito, a Flegonte, a Hermes, a Pátrobas, a Hermas, e aos irmãos que estão com eles. Saudai a Filólogo e a Júlia, a Nereu e a sua irmã, e a Olimpas, e a todos os santos que estão com eles. Saudai-vos uns aos outros com ósculo santo. As igrejas de Cristo vos saúdam. E rogo-vos, irmãos, que noteis os que promovem dissensões e escândalos contra a doutrina que aprendestes; desviai-vos deles. Porque os tais não servem a nosso Senhor Jesus Cristo, mas ao seu ventre; e com suaves palavras e lisonjas enganam os corações dos simples. E a vossa obediência é conhecida de todos. Comprazo-me, portanto, em vós; e quero que sejais sábios no bem, e simples no mal. E o Deus de paz esmagará em breve Satanás debaixo dos vossos pés. A graça de nosso Senhor Jesus Cristo seja convosco. Amém. Saúdam-vos Timóteo, meu cooperador, e Lúcio, e Jasom, e Sosípatro, meus parentes. Eu, Tércio, que esta carta escrevi, vos saúdo no Senhor. Saúda-vos Gaio, meu hospedeiro, e de toda a igreja. Saúda-vos Erasto, o tesoureiro da cidade, e a Quarto, irmão. A graça de nosso Senhor Jesus Cristo seja com todos vós. Amém. Ora, aquele que é poderoso para vos confirmar segundo o meu evangelho e a pregação de Jesus Cristo, conforme a revelação do mistério que desde tempos eternos esteve oculto, mas agora se manifestou e foi notificado pelas Escrituras dos profetas, segundo o mandamento do Deus eterno, a todas as nações para obediência da fé; ao único Deus, sábio, seja dada glória por Jesus Cristo para sempre. Amém.",
    };

    // Função simples para abrir popup
    function abrirPopupBiblico(reference, text) {
        const overlay = document.createElement('div');
        overlay.id = 'biblical-overlay-unico';
        overlay.style.position = 'fixed';
        overlay.style.zIndex = '9999';
        overlay.style.left = '0';
        overlay.style.top = 'var(--header-height)';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.9)';
        overlay.style.display = 'flex';
        overlay.style.justifyContent = 'center';
        overlay.style.alignItems = 'center';
        overlay.style.padding = '20px';
        overlay.style.boxSizing = 'border-box';
        overlay.style.overflow = 'hidden';

        const pageAside = document.querySelector('aside');
        const previousHtmlOverflow = document.documentElement.style.overflow;
        const previousBodyOverflow = document.body.style.overflow;

        function syncOverlayBounds() {
            let rightGap = 0;

            if (pageAside) {
                const asideRect = pageAside.getBoundingClientRect();

                if (asideRect.width > 0 && asideRect.right > asideRect.left) {
                    rightGap = Math.max(0, asideRect.width);
                }
            }

            overlay.style.width = `calc(100vw - ${rightGap}px)`;
            overlay.style.height = 'calc(100vh - var(--header-height))';
            overlay.style.height = 'calc(100dvh - var(--header-height))';
        }

        const modal = document.createElement('div');
        modal.style.cssText = 'background:white;border-radius:12px;padding:24px;max-width:min(90%, 600px);max-height:90%;overflow-y:auto;position:relative;font-family:Arial,sans-serif;box-shadow:0 4px 20px rgba(0,0,0,0.5);';

        const fechar = document.createElement('button');
        fechar.innerHTML = '✕';
        fechar.style.cssText = 'position:absolute;top:18px;right:18px;color:#003366;font-size:32px;font-weight:bold;cursor:pointer;user-select:none;line-height:1;width:40px;height:40px;display:flex;align-items:center;justify-content:center;border:none;border-radius:999px;background:rgba(0, 0, 0, 0.08);';

        const titulo = document.createElement('h3');
        titulo.textContent = reference;
        titulo.style.cssText = 'margin:0 0 16px 0;color:#003366;font-size:1.4em;';

        const texto = document.createElement('p');
        texto.innerHTML = text;
        texto.style.cssText = 'line-height:1.6;color:#333;margin:0 0 20px 0;';

        modal.appendChild(fechar);
        modal.appendChild(titulo);
        modal.appendChild(texto);
        overlay.appendChild(modal);

        const escHandler = function(e) {
            if (e.key === 'Escape') {
                closeOverlay();
            }
        };

        function closeOverlay() {
            if (document.body.contains(overlay)) {
                document.body.removeChild(overlay);
            }

            document.documentElement.style.overflow = previousHtmlOverflow;
            document.body.style.overflow = previousBodyOverflow;
            document.removeEventListener('keydown', escHandler);
            window.removeEventListener('resize', syncOverlayBounds);
        }

        fechar.onclick = closeOverlay;

        overlay.onclick = function(e) {
            if (e.target === overlay) {
                closeOverlay();
            }
        };

        syncOverlayBounds();
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', escHandler);
        window.addEventListener('resize', syncOverlayBounds);
        document.body.appendChild(overlay);
    }

    // Inicializar links - APENAS quando clicados
    function iniciarLinksBiblicos() {
        const links = document.querySelectorAll('.biblical-reference');

        links.forEach(function(link) {
            // Remover todos os eventos anteriores
            const novoLink = link.cloneNode(true);
            link.parentNode.replaceChild(novoLink, link);

            novoLink.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const ref = novoLink.getAttribute('data-reference');
                const texto = biblicalPassages[ref];

                if (texto) {
                    abrirPopupBiblico(ref, texto);
                } else {
                    alert('Texto não encontrado: ' + ref);
                }
            });
        });
    }

    // Iniciar apenas quando DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', iniciarLinksBiblicos);
    } else {
        iniciarLinksBiblicos();
    }

    console.log('Sistema bíblico isolado carregado');
})();
