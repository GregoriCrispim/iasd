<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\SpiritualGiftsScorer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SpiritualGiftsScorerTest extends TestCase
{
    private const GIFTS = 25;
    private const QUESTIONS = 125;

    private function gifts(): array
    {
        return array_map(
            static fn (int $i): array => ['name' => "Dom {$i}"],
            range(1, self::GIFTS)
        );
    }

    /**
     * Cada dom é avaliado pelas perguntas N, N+25, N+50, N+75 e N+100.
     */
    private function giftIndexes(): array
    {
        return array_map(
            static fn (int $i): int => $i % self::GIFTS,
            range(0, self::QUESTIONS - 1)
        );
    }

    private function score(array $answers): array
    {
        return (new SpiritualGiftsScorer())->score($answers, $this->gifts(), $this->giftIndexes());
    }

    public function test_five_responses_are_summed_into_each_gift(): void
    {
        $answers = array_fill(0, self::QUESTIONS, 1);

        // As cinco afirmações do primeiro dom valem 4, 3, 2, 1 e 4.
        $answers[0] = 4;
        $answers[25] = 3;
        $answers[50] = 2;
        $answers[75] = 1;
        $answers[100] = 4;

        $result = $this->score($answers);

        self::assertSame(14, $result['results'][0]['points']);
        self::assertSame(14, $result['highest_points']);
        self::assertSame(1.0, $result['results'][0]['proportion']);
        self::assertSame(20, $result['max_points_per_gift']);
    }

    public function test_results_come_in_gift_list_order(): void
    {
        $answers = array_fill(0, self::QUESTIONS, 1);

        // Dom 3 (perguntas 2, 27, 52, 77, 102) recebe o máximo.
        foreach ([2, 27, 52, 77, 102] as $i) {
            $answers[$i] = 4;
        }

        $result = $this->score($answers);

        self::assertSame('Dom 3', $result['results'][2]['name']);
        self::assertSame(20, $result['results'][2]['points']);
        self::assertSame(1, $result['results'][2]['rank']);
        // Demais dons empatam na 2ª posição, mas cada um segue na sua ordem.
        self::assertSame(5, $result['results'][0]['points']);
        self::assertSame(2, $result['results'][0]['rank']);
        self::assertSame('Dom 1', $result['results'][0]['name']);
        self::assertSame('Dom 25', $result['results'][24]['name']);
    }

    public function test_ties_share_the_same_rank(): void
    {
        $answers = array_fill(0, self::QUESTIONS, 1);

        // Dom 1 e Dom 2 terão a mesma pontuação máxima.
        foreach ([0, 25, 50, 75, 100] as $i) {
            $answers[$i] = 4;
        }
        foreach ([1, 26, 51, 76, 101] as $i) {
            $answers[$i] = 4;
        }

        $result = $this->score($answers);

        self::assertSame(1, $result['results'][0]['rank']);
        self::assertSame(1, $result['results'][1]['rank']);
        self::assertSame(20, $result['results'][0]['points']);
        self::assertSame(20, $result['results'][1]['points']);
        self::assertSame(3, $result['results'][2]['rank']);
    }

    public function test_invalid_answer_is_rejected(): void
    {
        $answers = array_fill(0, self::QUESTIONS, 1);
        $answers[10] = 5;

        $this->expectException(InvalidArgumentException::class);
        $this->score($answers);
    }

    public function test_zero_answer_is_rejected(): void
    {
        $answers = array_fill(0, self::QUESTIONS, 1);
        $answers[10] = 0;

        $this->expectException(InvalidArgumentException::class);
        $this->score($answers);
    }

    public function test_missing_answer_is_rejected(): void
    {
        $answers = array_fill(0, self::QUESTIONS - 1, 1);

        $this->expectException(InvalidArgumentException::class);
        $this->score($answers);
    }
}
