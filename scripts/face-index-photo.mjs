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
const minScore = Number(arg('minScore', '0.5'));
const minSizeRatio = Number(arg('minSizeRatio', '0.02'));
const maxFaces = Number(arg('maxFaces', '60'));
const maxSide = Number(arg('maxSide', '1024'));

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
    if (faces.length > maxFaces) {
        faces = faces.slice(0, maxFaces);
    }

    if (faces.length) {
        emit({ ok: true, status: 'ready', faces, reason: null });
    } else {
        emit({ ok: true, status: 'no_face', faces: [], reason: 'Nenhum rosto detectado.' });
    }
} catch (err) {
    fail(err && err.message ? err.message : err);
}
