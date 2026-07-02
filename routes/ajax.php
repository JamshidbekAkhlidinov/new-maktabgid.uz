<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisterInstitutionController;
use App\Http\Controllers\Auth\RegisterParentController;
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

});
