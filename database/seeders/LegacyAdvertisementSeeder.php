<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Support\LegacyMedia;
use Illuminate\Database\Seeder;

/** Eski `advertisement` jadvalidan (2026-08-06 backup: 2 ta banner) real reklamalarni import qiladi. */
class LegacyAdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $rows = $this->loadJson(__DIR__.'/legacy_fixtures/advertisement.json');

        if (empty($rows)) {
            $this->command?->warn('LegacyAdvertisementSeeder: legacy_fixtures/advertisement.json topilmadi — o\'tkazib yuborildi.');

            return;
        }

        $imported = 0;

        foreach ($rows as $r) {
            $legacyId = (int) $r['id'];
            $image = LegacyMedia::resolve($r['photo'] ?? null, 'advertisements');

            Advertisement::updateOrCreate(
                ['legacy_id' => $legacyId],
                [
                    'image_url' => $image['url'] ?? '',
                    'disk' => $image['path'] ? $image['disk'] : null,
                    'image_path' => $image['path'],
                    'link_url' => filled($r['url'] ?? null) ? $r['url'] : null,
                    'is_active' => (int) ($r['status'] ?? 0) === 1,
                    'started_at' => $r['started_at'] ?? null,
                    'finished_at' => $r['finished_at'] ?? null,
                ]
            );

            $imported++;
        }

        $this->command?->info("LegacyAdvertisementSeeder: {$imported} ta reklama import qilindi.");
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
