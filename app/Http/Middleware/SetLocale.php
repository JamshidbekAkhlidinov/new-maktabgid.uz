<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Har bir so'rovda joriy tilni belgilaydi (2026-08-06, uch tillilik).
 * Ustuvorlik: session (joriy tashrif davomida `/til/{locale}` orqali tanlangan)
 * → cookie (`maktabgid_locale`, keyingi tashriflar uchun eslab qoladi) →
 * config('localization.default'). Noto'g'ri/qo'llab-quvvatlanmagan qiymatlar
 * e'tiborga olinmaydi (standart tilga tushadi).
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = array_keys(config('localization.supported', []));
        $default = config('localization.default', 'uz');

        $locale = $request->session()->get('locale')
            ?? $request->cookie('maktabgid_locale')
            ?? $default;

        if (! in_array($locale, $supported, true)) {
            $locale = $default;
        }

        App::setLocale($locale);

        return $next($request);
    }
}
