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

    private function orthogonal(): array
    {
        $v = [];
        for ($i = 0; $i < $this->dims(); $i++) {
            $v[] = (($i % 2) ? 1 : -1) * 0.01 * (($i + 37) % 100);
        }

        return $v;
    }

    public function test_identical_vectors_have_similarity_and_cosine_one(): void
    {
        $svc = new FaceMatchService(new FaceDescriptorService);
        $a = $this->base();

        $this->assertSame(1.0, $svc->similarity($a, $a));
        $this->assertSame(1.0, $svc->cosine($a, $a));
    }

    public function test_orthogonal_vectors_are_below_match_thresholds(): void
    {
        $svc = new FaceMatchService(new FaceDescriptorService);
        $a = $this->base();
        $b = $this->orthogonal();

        $this->assertLessThan(0.42, $svc->cosine($a, $b));
        $this->assertLessThan(0.35, $svc->similarity($a, $b));
    }

    public function test_near_blend_lands_in_loose_cosine_band(): void
    {
        $svc = new FaceMatchService(new FaceDescriptorService);
        $a = $this->base();
        $orth = $this->orthogonal();
        $mixed = [];
        for ($i = 0; $i < $this->dims(); $i++) {
            $mixed[] = 0.35 * $a[$i] + 0.65 * $orth[$i];
        }

        $cos = $svc->cosine($a, $mixed);
        $this->assertGreaterThanOrEqual(0.42, $cos);
        $this->assertLessThan(0.55, $cos);
    }
}
