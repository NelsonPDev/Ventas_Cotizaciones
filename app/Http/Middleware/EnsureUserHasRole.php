<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Allow access only to authenticated, active users with one of the roles.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->activo || ! $user->hasRole(...$roles)) {
            abort(403, 'No tienes permisos para acceder a este modulo.');
        }

        return $next($request);
    }
}
