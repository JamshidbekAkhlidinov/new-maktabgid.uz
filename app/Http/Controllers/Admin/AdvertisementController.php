<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

/**
 * Reklama bannerlari (bosh sahifa, `<x-maktabgid.ad-banner>`) — matnli/gradient
 * kartochka, rasm ishlatilmaydi (2026-08-30, `image_url` ustuni faqat eski
 * import qoldig'i, endi bo'sh qatorga o'rnatiladi). Havola oddiy matn maydoni —
 * admin muassasa profilining ommaviy URL'ini (yoki istalgan boshqa manzilni)
 * qo'lda kiritadi, alohida "tashkilot tanlash" bog'lanishi shart emas.
 */
class AdvertisementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:advertisements.view', only: ['index']),
            new Middleware('permission:advertisements.create', only: ['create', 'store']),
            new Middleware('permission:advertisements.update', only: ['edit', 'update']),
            new Middleware('permission:advertisements.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $advertisements = Advertisement::query()
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.advertisements.index', compact('advertisements'));
    }

    public function create(): View
    {
        return view('admin.advertisements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['image_url'] = '';

        Advertisement::create($data);

        return redirect()->route('admin.advertisements.index')->with('status', 'Reklama yaratildi.');
    }

    public function edit(Advertisement $advertisement): View
    {
        return view('admin.advertisements.edit', compact('advertisement'));
    }

    public function update(Request $request, Advertisement $advertisement): RedirectResponse
    {
        $data = $this->validateData($request);

        $advertisement->update($data);

        return redirect()->route('admin.advertisements.index')->with('status', 'Reklama yangilandi.');
    }

    public function destroy(Advertisement $advertisement): RedirectResponse
    {
        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')->with('status', 'Reklama o\'chirildi.');
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:255'],
            'tag' => ['nullable', 'string', 'max:255'],
            'initials' => ['nullable', 'string', 'max:4'],
            'rating' => ['nullable', 'string', 'max:5'],
            'description' => ['nullable', 'string'],
            'cta_label' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'started_at' => ['nullable', 'date'],
            'finished_at' => ['nullable', 'date', 'after_or_equal:started_at'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        return $data;
    }
}
