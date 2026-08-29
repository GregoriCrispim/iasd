#!/usr/bin/env bash
# Copia o face-api e os 3 modelos necessários de node_modules para public/.
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
PKG="$ROOT/node_modules/@vladmandic/face-api"
VERSION="1.7.15"
DEST_JS="$ROOT/public/js/vendor"
DEST_MODELS="$ROOT/public/models/face-api/$VERSION"

if [[ ! -f "$PKG/dist/face-api.js" ]]; then
  echo "Instale as deps primeiro: npm install" >&2
  exit 1
fi

mkdir -p "$DEST_JS" "$DEST_MODELS"
cp "$PKG/dist/face-api.js" "$DEST_JS/face-api-$VERSION.js"

for base in ssd_mobilenetv1_model face_landmark_68_model face_recognition_model; do
  cp "$PKG/model/${base}-weights_manifest.json" "$DEST_MODELS/"
  cp "$PKG/model/${base}.bin" "$DEST_MODELS/"
done

echo "Assets sincronizados em:"
echo "  $DEST_JS/face-api-$VERSION.js"
echo "  $DEST_MODELS/"
