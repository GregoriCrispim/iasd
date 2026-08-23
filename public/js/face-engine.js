/**
 * Motor de reconhecimento facial no navegador (sem servidor Node).
 *
 * Carrega o @vladmandic/face-api e os modelos sob demanda a partir das URLs
 * definidas em window.FACE_CONFIG (injetadas pelo Blade a partir de config/face.php).
 * Expõe window.FaceEngine com detecção que devolve descritores de 128 posições.
 *
 * A imagem NUNCA é enviada ao servidor por este módulo: apenas o vetor numérico.
 */
(function () {
    'use strict';

    var scriptPromise = null;
    var modelsPromise = null;

    /**
     * Resolve a configuração de forma preguiçosa: usa window.FACE_CONFIG se
     * existir, senão faz o parse de um <script type="application/json" id="faceConfig">.
     */
    function getConfig() {
        if (window.FACE_CONFIG) return window.FACE_CONFIG;
        var el = document.getElementById('faceConfig');
        if (el) {
            try {
                window.FACE_CONFIG = JSON.parse(el.textContent || '{}');
                return window.FACE_CONFIG;
            } catch (e) { /* ignora */ }
        }
        return {};
    }

    function loadScript() {
        if (scriptPromise) return scriptPromise;
        scriptPromise = new Promise(function (resolve, reject) {
            if (window.faceapi) { resolve(window.faceapi); return; }
            var scriptUrl = getConfig().scriptUrl;
            if (!scriptUrl) { reject(new Error('URL do face-api não configurada.')); return; }
            var s = document.createElement('script');
            s.src = scriptUrl;
            s.async = true;
            s.onload = function () {
                if (window.faceapi) resolve(window.faceapi);
                else reject(new Error('face-api carregou mas não expôs a API.'));
            };
            s.onerror = function () {
                scriptPromise = null;
                reject(new Error('Falha ao carregar o face-api (' + scriptUrl + '). Verifique se o arquivo existe no servidor.'));
            };
            document.head.appendChild(s);
        });
        return scriptPromise;
    }

    function loadModels() {
        if (modelsPromise) return modelsPromise;
        modelsPromise = loadScript().then(function (faceapi) {
            var modelsUrl = getConfig().modelsUrl;
            if (!modelsUrl) throw new Error('URL dos modelos não configurada.');
            return Promise.all([
                faceapi.nets.ssdMobilenetv1.loadFromUri(modelsUrl),
                faceapi.nets.faceLandmark68Net.loadFromUri(modelsUrl),
                faceapi.nets.faceRecognitionNet.loadFromUri(modelsUrl)
            ]).then(function () { return faceapi; });
        }).catch(function (err) {
            // Permite nova tentativa após falha (ex.: assets ainda não descompactados).
            modelsPromise = null;
            scriptPromise = null;
            var msg = err && err.message ? err.message : String(err);
            if (/loadFromUri|404|Failed to fetch|NetworkError|fetch/i.test(msg)) {
                throw new Error('Falha ao carregar os modelos faciais. Confirme se /models/face-api/1.7.15/ está na pasta pública (veja deploy/LEIA-ME-face-api.txt).');
            }
            throw err;
        });
        return modelsPromise;
    }

    /**
     * Reduz uma imagem para no máximo maxSide px no maior lado, para acelerar
     * a análise. Devolve um canvas pronto para o face-api.
     */
    function toAnalysisCanvas(source, maxSide) {
        var w = source.naturalWidth || source.videoWidth || source.width;
        var h = source.naturalHeight || source.videoHeight || source.height;
        if (!w || !h) return source;
        var side = Math.max(w, h);
        var scale = side > maxSide ? maxSide / side : 1;
        var cw = Math.max(1, Math.round(w * scale));
        var ch = Math.max(1, Math.round(h * scale));
        var canvas = document.createElement('canvas');
        canvas.width = cw;
        canvas.height = ch;
        canvas.getContext('2d').drawImage(source, 0, 0, cw, ch);
        return canvas;
    }

    /** Espelha horizontalmente (câmera frontal / selfie invertida). */
    function mirrorCanvas(source) {
        var w = source.naturalWidth || source.videoWidth || source.width;
        var h = source.naturalHeight || source.videoHeight || source.height;
        if (!w || !h) return source;
        var canvas = document.createElement('canvas');
        canvas.width = w;
        canvas.height = h;
        var ctx = canvas.getContext('2d');
        ctx.translate(w, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(source, 0, 0, w, h);
        return canvas;
    }

    function loadImage(url) {
        return new Promise(function (resolve, reject) {
            var img = new Image();
            img.crossOrigin = 'anonymous';
            img.decoding = 'async';
            img.onload = function () { resolve(img); };
            img.onerror = function () { reject(new Error('Falha ao carregar a imagem para análise.')); };
            img.src = url;
        });
    }

    function normalizeResult(res, canvas) {
        var box = res.detection.box;
        var cw = canvas.width || 1;
        var ch = canvas.height || 1;
        return {
            descriptor: Array.prototype.slice.call(res.descriptor),
            score: res.detection.score,
            box: {
                // Guardamos a caixa em proporção (0..1) para ficar independente do tamanho.
                x: box.x / cw,
                y: box.y / ch,
                width: box.width / cw,
                height: box.height / ch
            },
            sizeRatio: Math.max(box.width / cw, box.height / ch)
        };
    }

    var FaceEngine = {
        preload: function () { return loadModels(); },

        /**
         * Detecta TODOS os rostos (fotos de evento).
         * opts: { maxSide, minScore, minSizeRatio, maxFaces }
         */
        detectAll: function (source, opts) {
            opts = opts || {};
            return loadModels().then(function (faceapi) {
                var canvas = toAnalysisCanvas(source, opts.maxSide || 1536);
                var options = new faceapi.SsdMobilenetv1Options({ minConfidence: opts.minScore || 0.35 });
                return faceapi.detectAllFaces(canvas, options)
                    .withFaceLandmarks()
                    .withFaceDescriptors()
                    .then(function (results) {
                        var faces = results.map(function (r) { return normalizeResult(r, canvas); });
                        faces = faces.filter(function (f) {
                            return f.sizeRatio >= (opts.minSizeRatio || 0) && f.descriptor.length === 128;
                        });
                        // Mantém os rostos com maior confiança (SSD não garante ordem).
                        faces.sort(function (a, b) { return (b.score || 0) - (a.score || 0); });
                        if (opts.maxFaces && faces.length > opts.maxFaces) {
                            faces = faces.slice(0, opts.maxFaces);
                        }
                        return faces;
                    });
            });
        },

        /**
         * Detecta UM único rosto (selfie). Exige nitidez e proporção mínima.
         * Resolve com o rosto ou lança erro descritivo.
         * opts: { maxSide, minScore, minSizeRatio }
         */
        detectSingle: function (source, opts) {
            opts = opts || {};
            return loadModels().then(function (faceapi) {
                var canvas = toAnalysisCanvas(source, opts.maxSide || 720);
                var options = new faceapi.SsdMobilenetv1Options({ minConfidence: opts.minScore || 0.65 });
                return faceapi.detectAllFaces(canvas, options)
                    .withFaceLandmarks()
                    .withFaceDescriptors()
                    .then(function (results) {
                        if (!results.length) {
                            throw new Error('Nenhum rosto foi detectado. Use uma foto nítida e de frente.');
                        }
                        var faces = results.map(function (r) { return normalizeResult(r, canvas); });
                        var big = faces.filter(function (f) { return f.sizeRatio >= (opts.minSizeRatio || 0.10); });
                        if (!big.length) {
                            throw new Error('O rosto está pequeno demais. Aproxime-se da câmera.');
                        }
                        if (big.length > 1) {
                            throw new Error('Mais de um rosto foi detectado. Envie uma foto somente sua.');
                        }
                        return big[0];
                    });
            });
        },

        /**
         * Detecta o rosto na imagem e também na versão espelhada (útil com
         * câmera frontal). Resolve com { descriptor, extraDescriptors, ... }.
         */
        detectSingleWithMirror: function (source, opts) {
            var self = this;
            return self.detectSingle(source, opts).then(function (primary) {
                var mirrored = mirrorCanvas(source);
                return self.detectSingle(mirrored, opts).then(function (alt) {
                    var extras = [];
                    if (alt && alt.descriptor && alt.descriptor.length === 128) {
                        extras.push(alt.descriptor);
                    }
                    return {
                        descriptor: primary.descriptor,
                        extraDescriptors: extras,
                        score: primary.score,
                        box: primary.box,
                        sizeRatio: primary.sizeRatio
                    };
                }).catch(function () {
                    return {
                        descriptor: primary.descriptor,
                        extraDescriptors: [],
                        score: primary.score,
                        box: primary.box,
                        sizeRatio: primary.sizeRatio
                    };
                });
            });
        },

        loadImage: loadImage
    };

    window.FaceEngine = FaceEngine;
})();
