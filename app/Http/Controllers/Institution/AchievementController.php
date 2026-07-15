<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Muassasa kabineti — "O'quvchilar yutuqlari" o'z-o'ziga xizmat CRUD'i
 * (institution/achievements.blade.php). MediaController bilan bir xil
 * andoza: config('filesystems.media_disk') diskiga ixtiyoriy sertifikat/rasm
 * yuklanadi (ADR-0002, Faza 2).
 */
class AchievementController extends Controller
{
    private const LEVELS = ['intl', 'national', 'regional', 'city'];

    public function store(Request $request): JsonResponse
    {
        $institution = $this->activeInstitutionOrFail($request);
        $this->authorize('update', $institution);

        $data = $this->validated($request);

        $achievement = $institution->achievements()->create(
            $data + $this->storeImage($request, $institution->id)
        );

        return response()->json(['achievement' => $achievement], 201);
    }

    public function update(Request $request, Achievement $achievement): JsonResponse
    {
        $institution = $this->activeInstitutionOrFail($request);
        $this->authorize('update', $institution);

        abort_unless($achievement->institution_id === $institution->id, 403);

        $data = $this->validated($request);
        $imageData = $this->storeImage($request, $institution->id);

        if ($imageData) {
            $this->deleteImage($achievement);
        }

        $achievement->update($data + $imageData);

        return response()->json(['achievement' => $achievement]);
    }

    public function destroy(Request $request, Achievement $achievement): JsonResponse
    {
        $institution = $this->activeInstitutionOrFail($request);
        $this->authorize('update', $institution);

        abort_unless($achievement->institution_id === $institution->id, 403);

        $this->deleteImage($achievement);
        $achievement->delete();

        return response()->json(['ok' => true]);
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
