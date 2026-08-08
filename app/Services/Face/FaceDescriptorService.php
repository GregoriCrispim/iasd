<?php

namespace App\Services\Face;

use App\Models\GalleryFaceDescriptor;
use App\Models\GalleryPhoto;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Valida, compacta, criptografa e persiste descritores faciais de 128 posições.
 *
 * O vetor nunca é gravado em claro: é serializado como binário Float32 e depois
 * criptografado com a APP_KEY (AES via Crypt). Na leitura, o processo é revertido.
 */
class FaceDescriptorService
{
    public const DIMENSIONS = 128;

    /**
     * Valida que o valor recebido é um descriptor de 128 números finitos.
     *
     * @param  mixed  $descriptor
     * @return list<float>
     *
     * @throws RuntimeException
     */
    public function validate($descriptor): array
    {
        if (! is_array($descriptor)) {
            throw new RuntimeException('Descriptor inválido.');
        }

        if (count($descriptor) !== self::DIMENSIONS) {
            throw new RuntimeException('Descriptor deve ter exatamente '.self::DIMENSIONS.' dimensões.');
        }

        $clean = [];
        foreach ($descriptor as $value) {
            if (! is_int($value) && ! is_float($value)) {
                throw new RuntimeException('Descriptor deve conter apenas números.');
            }
            $float = (float) $value;
            if (! is_finite($float)) {
                throw new RuntimeException('Descriptor contém valores não finitos.');
            }
            $clean[] = $float;
        }

        return $clean;
    }

    /**
     * Serializa (Float32 little-endian) e criptografa o vetor.
     *
     * @param  list<float>  $descriptor
     */
    public function encrypt(array $descriptor): string
    {
        $packed = pack('g*', ...$descriptor);

        return Crypt::encryptString(base64_encode($packed));
    }

    /**
     * Descriptografa e desserializa de volta para uma lista de floats.
     *
     * @return list<float>|null
     */
    public function decrypt(string $stored): ?array
    {
        try {
            $packed = base64_decode(Crypt::decryptString($stored), true);
        } catch (\Throwable) {
            return null;
        }

        if ($packed === false || strlen($packed) !== self::DIMENSIONS * 4) {
            return null;
        }

        /** @var array<int, float>|false $values */
        $values = unpack('g*', $packed);

        if ($values === false || count($values) !== self::DIMENSIONS) {
            return null;
        }

        return array_values($values);
    }

    /**
     * Substitui todos os descritores de uma foto pelos novos (transação).
     *
     * @param  array<int, array{descriptor:array<int,float|int>,score?:float|null,box?:array<string,float|int>|null}>  $faces
     */
    public function replaceForPhoto(GalleryPhoto $photo, array $faces, string $modelVersion): int
    {
        return DB::transaction(function () use ($photo, $faces, $modelVersion) {
            GalleryFaceDescriptor::query()
                ->where('gallery_photo_id', $photo->id)
                ->delete();

            $count = 0;
            foreach ($faces as $index => $face) {
                $vector = $this->validate($face['descriptor'] ?? null);
                $box = $face['box'] ?? [];

                GalleryFaceDescriptor::create([
                    'gallery_album_id' => $photo->gallery_album_id,
                    'gallery_photo_id' => $photo->id,
                    'face_index' => $index,
                    'box_x' => isset($box['x']) ? (float) $box['x'] : null,
                    'box_y' => isset($box['y']) ? (float) $box['y'] : null,
                    'box_w' => isset($box['width']) ? (float) $box['width'] : null,
                    'box_h' => isset($box['height']) ? (float) $box['height'] : null,
                    'score' => isset($face['score']) ? (float) $face['score'] : null,
                    'model_version' => $modelVersion,
                    'descriptor' => $this->encrypt($vector),
                ]);
                $count++;
            }

            return $count;
        });
    }
}
