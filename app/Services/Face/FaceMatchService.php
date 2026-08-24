<?php

namespace App\Services\Face;

use App\Models\GalleryAlbum;
use App\Models\GalleryFaceDescriptor;

/**
 * Busca 1:N: compara um (ou mais) descriptors de consulta com os descritores
 * gravados apenas do álbum atual, retornando os IDs das fotos correspondentes.
 *
 * Usa limiar em duas faixas: estrita (sempre aceita) e folgada (exige qualidade
 * mínima do rosto indexado — score e tamanho da caixa).
 */
class FaceMatchService
{
    public function __construct(private readonly FaceDescriptorService $descriptors) {}

    /**
     * @param  list<float>|list<list<float>>  $query  Um descriptor ou vários (OR: menor distância vence).
     * @return array{photo_ids:list<int>, matches:list<array{photo_id:int,distance:float}>}
     */
    public function search(GalleryAlbum $album, array $query, ?float $threshold = null, ?int $maxResults = null): array
    {
        $loose = $threshold ?? (float) config('face.match_threshold', 0.60);
        $strict = (float) config('face.match_threshold_strict', 0.52);
        if ($strict > $loose) {
            $strict = $loose;
        }
        $minLooseScore = (float) config('face.match_loose_min_score', 0.45);
        $minLooseSize = (float) config('face.match_loose_min_size_ratio', 0.015);
        $maxResults ??= (int) config('face.max_results', 200);
        $modelVersion = (string) config('face.version', 'v2');
        $queries = $this->normalizeQueries($query);

        if ($queries === []) {
            return ['photo_ids' => [], 'matches' => []];
        }

        $best = [];

        GalleryFaceDescriptor::query()
            ->where('gallery_album_id', $album->id)
            ->where('model_version', $modelVersion)
            ->orderBy('id')
            ->chunk(500, function ($rows) use ($queries, $loose, $strict, $minLooseScore, $minLooseSize, &$best) {
                foreach ($rows as $row) {
                    $vector = $this->descriptors->decrypt($row->descriptor);
                    if ($vector === null) {
                        continue;
                    }

                    $distance = $this->bestDistance($queries, $vector, $loose);
                    if ($distance === null || $distance > $loose) {
                        continue;
                    }

                    if (! $this->passesThreshold($distance, $strict, $loose, $row, $minLooseScore, $minLooseSize)) {
                        continue;
                    }

                    $photoId = (int) $row->gallery_photo_id;
                    // Deduplicação por foto: fica a menor distância encontrada.
                    if (! isset($best[$photoId]) || $distance < $best[$photoId]) {
                        $best[$photoId] = $distance;
                    }
                }
            });

        asort($best);
        $best = array_slice($best, 0, $maxResults, true);

        $matches = [];
        foreach ($best as $photoId => $distance) {
            $matches[] = ['photo_id' => $photoId, 'distance' => round($distance, 4)];
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

        // Lista de descriptors: primeiro elemento é um array (vetor).
        if (is_array($query[0] ?? null)) {
            $out = [];
            foreach ($query as $candidate) {
                if (is_array($candidate) && count($candidate) === 128) {
                    $out[] = array_map(static fn ($v) => (float) $v, $candidate);
                }
            }

            return $out;
        }

        if (count($query) !== 128) {
            return [];
        }

        return [array_map(static fn ($v) => (float) $v, $query)];
    }

    /**
     * @param  list<list<float>>  $queries
     * @param  list<float>  $vector
     */
    private function bestDistance(array $queries, array $vector, float $threshold): ?float
    {
        $best = null;

        foreach ($queries as $q) {
            $distance = $this->euclidean($q, $vector, $threshold);
            if ($distance === null) {
                continue;
            }
            if ($best === null || $distance < $best) {
                $best = $distance;
            }
            if ($best <= 0.0) {
                break;
            }
        }

        return $best;
    }

    private function passesThreshold(
        float $distance,
        float $strict,
        float $loose,
        GalleryFaceDescriptor $row,
        float $minLooseScore,
        float $minLooseSize,
    ): bool {
        if ($distance <= $strict) {
            return true;
        }

        if ($distance > $loose) {
            return false;
        }

        $score = $row->score !== null ? (float) $row->score : 0.0;
        $size = max((float) ($row->box_w ?? 0), (float) ($row->box_h ?? 0));

        return $score >= $minLooseScore && $size >= $minLooseSize;
    }

    /**
     * Distância euclidiana com interrupção antecipada: se a soma parcial dos
     * quadrados já ultrapassa o limiar², nem termina o cálculo.
     *
     * @param  list<float>  $a
     * @param  list<float>  $b
     */
    public function euclidean(array $a, array $b, ?float $threshold = null): ?float
    {
        $n = count($a);
        if ($n === 0 || count($b) !== $n) {
            return null;
        }

        $limit = $threshold !== null ? $threshold * $threshold : null;
        $sum = 0.0;

        for ($i = 0; $i < $n; $i++) {
            $diff = $a[$i] - $b[$i];
            $sum += $diff * $diff;
            if ($limit !== null && $sum > $limit) {
                return sqrt($sum);
            }
        }

        return sqrt($sum);
    }
}
