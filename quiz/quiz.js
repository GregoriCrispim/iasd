/**
 * QUIZ BÍBLICO FLOTUANTE
 * Teste seu conhecimento bíblico
 */

(function() {
    'use strict';

    // ============================================
    // CONFIGURAÇÃO
    // ============================================
    const STORAGE_PREFIX = 'quizBiblico_';
    const STORAGE_KEYS = {
        PROGRESSO: 'progresso',
        PONTUACAO: 'pontuacao',
        TEMA_ATUAL: 'temaAtual',
        MODO_ALEATORIO: 'modoAleatorio'
    };

    // ============================================
    // ESTADO DO QUIZ
    // ============================================
    let quizData = null;
    let estadoAtual = {
        temaAtual: null,
        perguntaAtual: 0,
        modoAleatorio: true,
        perguntasRespondidas: []
    };

    // ============================================
    // CARREGAR DADOS DO QUIZ
    // ============================================
    async function carregarDados() {
        try {
            const response = await fetch('/quiz/quiz-data.json');
            quizData = await response.json();
            console.log('Quiz carregado:', quizData.temas.length, 'temas');
        } catch (error) {
            console.error('Erro ao carregar quiz:', error);
        }
    }

    // ============================================
    // LOCAL STORAGE
    // ============================================
    function salvarProgresso() {
        const data = {
            temaAtual: estadoAtual.temaAtual,
            perguntaAtual: estadoAtual.perguntaAtual,
            modoAleatorio: estadoAtual.modoAleatorio,
            perguntasRespondidas: estadoAtual.perguntasRespondidas,
            timestamp: Date.now()
        };
        localStorage.setItem(STORAGE_PREFIX + STORAGE_KEYS.PROGRESSO, JSON.stringify(data));
    }

    function carregarProgresso() {
        try {
            const saved = localStorage.getItem(STORAGE_PREFIX + STORAGE_KEYS.PROGRESSO);
            if (saved) {
                const data = JSON.parse(saved);
                estadoAtual = { ...estadoAtual, ...data };
                return true;
            }
        } catch (error) {
            console.error('Erro ao carregar progresso:', error);
        }
        return false;
    }

    function getPontuacao() {
        try {
            const saved = localStorage.getItem(STORAGE_PREFIX + STORAGE_KEYS.PONTUACAO);
            return saved ? JSON.parse(saved) : { acertos: 0, erros: 0 };
        } catch (error) {
            return { acertos: 0, erros: 0 };
        }
    }

    function salvarPontuacao(acertos, erros) {
        const pontuacao = { acertos, erros };
        localStorage.setItem(STORAGE_PREFIX + STORAGE_KEYS.PONTUACAO, JSON.stringify(pontuacao));
    }

    function resetarProgresso() {
        localStorage.removeItem(STORAGE_PREFIX + STORAGE_KEYS.PROGRESSO);
        localStorage.removeItem(STORAGE_PREFIX + STORAGE_KEYS.PONTUACAO);
        localStorage.removeItem(STORAGE_PREFIX + STORAGE_KEYS.TEMA_ATUAL);
        localStorage.removeItem(STORAGE_PREFIX + STORAGE_KEYS.MODO_ALEATORIO);

        estadoAtual = {
            temaAtual: null,
            perguntaAtual: 0,
            modoAleatorio: true,
            perguntasRespondidas: []
        };

        mostrarPergunta();
        atualizarPontuacao();
    }

    // ============================================
    // LÓGICA DO QUIZ
    // ============================================
    function obterProximaPergunta() {
        if (!quizData || !quizData.temas.length) return null;

        if (estadoAtual.modoAleatorio) {
            // Modo aleatório: escolher tema e pergunta aleatória
            const temasDisponiveis = quizData.temas.filter(t =>
                !estadoAtual.perguntasRespondidas.some(p => p.tema === t.nome && p.indice === t.perguntas.length - 1)
            );

            if (temasDisponiveis.length === 0) {
                // Todas as perguntas foram respondidas
                return null;
            }

            const temaAleatorio = temasDisponiveis[Math.floor(Math.random() * temasDisponiveis.length)];
            const perguntasNaoRespondidas = temaAleatorio.perguntas.filter((_, idx) =>
                !estadoAtual.perguntasRespondidas.some(p => p.tema === temaAleatorio.nome && p.indice === idx)
            );

            if (perguntasNaoRespondidas.length === 0) return null;

            const perguntaAleatoria = perguntasNaoRespondidas[Math.floor(Math.random() * perguntasNaoRespondidas.length)];
            const perguntaIndice = temaAleatorio.perguntas.indexOf(perguntaAleatoria);

            return {
                tema: temaAleatorio.nome,
                pergunta: perguntaAleatoria,
                indice: perguntaIndice,
                total: temaAleatorio.perguntas.length
            };
        } else {
            // Modo sequencial por tema
            if (!estadoAtual.temaAtual) return null;

            const tema = quizData.temas.find(t => t.nome === estadoAtual.temaAtual);
            if (!tema || estadoAtual.perguntaAtual >= tema.perguntas.length) {
                return null;
            }

            return {
                tema: tema.nome,
                pergunta: tema.perguntas[estadoAtual.perguntaAtual],
                indice: estadoAtual.perguntaAtual,
                total: tema.perguntas.length
            };
        }
    }

    function registrarResposta(correta) {
        const pontuacao = getPontuacao();
        if (correta) {
            pontuacao.acertos++;
        } else {
            pontuacao.erros++;
        }
        salvarPontuacao(pontuacao.acertos, pontuacao.erros);

        // Registrar pergunta respondida
        const perguntaKey = `${estadoAtual.temaAtual}_${estadoAtual.perguntaAtual}`;
        if (!estadoAtual.perguntasRespondidas.some(p => p.key === perguntaKey)) {
            estadoAtual.perguntasRespondidas.push({
                tema: estadoAtual.temaAtual,
                indice: estadoAtual.perguntaAtual,
                key: perguntaKey,
                correta: correta
            });
        }

        // Avançar para próxima pergunta
        if (!estadoAtual.modoAleatorio) {
            estadoAtual.perguntaAtual++;
        }

        salvarProgresso();
    }

    // ============================================
    // UI - RENDERIZAÇÃO
    // ============================================
    function mostrarPergunta() {
        const dadosPergunta = obterProximaPergunta();

        if (!dadosPergunta) {
            mostrarFimDeJogo();
            return;
        }

        // Atualizar estado
        estadoAtual.temaAtual = dadosPergunta.tema;
        if (!estadoAtual.modoAleatorio) {
            estadoAtual.perguntaAtual = dadosPergunta.indice;
        }

        // Renderizar pergunta
        const perguntaEl = document.getElementById('quiz-pergunta-texto');
        const opcoesContainer = document.getElementById('quiz-opcoes');
        const temaSelect = document.getElementById('quiz-tema-select');

        perguntaEl.textContent = dadosPergunta.pergunta.pergunta;

        // Renderizar opções
        opcoesContainer.innerHTML = '';
        dadosPergunta.pergunta.respostas.forEach((opcao, idx) => {
            const btn = document.createElement('button');
            btn.className = 'opcao-btn';
            btn.innerHTML = `<span style="font-weight: 600; margin-right: 8px;">${String.fromCharCode(65 + idx)})</span> ${opcao}`;
            btn.dataset.opcao = idx;
            btn.dataset.resposta = opcao;
            btn.onclick = () => verificarResposta(idx, dadosPergunta.pergunta);
            opcoesContainer.appendChild(btn);
        });

        // Atualizar select de tema
        if (temaSelect) {
            temaSelect.value = dadosPergunta.tema;
        }

        // Limpar feedback
        const feedbackEl = document.getElementById('quiz-feedback');
        feedbackEl.className = 'feedback-container';

        // Habilitar botões
        document.querySelectorAll('.opcao-btn').forEach(btn => {
            btn.classList.remove('disabled');
        });
    }

    function verificarResposta(selecionada, perguntaData) {
        const botoes = document.querySelectorAll('.opcao-btn');
        const feedbackEl = document.getElementById('quiz-feedback');
        const correta = perguntaData.correta;
        const respostas = perguntaData.respostas;
        const explicacoes = perguntaData.explicacoes || [];
        const acertou = selecionada === correta;

        // Desabilitar todos os botões
        botoes.forEach((btn, idx) => {
            btn.classList.add('disabled');
            if (idx === correta) {
                btn.classList.add('correct');
            } else if (idx === selecionada && !acertou) {
                btn.classList.add('incorrect');
            }
        });

        // Mostrar feedback
        feedbackEl.className = `feedback-container show ${acertou ? 'correct' : 'incorrect'}`;
        const feedbackText = feedbackEl.querySelector('.feedback-text');
        const feedbackDetail = feedbackEl.querySelector('.feedback-detail');

        if (acertou) {
            feedbackText.textContent = '✓ Correto!';
            // Mostrar explicação da resposta correta
            if (explicacoes[correta]) {
                feedbackDetail.textContent = explicacoes[correta];
            } else {
                feedbackDetail.textContent = 'Você acertou esta pergunta.';
            }
        } else {
            feedbackText.textContent = '✗ Incorreto';

            // Montar texto com explicação da escolhida e da correta
            let explicacaoTexto = '';

            // Explicação da alternativa escolhida (por que está errada)
            if (explicacoes[selecionada]) {
                explicacaoTexto += `Sua escolha: ${explicacoes[selecionada]}\n\n`;
            }

            // Explicação da alternativa correta (por que é a certa)
            if (explicacoes[correta]) {
                explicacaoTexto += `Resposta correta: ${explicacoes[correta]}`;
            }

            feedbackDetail.style.whiteSpace = 'pre-line';
            feedbackDetail.textContent = explicacaoTexto || `A resposta correta é: ${respostas[correta]}`;
        }

        // Registrar resposta
        registrarResposta(acertou);

        // Atualizar pontuação
        atualizarPontuacao();

        // Habilitar botão de próxima pergunta após delay
        setTimeout(() => {
            const continuarBtn = document.getElementById('quiz-continuar-btn');
            if (continuarBtn) {
                continuarBtn.disabled = false;
            }
        }, 500);
    }

    function atualizarPontuacao() {
        const pontuacao = getPontuacao();
        const acertosEl = document.getElementById('quiz-acertos');
        const errosEl = document.getElementById('quiz-erros');

        if (acertosEl) acertosEl.textContent = pontuacao.acertos;
        if (errosEl) errosEl.textContent = pontuacao.erros;
    }

    function mostrarFimDeJogo() {
        const modalBody = document.querySelector('#quiz-modal .modal-body');
        const pontuacao = getPontuacao();
        const total = pontuacao.acertos + pontuacao.erros;
        const percentual = total > 0 ? Math.round((pontuacao.acertos / total) * 100) : 0;

        modalBody.innerHTML = `
            <div style="text-align: center; padding: 30px 20px;">
                <div style="font-size: 48px; margin-bottom: 20px;">🎉</div>
                <h3 style="font-size: 22px; color: #333; margin-bottom: 10px;">Parabéns!</h3>
                <p style="font-size: 16px; color: #666; margin-bottom: 25px;">Você completou todas as perguntas disponíveis!</p>

                <div style="background: #f8f9fa; border-radius: 15px; padding: 25px; margin-bottom: 25px;">
                    <div style="font-size: 36px; font-weight: 700; color: #667eea; margin-bottom: 10px;">${percentual}%</div>
                    <div style="font-size: 14px; color: #999;">Taxa de acertos</div>

                    <div style="display: flex; justify-content: space-around; margin-top: 20px; gap: 20px;">
                        <div>
                            <div style="font-size: 24px; font-weight: 600; color: #22c55e;">${pontuacao.acertos}</div>
                            <div style="font-size: 12px; color: #999;">Acertos</div>
                        </div>
                        <div>
                            <div style="font-size: 24px; font-weight: 600; color: #ef4444;">${pontuacao.erros}</div>
                            <div style="font-size: 12px; color: #999;">Erros</div>
                        </div>
                    </div>
                </div>

                <button onclick="window.quizBiblico.resetarProgresso()" style="padding: 15px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; width: 100%;">
                    Recomeçar Quiz
                </button>
            </div>

            <div class="reset-container">
                <button class="reset-btn" onclick="window.quizBiblico.fecharModal()">Fechar</button>
            </div>
        `;
    }

    function selecionarTema(temaNome) {
        if (temaNome === 'aleatorio') {
            estadoAtual.modoAleatorio = true;
            estadoAtual.temaAtual = null;
        } else {
            estadoAtual.modoAleatorio = false;
            estadoAtual.temaAtual = temaNome;

            // Encontrar primeira pergunta não respondida deste tema
            const respondidas = estadoAtual.perguntasRespondidas.filter(p => p.tema === temaNome);
            estadoAtual.perguntaAtual = respondidas.length;
        }

        salvarProgresso();
        mostrarPergunta();
    }

    // ============================================
    // MODAL
    // ============================================
    function abrirModal() {
        const modal = document.getElementById('quiz-modal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Carregar ou mostrar pergunta
        mostrarPergunta();
        atualizarPontuacao();
    }

    function fecharModal() {
        const modal = document.getElementById('quiz-modal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // ============================================
    // INICIALIZAÇÃO
    // ============================================
    function criarBotaoFlutuante() {
        const btn = document.createElement('button');
        btn.id = 'quiz-biblico-btn';
        btn.innerHTML = '<img src="/quiz/icone_quiz.webp" alt="Teste seu conhecimento bíblico">';
        btn.title = 'Teste seu conhecimento bíblico';
        btn.onclick = abrirModal;
        document.body.appendChild(btn);
    }

    function criarModal() {
        const modal = document.createElement('div');
        modal.id = 'quiz-modal';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">📖 Quiz Bíblico</h3>
                    <button class="modal-close" onclick="window.quizBiblico.fecharModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="tema-selector">
                        <select id="quiz-tema-select" onchange="window.quizBiblico.selecionarTema(this.value)">
                            <option value="aleatorio">🎲 Aleatório</option>
                        </select>
                    </div>

                    <div class="pergunta-container">
                        <p id="quiz-pergunta-texto" class="pergunta-texto">Carregando pergunta...</p>
                    </div>

                    <div id="quiz-opcoes" class="opcoes-container"></div>

                    <div id="quiz-feedback" class="feedback-container">
                        <div class="feedback-text"></div>
                        <div class="feedback-detail"></div>
                    </div>

                    <div class="pontuacao">
                        <div class="pontuacao-item acertos">
                            <span>✓</span>
                            <span>Acertos: <span id="quiz-acertos">0</span></span>
                        </div>
                        <div class="pontuacao-divider"></div>
                        <div class="pontuacao-item erros">
                            <span>✗</span>
                            <span>Erros: <span id="quiz-erros">0</span></span>
                        </div>
                    </div>

                    <div class="acoes-container">
                        <button id="quiz-continuar-btn" class="acao-btn primary" onclick="window.quizBiblico.mostrarPergunta()" disabled>
                            Próxima Pergunta
                        </button>
                        <button class="acao-btn secondary" onclick="window.quizBiblico.abrirSelecaoTema()">
                            Escolher Tema
                        </button>
                    </div>

                    <div class="reset-container">
                        <button class="reset-btn" onclick="window.quizBiblico.resetarProgresso()">Resetar Progresso</button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Fechar ao clicar fora
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                fecharModal();
            }
        });

        // Fechar com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                fecharModal();
            }
        });
    }

    function popularTemas() {
        const select = document.getElementById('quiz-tema-select');
        if (!select || !quizData) return;

        // Limpar opções (mantendo aleatório)
        select.innerHTML = '<option value="aleatorio">🎲 Aleatório</option>';

        // Adicionar temas
        quizData.temas.forEach(tema => {
            const option = document.createElement('option');
            option.value = tema.nome;
            option.textContent = tema.nome;
            select.appendChild(option);
        });
    }

    function abrirSelecaoTema() {
        const select = document.getElementById('quiz-tema-select');
        if (select) {
            select.focus();
            select.click();
        }
    }

    async function init() {
        // Carregar CSS
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = '/quiz/quiz.css';
        document.head.appendChild(link);

        // Carregar dados
        await carregarDados();

        // Carregar progresso salvo
        carregarProgresso();

        // Criar UI
        criarBotaoFlutuante();
        criarModal();
        popularTemas();

        // Expor API globalmente
        window.quizBiblico = {
            abrirModal,
            fecharModal,
            mostrarPergunta,
            selecionarTema,
            resetarProgresso,
            abrirSelecaoTema
        };

        console.log('Quiz Bíblico inicializado!');
    }

    // Iniciar quando DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
