<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Ensure the authenticated user has at least one of the given roles.
     * Usage: ->middleware('role:super_admin,manager')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user instanceof User) {
            abort(403);
        }

        if (empty($roles)) {
            return $next($request);
        }

        if (!$user->hasAnyRoleName($roles)) {
            abort(403);
        }

        return $next($request);
    }
}
