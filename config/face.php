<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Habilitação global
    |--------------------------------------------------------------------------
    | Permite desligar toda a busca facial e a indexação sem remover código.
    */
    'enabled' => (bool) env('FACE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Versão do modelo / índice
    |--------------------------------------------------------------------------
    | Alterar esta versão invalida os descritores já gravados: eles continuam
    | no banco, mas a busca compara apenas descritores da versão atual, e a
    | reindexação administrativa deve reprocessar as fotos.
    */
    'version' => env('FACE_MODEL_VERSION', 'v1'),

    /*
    |--------------------------------------------------------------------------
    | Modelos face-api (carregados no navegador)
    |--------------------------------------------------------------------------
    | O pacote versionado é deploy/face-api-assets.zip. No CI ele é extraído em
    | public/ antes do FTP. Ver deploy/LEIA-ME-face-api.txt.
    */
    'models_url' => env('FACE_MODELS_URL', '/models/face-api/1.7.15'),
    'script_url' => env('FACE_SCRIPT_URL', '/js/vendor/face-api-1.7.15.js'),

    /*
    |--------------------------------------------------------------------------
    | Correspondência (match) 1:N
    |--------------------------------------------------------------------------
    | Distância euclidiana entre descritores de 128 dimensões. Quanto menor,
    | mais parecido. 0,50 é um limiar conservador para reduzir falsos positivos.
    */
    'match_threshold' => (float) env('FACE_MATCH_THRESHOLD', 0.50),
    'max_results' => (int) env('FACE_MAX_RESULTS', 200),

    /*
    |--------------------------------------------------------------------------
    | Limites de qualidade / quantidade na detecção
    |--------------------------------------------------------------------------
    | 'selfie' é a foto enviada pelo membro (exige exatamente um rosto nítido).
    | 'photo' é a foto de evento (aceita vários rostos, inclusive menores).
    */
    'detection' => [
        'photo' => [
            'min_score' => (float) env('FACE_PHOTO_MIN_SCORE', 0.50),
            'max_faces' => (int) env('FACE_PHOTO_MAX_FACES', 60),
            'min_size_ratio' => (float) env('FACE_PHOTO_MIN_SIZE_RATIO', 0.02),
            'analysis_max_side' => (int) env('FACE_PHOTO_ANALYSIS_SIDE', 1024),
        ],
        'selfie' => [
            'min_score' => (float) env('FACE_SELFIE_MIN_SCORE', 0.70),
            'min_size_ratio' => (float) env('FACE_SELFIE_MIN_SIZE_RATIO', 0.12),
            'analysis_max_side' => (int) env('FACE_SELFIE_ANALYSIS_SIDE', 640),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Termo de consentimento
    |--------------------------------------------------------------------------
    | Versão do termo aceito na busca; incrementar quando o texto mudar.
    */
    'consent_version' => env('FACE_CONSENT_VERSION', '2026-07-25'),

    /*
    |--------------------------------------------------------------------------
    | Indexação no servidor (Node)
    |--------------------------------------------------------------------------
    | Após o upload, um Job dispara `scripts/face-index-photo.mjs` (face-api +
    | canvas + tfjs-wasm). Defina FACE_NODE_BINARY se o `node` não estiver no PATH.
    */
    'node_binary' => env('FACE_NODE_BINARY', ''),
];
