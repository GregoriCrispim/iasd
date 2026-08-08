<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberInvite extends Model
{
    protected $fillable = [
        'code',
        'code_hash',
        'code_prefix',
        'description',
        'max_uses',
        'uses_count',
        'expires_at',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
            'max_uses' => 'integer',
            'uses_count' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function uses(): HasMany
    {
        return $this->hasMany(MemberInviteUse::class);
    }

    /**
     * Gera um código aleatório legível e devolve [código em claro, hash].
     *
     * @return array{code:string,hash:string,prefix:string}
     */
    public static function generateCode(): array
    {
        $code = strtoupper(Str::random(4).'-'.Str::random(4).'-'.Str::random(4));
        $code = preg_replace('/[^A-Z0-9-]/', 'X', $code) ?? $code;

        return [
            'code' => $code,
            'hash' => hash('sha256', $code),
            'prefix' => substr($code, 0, 4),
        ];
    }

    public static function hashCode(string $code): string
    {
        return hash('sha256', trim(strtoupper($code)));
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isExhausted(): bool
    {
        return $this->uses_count >= $this->max_uses;
    }

    public function isUsable(): bool
    {
        return $this->is_active && ! $this->isExpired() && ! $this->isExhausted();
    }

    public function statusLabel(): string
    {
        if (! $this->is_active) {
            return 'Desativado';
        }
        if ($this->isExpired()) {
            return 'Expirado';
        }
        if ($this->isExhausted()) {
            return 'Esgotado';
        }

        return 'Ativo';
    }

    public function remainingUses(): int
    {
        return max(0, $this->max_uses - $this->uses_count);
    }
}
