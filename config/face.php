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
    |
    | v3: @vladmandic/human (embedding 1024-D + similaridade).
    */
    'version' => env('FACE_MODEL_VERSION', 'v3'),

    /*
    |--------------------------------------------------------------------------
    | Dimensão do descriptor
    |--------------------------------------------------------------------------
    */
    'descriptor_dimensions' => (int) env('FACE_DESCRIPTOR_DIMENSIONS', 1024),

    /*
    |--------------------------------------------------------------------------
    | Assets Human (carregados no navegador)
    |--------------------------------------------------------------------------
    | Bundle IIFE + modelos locais. Regenerar: ./scripts/sync-human-assets.sh
    */
    'models_url' => env('FACE_MODELS_URL', '/models/human/3.3.6'),
    'script_url' => env('FACE_SCRIPT_URL', '/js/vendor/human-3.3.6.js'),

    /*
    |--------------------------------------------------------------------------
    | Correspondência (match) 1:N
    |--------------------------------------------------------------------------
    | match_revision: sobe quando a lógica muda (permite confirmar deploy).
    |
    | Cosseno: métrica principal para embeddings Human 1024-D em fotos de
    | evento (pose/luz). A similaridade oficial Human (order=2) também conta.
    | Basta um dos dois critérios passar.
    |
    | Faixa estrita: aceita sempre.
    | Faixa folgada: exige qualidade mínima do rosto indexado.
    */
    'match_revision' => 2,
    'match_cosine_strict' => (float) env('FACE_MATCH_COSINE_STRICT', 0.55),
    'match_cosine' => (float) env('FACE_MATCH_COSINE', 0.42),
    'match_similarity_strict' => (float) env('FACE_MATCH_SIMILARITY_STRICT', 0.48),
    'match_similarity' => (float) env('FACE_MATCH_SIMILARITY', 0.35),
    'match_loose_min_score' => (float) env('FACE_MATCH_LOOSE_MIN_SCORE', 0.25),
    'match_loose_min_size_ratio' => (float) env('FACE_MATCH_LOOSE_MIN_SIZE', 0.015),
    'match_similarity_multiplier' => (float) env('FACE_MATCH_SIMILARITY_MULTIPLIER', 25),
    'match_similarity_min' => (float) env('FACE_MATCH_SIMILARITY_MIN', 0.2),
    'match_similarity_max' => (float) env('FACE_MATCH_SIMILARITY_MAX', 0.8),
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
            'min_score' => (float) env('FACE_PHOTO_MIN_SCORE', 0.30),
            'max_faces' => (int) env('FACE_PHOTO_MAX_FACES', 80),
            'min_size_ratio' => (float) env('FACE_PHOTO_MIN_SIZE_RATIO', 0.01),
            'analysis_max_side' => (int) env('FACE_PHOTO_ANALYSIS_SIDE', 1536),
        ],
        'selfie' => [
            'min_score' => (float) env('FACE_SELFIE_MIN_SCORE', 0.50),
            'min_size_ratio' => (float) env('FACE_SELFIE_MIN_SIZE_RATIO', 0.10),
            'analysis_max_side' => (int) env('FACE_SELFIE_ANALYSIS_SIDE', 720),
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
    | Indexação no servidor (legado face-api / Node)
    |--------------------------------------------------------------------------
    | Desativado no fluxo de upload: na HostGator a indexação é só no browser
    | do admin (Human). O script Node face-api permanece no repositório como
    | legado e não é disparado.
    */
    'node_binary' => env('FACE_NODE_BINARY', ''),
];
