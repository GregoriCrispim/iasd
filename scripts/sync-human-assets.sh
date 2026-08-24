#!/usr/bin/env bash
# Copia o @vladmandic/human (bundle IIFE) e os modelos de face necessários para public/.
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
PKG="$ROOT/node_modules/@vladmandic/human"
VERSION="3.3.6"
DEST_JS="$ROOT/public/js/vendor"
DEST_MODELS="$ROOT/public/models/human/$VERSION"

if [[ ! -f "$PKG/dist/human.js" ]]; then
  echo "Instale as deps primeiro: npm install @vladmandic/human" >&2
  exit 1
fi

mkdir -p "$DEST_JS" "$DEST_MODELS"
cp "$PKG/dist/human.js" "$DEST_JS/human-$VERSION.js"

# Detector + mesh + description (embedding 1024-D). Demais módulos ficam desligados no FaceEngine.
for base in blazeface facemesh faceres; do
  cp "$PKG/models/${base}.json" "$DEST_MODELS/"
  cp "$PKG/models/${base}.bin" "$DEST_MODELS/"
done

# models.json ajuda o Human a resolver nomes; copiamos o manifesto completo (leve).
cp "$PKG/models/models.json" "$DEST_MODELS/"

echo "Assets Human sincronizados em:"
echo "  $DEST_JS/human-$VERSION.js"
echo "  $DEST_MODELS/"
