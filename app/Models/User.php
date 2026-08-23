<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birth_date',
        'congregation',
        'is_church_member',
        'is_active',
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
            'birth_date' => 'date',
            'is_church_member' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function roles(): MorphToMany
    {
        return $this->morphToMany(Role::class, 'model', 'model_has_roles', 'model_id', 'role_id');
    }

    /**
     * @param  string|Role|array<int, string|Role>|Collection<int, string|Role>  $roles
     */
    public function syncRoles(string|Role|array|Collection $roles): static
    {
        $names = collect(is_array($roles) || $roles instanceof Collection ? $roles : [$roles])
            ->map(fn ($role) => $role instanceof Role ? $role->name : (string) $role)
            ->filter()
            ->unique()
            ->values();

        $ids = Role::query()
            ->where('guard_name', 'web')
            ->whereIn('name', $names)
            ->pluck('id');

        $this->roles()->sync($ids);
        $this->unsetRelation('roles');

        return $this;
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

    public function isFotografiaLider(): bool
    {
        return $this->roles->contains('name', 'fotografia_lider');
    }

    public function isFotografiaColaborador(): bool
    {
        return $this->roles->contains('name', 'fotografia_colaborador');
    }

    /**
     * Qualquer perfil do ministério de fotografia (líder ou colaborador).
     */
    public function isFotografia(): bool
    {
        return $this->hasAnyRoleName(['fotografia_lider', 'fotografia_colaborador']);
    }

    public function isMember(): bool
    {
        return $this->roles->contains('name', 'member');
    }

    /**
     * Contas de membro do site (busca facial). Independentes das contas do painel.
     */
    public function scopeMembers($query)
    {
        return $query->whereHas('roles', fn ($q) => $q->where('name', 'member'));
    }

    /**
     * Contas com acesso ao painel administrativo.
     */
    public function scopeAdmins($query)
    {
        return $query->whereHas(
            'roles',
            fn ($q) => $q->whereIn('name', [
                'super_admin',
                'manager',
                'collaborator',
                'fotografia_lider',
                'fotografia_colaborador',
            ])
        );
    }

    /**
     * Papéis do painel administrativo. Contas de membro são registros separados.
     */
    public function canAccessAdminPanel(): bool
    {
        return $this->hasAnyRoleName([
            'super_admin',
            'manager',
            'collaborator',
            'fotografia_lider',
            'fotografia_colaborador',
        ]);
    }

    /**
     * A busca facial só é liberada para membros ativos.
     */
    public function isActiveMember(): bool
    {
        return $this->isMember() && (bool) ($this->is_active ?? true);
    }

    public function isMinor(): bool
    {
        if (! $this->birth_date) {
            return false;
        }

        return $this->birth_date->age < 18;
    }

    public function canManageGaleria(): bool
    {
        return $this->hasAnyRoleName([
            'super_admin',
            'manager',
            'fotografia_lider',
            'fotografia_colaborador',
        ]);
    }

    /**
     * Criar, editar e remover álbuns (não inclui upload/capa/remoção de fotos).
     */
    public function canManageGalleryAlbums(): bool
    {
        return $this->hasAnyRoleName(['super_admin', 'manager', 'fotografia_lider']);
    }

    /**
     * @param  array<int, string>  $roles
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
        $key = $this->id.':'.$routeName;

        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        $page = CmsPage::query()->where('route_name', $routeName)->first();
        if (! $page) {
            return $cache[$key] = null;
        }

        $pivot = $this->pages()
            ->where('cms_pages.id', $page->id)
            ->first()
            ?->pivot;

        if (! $pivot) {
            return $cache[$key] = null;
        }

        return $cache[$key] = [
            'can_access' => (bool) $pivot->can_access,
            'can_edit' => (bool) $pivot->can_edit,
            'can_approve' => (bool) $pivot->can_approve,
        ];
    }
}
