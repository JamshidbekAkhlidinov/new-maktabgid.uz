<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionMedia;
use App\Services\Media\MediaUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Admin — muassasa nomidan galereya rasmlari va videolarni boshqarish
 * (institution kabinetidagi gallery.blade.php / profile.blade.php "Videolar"
 * bo'limi bilan bir xil imkoniyat, lekin route-bog'langan Institution orqali —
 * activeInstitutionOrFail() o'rniga, chunki bu yerda tizimga kirgan shaxs
 * muassasa egasi emas, admin, 2026-07-15).
 */
class InstitutionMediaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:institutions.update'),
        ];
    }

    public function index(Institution $institution): View
    {
        $institution->load('media');

        return view('admin.institutions.media', [
            'institution' => $institution,
            'logoMedia' => $institution->logoMedia(),
            'galleryMedia' => $institution->media->where('type', 'gallery')->sortBy('sort_order')->values(),
            'videoMedia' => $institution->media->where('type', 'video')->sortBy('sort_order')->values(),
        ]);
    }

    public function store(Request $request, Institution $institution, MediaUploadService $uploader): RedirectResponse
    {
        $isVideo = $request->input('type') === 'video';

        $data = $request->validate([
            'type' => ['required', Rule::in(['gallery', 'video'])],
            'file' => $isVideo
                ? ['required_without:url', 'nullable', 'file', 'mimes:mp4,mov,webm,avi,mkv', 'max:102400']
                : ['required_without:url', 'nullable', 'image', 'max:5120'],
            'url' => ['required_without:file', 'nullable', 'url'],
            'caption' => ['sometimes', 'nullable', 'string', 'max:255'],
            'duration' => ['sometimes', 'nullable', 'string', 'max:20'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ]);

        $uploader->store(
            $institution,
            $data['type'],
            $request->file('file'),
            $data['url'] ?? null,
            $data['caption'] ?? null,
            $data['duration'] ?? null,
            $data['description'] ?? null,
        );

        return redirect()->route('admin.institutions.media.index', $institution)->with('status', 'Fayl yuklandi.');
    }

    public function destroy(Institution $institution, InstitutionMedia $media, MediaUploadService $uploader): RedirectResponse
    {
        abort_unless($media->institution_id === $institution->id, 404);

        $uploader->delete($media);

        return redirect()->route('admin.institutions.media.index', $institution)->with('status', 'Fayl o\'chirildi.');
    }

    /**
     * Galereyadagi bitta rasmni "logo" qilib belgilaydi (bosh sahifa kartochkasi va
     * detail sahifadagi asosiy rasm sifatida ishlatiladi) — yoki agar u allaqachon
     * logo bo'lsa, qayta galereyaga qaytaradi. Bir muassasada faqat bitta logo
     * bo'lishi mumkin, shuning uchun avvalgi logo (bo'lsa) galereyaga qaytariladi.
     */
    public function toggleLogo(Institution $institution, InstitutionMedia $media): RedirectResponse
    {
        abort_unless($media->institution_id === $institution->id, 404);
        abort_unless(in_array($media->type, ['gallery', 'logo'], true), 404);

        if ($media->type === 'logo') {
            $media->update(['type' => 'gallery']);
            $status = 'Logo bekor qilindi.';
        } else {
            $institution->media()->where('type', 'logo')->update(['type' => 'gallery']);
            $media->update(['type' => 'logo']);
            $status = 'Logo belgilandi.';
        }

        return redirect()->route('admin.institutions.media.index', $institution)->with('status', $status);
    }
}
