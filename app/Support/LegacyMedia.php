<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Eski (Yii2) bazadan import qilingan rasm/fayl yo'llarini hal qiladi.
 *
 * `old_data_maktab.sql`dagi jadvallar rasm uchun faqat nisbiy yo'l saqlagan
 * (masalan "/uploads/IMG_1234.jpeg") — haqiqiy fayllar shu loyihada yo'q,
 * lekin eski sayt (config('legacy.media_base_url')) hali ishlab turibdi.
 *
 * config('legacy.media_mode') ga qarab ikki xil natija beradi ("url"/"download"
 * — to'liq izoh config/legacy.php'da). Legacy* seederlar hammasi shu klass
 * orqali ishlaydi — rejim almashsa (.env) seederlarga tegish shart emas.
 */
class LegacyMedia
{
    public static function mode(): string
    {
        return config('legacy.media_mode', 'url');
    }

    /** Eski nisbiy yo'lni to'liq tashqi havolaga aylantiradi (allaqachon to'liq bo'lsa — o'zgarishsiz). */
    public static function externalUrl(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return config('legacy.media_base_url').'/'.ltrim($path, '/');
    }

    /**
     * Bitta legacy fayl yo'lini rejimga mos holda hal qiladi.
     *
     * Diqqat: `disk` MediaUploadService bilan bir xil qoidada har doim to'ldiriladi
     * (institution_media.disk ustuni NOT NULL) — tashqi havola bo'lsa ham
     * config('filesystems.media_disk') qo'yiladi, faqat `path` null qoladi
     * ("haqiqiy fayl shu diskda YO'Q, lekin ko'rsatiladigan disk shu" ma'nosida).
     *
     * @return array{disk: string, path: ?string, url: ?string}
     */
    public static function resolve(?string $legacyPath, string $downloadFolder): array
    {
        $disk = config('filesystems.media_disk', 'public');
        $external = self::externalUrl($legacyPath);

        if ($external === null) {
            return ['disk' => $disk, 'path' => null, 'url' => null];
        }

        if (self::mode() !== 'download') {
            return ['disk' => $disk, 'path' => null, 'url' => $external];
        }

        try {
            $response = Http::timeout(15)->get($external);

            if (! $response->successful()) {
                throw new \RuntimeException("Legacy media fetch failed: HTTP {$response->status()} ({$external})");
            }

            $extension = pathinfo(parse_url($external, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION);
            $extension = $extension !== '' ? $extension : 'jpg';
            $storedPath = trim($downloadFolder, '/').'/'.Str::random(24).'.'.$extension;

            Storage::disk($disk)->put($storedPath, $response->body());

            return ['disk' => $disk, 'path' => $storedPath, 'url' => Storage::disk($disk)->url($storedPath)];
        } catch (\Throwable $e) {
            // Tarmoq/eski server muammosi — "url" rejimiga muqobil sifatida qaytamiz,
            // import to'xtab qolmasligi kerak (backend.md: importlar idempotent va bardoshli bo'lishi kerak).
            report($e);

            return ['disk' => $disk, 'path' => null, 'url' => $external];
        }
    }
}
