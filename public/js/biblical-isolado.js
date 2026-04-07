/**
 * SISTEMA DE LINKS BÍBLICOS - ISOLADO E SEGURO
 * Não modifica layout, não afeta DOM desnecessariamente
 */

(function() {
    'use strict';

    // Textos bíblicos NVI - APENAS para teste inicial
    const biblicalPassages = {
        // SEÇÃO 7 - TESTE APENAS
        "Apocalipse 13:16-17": "E faz que a todos, pequenos e grandes, ricos e pobres, livres e servos, lhes seja posto um sinal na mão direita ou na testa.",
        "Êxodo 20:8-11": "Lembre-se do dia de sábado, para santificá-lo. Trabalhe seis dias e neles realize toda a sua obra.",
        "Daniel 7:25": "E proferirá palavras contra o Altíssimo, e destruirá os santos do Altíssimo.",
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
