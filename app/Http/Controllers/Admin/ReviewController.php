<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class ReviewController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:reviews.view', only: ['index']),
            new Middleware('permission:reviews.create', only: ['create', 'store']),
            new Middleware('permission:reviews.update', only: ['edit', 'update']),
            new Middleware('permission:reviews.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $reviews = Review::query()
            ->with(['institution', 'author'])
            ->when($request->filled('q'), fn ($q) => $q->where('body', 'like', "%{$request->string('q')}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function create(): View
    {
        return view('admin.reviews.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        Review::create($this->validateData($request));

        return redirect()->route('admin.reviews.index')->with('status', 'Sharh yaratildi.');
    }

    public function edit(Review $review): View
    {
        return view('admin.reviews.edit', $this->formData($review));
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $review->update($this->validateData($request));

        return redirect()->route('admin.reviews.index')->with('status', 'Sharh yangilandi.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('status', 'Sharh o\'chirildi.');
    }

    /** @return array<string, mixed> */
    private function formData(?Review $review = null): array
    {
        return [
            'review' => $review,
            'institutions' => Institution::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ];
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'institution_id' => ['required', 'exists:institutions,id'],
            'user_id' => ['required', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['required', 'string'],
        ]);
    }
}
