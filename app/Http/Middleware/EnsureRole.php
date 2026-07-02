<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Route::middleware('role:institution') yoki ('role:parent,admin') kabi ishlatiladi.
 * Frontenddagi eski `kind === 'institution'` (localStorage) tekshiruvi o'rniga shu keladi.
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Sizda bu bo\'limga kirish huquqi yo\'q.');
        }

        return $next($request);
    }
}
