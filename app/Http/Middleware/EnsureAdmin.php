<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * /admin panelining tashqi darvozasi: faqat Spatie "Super Admin" roliga ega
 * foydalanuvchilar kira oladi (role=admin ustuni yetarli emas — real
 * saytda faqat bitta yuqori pog'onadagi rol /admin/login'ga kira olishi kerak).
 * Ichkaridagi har bir amal AppServiceProvider'dagi Gate::before orqali
 * Super Admin'ga avtomatik ruxsat beriladi (backend.md admin bo'limi).
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole('Super Admin')) {
            if ($request->expectsJson()) {
                abort(403, 'Sizda admin panelga kirish huquqi yo\'q.');
            }

            return redirect()->route('admin.login')->withErrors([
                'email' => 'Admin panelga faqat Super Admin huquqiga ega foydalanuvchilar kira oladi.',
            ]);
        }

        return $next($request);
    }
}
