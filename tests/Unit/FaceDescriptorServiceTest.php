<?php

namespace Tests\Unit;

use App\Services\Face\FaceDescriptorService;
use RuntimeException;
use Tests\TestCase;

class FaceDescriptorServiceTest extends TestCase
{
    private function vector(float $seed = 0.0): array
    {
        $dims = (int) config('face.descriptor_dimensions', FaceDescriptorService::DIMENSIONS);
        $v = [];
        for ($i = 0; $i < $dims; $i++) {
            $v[] = sin($i * 0.1 + $seed);
        }

        return $v;
    }

    public function test_validate_accepts_configured_finite_numbers(): void
    {
        $service = new FaceDescriptorService;
        $clean = $service->validate($this->vector());

        $this->assertCount($service->dimensions(), $clean);
        $this->assertContainsOnly('float', $clean);
    }

    public function test_validate_rejects_wrong_dimension(): void
    {
        $this->expectException(RuntimeException::class);
        (new FaceDescriptorService)->validate(array_fill(0, 64, 0.1));
    }

    public function test_validate_rejects_non_finite(): void
    {
        $this->expectException(RuntimeException::class);
        $v = $this->vector();
        $v[10] = INF;
        (new FaceDescriptorService)->validate($v);
    }

    public function test_encrypt_decrypt_round_trip(): void
    {
        $service = new FaceDescriptorService;
        $vector = $service->validate($this->vector(1.23));
        $dims = $service->dimensions();

        $encrypted = $service->encrypt($vector);
        $this->assertNotEquals(implode(',', $vector), $encrypted);

        $decrypted = $service->decrypt($encrypted);
        $this->assertNotNull($decrypted);
        $this->assertCount($dims, $decrypted);

        for ($i = 0; $i < $dims; $i++) {
            // Float32 tem precisão limitada; a tolerância cobre o arredondamento.
            $this->assertEqualsWithDelta($vector[$i], $decrypted[$i], 1e-5);
        }
    }

    public function test_decrypt_returns_null_for_garbage(): void
    {
        $this->assertNull((new FaceDescriptorService)->decrypt('not-a-valid-payload'));
    }
}
