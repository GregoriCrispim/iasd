<?php

namespace Tests\Unit;

use App\Services\Face\FaceDescriptorService;
use App\Services\Face\FaceMatchService;
use Tests\TestCase;

class FaceMatchServiceTest extends TestCase
{
    private function dims(): int
    {
        return (new FaceDescriptorService)->dimensions();
    }

    private function base(): array
    {
        $v = [];
        for ($i = 0; $i < $this->dims(); $i++) {
            $v[] = 0.01 * ($i % 100);
        }

        return $v;
    }

    private function shift(array $v, float $delta): array
    {
        $v[0] += $delta;

        return $v;
    }

    public function test_identical_vectors_have_similarity_one(): void
    {
        $svc = new FaceMatchService(new FaceDescriptorService);
        $a = $this->base();

        $this->assertSame(1.0, $svc->similarity($a, $a));
    }

    public function test_far_vectors_fall_below_match_threshold(): void
    {
        $svc = new FaceMatchService(new FaceDescriptorService);
        $a = $this->base();
        $b = array_map(static fn ($x) => $x + 1.0, $a);

        $this->assertLessThan(0.50, $svc->similarity($a, $b));
    }

    public function test_calibrated_deltas_sit_in_expected_bands(): void
    {
        $svc = new FaceMatchService(new FaceDescriptorService);
        $a = $this->base();

        // delta≈9.4 → ~0.55 (limite estrito); delta≈9.8 → ~0.52 (faixa folgada).
        $this->assertGreaterThanOrEqual(0.55, $svc->similarity($a, $this->shift($a, 9.4)));
        $simLoose = $svc->similarity($a, $this->shift($a, 9.8));
        $this->assertGreaterThanOrEqual(0.50, $simLoose);
        $this->assertLessThan(0.55, $simLoose);
    }
}
