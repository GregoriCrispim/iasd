<?php

namespace App\Services\Face;

use App\Models\GalleryAlbum;
use App\Models\GalleryFaceDescriptor;

/**
 * Busca 1:N com similaridade do @vladmandic/human (0..1, maior = mais parecido).
 *
 * Porta human.match.similarity (order=2) para PHP.
 */
class FaceMatchService
{
    public function __construct(private readonly FaceDescriptorService $descriptors) {}

    /**
     * @param  list<float>|list<list<float>>  $query
     * @return array{photo_ids:list<int>, matches:list<array{photo_id:int,similarity:float}>}
     */
    public function search(GalleryAlbum $album, array $query, ?float $minSimilarity = null, ?int $maxResults = null): array
    {
        $minSimilarity ??= (float) config('face.match_similarity', 0.50);
        $strict = (float) config('face.match_similarity_strict', 0.55);
        if ($strict < $minSimilarity) {
            $strict = $minSimilarity;
        }
        $minLooseScore = (float) config('face.match_loose_min_score', 0.55);
        $minLooseSize = (float) config('face.match_loose_min_size_ratio', 0.04);
        $maxResults ??= (int) config('face.max_results', 200);
        $modelVersion = (string) config('face.version', 'v3');
        $queries = $this->normalizeQueries($query);

        if ($queries === []) {
            return ['photo_ids' => [], 'matches' => []];
        }

        $best = [];

        GalleryFaceDescriptor::query()
            ->where('gallery_album_id', $album->id)
            ->where('model_version', $modelVersion)
            ->orderBy('id')
            ->chunk(500, function ($rows) use ($queries, $minSimilarity, $strict, $minLooseScore, $minLooseSize, &$best) {
                foreach ($rows as $row) {
                    $vector = $this->descriptors->decrypt($row->descriptor);
                    if ($vector === null) {
                        continue;
                    }

                    $similarity = $this->matchSimilarity(
                        $queries,
                        $vector,
                        $minSimilarity,
                        $strict,
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
        ];
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
    private function matchSimilarity(
        array $queries,
        array $vector,
        float $minSimilarity,
        float $strict,
        GalleryFaceDescriptor $row,
        float $minLooseScore,
        float $minLooseSize,
    ): ?float {
        $hits = [];

        foreach ($queries as $q) {
            $similarity = $this->similarity($q, $vector);
            if ($similarity < $minSimilarity) {
                continue;
            }
            $hits[] = $similarity;
        }

        if ($hits === []) {
            return null;
        }

        rsort($hits);
        $best = $hits[0];

        if ($best >= $strict) {
            return $best;
        }

        if (! $this->qualityOk($row, $minLooseScore, $minLooseSize)) {
            return null;
        }

        if (count($queries) === 1 || count($hits) >= 2) {
            return $best;
        }

        return null;
    }

    private function qualityOk(GalleryFaceDescriptor $row, float $minLooseScore, float $minLooseSize): bool
    {
        $score = $row->score !== null ? (float) $row->score : 0.0;
        $size = max((float) ($row->box_w ?? 0), (float) ($row->box_h ?? 0));

        return $score >= $minLooseScore && $size >= $minLooseSize;
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
