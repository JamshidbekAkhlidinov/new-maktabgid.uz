<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Institution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Admin — muassasa nomidan "O'quvchilar yutuqlari" CRUD'i (institution
 * kabinetidagi Institution\AchievementController bilan bir xil andoza, lekin
 * route-bog'langan Institution orqali — admin muassasa egasi emas, 2026-07-15).
 */
class InstitutionAchievementController extends Controller implements HasMiddleware
{
    private const LEVELS = ['intl', 'national', 'regional', 'city'];

    public static function middleware(): array
    {
        return [
            new Middleware('permission:institutions.update'),
        ];
    }

    public function index(Institution $institution): View
    {
        return view('admin.institutions.achievements', [
            'institution' => $institution,
            'achievements' => $institution->achievements()->latest()->get(),
        ]);
    }

    public function store(Request $request, Institution $institution): RedirectResponse
    {
        $data = $this->validated($request);

        $institution->achievements()->create($data + $this->storeImage($request, $institution->id));

        return redirect()->route('admin.institutions.achievements.index', $institution)->with('status', 'Yutuq qo\'shildi.');
    }

    public function update(Request $request, Institution $institution, Achievement $achievement): RedirectResponse
    {
        abort_unless($achievement->institution_id === $institution->id, 404);

        $data = $this->validated($request);
        $imageData = $this->storeImage($request, $institution->id);

        if ($imageData) {
            $this->deleteImage($achievement);
        }

        $achievement->update($data + $imageData);

        return redirect()->route('admin.institutions.achievements.index', $institution)->with('status', 'Yutuq yangilandi.');
    }

    public function destroy(Institution $institution, Achievement $achievement): RedirectResponse
    {
        abort_unless($achievement->institution_id === $institution->id, 404);

        $this->deleteImage($achievement);
        $achievement->delete();

        return redirect()->route('admin.institutions.achievements.index', $institution)->with('status', 'Yutuq o\'chirildi.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'student_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'year' => ['sometimes', 'nullable', 'integer', 'min:2000', 'max:2100'],
            'type' => ['sometimes', 'nullable', 'string', 'max:120'],
            'level' => ['required', Rule::in(self::LEVELS)],
        ]);
    }

    private function storeImage(Request $request, int $institutionId): array
    {
        if (! $request->hasFile('image')) {
            return [];
        }

        $disk = config('filesystems.media_disk', 'public');
        $path = $request->file('image')->store("institutions/{$institutionId}/achievements", $disk);

        return [
            'disk' => $disk,
            'image_path' => $path,
            'image_url' => Storage::disk($disk)->url($path),
        ];
    }

    private function deleteImage(Achievement $achievement): void
    {
        if ($achievement->image_path && $achievement->disk) {
            Storage::disk($achievement->disk)->delete($achievement->image_path);
        }
    }
}
