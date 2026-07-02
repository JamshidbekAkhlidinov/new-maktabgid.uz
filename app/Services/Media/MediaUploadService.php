<?php

namespace App\Services\Media;

use App\Models\Institution;
use App\Models\InstitutionMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Muassasa media fayllarini config('filesystems.media_disk') ga yozadi.
 * Disk almashsa (local -> r2) shu servisning kodini o'zgartirish shart emas — faqat .env.
 */
class MediaUploadService
{
    public function disk(): string
    {
        return config('filesystems.media_disk', 'public');
    }

    public function store(Institution $institution, string $type, ?UploadedFile $file, ?string $externalUrl, ?string $caption): InstitutionMedia
    {
        $disk = $this->disk();
        $path = null;
        $url = $externalUrl;

        if ($file) {
            $path = $file->store("institutions/{$institution->id}", $disk);
            $url = Storage::disk($disk)->url($path);
        }

        $sortOrder = (int) $institution->media()->where('type', $type)->max('sort_order') + 1;

        return $institution->media()->create([
            'type' => $type,
            'disk' => $disk,
            'url' => $url,
            'path' => $path,
            'caption' => $caption,
            'sort_order' => $sortOrder,
        ]);
    }

    public function delete(InstitutionMedia $media): void
    {
        if ($media->path) {
            Storage::disk($media->disk)->delete($media->path);
        }

        $media->delete();
    }
}
