<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            require __DIR__.'/../routes/ajax.php';
            require __DIR__.'/../routes/telegram.php';
            // admin.php endi routes/web.php ichida, {slug} catch-all'dan OLDIN
            // require qilinadi (aks holda bitta segmentli "/admin" {slug}'ga
            // tushib 404 qaytarardi — auth/admin middleware ishlamay qolardi).
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Uch tillilik (2026-08-06) — har bir web so'rovda joriy til belgilanadi
        // (session/cookie asosida), shu sababli barcha sahifalarda __() to'g'ri ishlaydi.
        $middleware->web(append: [SetLocale::class]);

        $middleware->alias([
            // Saytdagi oddiy rol tekshiruvi (parent|institution|admin) — mavjud API'lar uchun.
            'role' => EnsureRole::class,
            // /admin panelining tashqi darvozasi (faqat role=admin).
            'admin' => EnsureAdmin::class,
            // Spatie\Permission — admin panel ichidagi har bir amal shu bilan cheklanadi.
            'permission' => PermissionMiddleware::class,
            'spatie_role' => RoleMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);

        // Loyihada nomlangan umumiy 'login' route yo'q (sayt login'i AJAX/modal orqali
        // ishlaydi). Shu sababli /admin/* ostida 'auth' middleware'i mehmonni ushlab
        // qolganda Laravel'ning standart route('login') fallback'i xatolik berib qoladi —
        // shuning uchun guest redirect manzilini o'zimiz belgilaymiz.
        $middleware->redirectGuestsTo(function (Request $request) {
            // Diqqat: 'admin/*' pattern'i aynan "/admin" (segmentsiz, prefiksning o'zi)
            // manzilini QOPLAMAYDI — shu sababli 'admin' aniq nomi ham qo'shildi,
            // aks holda mehmon /admin'ga kirganda login sahifasi o'rniga bosh sahifaga
            // (url('/')) tashlab yuborilardi.
            return $request->is('admin', 'admin/*') ? route('admin.login') : url('/');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->is('ajax/*') || $request->is('telegram/*'),
        );
    })->create();
