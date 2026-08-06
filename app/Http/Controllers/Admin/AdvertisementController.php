<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/** Reklama bannerlari (bosh sahifa/katalog) — eski `advertisement`dan import qilingan (LegacyAdvertisementSeeder). */
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
        $data = array_merge($data, $this->handleImage($request));

        if (blank($data['image_url'] ?? null)) {
            return back()->withErrors(['image' => 'Banner rasmi shart.'])->withInput();
        }

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
        $data = array_merge($data, $this->handleImage($request, $advertisement));

        $advertisement->update($data);

        return redirect()->route('admin.advertisements.index')->with('status', 'Reklama yangilandi.');
    }

    public function destroy(Advertisement $advertisement): RedirectResponse
    {
        if ($advertisement->image_path) {
            Storage::disk($advertisement->disk ?? config('filesystems.media_disk', 'public'))->delete($advertisement->image_path);
        }

        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')->with('status', 'Reklama o\'chirildi.');
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'link_url' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'started_at' => ['nullable', 'date'],
            'finished_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        unset($data['image']);

        return $data;
    }

    /**
     * Yangi fayl yuklansa MEDIA_DISK'ga yoziladi (Article/InstitutionMedia bilan bir
     * xil andoza). Yuklanmasa — mavjud image_url (import qilingan tashqi havola
     * bo'lishi ham mumkin) o'zgarishsiz qoladi.
     *
     * @return array<string, mixed>
     */
    private function handleImage(Request $request, ?Advertisement $advertisement = null): array
    {
        if (! $request->hasFile('image')) {
            return [];
        }

        $disk = config('filesystems.media_disk', 'public');
        $path = $request->file('image')->store('advertisements', $disk);
        $url = Storage::disk($disk)->url($path);

        if ($advertisement && $advertisement->image_path) {
            Storage::disk($advertisement->disk ?? $disk)->delete($advertisement->image_path);
        }

        return ['disk' => $disk, 'image_path' => $path, 'image_url' => $url];
    }
}
