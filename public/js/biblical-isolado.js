/**
 * SISTEMA DE LINKS BÍBLICOS - ISOLADO E SEGURO
 * Não modifica layout, não afeta DOM desnecessariamente
 */

(function() {
    'use strict';

    // Textos bíblicos NVI - SEÇÃO 7 (SÁBADO E LEI DE DEUS)
    const biblicalPassages = {
        // SEÇÃO 7 - SÁBADO E LEI DE DEUS
        "Gênesis 2:1-3": "Assim foram acabados os céus e a terra, e todo o seu exército. No sétimo dia Deus já havia concluído a obra que realizara, e nesse dia descansou. Deus abençoou o sétimo dia e o santificou, porque nele descansou de toda a obra que realizara na criação.",
        "Êxodo 20:8-11": "Lembre-se do dia de sábado, para santificá-lo. Trabalhe seis dias e neles realize toda a sua obra. Mas o sétimo dia é o sábado dedicado ao Senhor, ao seu Deus. Não faça nenhum trabalho, nem você, nem seus filhos ou filhas, nem seus servos ou servas, nem seus animais, nem os estrangeiros que vivem nas suas cidades. Pois em seis dias o Senhor fez os céus e a terra, o mar e tudo o que neles existe, mas descansou no sétimo dia. Portanto, o Senhor abençoou o dia de sábado e o santificou.",
        "Lucas 4:16": "Ele foi para Nazaré, onde havia sido criado, e no dia de sábado entrou na sinagoga, como era seu costume. E levantou-se para ler.",
        "Marcos 2:27-28": "Então lhes disse: O sábado foi feito por causa do homem, e não o homem por causa do sábado. Assim, o Filho do homem é Senhor até mesmo do sábado.",
        "Atos 13:14": "De Perge eles prosseguiram para Antioquia da Pisídia e, no sábado, entraram na sinagoga e se assentaram.",
        "Atos 13:42-44": "Quando Paulo e Barnabé saíram da sinagoga, o povo insistiu com eles para que no sábado seguinte lhes falassem mais sobre estas coisas. No sábado seguinte, quase toda a cidade se reuniu para ouvir a palavra do Senhor.",
        "Atos 16:13": "No sábado saímos da cidade para a margem do rio, onde esperávamos encontrar um lugar de oração.",
        "Atos 17:2": "Conforme o seu costume, Paulo foi à sinagoga e por três sábados discutiu com eles com base nas Escrituras.",
        "Atos 18:4": "Cada sábado ele debatia na sinagoga e convencia judeus e gregos.",
        "Romanos 14:5": "Há quem considere um dia mais sagrado que outro; há quem considere todos os dias iguais. Cada um deve estar plenamente convicto em sua própria mente.",
        "Romanos 14:1-6": "Aceitem o que é fraco na fé, sem discutir assuntos controvertidos. Um crê que pode comer de tudo; já outro, cuja fé é fraca, come apenas vegetais. Quem come de tudo não deve desprezar quem não come, e quem não come de tudo não deve condenar quem come, pois Deus o aceitou. Quem é você para julgar o servo alheio? É para o seu senhor que ele está de pé ou cai. E ele permanecerá em pé, pois o Senhor é capaz de fazê-lo firmar-se.",
        "Romanos 7:12": "Assim, a lei é santa, e o mandamento é santo, justo e bom.",
        "Romanos 3:31": "Anulamos, pois, a lei pela fé? De maneira nenhuma! Ao contrário, confirmamos a lei.",
        "Deuteronômio 5:15": "Lembre-se que você foi escravo no Egito e que o Senhor, o seu Deus, o tirou de lá com mão poderosa e com braço forte. Por isso o Senhor, o seu Deus, ordenou que você guardasse o dia de sábado.",
        "Colossenses 2:16-17": "Portanto, não permitam que ninguém os julgue pelo que comem ou bebem, ou com relação a alguma festa religiosa, ou à celebração da lua nova ou aos dias de sábado. Essas coisas são sombras do que haveria de vir; a realidade, porém, encontra-se em Cristo.",
        "1 Crônicas 23:31": "E apresentavam os holocaustos ao Senhor nos sábados, nas luas novas e nas festas fixas, conforme o número ordenado.",
        "2 Crônicas 2:4": "Eis que vou construir um templo para o nome do Senhor, meu Deus, e lhe dedicarei, para queimar perante ele incenso aromático, apresentar os pães da proposição contínua e preparar os holocaustos da manhã e da tarde, nos sábados, nas luas novas e nas festas fixas do Senhor, nosso Deus.",
        "2 Crônicas 31:3": "O rei contribuiu com sua própria provisão para os holocaustos da manhã e da tarde, e para os holocaustos dos sábados, das luas novas e das festas fixas, como está escrito na Lei do Senhor.",
        "Ezequiel 45:17": "E caberá ao príncipe dar os holocaustos, as ofertas de cereais e as ofertas de libação nas festas, nas luas novas e nos sábados, em todas as festas fixas da casa de Israel.",
        "Gálatas 4:9-11": "Mas agora que vocês conhecem a Deus, ou melhor, sendo conhecidos por Deus, como é que estão voltando àqueles mesmos princípios fracos e pobres? Querem estar submetidos a eles outra vez? Vocês observam dias especiais, meses, estações e anos! Receio que tenha trabalhado inutilmente para vocês.",
        "Tiago 2:10-12": "Pois quem obedece a toda a Lei, mas tropeça em apenas um ponto, torna-se culpado de quebrá-la inteiramente. Pois aquele que disse: 'Não cometa adultério', também disse: 'Não mate'. Se você não comete adultério, mas comete assassinato, tornou-se transgressor da Lei. Falem e ajam como quem vai ser julgado pela lei da liberdade.",
        "Romanos 6:3-6": "Ou não estão cientes de que todos nós fomos batizados em Cristo Jesus para ser batizados em sua morte? Portanto, fomos sepultados com ele na morte por meio do batismo, a fim de que, assim como Cristo foi ressuscitado dos mortos mediante a glória do Pai, também nós vivamos uma vida nova. Se fomos unidos a ele na morte como ele, certamente seremos unidos a ele na ressurreição. Pois sabemos que o nosso velho homem foi crucificado com ele, para que o corpo do pecado seja destruído, e não mais sejamos escravos do pecado.",
        "Colossenses 2:12": "Tendo sido sepultados com ele no batismo, vocês também foram ressuscitados com ele mediante a fé no poder de Deus que ressuscitou a Cristo dentre os mortos.",
        "Atos 20:7": "No primeiro dia da semana, estando nós reunidos para partir o pão, Paulo conversava com eles, visto que tinha de partir no dia seguinte; e prolongou o discurso até a meia-noite.",
        "1 Coríntios 16:2": "No primeiro dia da semana, cada um de vocês deve separar uma quantia, em conformidade com a sua renda, guardando-a, para que não seja preciso fazer coletas quando eu chegar.",
        "Êxodo 31:18": "Quando o Senhor terminou de falar com Moisés no monte Sinai, deu-lhe as duas tábuas da aliança, tábuas de pedra, escritas pelo dedo de Deus.",
        "Deuteronômio 10:1-5": "Naquele momento o Senhor me disse: 'Corte duas tábuas de pedra, como as primeiras, e suba ao monte a mim. E também faça uma arca de madeira. Naquelas tábuas eu escreverei as palavras que estavam nas primeiras tábuas, que você quebrou, e você as colocará na arca.' Então fiz uma arca de madeira de acácia, cortei duas tábuas de pedra, como as primeiras, e subi ao monte com as duas tábuas nas mãos. O Senhor escreveu nas tábuas, como havia escrito antes, os Dez Mandamentos, que ele havia proclamado a vocês no monte, do meio do fogo, no dia da assembleia. E o Senhor as deu a mim. Então desci do monte e coloquei as tábuas na arca que eu tinha feito, conforme o Senhor tinha me ordenado; e ali estão.",
        "Mateus 22:37-40": "Jesus respondeu: 'Ame o Senhor, o seu Deus, de todo o seu coração, de toda a sua alma e de todo o seu entendimento'. Este é o primeiro e maior mandamento. E o segundo é semelhante a ele: 'Ame o seu próximo como a si mesmo'. Destes dois mandamentos dependem toda a Lei e os Profetas.",
        "Romanos 13:8-10": "Não devam nada a ninguém, a não ser o amor de uns pelos outros, pois aquele que ama seu próximo tem cumprido a Lei. Pois estes mandamentos: 'Não cometa adultério', 'Não mate', 'Não furte', 'Não cobiçe', e qualquer outro mandamento, todos se resumem neste único: 'Ame o seu próximo como a si mesmo'. O amor não pratica o mal contra o próximo. Portanto, o amor é o cumprimento da Lei.",
        "Deuteronômio 31:24-26": "Depois que Moisés terminou de escrever num livro as palavras desta lei do começo ao fim, deu esta ordem aos levitas que transportavam a arca da aliança do Senhor: 'Tomem este Livro da Lei e coloquem ao lado da arca da aliança do Senhor, do seu Deus. Ela servirá de testemunha contra vocês.'",
        "Hebreus 9:9-10": "Isso é uma ilustração para o tempo presente, indicando que as ofertas e os sacrifícios oferecidos não eram capazes de tornar perfeita a consciência do adorador. Eles consistiam apenas em ordenanças externas, como alimentos, bebidas e diversas cerimônias de purificação.",
        "Hebreus 10:1": "A Lei tem apenas uma sombra dos bens futuros, e não a sua realidade. Por isso, ela é incapaz de aperfeiçoar, mediante os mesmos sacrifícios repetidos ano após ano, aqueles que se aproximam de Deus.",
        "Efésios 2:8-9": "Pois vocês são salvos pela graça, por meio da fé, e isto não vem de vocês, é dom de Deus; não por obras, para que ninguém se glorie.",
        "Efésios 2:10": "Pois somos criação de Deus, feitos em Cristo Jesus para fazermos boas obras, as quais Deus preparou de antemão para que nós as praticássemos.",
        "João 14:15": "Se vocês me amam, obedecerão aos meus mandamentos.",
        "Romanos 7:4": "Assim, meus irmãos, vocês também morreram para a lei por meio do corpo de Cristo, para pertencerem a outro, àquele que ressuscitou dentre os mortos, a fim de que frutifiquemos para Deus.",
        "Atos 15:1-29": "Alguns homens desceram da Judeia para Antioquia e ensinavam aos irmãos: 'Se vocês não forem circuncidados conforme o costume ensinado por Moisés, não podem ser salvos.' Isso trouxe grande discussão e debate entre Paulo e Barnabé e os outros. Então Paulo e Barnabé e alguns outros foram designados para subir a Jerusalém e tratar dessa questão com os apóstolos e os presbíteros. [...] Os apóstolos e os presbíteros, com toda a igreja, decidiram escolher alguns de entre eles e enviá-los a Antioquia com Paulo e Barnabé.",
        "Gálatas 5:2-6": "Ouçam bem eu, Paulo: se vocês se deixarem circuncidar, Cristo de nada servirá para vocês. Novamente declaro a todo homem que se deixa circuncidar que está obrigado a cumprir toda a lei. Vocês que procuram ser justificados pela lei, separaram-se de Cristo; caíram da graça. Mas, pelo Espírito, aguardamos com firmeza a esperança da justiça. Porque em Cristo Jesus nem circuncisão nem incircuncisão têm efeito, mas sim a fé que atua pelo amor.",
        "Levítico 23": "O Senhor disse a Moisés: 'Fale aos israelitas e diga-lhes: São as festas fixas do Senhor, as quais vocês proclamarão como assembleias santas. Estas são as minhas festas fixas: O sábado...' [Contém as festas religiosas de Israel, incluindo a Páscoa, Pentecostes, Festa das Trombetas, Dia da Expiação e Festa dos Tabernáculos, além dos sábados cerimoniais]",
        "Levítico 25": "O Senhor disse a Moisés no monte Sinai: 'Fale aos israelitas e diga-lhes: Quando vocês entrarem na terra que eu lhes dou, a própria terra terá um sábado de descanso para o Senhor.' [Estabelece o ano sabático a cada sete anos e o ano do jubileu a cada cinquenta anos]",
        "Atos 15:21": "Pois Moisés há muito tempo tem em cada cidade quem o pregue, sendo lido nas sinagogas todos os sábados.",
        "2 Coríntios 3:7": "O ministério que trouxe morte e foi gravado com letras em pedras veio com tal glória que os israelitas não podiam fitar o rosto de Moisés, por causa da glória radiante do seu rosto, glória essa que estava se desvanecendo.",
        "Jeremias 31:33": 'Esta é a aliança que farei com a casa de Israel depois daqueles dias, declara o Senhor. Porei a minha lei no seu interior, e a escreverei nos seus corações. Serei o Deus deles, e eles serão o meu povo.',
        "Hebreus 8:10": "Esta é a aliança que farei com a casa de Israel depois daqueles dias, declara o Senhor. Porei as minhas leis em suas mentes e as escreverei em seus corações. Serei o Deus deles, e eles serão o meu povo.",
        "Isaías 58:13-14": "Se vocês comem o meu dia santo e consideram importante o meu sábado, e se o honram, não seguindo os seus próprios caminhos, nem fazendo o que bem lhes parecer, nem falando palavras vãs, então vocês se deliciarão no Senhor, e eu os farei cavalgar sobre as alturas da terra e os farei desfrutar a herança de Jacó, seu pai. A boca do Senhor falou.",
        "Mateus 12:1-12": "Naquele tempo Jesus passou pelas searas num sábado. Seus discípulos tiveram fome e começaram a colher espigas de cereal e comê-las. Quando os fariseus viram isso, disseram a Jesus: 'Olha! Os teus discípulos estão fazendo o que não é permitido fazer no sábado!' Jesus respondeu: 'Não vocês leram o que Davi fez quando ele e seus companheiros tiveram fome? Ele entrou na casa de Deus, e ele e seus companheiros comeram os pães da proposição, que não lhes era permitido comer, mas apenas aos sacerdotes. Ou não vocês leram na Lei que no sábado os sacerdotes no templo profanam o sábado e contudo não são culpados? Eu lhes digo que aqui está alguém maior que o templo. Se vocês soubessem o que significa: 'Desejo misericórdia, não sacrifícios', não teriam condenado inocentes. Pois o Filho do homem é Senhor do sábado.'",
        "Êxodo 31:14-15": "Guardem o sábado, pois é santo para vocês. Quem anyone o profanar terá que ser executado. Quem fizer algum trabalho nesse dia será eliminado do meio do seu povo. Seis dias se trabalhará, mas o sétimo dia é sábado de descanso, consagrado ao Senhor. Quem fizer algum trabalho no sábado será morto.",
        "Êxodo 35:2-3": "Trabalhem seis dias, mas o sétimo dia será para vocês um dia santo, um sábado de descanso consagrado ao Senhor. Quem fizer algum trabalho nesse dia terá que ser morto. Não acendam fogo em nenhuma das suas habitações no dia de sábado.",
        "Êxodo 16:22-23": "No sexto dia eles recolheram o dobro, dois ômeres para cada pessoa, e todos os líderes da comunidade vieram e relataram isso a Moisés. Ele lhes disse: 'Isto é o que o Senhor ordenou: Amanhã será dia de descanso, sábado santo consagrado ao Senhor. Assem o que vocês quiserem assar e cozam o que vocês quiserem cozinhar. Guardem tudo o que sobrar e guardem para a manhã.'",
        "Neemias 13:15-22": "Naqueles dias vi em Judá homens que pisavam uvas em lagares no sábado e traziam feixes de trigo, carregando-os sobre jumentos, juntamente com vinho, uvas, figos e toda espécie de cargas, e as traziam para Jerusalém no dia de sábado. Eu os adverti para que não vendessem as suas mercadorias naquele dia. [...] Desde aqueles dias, os levitas tinham ordem de trazer as contribuições, os dízimos e os primeiros frutos para os depósitos. Havia alegria em Judá por causa dos sacerdotes e levitas que serviam, pois observaram fielmente os preceitos do seu Deus e da purificação. [...] Eu adverti os levitas para que se purificassem e viessem guardar os portões, a fim de santificar o dia de sábado. Lembra-te também disso, ó meu Deus, e tem misericórdia de mim conforme a tua grande misericórdia.",
        "Levítico 23:32": "Será para vocês sábado de descanso completo, e vocês se humilharão. No nono dia do mês, à tarde, de uma tarde a outra tarde, vocês celebrarão o seu sábado.",
        "Gênesis 1": "No princípio Deus criou os céus e a terra. [...] Houve tarde e manhã, o primeiro dia. [...] Houve tarde e manhã, o segundo dia. [...] Assim houve tarde e manhã, o sexto dia. Assim foram acabados os céus e a terra, e todo o seu exército.",
        "Atos 5:29": "Pedro e os outros apóstolos responderam: 'É preciso obedecer antes a Deus do que aos homens!'",
        "Mateus 12:10-13": "E estava ali um homem com a mão atrofiada. Eles perguntaram a Jesus: 'É permitido curar no sábado?' Isso para que pudessem acusá-lo. Ele lhes respondeu: 'Se algum de vocês tiver uma ovelha e ela cair num buraco no sábado, não irá agarrá-la e tirá-la de lá? Pois quanto mais vale um homem do que uma ovelha! Portanto, é permitido fazer o bem no sábado.' Então disse ao homem: 'Estenda a sua mão.' Ele a estendeu, e ela foi restaurada, ficando sã como a outra.",
        "Lucas 13:15-16": "O Senhor lhe respondeu: 'Hipócritas! Cada um de vocês não desamarra o seu boi ou jumento do estábulo e o leva para beber água no sábado? Não deveria esta mulher, que é filha de Abraão, a quem Satanás mantinha presa por dezoito longos anos, ser libertada no sábado dessa amarra?'",
        "Lucas 14:3-5": "Jesus perguntou aos fariseus e aos peritos na lei: 'É permitido curar no sábado, ou não?' Mas eles ficaram em silêncio. Então Jesus tomou o homem, curou-o e mandou-o embora. Depois lhes perguntou: 'Se algum de vocês tiver um filho ou um boi que cair num poço no dia de sábado, não o tirará imediatamente?'",
        "Joel 2:28-29": 'E depois derramarei o meu Espírito sobre todos os povos. Os seus filhos e as suas filhas profetizarão, os velhos terão sonhos, os jovens terão visões. Até sobre os servos e as servas derramarei o meu Espírito naqueles dias.',
        "Atos 2:17-18": 'No últimos dias, diz Deus, derramarei o meu Espírito sobre todos os povos. Os seus filhos e as suas filhas profetizarão, os jovens terão visões, os velhos terão sonhos. Sobre os meus servos e as minhas servas derramarei do meu Espírito naqueles dias, e eles profetizarão.',
        "1 Coríntios 12:28": "Assim, na igreja, Deus estabeleceu primeiramente apóstolos, em segundo lugar profetas, em terceiro mestres, e depois operadores de milagres, depois dons de curar, de socorrer, de governar, de falar em várias línguas.",
        "Efésios 4:11-13": "E ele designou alguns para apóstolos, outros para profetas, outros para evangelistas, e outros para pastores e mestres, com o fim de preparar os santos para a obra do ministério, para que o corpo de Cristo seja edificado, até que todos alcancemos a unidade da fé e do conhecimento do Filho de Deus, e cheguemos à maturidade, atingindo a medida da plenitude de Cristo.",
        "Apocalipse 12:17": "Então o dragão irou-se contra a mulher e foi guerrear contra os demais descendentes dela, os que obedecem aos mandamentos de Deus e se mantêm fiéis ao testemunho de Jesus.",
        "Apocalipse 19:10": "Então caí aos seus pés para adorá-lo, mas ele me disse: 'Não faça isso! Sou servo como você e como os seus irmãos que se mantêm fiéis ao testemunho de Jesus. Adore a Deus! Pois o testemunho de Jesus é o espírito de profecia.'",
        "1 Tessalonicenses 5:19-21": "Não apaguem o Espírito. Não tratem com desprezo as profecias, mas ponham à prova todas as coisas e fiquem com o que é bom.",
        "1 Coríntios 14:1": "Sigam o caminho do amor e busquem com dedicação os dons espirituais, especialmente o de profecia.",
        "Isaías 8:20": "À lei e ao testemunho! Se eles não falarem desta maneira, nunca verão a alvorecer.",
        "1 João 4:1": "Amados, não creiam em qualquer espírito, mas examinem os espíritos para ver se eles procedem de Deus, porque muitos falsos profetas têm saído pelo mundo.",
        "1 Tessalonicenses 5:20-21": "Não tratem com desprezo as profecias, mas ponham à prova todas as coisas e fiquem com o que é bom.",
        "Apocalipse 22:18-19": "Eu aviso a todo aquele que ouvir as palavras da profecia deste livro: Se alguém lhe acrescentar algo, Deus lhe acrescentará as pragas descritas neste livro. Se alguém tirar alguma palavra deste livro de profecia, Deus tirará dele a sua parte na árvore da vida e na cidade santa, que são descritas neste livro.",
        "Lucas 1:1-4": "Muitos já se dedicaram a elaborar um relato ordenado dos fatos que se cumpriram entre nós, conforme nos foram transmitidos por aqueles que desde o início foram testemunhas oculares e servos da palavra. Eu mesmo investiguei tudo cuidadosamente, desde o começo, e decidi escrever-te um relato ordenado, ó excelentíssimo Teófilo, para que tenhas a certeza das coisas que te foram ensinadas.",
        "Atos 17:28": "Pois nele vivemos, nos movemos e existimos, como disseram alguns dos poetas de vocês: 'Também somos descendência dele.'",
        "Tito 1:12": "Um deles, seu próprio profeta, disse: 'Cretenses, sempre mentirosos, feras cruéis, preguiçosos vorazes.'",
        "Mateus 24:42-44": "Portanto, vigiem, porque vocês não sabem em que dia virá o seu Senhor. Mas entendam isto: se o dono da casa soubesse em que hora da noite o ladrão viria, ele estaria vigilante e não deixaria que a sua casa fosse arrombada. Por isso, vocês também estejam preparados, porque o Filho do homem virá numa hora em que não o esperam.",
        "Tiago 5:8-9": "Você também seja paciente e fortaleça o seu coração, pois a vinda do Senhor está próxima. Irmãos, não se queixem uns dos outros, para não serem julgados. O Juiz está à porta.",
        "Jeremias 18:7-10": "Se em algum momento eu declarar que uma nação ou reino será arrancado, destruído ou devastado, e se essa nação que eu adverti se arrepender de seus maus caminhos, eu me arrependerei do desastre que tinha planejado trazer sobre ela. E se em outro momento eu declarar que uma nação ou reino será construído e plantado, e se ela fizer o que eu reprovo e não me obedecer, eu me arrependerei do bem que tinha tido a intenção de fazer por ela.",
        "Jonas 3:4-10": "Jonas começou a percorrer a cidade. No primeiro dia, ele proclamou: 'Daqui a quarenta dias Nínive será destruída!' Os ninivitas creram em Deus. Proclamaram um jejum, e vestiram-se de pano de saco, desde o maior até o menor. Quando Deus viu o que eles fizeram e como se converteram do seu mau caminho, ele se arrependeu do desastre que tinha ameaçado trazer sobre eles, e não o fez.",
        "1 Coríntios 6:19-20": "Acaso não sabem que o corpo de vocês é santuário do Espírito Santo que está em vocês, o qual vocês receberam de Deus? Vocês não são de si mesmos; foram comprados por um preço. Portanto, glorifiquem a Deus com o corpo de vocês.",
        "1 Coríntios 10:31": "Assim, quer vocês comam, bebam ou façam qualquer outra coisa, façam tudo para a glória de Deus.",
        "3 João 2": "Amado, oro para que você vá bem em todas as coisas e tenha boa saúde, assim como vai bem a sua alma.",
        "João 3:16": "Porque Deus amou o mundo de tal maneira que deu o seu Filho unigênito, para que todo aquele que nele crê não pereça, mas tenha a vida eterna.",
        "Atos 4:12": "A salvação não se encontra em nenhum outro, pois debaixo do céu não há nenhum outro nome dado aos homens pelo qual devamos ser salvos.",
        "Gálatas 5:1-4": "Foi para a liberdade que Cristo nos libertou. Portanto, permaneçam firmes e não se deixem submeter novamente a um jugo de escravidão. Eu, Paulo, lhes digo que se deixarem circuncidar, Cristo de nada servirá para vocês. Novamente declaro a todo homem que se deixa circuncidar que está obrigado a cumprir toda a lei. Vocês que procuram ser justificados pela lei, separaram-se de Cristo; caíram da graça.",
        "Atos 15:1-11": "Alguns homens desceram da Judeia para Antioquia e ensinavam aos irmãos: 'Se vocês não forem circuncidados conforme o costume ensinado por Moisés, não podem ser salvos.' Isso trouxe grande discussão e debate entre Paulo e Barnabé e os outros. Então Paulo e Barnabé e alguns outros foram designados para subir a Jerusalém e tratar dessa questão com os apóstolos e os presbíteros. [...] Deus, que conhece o coração, demonstrou que os aceitou, dando-lhes o Espírito Santo, exatamente como deu a nós. Não fez diferença alguma entre eles e nós, pois purificou os seus corações pela fé.",
        "Mateus 7:15-20": "Cuidado com os falsos profetas! Eles vêm até vocês disfarçados de ovelhas, mas por dentro são lobos devoradores. Vocês os reconhecerão pelos seus frutos. É possível colher uvas de espinheiros ou figos de cardos? Da mesma forma, toda árvore boa dá frutos bons, mas a árvore ruim dá frutos ruins. A árvore boa não pode dar frutos ruins, nem a árvore ruim pode dar frutos bons. Toda árvore que não produz bons frutos é cortada e lançada ao fogo. Assim, pelos seus frutos vocês os reconhecerão.",
        "Jeremias 28:9": "Quanto ao profeta que profetiza paz, só será reconhecido como enviado pelo Senhor se a sua predição se cumprir.",
        "1 Coríntios 14:3": "Mas quem profetiza fala aos homens para edificação, exortação e consolação.",
        "Mateus 5:8": "Bem-aventurados os puros de coração, porque eles verão a Deus.",

        // SEÇÃO 8 - ELLEN G. WHITE, BÍBLIA E DOM DE PROFECIA
        "Romanos 12:1-2": "Portanto, irmãos, rogo-lhes pelas misericórdias de Deus que se ofereçam em sacrifício vivo, santo e agradável a Deus; este é o culto racional de vocês. Não se amoldem ao padrão deste mundo, mas transformem-se pela renovação da sua mente, para que sejam capazes de experimentar e comprovar a boa, agradável e perfeita vontade de Deus.",
        "Lucas 2:52": "E Jesus crescia em sabedoria, estatura e graça diante de Deus e dos homens.",
        "Daniel 10:13": "Mas o príncipe do reino da Pérsia me resistiu vinte e um dias. Mas Miguel, um dos principais príncipes, veio em minha ajuda, porque eu fui impedido lá pelos reis da Pérsia.",
        "Daniel 12:1": 'Nesse tempo se levantará Miguel, o grande príncipe que protege o seu povo. Haverá um tempo de angústia como nunca houve desde que nações existiram até então. Mas naquele tempo o seu povo será libertado, todo aquele cujo nome está escrito no livro.',
        "Judas 9": "Mas o arcanjo Miguel, quando contendia com o diabo e disputava acerca do corpo de Moisés, não ousou pronunciar sentença injuriosa contra ele, mas disse: O Senhor te repreenda.",
        "1 Tessalonicenses 4:16": "Pois o Senhor mesmo descerá do céu, com voz de arcanjo e com sonido de trombeta de Deus, e os que morreram em Cristo ressuscitarão primeiro.",
        "João 1:1-3": "No princípio era aquele que é a Palavra. Ele estava com Deus, e era Deus. Ele estava com Deus no princípio. Todas as coisas foram feitas por intermédio dele; sem ele, nada do que foi feito se fez.",
        "Colossenses 1:15-17": "Ele é a imagem do Deus invisível, o primogênito de toda a criação. Pois nele foram criadas todas as coisas nos céus e na terra, as visíveis e as invisíveis, sejam tronos ou soberanias, poderes ou autoridades; todas as coisas foram criadas por ele e para ele. Ele é antes de todas as coisas, e nele tudo subsiste.",
        "Hebreus 1:1-8": "Havendo Deus, antigamente, falado muitas vezes e de muitas maneiras aos pais, pelos profetas, a nós falou-nos nestes últimos dias pelo Filho, a quem constituiu herdeiro de tudo, por quem fez também o mundo. O qual, sendo o resplendor da sua glória, e a expressa imagem da sua pessoa, e sustentando todas as coisas pela palavra do seu poder, havendo feito por si mesmo a purificação dos nossos pecados, assentou-se à destra da majestade nas alturas. [...] E, quando outra vez introduz no mundo o Primogênito, diz: E todos os anjos de Deus o adorem.",

        // SEÇÃO 9 - JUÍZO INVESTIGATIVO, SANTUÁRIO CELESTIAL E 1844
        "Daniel 7:9-10": "Enquanto eu olhava, foram postos uns tronos, e um Ancião de Dias se assentou. Sua veste era branca como a neve, e o cabelo de sua cabeça era como a lã pura; seu trono estava chamejante, e suas rodas eram fogo ardente. Um rio de fogo estava fluindo, saindo de diante dele. Milhares de milhares o serviam, e milhões de milhões estavam diante dele. O tribunal foi instalado, e foram abertos os livros.",
        "Daniel 8:14": "Ele me disse: 'Até duas mil e trezentas tardes e manhãs; então o santuário será reconstruído e a cidade será restaurada.'",
        "Daniel 9:24-27": "Setenta semanas estão decretadas para o seu povo e para a sua santa cidade, para acabar com a transgressão, para pôr fim ao pecado, para expiar a iniqüidade, para trazer justiça eterna, para selar a visão e a profecia, e para ungir o santíssimo. Saiba e entenda: Desde a emissão do decreto para restaurar e reconstruir Jerusalém até ao Ungido, ao Príncipe, haverá sete semanas e sessenta e duas semanas. Ela será restaurada e reconstruída com praças e valas, mas em tempos difíceis. Depois das sessenta e duas semanas, o Ungido será morto e já não existirá. O povo do governante que virá destruirá a cidade e o santuário. O fim virá como uma inundação, e até o fim haverá guerra; desolações foram decretadas. Ele fará uma aliança com muitos por uma semana; mas no meio da semana ele porá fim ao sacrifício e à oferta, e sobre uma das asas do templo estará uma abominação que causa desolação, até que o fim determinado seja derramado sobre a cidade desolada.",
        "Hebreus 8": "O ponto principal do que estamos dizendo é este: Temos um sumo sacerdote tal, que se assentou à destra do trono da Majestade nos céus, como ministro do santuário e do verdadeiro tabernáculo que o Senhor erigiu, não o homem. [...] Mas agora Cristo obteve um ministério tanto mais excelente, quanto é ele também o mediador de uma melhor aliança, a qual foi estabelecida sobre melhores promessas.",
        "Hebreus 9": "Ora, a primeira aliança tinha normas para culto e um santuário terrestre. Foi preparado um tabernáculo; a primeira parte, chamada o Lugar Santo, continha o candelabro, a mesa e os pães da proposição. Por trás do segundo véu estava a parte chamada o Santo dos Santos, contendo o altar de incenso e a arca da aliança coberta de ouro. [...] Mas Cristo veio como sumo sacerdote dos bens futuros, e não através de sangue de bodes e bezerros, mas através do seu próprio sangue, entrou no Santo dos Santos, de uma vez por todas, tendo obtido eterna redenção.",
        "Apocalipse 14:6-7": "Vi outro anjo voando pelo meio do céu, tendo um evangelho eterno para pregar aos que habitam sobre a terra, e a toda nação, e tribo, e língua, e povo, dizendo com grande voz: Temei a Deus, e dai-lhe glória; porque é chegada a hora do seu juízo; e adorai aquele que fez o céu, e a terra, e o mar, e as fontes das águas.",
        "Apocalipse 22:12": "Eis que venho em breve! A minha recompensa está comigo, e eu retribuirei a cada um de acordo com o que fez.",
        "Números 14:34": "Por quarenta anos - um ano por cada um dos quarenta dias em que vocês espiaram a terra - vocês sofrerão as consequências dos seus pecados e conhecerão a minha hostilidade.",
        "Ezequiel 4:6": "Quando tiver completado esses dias, deite-se sobre o seu lado direito, e sofrerá a iniqüidade da casa de Judá por quarenta dias; um dia para cada ano, eu lhe impus.",
        "Esdras 7:11-26": "Esta é uma cópia da carta que o rei Artaxerxes deu ao sacerdote Esdras, mestre da lei de Deus: [...] Qualquer que não observar a lei do seu Deus e a lei do rei, seja condenado à morte, ou ao desterro, ou ao confisco de bens, ou à prisão.",
        "Levítico 16": "O Senhor disse a Moisés: 'Depois que Arão e seus filhos entrarem na Tenda da Reunião, ele deverá sair e apresentar um novilho como oferta pelo pecado e um carneiro como holocausto, ambos sem defeito. [...] Arão apresentará o bode como oferta pelo pecado para o povo e fará expiação pelo povo, diante do Senhor, para purificar o santuário das impurezas dos israelitas e das suas transgressões e de todos os seus pecados.",

        // SEÇÃO 10 - SAÚDE, ALIMENTAÇÃO E ESTILO DE VIDA
        "Levítico 11": "O Senhor disse a Moisés e a Arão: 'Digam aos israelitas: Dos animais terrestres, estes vocês podem comer: Todo animal que tem casco fendido e que rumina, podem comer. [...] Destes vocês não podem comer: o camelo, o coelho, o porco. [...] Dos animais aquáticos, estes vocês podem comer: Todo animal que tem barbatanas e escamas. [...] Destes não podem comer: a águia, o corvo, o gavião, o mocho, a coruja, o ganso, o pelicano, a cegonha, a garça. [...] Todo animal rasteiro que arrasta o corpo sobre a terra será detestável.",
        "Deuteronômio 14": "São estes os animais que vocês podem comer: o boi, a ovelha, o cabrito, o veado, a gazela, o corço, a cabra montês, o antílope, o carneiro e o órix. [...] Mas destes não comam: o camelo, a lebre, o coelho, o porco. [...] Todo animal que tem barbatanas e escamas vocês podem comer; mas tudo o que não tem barbatanas nem escamas não podem comer. [...] Toda ave limpa vocês podem comer. [...] Não comam nenhuma ave impura: a águia, o abutre, a águia marinha, o corvo, o mocho, a coruja, o gavião, o falcão.",
        "Gênesis 7:2": "De todo animal puro, leve sete pares, macho e fêmea; de todo animal impuro, leve um par, macho e fêmea.",
        "Gênesis 1:29": "Disse Deus: 'Eis que lhes dou todas as plantas que nascem em toda a terra e produzem sementes, e todas as árvores com frutos que dão sementes. Elas servirão de alimento para vocês.",
        "Gênesis 9:3-4": "Tudo o que vive e se move lhes servirá de alimento. Assim como lhes dei os vegetais, agora lhes dou todas as coisas. Mas não comam carne com sua vida, isto é, com o sangue.",
        "Daniel 1:8-20": "Daniel, contudo, decidiu não se contaminar com a comida e o vinho do rei, e pediu ao chefe dos eunucos permissão para se abster deles. [...] 'Peço que faças uma experiência de dez dias com os teus servos: que nos deem apenas vegetais para comer e água para beber. Depois compare a nossa aparência com a dos jovens que comem a comida do rei, e trata os teus servos de acordo com o que vires.' [...] Ao fim dos dez dias, eles pareciam mais saudáveis e mais fortes do que todos os jovens que comiam a comida do rei.",
        "Romanos 14:17": "Pois o Reino de Deus não é comida nem bebida, mas justiça, paz e alegria no Espírito Santo.",
        "1 Tessalonicenses 5:23": "Que o próprio Deus da paz os santifique completamente. Que todo o espírito, a alma e o corpo de vocês sejam preservados irrepreensíveis na vinda de nosso Senhor Jesus Cristo.",
        "Atos 10": "Em Cesaréia havia um homem chamado Cornélio, centurião do regimento italiano. [...] Certa tarde, por volta das três horas, ele teve uma visão. Viu claramente um anjo de Deus, que veio até ele e disse: 'Cornélio!' [...] No dia seguinte, enquanto eles viajavam e se aproximavam da cidade, Pedro subiu ao terraço para orar. [...] Ficou com fome e quis comer. Enquanto lhe preparavam a comida, caiu em transe. Viu o céu aberto e algo parecido com um grande lençol descendo, baixado à terra pelas quatro pontas. Nele havia todos os tipos de animais, répteis e aves. Então uma voz lhe disse: 'Levante-se, Pedro! Mate e coma!' 'De modo nenhum, Senhor!', respondeu Pedro. 'Nunca comi nada impuro ou imundo!' A voz lhe falou segunda vez: 'Não chame impuro ao que Deus purificou.'",
        "Atos 10:9-28": "No dia seguinte, enquanto eles viajavam e se aproximavam da cidade, Pedro subiu ao terraço para orar. [...] Ficou com fome e quis comer. Enquanto lhe preparavam a comida, caiu em transe. Viu o céu aberto e algo parecido com um grande lençol descendo, baixado à terra pelas quatro pontas. [...] A voz lhe falou segunda vez: 'Não chame impuro ao que Deus purificou.' [...] Pedro ainda estava pensando na visão quando o Espírito lhe disse: 'Simão, três homens estão procurando você. Levante-se e desça. Não hesite em ir com eles, pois eu os enviei.' [...] Quando Pedro entrou na casa, Cornélio foi ao seu encontro e prostrou-se aos seus pés para reverenciá-lo. Mas Pedro o ajudou a levantar-se e disse: 'Levante-se; eu sou apenas um homem como você.' E disse-lhes: 'Vocês sabem que é contra a lei para um judeu associar-se a um gentio ou visitá-lo. Mas Deus me mostrou que não devo chamar impuro ou imundo a nenhum homem.'",
        "Atos 10:28": "Vocês sabem que é contra a lei para um judeu associar-se a um gentio ou visitá-lo. Mas Deus me mostrou que não devo chamar impuro ou imundo a nenhum homem.",
        "Marcos 7:1-23": "Os fariseus e alguns mestres da lei, vindos de Jerusalém, foram juntar-se a Jesus e viram alguns dos seus discípulos comerem com as mãos impuras, isto é, sem lavar. [...] Então ele lhes disse: 'Hipócritas! Bem profetizou Isaías sobre vocês, quando disse: Este povo me honra com os lábios, mas o seu coração está longe de mim. Em vão me adoram; seus ensinamentos são apenas regras humanas. [...] Ele disse-lhes: 'Vocês são tão insensíveis! Não percebem que nada que entra no homem o pode tornar impuro, pois não entra em seu coração, mas em seu estômago e depois é eliminado?' Assim ele declarou puros todos os alimentos. [...] Pois é de dentro do coração que vêm os maus pensamentos, a imoralidade sexual, os roubos, os homicídios, os adultérios, as cobiças, as maldades, o engano, a devassidão, a inveja, a calúnia, a arrogância e a insensatez. Todos esses males vêm de dentro e tornam o homem impuro.'",
        "Mateus 15:1-20": "Então alguns fariseus e mestres da lei vieram de Jerusalém a Jesus e perguntaram: 'Por que os teus discípulos transgridem a tradição dos anciãos? Pois não lavam as mãos antes de comer!' [...] Jesus chamou a multidão e disse: 'Ouçam e entendam. Não é o que entra na boca que torna o homem impuro; mas o que sai da sua boca, isso o torna impuro.' [...] Pois do coração procedem os maus pensamentos, os homicídios, os adultérios, as imoralidades sexuais, os roubos, os falsos testemunhos e as calúnias. Essas coisas tornam o homem impuro; mas comer sem lavar as mãos não o torna impuro.",

        // SEÇÃO 11 - DÍZIMOS, OFERTAS E FINANÇAS
        "Gênesis 14:20": "E bendito seja o Deus Altíssimo, que entregou os seus inimigos nas tuas mãos. E Abrão lhe deu o dízimo de tudo.",
        "Gênesis 28:22": "E esta pedra que pus como coluna será a casa de Deus; e de tudo quanto me deres, certamente te darei o dízimo.",
        "Levítico 27:30": "Todos os dízimos da terra, tanto dos cereais como dos frutos das árvores, pertencem ao Senhor; são consagrados ao Senhor.",
        "Malaquias 3:8-10": "Pode um homem roubar a Deus? Vocês estão me roubando. E ainda perguntam: 'Como é que te roubamos?' Nos dízimos e nas ofertas. Vocês estão sob maldição, e a nação inteira está roubando a mim. Tragam o dízimo todo ao depósito do templo, para que haja alimento em minha casa. Ponham-me à prova, diz o Senhor dos Exércitos, e vejam se não vou abrir as comportas do céu e derramar sobre vocês tantas bênçãos que nem terão onde guardá-las.",
        "Mateus 23:23": "Ai de vocês, mestres da lei e fariseus, hipócritas! Vocês dão o dízimo da hortelã, da endro e do cominho, mas têm negligenciado os preceitos mais importantes da lei: a justiça, a misericórdia e a fidelidade. Vocês deviam ter praticado estas coisas, sem omitir aquelas.",
        "2 Coríntios 9:6-8": "Lembre-se: quem semeia pouco, pouco também colherá; e quem semeia com fartura, também com fartura colherá. Cada um dê conforme determinou em seu coração, não com pesar ou por obrigação, pois Deus ama quem dá com alegria. E Deus é poderoso para fazer que lhes seja acrescentada toda a graça, para que, tendo sempre, em tudo, suficiente suficiência em tudo, tenham sobra toda boa obra.",

        // SEÇÃO 12 - BATISMO, CEIA E PRÁTICAS LITÚRGICAS
        "Mateus 3:13-17": "Então Jesus veio da Galileia ao Jordão, para ser batizado por João. Mas João tentava impedi-lo, dizendo: Eu é que preciso ser batizado por ti, e tu vens a mim? Jesus, porém, respondeu: Deixa agora, porque assim nos convém cumprir toda a justiça. Então João o deixou. Batizado Jesus, saiu logo da água; e eis que se abriram os céus, e viu o Espírito de Deus descendo como pomba e vindo sobre ele; e eis que uma voz dos céus dizia: Este é o meu Filho amado, em quem me comprazo.",
        "João 3:23": "Ora, João também estava batizando em Enom, perto de Salim, porque havia ali muitas águas; e o povo ia e se batizava.",
        "Atos 8:38-39": "E mandou parar o carro. Tanto Filipe como o eunuco desceram à água, e Filipe o batizou. Quando saíram da água, o Espírito do Senhor arrebatou a Filipe; o eunuco não o viu mais, e seguiu o seu caminho cheio de alegria.",
        "Marcos 16:16": "Quem crer e for batizado será salvo, mas quem não crer será condenado.",
        "Atos 2:38": "Pedro respondeu: 'Arrependam-se, e cada um de vocês seja batizado em nome de Jesus Cristo para perdão dos seus pecados, e receberão o dom do Espírito Santo.'",
        "Atos 8:12": "Mas, quando creram em Filipe, que lhes pregava o Reino de Deus e o nome de Jesus Cristo, tanto homens como mulheres foram batizados.",
        "Atos 8:36-38": "Continuando viagem, chegaram a um lugar onde havia água, e disse o eunuco: Eis aqui água; que impede que eu seja batizado? [Disse Filipe: Se crês de todo o coração, podes. E, respondendo ele, disse: Creio que Jesus Cristo é o Filho de Deus.] E mandou parar o carro. Tanto Filipe como o eunuco desceram à água, e Filipe o batizou.",
        "1 Samuel 1:27-28": "Orei por este menino, e o Senhor me concedeu o que lhe pedi. Agora eu o dedico ao Senhor por toda a vida dele, e ele pertencerá ao Senhor. E ali adoraram o Senhor.",
        "Lucas 2:22": "Quando se completaram os dias para a purificação deles, segundo a lei de Moisés, levaram-no a Jerusalém para apresentá-lo ao Senhor.",

        // SEÇÃO 13 - CASAMENTO, FAMÍLIA E QUESTÕES ÉTICAS
        "Mateus 19:6": "De modo que já não são mais dois, mas uma só carne. Portanto, o que Deus ajuntou, não o separe o homem.",
        "Marcos 10:9": "Por isso, o que Deus ajuntou, não o separe o homem.",
        "Mateus 5:32": "Mas eu lhes digo: todo aquele que se divorcia de sua mulher, exceto por imoralidade sexual, faz que ela se torne adúltera, e quem se casa com a mulher divorciada comete adultério.",
        "Mateus 19:9": "Eu lhes digo que todo aquele que se divorcia de sua mulher, exceto por imoralidade sexual, e se casa com outra mulher, comete adultério.",
        "1 Coríntios 7:10-15": "Aos casados dou este mandamento, não eu, mas o Senhor: que a mulher não se separe do marido. Mas, se ela se separar, que fique sem casar ou reconcilie-se com o marido. E que o marido não se divorcie da sua mulher. [...] Mas, se o descrente se separar, que se separe. O irmão ou a irmã não fica ligado nestes casos; pois Deus nos chamou para vivermos em paz.",
        "Gênesis 1:27": "Criou Deus o homem à sua imagem, à imagem de Deus o criou; homem e mulher os criou.",
        "Gênesis 2:24": "Por isso, o homem deixa pai e mãe e se une à sua mulher, e eles se tornam uma só carne.",
        "Mateus 19:4-6": "Ele respondeu: 'Vocês não leram que, no princípio, o Criador os fez homem e mulher, e disse: Por essa razão, o homem deixará pai e mãe e se unirá à sua mulher, e os dois se tornarão uma só carne? Assim, eles já não são dois, mas uma só carne. Portanto, o que Deus ajuntou, não o separe o homem.'",
        "Romanos 1:24-27": "Por essa razão, Deus os entregou à impureza sexual, segundo os desejos do seu coração, para a degradação dos seus corpos entre si. Trocaram a verdade de Deus pela mentira e adoraram e serviram coisas criadas em lugar do Criador, que é bendito para sempre. Amém. Por causa disso, Deus os entregou a desejos vergonhosos. Até mesmo as mulheres trocaram as relações naturais por relações antinaturais. Da mesma forma, os homens também abandonaram as relações naturais com mulheres e se inflamaram de desejo uns pelos outros. Cometeram atos indecentes, homens com homens, e receberam em si mesmos o castigo merecido pela sua perversão.",
        "1 Coríntios 6:9-11": "Vocês não sabem que os perversos não herdarão o Reino de Deus? Não se enganem: nem imorais, nem idólatras, nem adúlteros, nem homossexuais passivos ou ativos, nem ladrões, nem avarentos, nem bêbados, nem caluniadores, nem ladrões herdarão o Reino de Deus. É isso que alguns de vocês eram. Mas vocês foram lavados, foram santificados, foram justificados no nome do Senhor Jesus Cristo e no Espírito do nosso Deus.",
        "2 Coríntios 6:14": "Não se ponham em jugo desigual com os descrentes. Pois o que têm em comum a justiça e a iniqüidade? Ou que comunhão pode ter a luz com as trevas?",
        "Amós 3:3": "Andarão dois juntos, se não estiverem de acordo?",
        "1 Coríntios 7:39": "A mulher está ligada enquanto o marido vive. Mas, se o marido morrer, ela está livre para se casar com quem quiser, contanto que seja no Senhor.",

        // SEÇÃO 14 - CRIAÇÃO, EVOLUÇÃO E ORIGENS
        "Gênesis 1": "No princípio Deus criou os céus e a terra. Era a terra sem forma e vazia; trevas cobriam a face do abismo, e o Espírito de Deus se movia sobre a face das águas. Disse Deus: Haja luz. E houve luz. [...] Deus chamou à luz Dia, e às trevas chamou Noite. Houve tarde e manhã, o primeiro dia. [...] Disse Deus: Haja um firmamento no meio das águas, e haja separação entre águas e águas. [...] Houve tarde e manhã, o segundo dia. [...] Disse Deus: Ajuntem-se as águas debaixo dos céus num só lugar, e apareça a porção seca. E assim foi. [...] Houve tarde e manhã, o terceiro dia. [...] Disse Deus: Haja luminares no firmamento dos céus, para fazerem separação entre o dia e a noite; sejam eles para sinais e para tempos determinados e para dias e anos. [...] Houve tarde e manhã, o quarto dia. [...] Disse Deus: Produzam as águas abundantemente répteis de alma vivente; e voem as aves sobre a terra. [...] Houve tarde e manhã, o quinto dia. [...] Disse Deus: Produza a terra alma vivente conforme a sua espécie: gado, répteis e bestas-feras da terra conforme a sua espécie. E assim foi. [...] Houve tarde e manhã, o sexto dia.",
        "Gênesis 2": "Assim foram acabados os céus e a terra, e todo o seu exército. E havendo Deus completado no sétimo dia a sua obra, que tinha feito, descansou no sétimo dia de toda a sua obra, que tinha feito. [...] Então o Senhor Deus formou o homem do pó da terra, e soprou-lhe nas narinas o fôlego da vida; e o homem tornou-se alma vivente. [...] Então o Senhor Deus fez cair um sono pesado sobre o homem, e este adormeceu; tomou uma das suas costelas, e fechou o lugar com carne. E da costela que o Senhor Deus lhe tomara, formou a mulher, e a trouxe ao homem.",
        "Gênesis 3": "Ora, a serpente era o mais astuto de todos os animais do campo, que o Senhor Deus tinha feito. E esta disse à mulher: É assim que Deus disse: Não comereis de toda árvore do jardim? [...] Então a serpente disse à mulher: Certamente não morrereis. Porque Deus sabe que no dia em que comerdes desse fruto, vossos olhos se abrirão, e sereis como Deus, conhecendo o bem e o mal. [...] Vendo, pois, a mulher que aquela árvore era boa para se comer, e agradável aos olhos, e árvore desejável para dar entendimento, tomou do seu fruto, e comeu, e deu também a seu marido, que estava com ela, e ele comeu. [...] Então foram abertos os olhos de ambos, e conheceram que estavam nus; pelo que coseram folhas de figueira, e fizeram para si aventais.",
        "Romanos 5:12-21": "Portanto, assim como por um só homem entrou o pecado no mundo, e pelo pecado a morte, assim também a morte passou a todos os homens, porque todos pecaram. [...] Pois se pela ofensa de um só, a morte reinou por esse, muito mais os que recebem a abundância da graça, e do dom da justiça, reinarão em vida por um só, Jesus Cristo. Pois assim como por uma só ofensa veio o juízo sobre todos os homens para condenação, assim também por um só ato de justiça veio o dom sobre todos os homens para justificação de vida. Porque, como pela desobediência de um só homem muitos foram feitos pecadores, assim pela obediência de um muitos serão feitos justos.",
        "1 Coríntios 15:21-22": "Pois visto que a morte veio por um homem, também por um homem veio a ressurreição dos mortos. Pois assim como em Adão todos morrem, assim também todos serão vivificados em Cristo.",
        "1 Coríntios 15:45-49": "Assim está escrito: O primeiro homem, Adão, tornou-se alma vivente; o último Adão, espírito vivificante. Mas não é primeiro o espiritual, senão o animal; depois o espiritual. O primeiro homem, sendo da terra, é terreno; o segundo homem é do céu. Qual o terreno, tais são também os terrestres; e, qual o celestial, tais também os celestiais. E, assim como trouxemos a imagem do terreno, traremos também a imagem do celestial.",

        // SEÇÃO 16 - LIDERANÇA FEMININA E ORDENAÇÃO DE MULHERES
        "Romanos 16": "Recomendo-vos, nossa irmã Febe, que está servindo à igreja de Cencréia. [...] Saúdem Priscila e Áquila, meus colaboradores em Cristo Jesus. [...] Saúdem Maria, que muito trabalhou por vocês. [...] Saúdem Andrônico e Júnias, meus parentes e companheiros de prisão, que são muito estimados entre os apóstolos e também estavam em Cristo antes de mim. [...] Saúdem Trifena e Trifosa, mulheres que trabalham no Senhor. Saúdem a estimada Pérside, que também muito trabalhou no Senhor.",
        "Juízes 4-5": "Débora, profetisa, mulher de Lapidote, liderava Israel naquele tempo. [...] Ela se sentava debaixo da Palmeira de Débora, entre Ramá e Betel, na região montanhosa de Efraim, e os israelitas subiam a ela para resolverem suas disputas. [...] Então Débora chamou Baraque, filho de Abinoão, de Quedes-Naftali, e disse: O Senhor, o Deus de Israel, ordena: Vá e reúna dez mil homens de Naftali e Zebulom e leve-os ao monte Tabor. Eu atrairei Sísera, o comandante do exército de Jabim, com seus carros e tropas, para o rio Quisom, e o entregarei em suas mãos.",
        "Atos 18:26": "Ele começou a falar com ousadia na sinagoga. Quando Priscila e Áquila o ouviram, convidaram-no para sua casa e explicaram-lhe o caminho de Deus com mais exatidão.",
        "1 Timóteo 2": "Antes de tudo, recomendo que se façam súplicas, orações, intercessões e ação de graças por todos os homens. [...] Quero, pois, que os homens orem em todo lugar, levantando mãos santas, sem ira nem contenda. Da mesma forma, quero que as mulheres se vistam modestamente, com decência e modéstia, não com tranças, ou com ouro, ou pérolas, ou vestidos caros, mas com boas obras, como convém a mulheres que professam a piedade. A mulher aprenda em silêncio, com toda a submissão. Pois não permito que a mulher ensine, nem que exerça autoridade sobre o marido, mas que esteja em silêncio. Porque primeiro foi formado Adão, depois Eva. E Adão não foi enganado, mas a mulher, sendo enganada, caiu em transgressão.",
        "1 Coríntios 11": "Sede meus imitadores, como também eu de Cristo. [...] Mas quero que saibais que Cristo é a cabeça de todo homem, e o homem a cabeça da mulher, e Deus a cabeça de Cristo. [...] Todo homem que ora ou profetiza com a cabeça coberta desonra a sua cabeça. Mas toda mulher que ora ou profetiza com a cabeça descoberta desonra a sua cabeça, porque é como se estivesse rapada. [...] Pois o homem não deve cobrir a cabeça, sendo ele imagem e glória de Deus; mas a mulher é glória do homem.",
        "1 Coríntios 14": "Segui o amor, e procurai com zelo os dons espirituais, mas principalmente o de profetizar. [...] Pois quem fala em língua não fala aos homens, mas a Deus; pois ninguém o entende; e em espírito fala mistérios. [...] Mas na igreja quero falar cinco palavras inteligíveis, para instruir os outros, do que dez mil palavras em língua. [...] As mulheres estejam caladas nas igrejas; porque lhes não é permitido falar; mas estejam submissas, como também ordena a lei.",
    };

    // Função simples para abrir popup
    function abrirPopupBiblico(reference, text) {
        // Criar overlay COMPLETAMENTE isolado
        const overlay = document.createElement('div');
        overlay.id = 'biblical-overlay-unico';
        overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);display:flex;align-items:center;justify-content:center;z-index:99999;padding:20px;box-sizing:border-box;';

        const modal = document.createElement('div');
        modal.style.cssText = 'background:white;border-radius:12px;padding:24px;max-width:600px;max-height:80vh;overflow-y:auto;position:relative;font-family:Arial,sans-serif;';

        const fechar = document.createElement('button');
        fechar.innerHTML = '✕';
        fechar.style.cssText = 'position:absolute;top:10px;right:10px;background:#f0f0f0;border:none;border-radius:50%;width:30px;height:30px;cursor:pointer;font-size:18px;';
        fechar.onclick = function() {
            document.body.removeChild(overlay);
        };

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

        // Fechar ao clicar fora
        overlay.onclick = function(e) {
            if (e.target === overlay) {
                document.body.removeChild(overlay);
            }
        };

        // Fechar com ESC
        const escHandler = function(e) {
            if (e.key === 'Escape') {
                if (document.body.contains(overlay)) {
                    document.body.removeChild(overlay);
                }
                document.removeEventListener('keydown', escHandler);
            }
        };
        document.addEventListener('keydown', escHandler);

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
