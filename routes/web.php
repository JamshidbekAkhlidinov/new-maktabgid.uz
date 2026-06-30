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

    return view('forum-thread', ['thread' => $thread, 'replies' => MaktabgidData::forumReplies()]);
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
