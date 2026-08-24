/**
 * Motor de reconhecimento facial no navegador (@vladmandic/human).
 *
 * Carrega Human + modelos locais sob demanda a partir de window.FACE_CONFIG
 * (Blade / config/face.php). Expõe window.FaceEngine com a mesma API usada
 * por face-search.js e pelas telas admin de indexação.
 *
 * A imagem NUNCA é enviada ao servidor por este módulo: apenas o vetor 1024-D.
 */
(function () {
    'use strict';

    var scriptPromise = null;
    var humanPromise = null;
    var humanInstance = null;
    var DESCRIPTOR_DIM = 1024;

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

    /** O IIFE de human-*.js exporta um namespace { Human, default, ... }, não a classe. */
    function resolveHumanCtor() {
        var ns = window.Human;
        if (typeof ns === 'function') return ns;
        if (ns && typeof ns.Human === 'function') return ns.Human;
        if (ns && typeof ns.default === 'function') return ns.default;
        return null;
    }

    function loadScript() {
        if (scriptPromise) return scriptPromise;
        scriptPromise = new Promise(function (resolve, reject) {
            var existing = resolveHumanCtor();
            if (existing) {
                resolve(existing);
                return;
            }
            var scriptUrl = getConfig().scriptUrl;
            if (!scriptUrl) {
                reject(new Error('URL do Human não configurada.'));
                return;
            }
            var s = document.createElement('script');
            s.src = scriptUrl;
            s.async = true;
            s.onload = function () {
                var ctor = resolveHumanCtor();
                if (ctor) resolve(ctor);
                else reject(new Error('Human carregou mas não expôs o construtor.'));
            };
            s.onerror = function () {
                scriptPromise = null;
                reject(new Error('Falha ao carregar o Human (' + scriptUrl + ').'));
            };
            document.head.appendChild(s);
        });
        return scriptPromise;
    }

    function buildHumanConfig(maxDetected, minConfidence) {
        var modelsUrl = getConfig().modelsUrl || '/models/human/3.3.6';
        // Human espera barra final no modelBasePath.
        if (modelsUrl.slice(-1) !== '/') modelsUrl += '/';

        return {
            modelBasePath: modelsUrl,
            backend: 'webgl',
            debug: false,
            async: true,
            warmup: 'none',
            face: {
                enabled: true,
                detector: {
                    modelPath: 'blazeface.json',
                    rotation: true,
                    maxDetected: maxDetected,
                    minConfidence: minConfidence,
                    return: true
                },
                mesh: {
                    enabled: true,
                    modelPath: 'facemesh.json'
                },
                description: {
                    enabled: true,
                    modelPath: 'faceres.json'
                },
                iris: { enabled: false },
                emotion: { enabled: false },
                antispoof: { enabled: false },
                liveness: { enabled: false },
                attention: { enabled: false },
                gear: { enabled: false }
            },
            body: { enabled: false },
            hand: { enabled: false },
            gesture: { enabled: false },
            object: { enabled: false },
            segmentation: { enabled: false },
            filter: { enabled: false }
        };
    }

    function getHuman(maxDetected, minConfidence) {
        return loadScript().then(function (HumanCtor) {
            if (humanInstance) {
                // Atualiza limites por chamada (foto de álbum vs selfie).
                humanInstance.config.face.detector.maxDetected = maxDetected;
                humanInstance.config.face.detector.minConfidence = minConfidence;
                return humanInstance;
            }
            humanInstance = new HumanCtor(buildHumanConfig(maxDetected, minConfidence));
            humanPromise = humanInstance.load().then(function () {
                return humanInstance.warmup();
            }).then(function () {
                return humanInstance;
            }).catch(function (err) {
                humanInstance = null;
                humanPromise = null;
                var msg = err && err.message ? err.message : String(err);
                if (/404|Failed to fetch|NetworkError|fetch|load/i.test(msg)) {
                    throw new Error('Falha ao carregar os modelos Human. Confirme se /models/human/3.3.6/ está público (./scripts/sync-human-assets.sh).');
                }
                throw err;
            });
            return humanPromise;
        });
    }

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

    /** boxRaw do Human: [x, y, width, height] normalizado 0..1 */
    function normalizeFace(face) {
        var raw = face.boxRaw || face.box || [0, 0, 0, 0];
        var x = Number(raw[0]) || 0;
        var y = Number(raw[1]) || 0;
        var w = Number(raw[2]) || 0;
        var h = Number(raw[3]) || 0;
        var emb = face.embedding;
        if (!emb || !emb.length) return null;
        return {
            descriptor: Array.prototype.slice.call(emb),
            score: typeof face.score === 'number' ? face.score : (face.boxScore || 0),
            box: { x: x, y: y, width: w, height: h },
            sizeRatio: Math.max(w, h)
        };
    }

    function iou(a, b) {
        var ax2 = a.x + a.width, ay2 = a.y + a.height;
        var bx2 = b.x + b.width, by2 = b.y + b.height;
        var ix1 = Math.max(a.x, b.x), iy1 = Math.max(a.y, b.y);
        var ix2 = Math.min(ax2, bx2), iy2 = Math.min(ay2, by2);
        var iw = Math.max(0, ix2 - ix1), ih = Math.max(0, iy2 - iy1);
        var inter = iw * ih;
        if (inter <= 0) return 0;
        var union = a.width * a.height + b.width * b.height - inter;
        return union > 0 ? inter / union : 0;
    }

    function mirrorBox(box) {
        return { x: 1 - (box.x + box.width), y: box.y, width: box.width, height: box.height };
    }

    function withMirrorVariants(faces, mirroredFaces) {
        var out = [];
        for (var i = 0; i < faces.length; i++) {
            var face = faces[i];
            out.push(face);
            var expected = mirrorBox(face.box);
            var best = null, bestIou = 0;
            for (var j = 0; j < mirroredFaces.length; j++) {
                var alt = mirroredFaces[j];
                var score = iou(expected, alt.box);
                if (score > bestIou) { bestIou = score; best = alt; }
            }
            if (best && bestIou >= 0.25 && best.descriptor.length === DESCRIPTOR_DIM) {
                out.push({
                    descriptor: best.descriptor,
                    score: face.score,
                    box: face.box,
                    sizeRatio: face.sizeRatio
                });
            }
        }
        return out;
    }

    function descriptorDistance(a, b) {
        if (!a || !b || a.length !== b.length) return 999;
        var sum = 0;
        for (var i = 0; i < a.length; i++) {
            var d = a[i] - b[i];
            sum += d * d;
        }
        return Math.sqrt(sum);
    }

    function dedupeDescriptors(primary, extras, max) {
        var out = [];
        var refs = [primary];
        for (var i = 0; i < extras.length && out.length < max; i++) {
            var cand = extras[i];
            if (!cand || cand.length !== DESCRIPTOR_DIM) continue;
            var ok = true;
            for (var j = 0; j < refs.length; j++) {
                if (descriptorDistance(cand, refs[j]) < 0.35) { ok = false; break; }
            }
            if (ok) { out.push(cand); refs.push(cand); }
        }
        return out;
    }

    function detectOnCanvas(human, canvas) {
        return human.detect(canvas).then(function (result) {
            var faces = [];
            var list = (result && result.face) ? result.face : [];
            for (var i = 0; i < list.length; i++) {
                var n = normalizeFace(list[i]);
                if (n && n.descriptor.length === DESCRIPTOR_DIM) faces.push(n);
            }
            return faces;
        });
    }

    var FaceEngine = {
        preload: function () {
            return getHuman(20, 0.3);
        },

        detectAll: function (source, opts) {
            opts = opts || {};
            var maxSide = opts.maxSide || 1536;
            var minScore = opts.minScore || 0.30;
            var maxFaces = opts.maxFaces || 80;
            var minSize = opts.minSizeRatio || 0;

            return getHuman(maxFaces, minScore).then(function (human) {
                var canvas = toAnalysisCanvas(source, maxSide);
                return detectOnCanvas(human, canvas).then(function (faces) {
                    faces = faces.filter(function (f) {
                        return f.sizeRatio >= minSize && f.descriptor.length === DESCRIPTOR_DIM;
                    });
                    faces.sort(function (a, b) { return (b.score || 0) - (a.score || 0); });
                    if (faces.length > maxFaces) faces = faces.slice(0, maxFaces);
                    if (!faces.length) return faces;

                    var mirrored = mirrorCanvas(canvas);
                    return detectOnCanvas(human, mirrored).then(function (mirroredFaces) {
                        mirroredFaces = mirroredFaces.filter(function (f) {
                            return f.sizeRatio >= minSize && f.descriptor.length === DESCRIPTOR_DIM;
                        });
                        return withMirrorVariants(faces, mirroredFaces);
                    }).catch(function () { return faces; });
                });
            });
        },

        detectSingle: function (source, opts) {
            opts = opts || {};
            var maxSide = opts.maxSide || 720;
            var minScore = opts.minScore || 0.50;
            var minSize = opts.minSizeRatio || 0.10;

            return getHuman(5, minScore).then(function (human) {
                var canvas = toAnalysisCanvas(source, maxSide);
                return detectOnCanvas(human, canvas).then(function (faces) {
                    if (!faces.length) {
                        throw new Error('Nenhum rosto foi detectado. Use uma foto nítida e de frente.');
                    }
                    var big = faces.filter(function (f) { return f.sizeRatio >= minSize; });
                    if (!big.length) {
                        throw new Error('O rosto está pequeno demais. Aproxime-se da câmera.');
                    }
                    big.sort(function (a, b) { return (b.sizeRatio || 0) - (a.sizeRatio || 0); });
                    if (big.length > 1 && big[1].sizeRatio > minSize * 1.2) {
                        throw new Error('Mais de um rosto foi detectado. Envie uma foto somente sua.');
                    }
                    return big[0];
                });
            });
        },

        detectSingleWithMirror: function (source, opts) {
            return this.detectSingleWithProbes(source, opts);
        },

        detectSingleWithProbes: function (source, opts) {
            var self = this;
            return self.detectSingle(source, opts).then(function (primary) {
                var extras = [];
                return self.detectSingle(mirrorCanvas(source), opts).then(function (alt) {
                    if (alt && alt.descriptor && alt.descriptor.length === DESCRIPTOR_DIM) {
                        extras.push(alt.descriptor);
                    }
                }).catch(function () { /* ignora */ }).then(function () {
                    return {
                        descriptor: primary.descriptor,
                        extraDescriptors: dedupeDescriptors(primary.descriptor, extras, 4),
                        score: primary.score,
                        box: primary.box,
                        sizeRatio: primary.sizeRatio
                    };
                });
            });
        },

        detectFromSources: function (sources, opts) {
            var self = this;
            if (!sources || !sources.length) {
                return Promise.reject(new Error('Nenhuma imagem para analisar.'));
            }
            return self.detectSingleWithProbes(sources[0], opts).then(function (primary) {
                var extras = primary.extraDescriptors.slice();
                var rest = sources.slice(1);
                return rest.reduce(function (chain, src) {
                    return chain.then(function () {
                        return self.detectSingle(src, opts).then(function (face) {
                            if (face && face.descriptor) extras.push(face.descriptor);
                            return self.detectSingle(mirrorCanvas(src), opts).then(function (alt) {
                                if (alt && alt.descriptor) extras.push(alt.descriptor);
                            }).catch(function () {});
                        }).catch(function () {});
                    });
                }, Promise.resolve()).then(function () {
                    return {
                        descriptor: primary.descriptor,
                        extraDescriptors: dedupeDescriptors(primary.descriptor, extras, 4),
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
