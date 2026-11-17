<?php

namespace App\Support;

use App\Models\Doutores\Profile;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class DoutoresAuth
{
    public const SESSION_PERFIL_KEY = 'doutores_perfil_ativo';

    public const ROLE_ADMIN = 'doutores_admin';

    public const ROLE_COORD = 'doutores_coordenador';

    public const ROLE_VOL = 'doutores_voluntario';

    public const PERFIL_ADMIN = 'administrador';

    public const PERFIL_COORD = 'coordenador';

    public const PERFIL_VOL = 'voluntario';

    /**
     * @return list<string>
     */
    public static function doutoresRoleNames(): array
    {
        return [self::ROLE_ADMIN, self::ROLE_COORD, self::ROLE_VOL];
    }

    /**
     * @return list<string>
     */
    public static function availablePerfis(User $user): array
    {
        $perfis = [];

        if ($user->hasAnyRoleName([self::ROLE_ADMIN])) {
            $perfis[] = self::PERFIL_ADMIN;
        }

        if ($user->hasAnyRoleName([self::ROLE_COORD])) {
            $perfis[] = self::PERFIL_COORD;
        }

        if ($user->hasAnyRoleName([self::ROLE_VOL])) {
            $perfis[] = self::PERFIL_VOL;
        }

        return $perfis;
    }

    public static function defaultPerfil(User $user): ?string
    {
        $available = self::availablePerfis($user);

        if (in_array(self::PERFIL_ADMIN, $available, true)) {
            return self::PERFIL_ADMIN;
        }

        if (in_array(self::PERFIL_COORD, $available, true)) {
            return self::PERFIL_COORD;
        }

        if (in_array(self::PERFIL_VOL, $available, true)) {
            return self::PERFIL_VOL;
        }

        return null;
    }

    /**
     * Possui algum papel Doutores (independente de papéis do CMS / site).
     */
    public static function canAccessDoutoresLogin(User $user): bool
    {
        return $user->hasAnyRoleName(self::doutoresRoleNames());
    }

    /**
     * Pode autenticar no portal Doutores: papel doutores_* + perfil com status ativo.
     */
    public static function canLoginDoutores(User $user): bool
    {
        if (! self::canAccessDoutoresLogin($user)) {
            return false;
        }

        $profile = $user->relationLoaded('doutoresProfile')
            ? $user->doutoresProfile
            : Profile::query()->where('user_id', $user->id)->first();

        return $profile?->status === Profile::STATUS_ATIVO;
    }

    public static function setActivePerfil(Request|Session $target, string $perfil): void
    {
        if ($target instanceof Request) {
            $target->session()->put(self::SESSION_PERFIL_KEY, $perfil);

            return;
        }

        $target->put(self::SESSION_PERFIL_KEY, $perfil);
    }

    public static function getActivePerfil(Request|Session $target): ?string
    {
        if ($target instanceof Request) {
            $perfil = $target->session()->get(self::SESSION_PERFIL_KEY);
        } else {
            $perfil = $target->get(self::SESSION_PERFIL_KEY);
        }

        return is_string($perfil) && $perfil !== '' ? $perfil : null;
    }

    /**
     * Administrador do módulo: exige o papel doutores_admin (a sessão apenas escolhe o perfil ativo).
     */
    public static function canActAsAdmin(User $user, Request|Session|null $session = null): bool
    {
        return $user->hasAnyRoleName([self::ROLE_ADMIN]);
    }

    public static function canActAsManager(User $user, Request|Session|null $session = null): bool
    {
        return self::canActAsAdmin($user, $session)
            || $user->hasAnyRoleName([self::ROLE_COORD]);
    }

    /**
     * Rota inicial conforme o perfil.
     */
    public static function homeRouteFor(?string $perfil): string
    {
        return $perfil === self::PERFIL_VOL ? 'doutores.area.inicio' : 'doutores.home';
    }
}
