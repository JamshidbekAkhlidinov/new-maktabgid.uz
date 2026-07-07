<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class ArticleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:articles.view', only: ['index']),
            new Middleware('permission:articles.create', only: ['create', 'store']),
            new Middleware('permission:articles.update', only: ['edit', 'update']),
            new Middleware('permission:articles.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $articles = Article::query()
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', "%{$request->string('q')}%"))
            ->latest('published_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('admin.articles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Article::create($this->validateData($request));

        return redirect()->route('admin.articles.index')->with('status', 'Maqola yaratildi.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $article->update($this->validateData($request));

        return redirect()->route('admin.articles.index')->with('status', 'Maqola yangilandi.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('admin.articles.index')->with('status', 'Maqola o\'chirildi.');
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'tag' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string'],
            'body' => ['nullable', 'string'],
            'author_name' => ['required', 'string', 'max:255'],
            'read_minutes' => ['required', 'integer', 'min:1', 'max:120'],
            'featured' => ['nullable', 'boolean'],
            'published_at' => ['required', 'date'],
        ]);

        $data['featured'] = (bool) ($data['featured'] ?? false);

        return $data;
    }
}
