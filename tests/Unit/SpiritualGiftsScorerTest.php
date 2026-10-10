<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\SpiritualGiftsScorer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SpiritualGiftsScorerTest extends TestCase
{
    private function gifts(): array
    {
        return array_map(
            static fn (int $i): array => ['name' => "Dom {$i}"],
            range(1, 19)
        );
    }

    public function test_four_responses_are_summed_into_each_gift(): void
    {
        $answers = array_fill(0, 76, 0);

        // As quatro afirmações do primeiro dom são 3, 2, 1 e 0.
        $answers[0] = 3;
        $answers[19] = 2;
        $answers[38] = 1;
        $answers[57] = 0;

        $result = (new SpiritualGiftsScorer())->score($answers, $this->gifts());

        self::assertSame(6, $result['results'][0]['points']);
        self::assertSame(6, $result['highest_points']);
        self::assertSame(1.0, $result['results'][0]['proportion']);
    }

    public function test_all_zero_scores_do_not_divide_by_zero(): void
    {
        $result = (new SpiritualGiftsScorer())->score(
            array_fill(0, 76, 0),
            $this->gifts()
        );

        self::assertSame(0, $result['highest_points']);
        self::assertSame(0.0, $result['results'][0]['proportion']);
        self::assertSame(0, $result['results'][0]['percentage']);
    }

    public function test_ties_share_the_same_rank(): void
    {
        $answers = array_fill(0, 76, 0);

        // Dom 1 e Dom 2 terão 12 pontos.
        foreach ([0, 19, 38, 57] as $i) {
            $answers[$i] = 3;
        }
        foreach ([1, 20, 39, 58] as $i) {
            $answers[$i] = 3;
        }

        $result = (new SpiritualGiftsScorer())->score($answers, $this->gifts());

        self::assertSame(1, $result['results'][0]['rank']);
        self::assertSame(1, $result['results'][1]['rank']);
        self::assertSame(12, $result['results'][0]['points']);
        self::assertSame(12, $result['results'][1]['points']);
    }

    public function test_invalid_answer_is_rejected(): void
    {
        $answers = array_fill(0, 76, 0);
        $answers[10] = 4;

        $this->expectException(InvalidArgumentException::class);
        (new SpiritualGiftsScorer())->score($answers, $this->gifts());
    }

    public function test_missing_answer_is_rejected(): void
    {
        $answers = array_fill(0, 75, 0);

        $this->expectException(InvalidArgumentException::class);
        (new SpiritualGiftsScorer())->score($answers, $this->gifts());
    }
}
