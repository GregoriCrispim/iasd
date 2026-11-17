<?php

namespace App\Support;

use App\Models\Doutores\Hospital;
use App\Models\Doutores\Profile;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Escopo por polo/hospital para coordenadores do módulo Doutores da Esperança.
 *
 * Administradores enxergam tudo; coordenadores enxergam apenas os hospitais
 * dos polos vinculados em `doutores_coordenador_polo`.
 */
class DoutoresPoloScope
{
    /**
     * Verdadeiro quando o usuário não sofre restrição de polo (perfil administrador).
     * Quando há sessão/requisição, respeita o perfil ativo (um admin que alterna para
     * "coordenador" passa a enxergar somente os seus polos).
     */
    public static function isUnrestricted(User $user, Request|Session|null $context = null): bool
    {
        $user->loadMissing('roles');

        if ($context !== null) {
            $perfil = DoutoresAuth::getActivePerfil($context);

            if ($perfil === DoutoresAuth::PERFIL_ADMIN) {
                return DoutoresAuth::canActAsAdmin($user);
            }

            if ($perfil !== null) {
                return false;
            }
        }

        return DoutoresAuth::canActAsAdmin($user);
    }

    /**
     * IDs de polo no escopo. `null` significa todos (administrador).
     *
     * @return list<int>|null
     */
    public static function getPoloIds(User $user, Request|Session|null $context = null): ?array
    {
        if (self::isUnrestricted($user, $context)) {
            return null;
        }

        return $user->doutoresPolos()
            ->pluck('doutores_polos.id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
    }

    /**
     * Nomes de hospitais no escopo do usuário.
     *
     * @return list<string>
     */
    public static function hospitalNamesInScope(User $user, Request|Session|null $context = null, bool $onlyActive = false): array
    {
        $poloIds = self::getPoloIds($user, $context);

        $query = Hospital::query()->orderBy('nome');

        if ($onlyActive) {
            $query->where('status', 'ativo');
        }

        if ($poloIds !== null) {
            if ($poloIds === []) {
                return [];
            }

            $query->whereIn('polo_id', $poloIds);
        }

        return $query->pluck('nome')->unique()->values()->all();
    }

    public static function isHospitalInScope(User $user, ?string $hospitalName, Request|Session|null $context = null): bool
    {
        if (self::isUnrestricted($user, $context)) {
            return true;
        }

        if ($hospitalName === null || trim($hospitalName) === '') {
            return false;
        }

        $needle = mb_strtolower(trim($hospitalName));

        foreach (self::hospitalNamesInScope($user, $context) as $name) {
            if (mb_strtolower(trim($name)) === $needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * Aborta com 403 se o hospital estiver fora do escopo do usuário.
     */
    public static function assertHospitalInScope(User $user, string $hospitalName, Request|Session|null $context = null): void
    {
        if (! self::isHospitalInScope($user, $hospitalName, $context)) {
            abort(403, 'Hospital fora do seu polo de atuação.');
        }
    }

    /**
     * Restringe uma query de plantões (coluna `hospital`) ao escopo do usuário.
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    public static function filterPlantaoQuery(Builder $query, User $user, Request|Session|null $context = null, string $column = 'hospital', bool $includeNull = false): Builder
    {
        if (self::isUnrestricted($user, $context)) {
            return $query;
        }

        $names = self::hospitalNamesInScope($user, $context);

        return $query->where(function (Builder $q) use ($names, $column, $includeNull) {
            if ($names === []) {
                $q->whereRaw('1 = 0');
            } else {
                $q->whereIn($column, $names);
            }

            if ($includeNull) {
                $q->orWhereNull($column);
            }
        });
    }

    /**
     * Alias semântico para tabelas que guardam o nome do hospital em outra coluna.
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    public static function filterHospitalColumn(Builder $query, User $user, Request|Session|null $context = null, string $column = 'hospital', bool $includeNull = false): Builder
    {
        return self::filterPlantaoQuery($query, $user, $context, $column, $includeNull);
    }

    /**
     * Perfis de voluntário visíveis ao usuário: sob sua responsabilidade ou com
     * hospital preferido no seu escopo.
     *
     * @param  Builder<Profile>  $query
     * @return Builder<Profile>
     */
    public static function filterProfileQuery(Builder $query, User $user, Request|Session|null $context = null): Builder
    {
        if (self::isUnrestricted($user, $context)) {
            return $query;
        }

        $names = self::hospitalNamesInScope($user, $context);

        return $query->where(function (Builder $q) use ($user, $names) {
            $q->where('coordenador_responsavel_id', $user->id);

            if ($names !== []) {
                $q->orWhereIn('hospital_preferido', $names);
            }
        });
    }

    public static function canAccessProfile(User $user, Profile $profile, Request|Session|null $context = null): bool
    {
        if (self::isUnrestricted($user, $context)) {
            return true;
        }

        if ((int) $profile->coordenador_responsavel_id === (int) $user->id) {
            return true;
        }

        return self::isHospitalInScope($user, $profile->hospital_preferido, $context);
    }

    public static function assertProfileInScope(User $user, Profile $profile, Request|Session|null $context = null): void
    {
        if (! self::canAccessProfile($user, $profile, $context)) {
            abort(403, 'Voluntário fora do seu polo de atuação.');
        }
    }

    /**
     * Restringe registros que pertencem a um usuário (relação `$relation`) cujo perfil
     * Doutores esteja no escopo.
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    public static function filterByOwnerProfile(Builder $query, User $user, Request|Session|null $context = null, string $relation = 'user'): Builder
    {
        if (self::isUnrestricted($user, $context)) {
            return $query;
        }

        return $query->whereHas(
            $relation.'.doutoresProfile',
            fn (Builder $p) => self::filterProfileQuery($p, $user, $context)
        );
    }
}
