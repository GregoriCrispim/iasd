<?php

declare(strict_types=1);

namespace App\Services;

use InvalidArgumentException;

final class SpiritualGiftsScorer
{
    /**
     * @param array<int|string, int|string> $answers
     * @param array<int, array{name:string}> $gifts
     * @return array{
     *   total_questions:int,
     *   total_gifts:int,
     *   max_points_per_gift:int,
     *   highest_points:int,
     *   results:array<int, array{
     *      rank:int,
     *      name:string,
     *      points:int,
     *      proportion:float,
     *      percentage:int
     *   }>
     * }
     */
    public function score(array $answers, array $gifts): array
    {
        $giftCount = count($gifts);

        if ($giftCount !== 19) {
            throw new InvalidArgumentException('O conjunto de dons deve conter exatamente 19 dons.');
        }

        if (count($answers) !== 76) {
            throw new InvalidArgumentException('O teste exige exatamente 76 respostas.');
        }

        $normalized = [];
        foreach ($answers as $key => $value) {
            $index = is_string($key) && str_starts_with($key, 'question')
                ? (int) substr($key, 8)
                : (int) $key;

            $score = filter_var($value, FILTER_VALIDATE_INT);
            if ($score === false || $score < 0 || $score > 3) {
                throw new InvalidArgumentException('Cada resposta deve ser um inteiro entre 0 e 3.');
            }
            $normalized[$index] = $score;
        }

        ksort($normalized);

        if (array_keys($normalized) !== range(0, 75)) {
            throw new InvalidArgumentException('As 76 respostas devem estar presentes exatamente uma vez.');
        }

        $totals = array_fill(0, $giftCount, 0);

        foreach ($normalized as $questionIndex => $score) {
            $giftIndex = $questionIndex % $giftCount;
            $totals[$giftIndex] += $score;
        }

        $results = [];
        foreach ($gifts as $index => $gift) {
            $results[] = [
                'original_index' => $index,
                'name' => (string) $gift['name'],
                'points' => $totals[$index],
            ];
        }

        usort($results, static function (array $a, array $b): int {
            if ($a['points'] === $b['points']) {
                return $a['original_index'] <=> $b['original_index'];
            }
            return $b['points'] <=> $a['points'];
        });

        $highest = $results[0]['points'];
        $rank = 0;
        $position = 0;
        $lastPoints = null;

        foreach ($results as &$result) {
            $position++;

            if ($lastPoints === null || $result['points'] !== $lastPoints) {
                $rank = $position;
                $lastPoints = $result['points'];
            }

            $result['rank'] = $rank;
            $result['proportion'] = $highest > 0
                ? round($result['points'] / $highest, 6)
                : 0.0;
            $result['percentage'] = $highest > 0
                ? (int) round($result['points'] * 100 / $highest)
                : 0;
            unset($result['original_index']);
        }
        unset($result);

        return [
            'total_questions' => 76,
            'total_gifts' => 19,
            'max_points_per_gift' => 12,
            'highest_points' => $highest,
            'results' => $results,
        ];
    }
}
