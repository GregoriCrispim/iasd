<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method bool hasRole(string|array|\Spatie\Permission\Models\Role|\Illuminate\Support\Collection $roles, ?string $guard = null)
 * @method bool hasAnyRole(string|array|\Spatie\Permission\Models\Role|\Illuminate\Support\Collection ...$roles)
 * @method \Illuminate\Support\Collection getRoleNames()
 */
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'created_by',
        'manager_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRoleName(['super_admin', 'manager', 'collaborator']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->roles->contains('name', 'super_admin');
    }

    public function isManager(): bool
    {
        return $this->roles->contains('name', 'manager');
    }

    public function isCollaborator(): bool
    {
        return $this->roles->contains('name', 'collaborator');
    }

    /**
     * @param array<int, string> $roles
     */
    public function hasAnyRoleName(array $roles): bool
    {
        return $this->roles->whereIn('name', $roles)->isNotEmpty();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'created_by');
    }

    public function createdUsers(): HasMany
    {
        return $this->hasMany(self::class, 'created_by');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(self::class, 'manager_id');
    }

    public function collaborators(): HasMany
    {
        return $this->hasMany(self::class, 'manager_id');
    }

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(CmsPage::class, 'cms_page_user')
            ->withPivot(['can_access', 'can_edit', 'can_approve'])
            ->withTimestamps();
    }

    public function canAccessPage(string $routeName): bool
    {
        return $this->pagePivot($routeName)['can_access'] ?? false;
    }

    public function canEditPage(string $routeName): bool
    {
        return $this->pagePivot($routeName)['can_edit'] ?? false;
    }

    public function canApprovePage(string $routeName): bool
    {
        return $this->pagePivot($routeName)['can_approve'] ?? false;
    }

    /**
     * @return array{can_access:bool,can_edit:bool,can_approve:bool}|null
     */
    protected function pagePivot(string $routeName): ?array
    {
        static $cache = [];
        $key = $this->id . ':' . $routeName;

        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        $page = CmsPage::query()->where('route_name', $routeName)->first();
        if (!$page) {
            return $cache[$key] = null;
        }

        $pivot = $this->pages()
            ->where('cms_pages.id', $page->id)
            ->first()
            ?->pivot;

        if (!$pivot) {
            return $cache[$key] = null;
        }

        return $cache[$key] = [
            'can_access' => (bool) $pivot->can_access,
            'can_edit' => (bool) $pivot->can_edit,
            'can_approve' => (bool) $pivot->can_approve,
        ];
    }
}
