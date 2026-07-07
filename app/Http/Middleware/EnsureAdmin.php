<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * /admin panelining tashqi darvozasi: faqat role=admin foydalanuvchilar kira oladi.
 * Ichkaridagi har bir amal esa alohida Spatie permission middleware bilan cheklanadi
 * (bootstrap/app.php dagi 'permission' aliasi, backend.md admin bo'limi).
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            if ($request->expectsJson()) {
                abort(403, 'Sizda admin panelga kirish huquqi yo\'q.');
            }

            return redirect()->route('admin.login')->withErrors([
                'phone' => 'Admin panelga kirish uchun admin hisobingiz bilan kiring.',
            ]);
        }

        return $next($request);
    }
}
