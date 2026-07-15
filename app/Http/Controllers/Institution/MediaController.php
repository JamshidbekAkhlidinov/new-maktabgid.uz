<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\InstitutionMedia;
use App\Services\Media\MediaUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MediaController extends Controller
{
    public function store(Request $request, MediaUploadService $uploader): JsonResponse
    {
        $institution = $this->activeInstitutionOrFail($request);
        $this->authorize('update', $institution);

        // "Videolar" (type=video) haqiqiy fayl sifatida yuklanadi (mp4/webm/mov, maks 100MB) —
        // rasm/dars lavhasi (gallery/lesson) esa hamon rasm sifatida qoladi. Ikkalasida ham
        // 'url' (masalan YouTube/Vimeo havolasi) fayl o'rniga ixtiyoriy qoladi (2026-07-15).
        $isVideo = $request->input('type') === 'video';

        $data = $request->validate([
            'type' => ['required', Rule::in(['gallery', 'lesson', 'video'])],
            'file' => $isVideo
                ? ['required_without:url', 'nullable', 'file', 'mimes:mp4,mov,webm,avi,mkv', 'max:102400']
                : ['required_without:url', 'nullable', 'image', 'max:5120'],
            'url' => ['required_without:file', 'nullable', 'url'], // masalan YouTube/Vimeo video link
            'caption' => ['sometimes', 'nullable', 'string', 'max:255'],
            'duration' => ['sometimes', 'nullable', 'string', 'max:20'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ]);

        $media = $uploader->store(
            $institution,
            $data['type'],
            $request->file('file'),
            $data['url'] ?? null,
            $data['caption'] ?? null,
            $data['duration'] ?? null,
            $data['description'] ?? null,
        );

        return response()->json(['media' => $media], 201);
    }

    public function destroy(Request $request, InstitutionMedia $media, MediaUploadService $uploader): JsonResponse
    {
        $institution = $this->activeInstitutionOrFail($request);
        $this->authorize('update', $institution);

        abort_unless($media->institution_id === $institution->id, 403);

        $uploader->delete($media);

        return response()->json(['ok' => true]);
    }
}
