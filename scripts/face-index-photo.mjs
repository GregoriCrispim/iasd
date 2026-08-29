/**
 * Indexa rostos de uma foto de álbum (CLI para Jobs Laravel).
 *
 * Uso:
 *   node scripts/face-index-photo.mjs --image=/abs/path.jpg --models=/abs/models/dir
 *
 * Saída JSON em stdout:
 *   { "ok": true, "status": "ready"|"no_face", "faces": [...], "reason": null }
 */
import fs from 'node:fs';
import path from 'node:path';
import { createRequire } from 'node:module';
import canvas from 'canvas';
import { setWasmPaths } from '@tensorflow/tfjs-backend-wasm';
import * as tf from '@tensorflow/tfjs';
import * as faceapi from '@vladmandic/face-api/dist/face-api.node-wasm.js';

const require = createRequire(import.meta.url);
const { Canvas, Image, ImageData, loadImage } = canvas;

faceapi.env.monkeyPatch({ Canvas, Image, ImageData });

function arg(name, fallback = null) {
    const prefix = `--${name}=`;
    for (let i = 0; i < process.argv.length; i++) {
        const token = process.argv[i];
        if (token.startsWith(prefix)) {
            return token.slice(prefix.length) || fallback;
        }
        if (token === `--${name}`) {
            return process.argv[i + 1] || fallback;
        }
    }
    return fallback;
}

function emit(payload, code = 0) {
    process.stdout.write(JSON.stringify(payload));
    process.exit(code);
}

function fail(message, code = 1) {
    emit({ ok: false, status: 'failed', faces: [], reason: String(message).slice(0, 200) }, code);
}

const imagePath = arg('image');
const modelsDir = arg('models');
const minScore = Number(arg('minScore', '0.30'));
const minSizeRatio = Number(arg('minSizeRatio', '0.01'));
const maxFaces = Number(arg('maxFaces', '80'));
const maxSide = Number(arg('maxSide', '1536'));

if (!imagePath || !fs.existsSync(imagePath)) {
    fail('Imagem não encontrada.');
}
if (!modelsDir || !fs.existsSync(modelsDir)) {
    fail('Diretório de modelos não encontrado.');
}

function toAnalysisCanvas(img) {
    const w = img.width;
    const h = img.height;
    const side = Math.max(w, h);
    const scale = side > maxSide ? maxSide / side : 1;
    const cw = Math.max(1, Math.round(w * scale));
    const ch = Math.max(1, Math.round(h * scale));
    const out = canvas.createCanvas(cw, ch);
    out.getContext('2d').drawImage(img, 0, 0, cw, ch);
    return out;
}

function mirrorCanvas(src) {
    const out = canvas.createCanvas(src.width, src.height);
    const ctx = out.getContext('2d');
    ctx.translate(src.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(src, 0, 0);
    return out;
}

function normalizeResult(res, cnv) {
    const box = res.detection.box;
    const cw = cnv.width || 1;
    const ch = cnv.height || 1;
    return {
        descriptor: Array.from(res.descriptor),
        score: res.detection.score,
        box: {
            x: box.x / cw,
            y: box.y / ch,
            width: box.width / cw,
            height: box.height / ch,
        },
        sizeRatio: Math.max(box.width / cw, box.height / ch),
    };
}

function iou(a, b) {
    const ax2 = a.x + a.width;
    const ay2 = a.y + a.height;
    const bx2 = b.x + b.width;
    const by2 = b.y + b.height;
    const ix1 = Math.max(a.x, b.x);
    const iy1 = Math.max(a.y, b.y);
    const ix2 = Math.min(ax2, bx2);
    const iy2 = Math.min(ay2, by2);
    const iw = Math.max(0, ix2 - ix1);
    const ih = Math.max(0, iy2 - iy1);
    const inter = iw * ih;
    if (inter <= 0) return 0;
    const union = a.width * a.height + b.width * b.height - inter;
    return union > 0 ? inter / union : 0;
}

function mirrorBox(box) {
    return {
        x: 1 - (box.x + box.width),
        y: box.y,
        width: box.width,
        height: box.height,
    };
}

/** Acrescenta descriptor espelhado de cada rosto (IoU ≥ 0,25). */
function withMirrorVariants(faces, mirroredFaces) {
    const out = [];
    for (const face of faces) {
        out.push(face);
        const expected = mirrorBox(face.box);
        let best = null;
        let bestIou = 0;
        for (const alt of mirroredFaces) {
            const score = iou(expected, alt.box);
            if (score > bestIou) {
                bestIou = score;
                best = alt;
            }
        }
        if (best && bestIou >= 0.25 && best.descriptor.length === 128) {
            out.push({
                descriptor: best.descriptor,
                score: face.score,
                box: face.box,
                sizeRatio: face.sizeRatio,
            });
        }
    }
    return out;
}

try {
    const wasmPath = path.dirname(require.resolve('@tensorflow/tfjs-backend-wasm/wasm-out/tfjs-backend-wasm.wasm'));
    setWasmPaths(wasmPath + path.sep);
    await tf.setBackend('wasm');
    await tf.ready();

    await Promise.all([
        faceapi.nets.ssdMobilenetv1.loadFromDisk(modelsDir),
        faceapi.nets.faceLandmark68Net.loadFromDisk(modelsDir),
        faceapi.nets.faceRecognitionNet.loadFromDisk(modelsDir),
    ]);

    const img = await loadImage(imagePath);
    const analysis = toAnalysisCanvas(img);
    const options = new faceapi.SsdMobilenetv1Options({ minConfidence: minScore });
    const results = await faceapi
        .detectAllFaces(analysis, options)
        .withFaceLandmarks()
        .withFaceDescriptors();

    let faces = results.map((r) => normalizeResult(r, analysis));
    faces = faces.filter((f) => f.sizeRatio >= minSizeRatio && f.descriptor.length === 128);
    // Mantém os rostos com maior confiança (SSD não garante ordem).
    faces.sort((a, b) => (b.score || 0) - (a.score || 0));
    if (faces.length > maxFaces) {
        faces = faces.slice(0, maxFaces);
    }

    if (faces.length) {
        const mirrored = mirrorCanvas(analysis);
        const mirroredResults = await faceapi
            .detectAllFaces(mirrored, options)
            .withFaceLandmarks()
            .withFaceDescriptors();
        const mirroredFaces = mirroredResults
            .map((r) => normalizeResult(r, mirrored))
            .filter((f) => f.sizeRatio >= minSizeRatio && f.descriptor.length === 128);
        faces = withMirrorVariants(faces, mirroredFaces);
        emit({ ok: true, status: 'ready', faces, reason: null });
    } else {
        emit({ ok: true, status: 'no_face', faces: [], reason: 'Nenhum rosto detectado.' });
    }
} catch (err) {
    fail(err && err.message ? err.message : err);
}
