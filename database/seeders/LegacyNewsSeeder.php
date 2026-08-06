<?php

namespace Database\Seeders;

use App\Models\News;
use App\Support\LegacyMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** Eski `post` jadvalidan (2026-08-06 backup: 2 ta yozuv) real yangiliklarni import qiladi. */
class LegacyNewsSeeder extends Seeder
{
    public function run(): void
    {
        $posts = $this->loadJson(__DIR__.'/legacy_fixtures/post.json');

        if (empty($posts)) {
            $this->command?->warn('LegacyNewsSeeder: legacy_fixtures/post.json topilmadi — o\'tkazib yuborildi.');

            return;
        }

        $imported = 0;

        foreach ($posts as $p) {
            $legacyId = (int) $p['id'];
            $title = $this->pickLang($p['title'] ?? null) ?? "Yangilik #{$legacyId}";
            $excerpt = $this->pickLang($p['sub_text'] ?? null);
            $body = $this->cleanHtml($this->pickLang($p['description'] ?? null));

            if (blank($excerpt)) {
                $excerpt = $body ? Str::limit($body, 160) : Str::limit($title, 160);
            }

            $image = LegacyMedia::resolve($p['image'] ?? null, 'news');

            News::updateOrCreate(
                ['legacy_id' => $legacyId],
                [
                    'tag' => 'Yangilik',
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'body' => $body,
                    'source' => null,
                    'published_at' => $p['publish_at'] ?? $p['created_at'] ?? now(),
                    'hot' => false,
                    'disk' => $image['disk'],
                    'image_path' => $image['path'],
                    'image_url' => $image['url'],
                ]
            );

            $imported++;
        }

        $this->command?->info("LegacyNewsSeeder: {$imported} ta yangilik import qilindi.");
    }

    private function cleanHtml(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $withBreaks = preg_replace('/<\s*(br|\/p|\/div)\s*\/?>/i', "\n", $html);
        $text = trim(strip_tags((string) $withBreaks));

        return $text !== '' ? $text : null;
    }

    private function decode(mixed $json): mixed
    {
        if ($json === null || $json === '') {
            return null;
        }

        if (is_array($json)) {
            return $json;
        }

        if (! is_string($json)) {
            return null;
        }

        $decoded = json_decode($json, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }

    private function pickLang(mixed $json): ?string
    {
        $decoded = $this->decode($json);

        if ($decoded === null) {
            return is_string($json) && trim($json) !== '' ? trim($json) : null;
        }

        if (! is_array($decoded)) {
            return is_string($decoded) && trim($decoded) !== '' ? trim($decoded) : null;
        }

        foreach (['uzl', 'ru', 'uzk', 'en'] as $lang) {
            if (! empty($decoded[$lang])) {
                return trim((string) $decoded[$lang]);
            }
        }

        foreach ($decoded as $value) {
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        return null;
    }

    /** @return array<int, array<string, mixed>> */
    private function loadJson(string $path): array
    {
        if (! is_file($path)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        return is_array($decoded) ? $decoded : [];
    }
}
