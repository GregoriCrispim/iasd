<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GalleryPhoto extends Model
{
    public const DISK = 'galeria';

    /**
     * URL base do disco. Relativa de propósito: mantém o mesmo host em
     * localhost e produção, sem depender de APP_URL nem do symlink storage.
     */
    public const BASE_URL = '/img/galeria/uploads';

    protected $fillable = [
        'gallery_album_id',
        'path',
        'original_filename',
        'mime_type',
        'size_bytes',
        'width',
        'height',
        'sort_order',
        'faces_status',
        'faces_scanned_at',
        'optimized_at',
        'meta_json',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'faces_scanned_at' => 'datetime',
            'optimized_at' => 'datetime',
            'meta_json' => 'array',
        ];
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Arquivo guardado (o maior que existe), usado nos downloads.
     */
    public function publicUrl(): string
    {
        return self::BASE_URL.'/'.$this->relativePath();
    }

    /**
     * Versão reduzida para a lightbox; cai no arquivo guardado se não houver.
     */
    public function displayUrl(): string
    {
        $variant = $this->variantPath('display');

        return $variant ? self::BASE_URL.'/'.$variant : $this->publicUrl();
    }

    /**
     * Miniatura para as grades. Sem a derivada em disco, usa a rota que gera
     * sob demanda, que por sua vez cai no original se o servidor não tiver GD.
     */
    public function thumbUrl(): string
    {
        $variant = $this->variantPath('thumb');

        return $variant ? self::BASE_URL.'/'.$variant : '/galeria/thumb?photo='.$this->id;
    }

    public function absolutePath(): string
    {
        return Storage::disk(self::DISK)->path($this->path);
    }

    public function basename(): string
    {
        return basename($this->path);
    }

    /**
     * Caminho relativo de uma derivada registrada no upload ('thumb'|'display').
     */
    public function variantPath(string $variant): ?string
    {
        $meta = $this->meta_json;

        if (! is_array($meta)) {
            return null;
        }

        $path = $meta['variants'][$variant] ?? null;

        return is_string($path) && $path !== '' ? $path : null;
    }

    /**
     * Caminhos de todos os arquivos da foto, para remoção.
     *
     * @return list<string>
     */
    public function allFilePaths(): array
    {
        $paths = [$this->relativePath()];

        foreach (['thumb', 'display'] as $variant) {
            $path = $this->variantPath($variant);
            if ($path !== null) {
                $paths[] = $path;
            }
        }

        // Miniaturas geradas sob demanda antes da otimização entrar no ar.
        $paths[] = 'thumbs/'.$this->gallery_album_id.'/'.pathinfo($this->basename(), PATHINFO_FILENAME).'.webp';
        $paths[] = 'thumbs/'.$this->gallery_album_id.'/'.$this->basename();

        return array_values(array_unique($paths));
    }

    /**
     * Nome sugerido no download, coerente com a extensão realmente guardada.
     */
    public function downloadName(): string
    {
        $name = $this->original_filename ?: $this->basename();
        $stored = strtolower(pathinfo($this->relativePath(), PATHINFO_EXTENSION));

        if ($stored === '' || strtolower(pathinfo($name, PATHINFO_EXTENSION)) === $stored) {
            return $name;
        }

        return pathinfo($name, PATHINFO_FILENAME).'.'.$stored;
    }

    /**
     * Shape esperado pelo JS da galeria pública.
     *
     * @return array{name:string,url:string,thumbUrl:string,downloadUrl:string,downloadName:string}
     */
    public function toPublicArray(): array
    {
        return [
            'name' => $this->original_filename ?: $this->basename(),
            'url' => $this->displayUrl(),
            'thumbUrl' => $this->thumbUrl(),
            'downloadUrl' => $this->publicUrl(),
            'downloadName' => $this->downloadName(),
        ];
    }

    private function relativePath(): string
    {
        return str_replace('\\', '/', ltrim((string) $this->path, '/'));
    }
}
