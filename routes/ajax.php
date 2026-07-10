<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisterInstitutionController;
use App\Http\Controllers\Auth\RegisterParentController;
use App\Http\Controllers\Cabinet\StatsController as CabinetStatsController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Institution\AcceptingController;
use App\Http\Controllers\Institution\InboxController;
use App\Http\Controllers\Institution\MediaController;
use App\Http\Controllers\Institution\MessageController;
use App\Http\Controllers\Institution\ProfileController;
use App\Http\Controllers\Institution\StatsController as InstitutionStatsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ajax routes
|--------------------------------------------------------------------------
| Blade sahifalaridagi fetch() chaqiruvlari shu yerga POST/GET qiladi.
| 'web' guruhi — session + CSRF himoyasi (bir domenda ishlagani uchun
| Sanctum SPA token shart emas, backend.md §5).
*/

Route::middleware('web')->prefix('ajax')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('register/parent', RegisterParentController::class);
        Route::post('register/institution', RegisterInstitutionController::class);
        Route::post('login', LoginController::class);
        Route::post('logout', LogoutController::class);
        Route::post('otp/request', [OtpController::class, 'request']);
        Route::post('otp/verify', [OtpController::class, 'verify']);
    });

    // Muassasa kabineti (role=institution) — backend.md §3, Phase 3
    Route::middleware(['auth', 'role:institution'])->prefix('institution/me')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::post('media', [MediaController::class, 'store']);
        Route::delete('media/{media}', [MediaController::class, 'destroy']);
        Route::patch('accepting', AcceptingController::class);
        Route::get('stats', InstitutionStatsController::class);

        // Ekskursiya/joylashtirish arizalari — backend.md Phase 5
        Route::get('applications', [InboxController::class, 'index']);
        Route::patch('applications/{application}/status', [InboxController::class, 'updateStatus']);

        // Suhbatlar — muassasa ota-onaga javob yozadi (institution-cabinet Suhbatlar sahifasi)
        Route::post('conversations/{conversation}/messages', [MessageController::class, 'store']);
    });

    // Arizalar — mehmon ham yubora oladi (backend.md Phase 4)
    Route::post('applications', [ApplicationController::class, 'store']);

    // Ota-ona tomoni (role=parent) — backend.md Phase 4
    Route::middleware(['auth', 'role:parent'])->group(function () {
        Route::get('favorites', [FavoriteController::class, 'index']);
        Route::post('favorites/{institution}', [FavoriteController::class, 'store']);
        Route::delete('favorites/{institution}', [FavoriteController::class, 'destroy']);

        Route::get('applications/me', [ApplicationController::class, 'mine']);

        Route::get('me/stats', CabinetStatsController::class);
    });

});
