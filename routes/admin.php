<?php

use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DistrictController as AdminDistrictController;
use App\Http\Controllers\Admin\InstitutionController as AdminInstitutionController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\ResumeController as AdminResumeController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\SpecializationController as AdminSpecializationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VacancyController as AdminVacancyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin panel routes
|--------------------------------------------------------------------------
| Laravel Blade'da yozilgan to'liq boshqaruv paneli. Har bir amal ikki
| bosqichda himoyalangan:
|   1) 'admin' middleware — faqat role=admin (EnsureAdmin, tashqi darvoza)
|   2) 'permission:<resurs>.<amal>' — Spatie\Permission (har bir controller
|      o'zining middleware()'sida e'lon qiladi, backend.md admin bo'limi)
| Super Admin AppServiceProvider'dagi Gate::before orqali barcha huquqlarga
| avtomatik ega.
*/

Route::middleware('web')->prefix('admin')->name('admin.')->group(function () {

    // ---- Login/Logout (himoyalanmagan) ----
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.attempt');
    Route::post('logout', [AdminAuthController::class, 'logout'])->middleware('auth')->name('logout');

    // ---- Panelning o'zi (auth + role=admin) ----
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::resource('institutions', AdminInstitutionController::class)->except(['show']);
        Route::resource('vacancies', AdminVacancyController::class)->except(['show']);
        Route::resource('applications', AdminApplicationController::class)->except(['show']);
        Route::resource('specializations', AdminSpecializationController::class)->except(['show']);
        Route::resource('districts', AdminDistrictController::class)->except(['show']);
        Route::resource('news', AdminNewsController::class)->except(['show']);
        Route::resource('articles', AdminArticleController::class)->except(['show']);
        Route::resource('reviews', AdminReviewController::class)->except(['show']);
        Route::resource('resumes', AdminResumeController::class)->except(['show']);
        Route::resource('roles', AdminRoleController::class)->except(['show']);
        Route::get('permissions', [AdminPermissionController::class, 'index'])->name('permissions.index');
    });
});
