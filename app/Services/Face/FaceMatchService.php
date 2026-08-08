<?php

namespace App\Services\Face;

use App\Models\GalleryAlbum;
use App\Models\GalleryFaceDescriptor;

/**
 * Busca 1:N: compara um descriptor de consulta com os descritores gravados
 * apenas do álbum atual, retornando os IDs das fotos correspondentes.
 */
class FaceMatchService
{
    public function __construct(private readonly FaceDescriptorService $descriptors) {}

    /**
     * @param  list<float>  $query
     * @return array{photo_ids:list<int>, matches:list<array{photo_id:int,distance:float}>}
     */
    public function search(GalleryAlbum $album, array $query, ?float $threshold = null, ?int $maxResults = null): array
    {
        $threshold ??= (float) config('face.match_threshold', 0.50);
        $maxResults ??= (int) config('face.max_results', 200);
        $modelVersion = (string) config('face.version', 'v1');

        $best = [];

        GalleryFaceDescriptor::query()
            ->where('gallery_album_id', $album->id)
            ->where('model_version', $modelVersion)
            ->orderBy('id')
            ->chunk(500, function ($rows) use ($query, $threshold, &$best) {
                foreach ($rows as $row) {
                    $vector = $this->descriptors->decrypt($row->descriptor);
                    if ($vector === null) {
                        continue;
                    }

                    $distance = $this->euclidean($query, $vector, $threshold);
                    if ($distance === null || $distance > $threshold) {
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
