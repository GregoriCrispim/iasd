<?php

namespace App\Services\Face;

use App\Models\GalleryAlbum;
use App\Models\GalleryFaceDescriptor;

/**
 * Busca 1:N de rostos indexados.
 *
 * Usa cosseno (estável em embeddings Human 1024-D sob pose/luz) e, em
 * paralelo, a similaridade oficial human.match (order=2). Basta um dos dois
 * critérios passar.
 */
class FaceMatchService
{
    public function __construct(private readonly FaceDescriptorService $descriptors) {}

    /**
     * @param  list<float>|list<list<float>>  $query
     * @return array{photo_ids:list<int>, matches:list<array{photo_id:int,similarity:float}>, match_rev:int}
     */
    public function search(GalleryAlbum $album, array $query, ?float $minSimilarity = null, ?int $maxResults = null): array
    {
        $minCosine = (float) config('face.match_cosine', 0.42);
        $strictCosine = (float) config('face.match_cosine_strict', 0.55);
        if ($strictCosine < $minCosine) {
            $strictCosine = $minCosine;
        }

        $minHuman = $minSimilarity ?? (float) config('face.match_similarity', 0.35);
        $strictHuman = (float) config('face.match_similarity_strict', 0.48);
        if ($strictHuman < $minHuman) {
            $strictHuman = $minHuman;
        }

        $minLooseScore = (float) config('face.match_loose_min_score', 0.25);
        $minLooseSize = (float) config('face.match_loose_min_size_ratio', 0.015);
        $maxResults ??= (int) config('face.max_results', 200);
        $modelVersion = (string) config('face.version', 'v3');
        $queries = $this->normalizeQueries($query);

        if ($queries === []) {
            return ['photo_ids' => [], 'matches' => [], 'match_rev' => $this->revision()];
        }

        $best = [];

        GalleryFaceDescriptor::query()
            ->where('gallery_album_id', $album->id)
            ->where('model_version', $modelVersion)
            ->orderBy('id')
            ->chunk(500, function ($rows) use (
                $queries,
                $minCosine,
                $strictCosine,
                $minHuman,
                $strictHuman,
                $minLooseScore,
                $minLooseSize,
                &$best
            ) {
                foreach ($rows as $row) {
                    $vector = $this->descriptors->decrypt($row->descriptor);
                    if ($vector === null) {
                        continue;
                    }

                    $similarity = $this->matchScore(
                        $queries,
                        $vector,
                        $minCosine,
                        $strictCosine,
                        $minHuman,
                        $strictHuman,
                        $row,
                        $minLooseScore,
                        $minLooseSize,
                    );
                    if ($similarity === null) {
                        continue;
                    }

                    $photoId = (int) $row->gallery_photo_id;
                    if (! isset($best[$photoId]) || $similarity > $best[$photoId]) {
                        $best[$photoId] = $similarity;
                    }
                }
            });

        arsort($best);
        $best = array_slice($best, 0, $maxResults, true);

        $matches = [];
        foreach ($best as $photoId => $similarity) {
            $matches[] = ['photo_id' => $photoId, 'similarity' => round($similarity, 4)];
        }

        return [
            'photo_ids' => array_map(static fn ($m) => $m['photo_id'], $matches),
            'matches' => $matches,
            'match_rev' => $this->revision(),
        ];
    }

    public function revision(): int
    {
        return (int) config('face.match_revision', 2);
    }

    /**
     * @param  list<float>|list<list<float>>  $query
     * @return list<list<float>>
     */
    private function normalizeQueries(array $query): array
    {
        if ($query === []) {
            return [];
        }

        $dimensions = $this->descriptors->dimensions();

        if (is_array($query[0] ?? null)) {
            $out = [];
            foreach ($query as $candidate) {
                if (is_array($candidate) && count($candidate) === $dimensions) {
                    $out[] = array_map(static fn ($v) => (float) $v, $candidate);
                }
            }

            return $out;
        }

        if (count($query) !== $dimensions) {
            return [];
        }

        return [array_map(static fn ($v) => (float) $v, $query)];
    }

    /**
     * @param  list<list<float>>  $queries
     * @param  list<float>  $vector
     */
    private function matchScore(
        array $queries,
        array $vector,
        float $minCosine,
        float $strictCosine,
        float $minHuman,
        float $strictHuman,
        GalleryFaceDescriptor $row,
        float $minLooseScore,
        float $minLooseSize,
    ): ?float {
        $best = null;

        foreach ($queries as $q) {
            $cosine = $this->cosine($q, $vector);
            $human = $this->similarity($q, $vector);
            $score = max($cosine, $human);

            $strictHit = $cosine >= $strictCosine || $human >= $strictHuman;
            $looseHit = $cosine >= $minCosine || $human >= $minHuman;

            if (! $strictHit && ! $looseHit) {
                continue;
            }

            if (! $strictHit && ! $this->qualityOk($row, $minLooseScore, $minLooseSize)) {
                continue;
            }

            if ($best === null || $score > $best) {
                $best = $score;
            }
        }

        return $best;
    }

    private function qualityOk(GalleryFaceDescriptor $row, float $minLooseScore, float $minLooseSize): bool
    {
        if ($row->score === null && $row->box_w === null && $row->box_h === null) {
            return true;
        }

        $score = $row->score !== null ? (float) $row->score : 1.0;
        $size = max((float) ($row->box_w ?? 0), (float) ($row->box_h ?? 0));
        if ($size <= 0) {
            $size = 1.0;
        }

        return $score >= $minLooseScore && $size >= $minLooseSize;
    }

    /**
     * Cosseno em [0, 1] (valores negativos viram 0 — embeddings faciais).
     *
     * @param  list<float>  $a
     * @param  list<float>  $b
     */
    public function cosine(array $a, array $b): float
    {
        $n = count($a);
        if ($n === 0 || count($b) !== $n) {
            return 0.0;
        }

        $dot = 0.0;
        $na = 0.0;
        $nb = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $dot += $a[$i] * $b[$i];
            $na += $a[$i] * $a[$i];
            $nb += $b[$i] * $b[$i];
        }

        if ($na < 1e-12 || $nb < 1e-12) {
            return 0.0;
        }

        $cos = $dot / (sqrt($na) * sqrt($nb));

        return round(100 * max(min($cos, 1.0), 0.0)) / 100;
    }

    /**
     * Porta de human.match.similarity (order=2).
     *
     * @param  list<float>  $a
     * @param  list<float>  $b
     */
    public function similarity(array $a, array $b): float
    {
        $n = count($a);
        if ($n === 0 || count($b) !== $n) {
            return 0.0;
        }

        $multiplier = (float) config('face.match_similarity_multiplier', 25);
        $min = (float) config('face.match_similarity_min', 0.2);
        $max = (float) config('face.match_similarity_max', 0.8);
        if ($max <= $min) {
            $max = $min + 0.0001;
        }

        $sum = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $diff = $a[$i] - $b[$i];
            $sum += $diff * $diff;
        }

        $dist = round(100 * $multiplier * $sum) / 100;
        if ($dist === 0.0) {
            return 1.0;
        }

        $root = sqrt($dist);
        $norm = (1 - ($root / 100) - $min) / ($max - $min);

        return round(100 * max(min($norm, 1.0), 0.0)) / 100;
    }
}
