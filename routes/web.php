<?php

use App\Http\Controllers\InstitutionCabinetController;
use App\Http\Controllers\ParentCabinetController;
use App\Http\Controllers\TeacherCabinetController;
use App\Models\ForumThread;
use App\Models\InstitutionView;
use App\Support\MaktabgidData;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/* ---------------- Til almashtirish (uch tillilik, 2026-08-06) ----------------
 * Sessiyaga va cookie'ga ('maktabgid_locale', 1 yil) yozadi — SetLocale
 * middleware'i shulardan o'qiydi. Foydalanuvchi qaysi sahifada bo'lsa, o'sha
 * sahifaga qaytariladi (HTTP Referer, aks holda bosh sahifa). */
Route::get('/til/{locale}', function (string $locale) {
    $supported = array_keys(config('localization.supported', []));
    abort_unless(in_array($locale, $supported, true), 404);

    session(['locale' => $locale]);

    return redirect()->to(url()->previous('/'))
        ->withCookie(cookie('maktabgid_locale', $locale, 60 * 24 * 365));
})->name('locale.switch');

/* ---------------- Forum ---------------- */
Route::get('/forum', function () {
    return view('forum', ['threads' => MaktabgidData::forumThreads()]);
})->name('forum.index');

Route::get('/forum/{id}', function (int $id) {
    $thread = MaktabgidData::forumThread($id);
    abort_if(! $thread, 404);

    // Ko'rishlar hisoblagichi — har bir sahifa ochilishida oshiriladi (oddiy, IP/sessiyaga
    // qarab bir martalik hisoblash hozircha yo'q — institution_views bilan bir xil darajada
    // sodda, ADR-0002 Faza 2'da kengaytirilishi mumkin).
    ForumThread::whereKey($id)->increment('view_count');

    return view('forum-thread', ['thread' => $thread, 'replies' => MaktabgidData::forumReplies($id)]);
})->name('forum.show');

/* ---------------- Blog ---------------- */
Route::get('/blog', function () {
    return view('blog', ['articles' => MaktabgidData::articles()]);
})->name('blog.index');

Route::get('/blog/{id}', function (int $id) {
    $article = MaktabgidData::article($id);
    abort_if(! $article, 404);

    return view('blog-article', ['article' => $article, 'related' => MaktabgidData::articles()]);
})->name('blog.show');

/* ---------------- Yangiliklar (news) ---------------- */
Route::get('/yangiliklar', function () {
    return view('news', ['news' => MaktabgidData::news()]);
})->name('news.index');

Route::get('/yangiliklar/{id}', function (int $id) {
    $item = MaktabgidData::newsItem($id);
    abort_if(! $item, 404);

    return view('news-article', ['item' => $item, 'related' => MaktabgidData::news()]);
})->name('news.show');

/* ---------------- Vakansiyalar (careers) ---------------- */
Route::get('/vakansiyalar', function () {
    return view('careers', [
        'vacancies' => MaktabgidData::careerVacancies(),
        'resumes' => MaktabgidData::resumes(),
        'tab' => request('tab', 'vac'),
    ]);
})->name('careers.index');

Route::get('/vakansiyalar/{id}', function (int $id) {
    $vacancy = MaktabgidData::careerVacancy($id);
    abort_if(! $vacancy, 404);

    return view('vacancy', ['vacancy' => $vacancy]);
})->name('careers.show');

/* ---------------- Kabinet (ota-ona) ----------------
 * ParentCabinetController + x-parent.shell — institution-cabinet/teacher-cabinet
 * bilan bir xil andozada (bitta umumiy qobiq, har bir bo'lim o'z route'i). */
Route::get('/cabinet', [ParentCabinetController::class, 'dashboard'])->name('cabinet.index');
Route::get('/cabinet/children', [ParentCabinetController::class, 'children'])->name('cabinet.children');
Route::get('/cabinet/favorites', [ParentCabinetController::class, 'favorites'])->name('cabinet.favorites');
Route::get('/cabinet/applications', [ParentCabinetController::class, 'applications'])->name('cabinet.applications');
Route::get('/cabinet/conversations', [ParentCabinetController::class, 'conversations'])->name('cabinet.conversations');
Route::get('/cabinet/subscription', [ParentCabinetController::class, 'subscription'])->name('cabinet.subscription');

/* ---------------- Muassasa kabineti ("Boshqaruv paneli" dashboard) ---------------- */
Route::get('/institution-cabinet', [InstitutionCabinetController::class, 'dashboard'])->name('institution.cabinet');
Route::get('/institution-cabinet/lidlar', [InstitutionCabinetController::class, 'leads'])->name('institution.cabinet.leads');
Route::get('/institution-cabinet/ekskursiyalar', [InstitutionCabinetController::class, 'excursions'])->name('institution.cabinet.excursions');
Route::get('/institution-cabinet/suhbatlar', [InstitutionCabinetController::class, 'conversations'])->name('institution.cabinet.conversations');
Route::get('/institution-cabinet/analitika', [InstitutionCabinetController::class, 'analytics'])->name('institution.cabinet.analytics');
Route::get('/institution-cabinet/teachers', [InstitutionCabinetController::class, 'teachers'])->name('institution.cabinet.teachers');
Route::get('/institution-cabinet/achievements', [InstitutionCabinetController::class, 'achievements'])->name('institution.cabinet.achievements');
Route::get('/institution-cabinet/gallery', [InstitutionCabinetController::class, 'gallery'])->name('institution.cabinet.gallery');
Route::get('/institution-cabinet/vacancies', [InstitutionCabinetController::class, 'vacancies'])->name('institution.cabinet.vacancies');
Route::get('/institution-cabinet/profil', [InstitutionCabinetController::class, 'profile'])->name('institution.cabinet.profile');
Route::get('/institution-cabinet/tariflar', [InstitutionCabinetController::class, 'plans'])->name('institution.cabinet.plans');
Route::get('/institution-cabinet/tariflar/{plan}', [InstitutionCabinetController::class, 'checkout'])
    ->where('plan', 'standard|gold|premium')
    ->name('institution.cabinet.checkout');

/* ---------------- Ustoz kabineti (o'qituvchi dashboard qobig'i) ----------------
 * "teacher" — User::ROLE_TEACHER sifatida to'liq modellashtirilgan (ADR-0001/0002):
 * ro'yxatdan o'tish (RegisterTeacherController), login-redirect va auth/rol
 * tekshiruvi (TeacherCabinetController::context()) real ishlaydi. Rezyume ro'yxati
 * real (Resume.owner_user_id); Takliflar/Suhbatlar hali Faza 2'da rejalashtirilgan. */
Route::get('/teacher-cabinet', [TeacherCabinetController::class, 'dashboard'])->name('teacher.cabinet');
Route::get('/teacher-cabinet/resumes', [TeacherCabinetController::class, 'resumes'])->name('teacher.cabinet.resumes');
Route::get('/teacher-cabinet/vacancies', [TeacherCabinetController::class, 'vacancies'])->name('teacher.cabinet.vacancies');
Route::get('/teacher-cabinet/offers', [TeacherCabinetController::class, 'offers'])->name('teacher.cabinet.offers');
Route::get('/teacher-cabinet/conversations', [TeacherCabinetController::class, 'conversations'])->name('teacher.cabinet.conversations');
Route::get('/teacher-cabinet/payment', [TeacherCabinetController::class, 'tariffs'])->name('teacher.cabinet.tariffs');

/* ---------------- Muassasa profili (ommaviy) — /{slug}, masalan /katta-tanaffus-uchtepa-filiali-1 ----------------
 * Diqqat: bu route ATAYLAB fayl OXIRIDA turadi — bitta segmentli "catch-all"
 * ({slug} har qanday bitta segmentga mos keladi). Laravel routelarni ro'yxatga
 * olingan tartibda tekshiradi, shu sababli yuqoridagi barcha aniq yo'llar
 * (/forum, /blog, /yangiliklar, /vakansiyalar, /cabinet/*, /institution-cabinet/*,
 * /teacher-cabinet/*) BIRINCHI tekshiriladi va ustunlik qiladi — {slug} faqat
 * shulardan hech biriga mos kelmagan so'rovlarda ishga tushadi. Yangi bitta
 * segmentli sahifa/route qo'shilsa, u albatta shu qatordan OLDIN joylashtirilishi
 * kerak (aks holda muassasa slug'i sifatida "yutilib" ketishi mumkin). */
Route::get('/{slug}', function (string $slug) {
    $school = MaktabgidData::schoolBySlug($slug);
    abort_if(! $school, 404);

    // Ko'rishlar jurnali — muassasa kabinetidagi "Analitika" sahifasi shu yerdan
    // hisoblanadi (ADR-0002, Faza 2). Forum'dagi view_count bilan bir xil darajada
    // sodda: bir martalik hisoblash (IP/sessiya dedupe) hozircha yo'q.
    InstitutionView::create([
        'institution_id' => $school['id'],
        'viewer_user_id' => auth()->id(),
        'created_at' => now(),
    ]);

    return view('school', ['school' => $school]);
})->name('maktabgid.school');
