#!/usr/bin/env bash
# Gera deploy/face-api-assets.zip para extrair na raiz pública do site (public/ ou public_html/).
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
VERSION="1.7.15"
OUT_DIR="$ROOT/deploy"
ZIP="$OUT_DIR/face-api-assets.zip"
STAGING="$OUT_DIR/.face-api-staging"

need=(
  "public/js/vendor/face-api-$VERSION.js"
  "public/js/face-engine.js"
  "public/js/face-search.js"
  "public/models/face-api/$VERSION/ssd_mobilenetv1_model-weights_manifest.json"
  "public/models/face-api/$VERSION/ssd_mobilenetv1_model.bin"
  "public/models/face-api/$VERSION/face_landmark_68_model-weights_manifest.json"
  "public/models/face-api/$VERSION/face_landmark_68_model.bin"
  "public/models/face-api/$VERSION/face_recognition_model-weights_manifest.json"
  "public/models/face-api/$VERSION/face_recognition_model.bin"
)

for f in "${need[@]}"; do
  if [[ ! -f "$ROOT/$f" ]]; then
    echo "Arquivo ausente: $f" >&2
    echo "Rode: npm install && ./scripts/sync-face-api-assets.sh" >&2
    exit 1
  fi
done

rm -rf "$STAGING"
mkdir -p "$STAGING/js/vendor" "$STAGING/js" "$STAGING/models/face-api/$VERSION"

# Estrutura relativa à pasta pública do site (public / public_html).
cp "$ROOT/public/js/vendor/face-api-$VERSION.js" "$STAGING/js/vendor/"
cp "$ROOT/public/js/face-engine.js" "$STAGING/js/"
cp "$ROOT/public/js/face-search.js" "$STAGING/js/"
cp "$ROOT/public/models/face-api/$VERSION/"* "$STAGING/models/face-api/$VERSION/"

cat > "$STAGING/LEIA-ME.txt" << 'TXT'
Reconhecimento facial — assets para o servidor
==============================================

O processamento roda NO NAVEGADOR. Estes arquivos precisam existir
na pasta pública do site (em Laravel: public/; na Hostgator costuma
ser a raiz do domínio / public_html).

Como instalar
-------------
1. Faça upload deste ZIP para a pasta pública do site.
2. Extraia ali (os caminhos js/ e models/ devem ficar na raiz pública).
3. Confirme no navegador (substitua pelo seu domínio):

   https://SEU-DOMINIO/js/vendor/face-api-1.7.15.js
   https://SEU-DOMINIO/models/face-api/1.7.15/ssd_mobilenetv1_model-weights_manifest.json
   https://SEU-DOMINIO/models/face-api/1.7.15/ssd_mobilenetv1_model.bin

   Os três devem retornar HTTP 200 (o .bin tem ~5 MB).

4. Volte em Admin → Galeria → Reconhecimento facial e clique
   em "Processar pendentes".

Conteúdo
--------
js/vendor/face-api-1.7.15.js
js/face-engine.js
js/face-search.js
models/face-api/1.7.15/  (3 manifests .json + 3 pesos .bin)
TXT

mkdir -p "$OUT_DIR"
rm -f "$ZIP"
( cd "$STAGING" && zip -r -9 "$ZIP" . )
rm -rf "$STAGING"

echo "Gerado: $ZIP ($(du -h "$ZIP" | cut -f1))"
