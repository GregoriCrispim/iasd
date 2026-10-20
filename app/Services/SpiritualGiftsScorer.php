<?php

declare(strict_types=1);

namespace App\Services;

use InvalidArgumentException;

final class SpiritualGiftsScorer
{
    /**
     * @param array<int|string, int|string> $answers Respostas de 1 a 4, na ordem das perguntas
     * @param array<int, array{name:string}> $gifts Dons na ordem da lista do questionário
     * @param array<int, int> $giftIndexes Índice do dom avaliado por cada pergunta
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
     * } O resultado vem na ordem da lista de dons; "rank" indica a posição por pontuação.
     */
    public function score(array $answers, array $gifts, array $giftIndexes): array
    {
        $giftCount = count($gifts);
        $questionCount = count($giftIndexes);

        if ($giftCount === 0 || count($answers) !== $questionCount) {
            throw new InvalidArgumentException(
                sprintf('O teste exige exatamente %d respostas.', $questionCount)
            );
        }

        $minPoints = 1;
        $maxPoints = 4;

        $normalized = [];
        foreach ($answers as $key => $value) {
            $index = is_string($key) && str_starts_with($key, 'question')
                ? (int) substr($key, 8)
                : (int) $key;

            $score = filter_var($value, FILTER_VALIDATE_INT);
            if ($score === false || $score < $minPoints || $score > $maxPoints) {
                throw new InvalidArgumentException(
                    sprintf('Cada resposta deve ser um inteiro entre %d e %d.', $minPoints, $maxPoints)
                );
            }
            $normalized[$index] = $score;
        }

        ksort($normalized);

        if (array_keys($normalized) !== range(0, $questionCount - 1)) {
            throw new InvalidArgumentException(
                sprintf('As %d respostas devem estar presentes exatamente uma vez.', $questionCount)
            );
        }

        $totals = array_fill(0, $giftCount, 0);

        foreach ($normalized as $questionIndex => $score) {
            $giftIndex = $giftIndexes[$questionIndex];
            if (! array_key_exists($giftIndex, $totals)) {
                throw new InvalidArgumentException('Pergunta aponta para um dom inexistente.');
            }
            $totals[$giftIndex] += $score;
        }

        // Pontos por dom, na ordem da lista do questionário.
        $results = [];
        foreach ($gifts as $index => $gift) {
            $results[] = [
                'original_index' => $index,
                'name' => (string) $gift['name'],
                'points' => $totals[$index],
                'rank' => 0,
            ];
        }

        // Ranking calculado em uma cópia ordenada por pontuação (empate: ordem da lista).
        $ranked = $results;
        usort($ranked, static function (array $a, array $b): int {
            if ($a['points'] === $b['points']) {
                return $a['original_index'] <=> $b['original_index'];
            }
            return $b['points'] <=> $a['points'];
        });

        $rank = 0;
        $position = 0;
        $lastPoints = null;
        foreach ($ranked as $rankedResult) {
            $position++;

            if ($lastPoints === null || $rankedResult['points'] !== $lastPoints) {
                $rank = $position;
                $lastPoints = $rankedResult['points'];
            }

            $results[$rankedResult['original_index']]['rank'] = $rank;
        }

        $highest = $ranked[0]['points'];

        foreach ($results as &$result) {
            $result['proportion'] = $highest > 0
                ? round($result['points'] / $highest, 6)
                : 0.0;
            $result['percentage'] = $highest > 0
                ? (int) round($result['points'] * 100 / $highest)
                : 0;
            unset($result['original_index']);
        }
        unset($result);

        $statementsPerGift = intdiv($questionCount, $giftCount);

        return [
            'total_questions' => $questionCount,
            'total_gifts' => $giftCount,
            'max_points_per_gift' => $statementsPerGift * $maxPoints,
            'highest_points' => $highest,
            'results' => $results,
        ];
    }
}
