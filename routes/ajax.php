<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisterInstitutionController;
use App\Http\Controllers\Auth\RegisterParentController;
use App\Http\Controllers\Institution\AcceptingController;
use App\Http\Controllers\Institution\MediaController;
use App\Http\Controllers\Institution\ProfileController;
use App\Http\Controllers\Institution\StatsController;
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
        Route::get('stats', StatsController::class);
    });

});
