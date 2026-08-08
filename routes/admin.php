<?php

use App\Http\Controllers\Admin\AdvertisementController as AdminAdvertisementController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ComingSoonController as AdminComingSoonController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DistrictController as AdminDistrictController;
use App\Http\Controllers\Admin\InstitutionAchievementController as AdminInstitutionAchievementController;
use App\Http\Controllers\Admin\InstitutionController as AdminInstitutionController;
use App\Http\Controllers\Admin\InstitutionMediaController as AdminInstitutionMediaController;
use App\Http\Controllers\Admin\InstitutionTypeController as AdminInstitutionTypeController;
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
|   1) 'admin' middleware — faqat Spatie "Super Admin" roli (EnsureAdmin,
|      tashqi darvoza, /admin/login shu yerdan boshlanadi)
|   2) 'permission:<resurs>.<amal>' — Spatie\Permission (har bir controller
|      o'zining middleware()'sida e'lon qiladi, backend.md admin bo'limi)
| Super Admin AppServiceProvider'dagi Gate::before orqali barcha huquqlarga
| avtomatik ega — shu sababli /admin/login'ga kirgan har bir foydalanuvchi
| pastdagi barcha bo'limlarni ko'ra oladi.
*/

Route::middleware('web')->prefix('admin')->name('admin.')->group(function () {

    // ---- Login/Logout (himoyalanmagan) ----
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.attempt');
    Route::post('logout', [AdminAuthController::class, 'logout'])->middleware('auth')->name('logout');

    // ---- Google orqali kirish (faqat mavjud Super Admin hisoblar uchun) ----
    Route::get('google/redirect', [AdminAuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('google/callback', [AdminAuthController::class, 'handleGoogleCallback'])->name('google.callback');

    // ---- Panelning o'zi (auth + role=admin) ----
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::resource('institutions', AdminInstitutionController::class)->except(['show']);

        // Institution kabinetidagi galereya/video va yutuqlar bilan bir xil imkoniyat —
        // admin muassasa nomidan boshqaradi (2026-07-15).
        Route::prefix('institutions/{institution}')->name('institutions.')->group(function () {
            Route::get('media', [AdminInstitutionMediaController::class, 'index'])->name('media.index');
            Route::post('media', [AdminInstitutionMediaController::class, 'store'])->name('media.store');
            Route::delete('media/{media}', [AdminInstitutionMediaController::class, 'destroy'])->name('media.destroy');

            Route::get('achievements', [AdminInstitutionAchievementController::class, 'index'])->name('achievements.index');
            Route::post('achievements', [AdminInstitutionAchievementController::class, 'store'])->name('achievements.store');
            Route::put('achievements/{achievement}', [AdminInstitutionAchievementController::class, 'update'])->name('achievements.update');
            Route::delete('achievements/{achievement}', [AdminInstitutionAchievementController::class, 'destroy'])->name('achievements.destroy');
        });
        Route::resource('vacancies', AdminVacancyController::class)->except(['show']);
        Route::resource('applications', AdminApplicationController::class)->except(['show']);
        Route::resource('specializations', AdminSpecializationController::class)->except(['show']);
        Route::resource('institution-types', AdminInstitutionTypeController::class)->except(['show']);
        Route::resource('districts', AdminDistrictController::class)->except(['show']);
        Route::resource('news', AdminNewsController::class)->except(['show']);
        Route::resource('articles', AdminArticleController::class)->except(['show']);
        Route::resource('reviews', AdminReviewController::class)->except(['show']);
        Route::resource('resumes', AdminResumeController::class)->except(['show']);
        Route::resource('advertisements', AdminAdvertisementController::class)->except(['show']);
        Route::resource('roles', AdminRoleController::class)->except(['show']);
        Route::get('permissions', [AdminPermissionController::class, 'index'])->name('permissions.index');

        // ---- Dizayn maketidagi "tez orada" bo'limlari (funksiyasiz, faqat vizual) ----
        Route::get('group', [AdminComingSoonController::class, 'show'])->name('group.index')->defaults('page', 'group');
        Route::get('tags', [AdminComingSoonController::class, 'show'])->name('tags.index')->defaults('page', 'tags');
        Route::get('subscriptions', [AdminComingSoonController::class, 'show'])->name('subscriptions.index')->defaults('page', 'subscriptions');
        Route::get('tariffs', [AdminComingSoonController::class, 'show'])->name('tariffs.index')->defaults('page', 'tariffs');
        Route::get('billing-settings', [AdminComingSoonController::class, 'show'])->name('billing-settings.index')->defaults('page', 'billing-settings');
        Route::get('settings', [AdminComingSoonController::class, 'show'])->name('settings.index')->defaults('page', 'settings');
    });
});
