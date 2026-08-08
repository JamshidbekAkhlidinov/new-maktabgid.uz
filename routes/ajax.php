<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisterInstitutionController;
use App\Http\Controllers\Auth\RegisterParentController;
use App\Http\Controllers\Auth\RegisterTeacherController;
use App\Http\Controllers\Cabinet\StatsController as CabinetStatsController;
use App\Http\Controllers\Career\VacancyApplicationController as CareerVacancyApplicationController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Forum\LikeController as ForumLikeController;
use App\Http\Controllers\Forum\ReplyController as ForumReplyController;
use App\Http\Controllers\Forum\ThreadController as ForumThreadController;
use App\Http\Controllers\Institution\AcceptingController;
use App\Http\Controllers\Institution\AchievementController;
use App\Http\Controllers\Institution\InboxController;
use App\Http\Controllers\Institution\MediaController;
use App\Http\Controllers\Institution\MessageController;
use App\Http\Controllers\Institution\OrganizationController;
use App\Http\Controllers\Institution\ProfileController;
use App\Http\Controllers\Institution\StatsController as InstitutionStatsController;
use App\Http\Controllers\Institution\VacancyController as InstitutionVacancyController;
use App\Http\Controllers\ParentCabinetController;
use App\Http\Controllers\ParentChildController;
use App\Http\Controllers\ParentMessageController;
use App\Http\Controllers\TeacherMessageController;
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
        Route::post('register/teacher', RegisterTeacherController::class);
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

        // Suhbatlar — muassasa ota-ona/ustozga javob yozadi (institution-cabinet Suhbatlar sahifasi)
        Route::post('conversations/{conversation}/messages', [MessageController::class, 'store'])
            ->middleware('throttle:chat-message');

        // Vakansiya ochish/o'chirish (institution-cabinet Vakansiyalar sahifasi, ADR-0002)
        Route::post('vacancies', [InstitutionVacancyController::class, 'store']);
        Route::delete('vacancies/{vacancy}', [InstitutionVacancyController::class, 'destroy']);

        // Nomzod arizasi holatini o'zgartirish — qabul/rad (ADR-0002, Faza 2)
        Route::patch('vacancy-applications/{application}/status', [InstitutionVacancyController::class, 'updateApplicationStatus']);

        // O'quvchilar yutuqlari — kabinetda qo'shish/tahrirlash/o'chirish (ADR-0002, Faza 2).
        // fetch() FormData bilan PUT so'rovini to'g'ridan-to'g'ri yuborishi mumkin (native
        // <form> orqali emas), shuning uchun _method spoofing shart emas.
        Route::post('achievements', [AchievementController::class, 'store']);
        Route::put('achievements/{achievement}', [AchievementController::class, 'update']);
        Route::delete('achievements/{achievement}', [AchievementController::class, 'destroy']);

        // Ko'p-filial: "Yangi muassasa qo'shish" + tashkilot almashtirish (2026-07-15)
        Route::post('organizations', [OrganizationController::class, 'store']);
        Route::patch('active', [OrganizationController::class, 'activate']);
    });

    // Arizalar — mehmon ham yubora oladi (backend.md Phase 4)
    Route::post('applications', [ApplicationController::class, 'store']);

    // Vakansiyaga ariza — mehmon ham yubora oladi, Application bilan bir xil qoida (ADR-0002, Faza 2)
    Route::post('vacancies/{vacancy}/apply', [CareerVacancyApplicationController::class, 'store']);

    // Suhbatlar — rol-agnostik qismlar (ADR-0003): maktab profilidagi "Suhbat
    // boshlash" tugmasi parent va teacher uchun bitta endpointga murojaat qiladi;
    // xabar tarixini olish (polling, har 3 sekunda) ham ishtirokchi kim bo'lishidan
    // qat'i nazar bitta endpoint — ConversationPolicy egalikni tekshiradi.
    Route::middleware('auth')->group(function () {
        Route::post('conversations', [ConversationController::class, 'start']);
        Route::get('conversations/{conversation}/messages', [ConversationController::class, 'messages']);
    });

    // Ustoz tomoni (role=teacher) — muassasaga xabar yozish (ADR-0003, ADR-0002'da
    // kechiktirilgan bo'shliq). Ro'yxat/tarix yuqoridagi umumiy 'auth' guruhida.
    // Diqqat: alohida 'teacher/' prefiksi shart — aks holda parent'ning
    // `POST conversations/{conversation}/messages`i bilan bir xil URI bo'lib
    // qolib, marshrutlashda bir-birini "soya qilib qo'yardi" (birinchi ro'yxatga
    // olingani ustunlik qiladi, ikkinchisi hech qachon ishlamay qolardi).
    Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->group(function () {
        Route::post('conversations/{conversation}/messages', [TeacherMessageController::class, 'store'])
            ->middleware('throttle:chat-message');
    });

    // Forum — mavzu ochish/javob/layk (backend.md §6, ADR-0002 Faza 2)
    Route::middleware('auth')->prefix('forum')->group(function () {
        Route::post('threads', [ForumThreadController::class, 'store']);
        Route::post('threads/{thread}/replies', [ForumReplyController::class, 'store']);
        Route::post('threads/{thread}/like', [ForumLikeController::class, 'thread']);
        Route::post('replies/{reply}/like', [ForumLikeController::class, 'reply']);
    });

    // Ota-ona tomoni (role=parent) — backend.md Phase 4
    Route::middleware(['auth', 'role:parent'])->group(function () {
        Route::get('favorites', [FavoriteController::class, 'index']);
        Route::post('favorites/{institution}', [FavoriteController::class, 'store']);
        Route::delete('favorites/{institution}', [FavoriteController::class, 'destroy']);

        Route::get('applications/me', [ApplicationController::class, 'mine']);

        Route::get('me/stats', CabinetStatsController::class);

        // Profilni tahrirlash (parent/dashboard.blade.php)
        Route::put('me', [ParentCabinetController::class, 'updateProfile']);

        // Farzandlarim — qo'shish/tahrirlash/o'chirish (parent/children.blade.php, ADR 2026-07-14)
        Route::post('children', [ParentChildController::class, 'store']);
        Route::put('children/{child}', [ParentChildController::class, 'update']);
        Route::delete('children/{child}', [ParentChildController::class, 'destroy']);

        // Suhbatlar — parent-cabinet Suhbatlar sahifasida yozish ("Suhbat boshlash"
        // endi yuqoridagi umumiy ConversationController@start'da, ADR-0003)
        Route::post('conversations/{conversation}/messages', [ParentMessageController::class, 'store'])
            ->middleware('throttle:chat-message');
    });

});
