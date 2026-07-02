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
        $institution = $request->user()->institution()->firstOrFail();
        $this->authorize('update', $institution);

        $data = $request->validate([
            'type' => ['required', Rule::in(['gallery', 'lesson', 'video'])],
            'file' => ['required_without:url', 'nullable', 'image', 'max:5120'],
            'url' => ['required_without:file', 'nullable', 'url'], // masalan YouTube/Vimeo video link
            'caption' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $media = $uploader->store(
            $institution,
            $data['type'],
            $request->file('file'),
            $data['url'] ?? null,
            $data['caption'] ?? null,
        );

        return response()->json(['media' => $media], 201);
    }

    public function destroy(Request $request, InstitutionMedia $media, MediaUploadService $uploader): JsonResponse
    {
        $institution = $request->user()->institution()->firstOrFail();
        $this->authorize('update', $institution);

        abort_unless($media->institution_id === $institution->id, 403);

        $uploader->delete($media);

        return response()->json(['ok' => true]);
    }
}
