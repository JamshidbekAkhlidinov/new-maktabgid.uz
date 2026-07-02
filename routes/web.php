<?php

use App\Support\MaktabgidData;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/maktab/{id}', function (int $id) {
    $school = MaktabgidData::school($id);
    abort_if(! $school, 404);

    return view('school', ['school' => $school]);
})->name('maktabgid.school');

/* ---------------- Forum ---------------- */
Route::get('/forum', function () {
    return view('forum', ['threads' => MaktabgidData::forumThreads()]);
})->name('forum.index');

Route::get('/forum/{id}', function (int $id) {
    $thread = MaktabgidData::forumThread($id);
    abort_if(! $thread, 404);

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

/* ---------------- Kabinet ---------------- */
Route::get('/cabinet', function () {
    $user = auth()->user();

    if (! $user || ! $user->isParent()) {
        return view('cabinet', [
            'favorites' => collect(),
            'applications' => collect(),
            'conversations' => collect(),
            'stats' => ['favorites' => 0, 'applications' => 0, 'conversations' => 0],
        ]);
    }

    $user->loadMissing('district');

    $favorites = $user->favorites()->with('institution.district')->latest()->get();
    $applications = $user->applications()->with('institution')->latest()->get();
    $conversations = $user->conversations()->with('institution')->latest('last_message_at')->get();

    return view('cabinet', [
        'favorites' => $favorites,
        'applications' => $applications,
        'conversations' => $conversations,
        'stats' => [
            'favorites' => $favorites->count(),
            'applications' => $applications->count(),
            'conversations' => $conversations->count(),
        ],
    ]);
})->name('cabinet.index');

Route::get('/institution-cabinet', function () {
    $user = auth()->user();

    if (! $user || ! $user->isInstitution()) {
        return view('institution-cabinet', [
            'institution' => null,
            'applications' => collect(),
            'stats' => ['applications' => 0, 'conversations' => 0, 'favorites' => 0],
        ]);
    }

    $institution = $user->institution()->with(['district', 'specializations', 'media'])->first();
    $applications = $institution ? $institution->applications()->latest()->get() : collect();

    return view('institution-cabinet', [
        'institution' => $institution,
        'applications' => $applications,
        'stats' => [
            'applications' => $applications->count(),
            'pending' => $applications->where('status', 'pending')->count(),
            'confirmed' => $applications->where('status', 'confirmed')->count(),
            'conversations' => $institution ? $institution->conversations()->count() : 0,
            'favorites' => $institution ? $institution->favorites()->count() : 0,
        ],
    ]);
})->name('institution.cabinet');

Route::get('/chat', function () {
    return view('chat');
})->name('chat.index');
