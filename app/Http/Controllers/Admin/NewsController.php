<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class NewsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:news.view', only: ['index']),
            new Middleware('permission:news.create', only: ['create', 'store']),
            new Middleware('permission:news.update', only: ['edit', 'update']),
            new Middleware('permission:news.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $newsItems = News::query()
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', "%{$request->string('q')}%"))
            ->latest('published_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.news.index', compact('newsItems'));
    }

    public function create(): View
    {
        return view('admin.news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        News::create($this->validateData($request));

        return redirect()->route('admin.news.index')->with('status', 'Yangilik yaratildi.');
    }

    public function edit(News $news): View
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $news->update($this->validateData($request));

        return redirect()->route('admin.news.index')->with('status', 'Yangilik yangilandi.');
    }

    public function destroy(News $news): RedirectResponse
    {
        $news->delete();

        return redirect()->route('admin.news.index')->with('status', 'Yangilik o\'chirildi.');
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'tag' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string'],
            'body' => ['nullable', 'string'],
            'source' => ['nullable', 'string', 'max:255'],
            'published_at' => ['required', 'date'],
            'hot' => ['nullable', 'boolean'],
        ]);

        $data['hot'] = (bool) ($data['hot'] ?? false);

        return $data;
    }
}
