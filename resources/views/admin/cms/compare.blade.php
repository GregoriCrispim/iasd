<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comparação — CMS</title>
    <style>
        body { margin: 0; font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; background: #0b1220; color: #e2e8f0; }
        header { padding: 12px 16px; border-bottom: 1px solid rgba(148,163,184,.2); background: rgba(15,23,42,.7); backdrop-filter: blur(10px); position: sticky; top: 0; z-index: 5; }
        header .meta { font-size: 13px; color: rgba(226,232,240,.85); }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 12px; }
        .panel { border: 1px solid rgba(148,163,184,.2); border-radius: 12px; overflow: hidden; background: #0f172a; min-height: 70vh; }
        .panel h2 { margin: 0; padding: 10px 12px; font-size: 14px; background: rgba(2,132,199,.12); border-bottom: 1px solid rgba(148,163,184,.2); }
        iframe { width: 100%; height: 70vh; border: 0; background: #fff; }
        .diffWrap { padding: 12px; }
        .diff { white-space: pre-wrap; word-break: break-word; font-size: 13px; line-height: 1.6; background: #0f172a; border: 1px solid rgba(148,163,184,.2); border-radius: 12px; padding: 12px; }
        .ins { background: rgba(34,197,94,.25); padding: 0 2px; border-radius: 4px; }
        .del { background: rgba(239,68,68,.25); padding: 0 2px; border-radius: 4px; text-decoration: line-through; }
        @media (max-width: 980px) { .grid { grid-template-columns: 1fr; } iframe { height: 55vh; } }
    </style>
</head>
<body>
<header>
    <div><strong>Comparação visual</strong></div>
    <div class="meta">
        Página: {{ $cmsRevision->block?->page?->label }} |
        Bloco: {{ $cmsRevision->block?->label }} ({{ $cmsRevision->block?->block_key }}) |
        Autor: {{ $cmsRevision->author?->name }} |
        Status: {{ $cmsRevision->status }}
    </div>
</header>

<div class="grid">
    <div class="panel">
        <h2>Publicado (site)</h2>
        <iframe src="{{ $publishedUrl }}"></iframe>
    </div>
    <div class="panel">
        <h2>Proposto (preview)</h2>
        <iframe src="{{ $previewUrl }}"></iframe>
    </div>
</div>

<div class="diffWrap">
    <h2 style="margin: 0 0 8px 0; font-size: 14px;">Diferenças (HTML)</h2>
    <div id="diff" class="diff">Calculando…</div>
</div>

<script type="application/json" id="cmsPublishedHtml">@json($publishedHtml)</script>
<script type="application/json" id="cmsProposedHtml">@json($proposedHtml)</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/diff_match_patch/20121119/diff_match_patch.js" integrity="sha512-5Z9T3MtZtQohwZPrcFJrckR2S84Vn5E1w9fS3t8+QkS9o9fYf8aCEV8JdZ0pY0cM2yYkzS8J6Vq5Uo0fE+JZ8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    const published = JSON.parse(document.getElementById('cmsPublishedHtml').textContent || '\"\"');
    const proposed = JSON.parse(document.getElementById('cmsProposedHtml').textContent || '\"\"');

    const dmp = new diff_match_patch();
    const diffs = dmp.diff_main(published, proposed);
    dmp.diff_cleanupSemantic(diffs);

    const diffEl = document.getElementById('diff');
    diffEl.textContent = '';

    const frag = document.createDocumentFragment();
    for (const [op, text] of diffs) {
        if (!text) continue;
        const span = document.createElement('span');
        span.textContent = text;
        if (op === 1) span.className = 'ins';
        if (op === -1) span.className = 'del';
        frag.appendChild(span);
    }
    diffEl.appendChild(frag);
</script>
</body>
</html>

