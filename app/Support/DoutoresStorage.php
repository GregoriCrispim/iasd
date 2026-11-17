<?php

namespace App\Support;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Armazenamento do módulo Doutores alinhado à galeria do CMS:
 * - mídia pública (fotos/publicações) em public/img/doutores/uploads (sem storage:link)
 * - anexos sensíveis no disco local privado, servidos só via controller autenticado
 */
class DoutoresStorage
{
    public const DISK_PUBLIC = 'doutores';

    public const DISK_PRIVATE = 'local';

    public const DISK_LEGACY_PUBLIC = 'public';

    public static function publicDisk(): Filesystem
    {
        return Storage::disk(self::DISK_PUBLIC);
    }

    public static function privateDisk(): Filesystem
    {
        return Storage::disk(self::DISK_PRIVATE);
    }

    public static function storePublic(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, self::DISK_PUBLIC);
    }

    public static function storePrivate(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, self::DISK_PRIVATE);
    }

    public static function publicUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (self::publicDisk()->exists($path)) {
            return self::publicDisk()->url($path);
        }

        // Compatibilidade com uploads antigos em storage/app/public.
        if (Storage::disk(self::DISK_LEGACY_PUBLIC)->exists($path)) {
            return Storage::disk(self::DISK_LEGACY_PUBLIC)->url($path);
        }

        return self::publicDisk()->url($path);
    }

    public static function deletePublic(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (self::publicDisk()->exists($path)) {
            self::publicDisk()->delete($path);

            return;
        }

        if (Storage::disk(self::DISK_LEGACY_PUBLIC)->exists($path)) {
            Storage::disk(self::DISK_LEGACY_PUBLIC)->delete($path);
        }
    }

    /**
     * Disco onde o caminho existe (privado atual, legado público ou privado).
     */
    public static function resolvePrivateDisk(string $path): ?Filesystem
    {
        if (self::privateDisk()->exists($path)) {
            return self::privateDisk();
        }

        if (Storage::disk(self::DISK_LEGACY_PUBLIC)->exists($path)) {
            return Storage::disk(self::DISK_LEGACY_PUBLIC);
        }

        return null;
    }

    public static function deletePrivate(?string $path): void
    {
        if (! $path) {
            return;
        }

        $disk = self::resolvePrivateDisk($path);
        $disk?->delete($path);
    }
}
