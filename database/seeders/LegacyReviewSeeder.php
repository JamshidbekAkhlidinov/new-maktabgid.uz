<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Review;
use Illuminate\Database\Seeder;

/**
 * Eski `telegram_object_comment` (matnli izoh) va `telegram_object_rate`
 * (faqat baho, matnsiz) jadvallaridan real sharhlarni import qiladi.
 *
 * Ikkalasi ham Telegram-bot foydalanuvchisidan kelgan (ro'yxatdan o'tgan sayt
 * akkaunti emas) — shu sababli `user_id` null, ism `guest_name`da saqlanadi
 * (reviews migration, 2026-08-06). Institution.rating/review_count'ni
 * ReviewObserver har bir Review yaratilishidan keyin avtomatik qayta hisoblaydi.
 *
 * Faqat LegacyInstitutionSeeder import qilgan (legacy_id bor) muassasalarga
 * bog'langan yozuvlar olinadi — bog'liq muassasa yo'q bo'lsa o'tkazib yuboriladi.
 */
class LegacyReviewSeeder extends Seeder
{
    public function run(): void
    {
        $fixtures = __DIR__.'/legacy_fixtures';

        $comments = $this->loadJson("$fixtures/telegram_object_comment.json");
        $rates = $this->loadJson("$fixtures/telegram_object_rate.json");

        if (empty($comments) && empty($rates)) {
            $this->command?->warn('LegacyReviewSeeder: legacy_fixtures topilmadi — o\'tkazib yuborildi.');

            return;
        }

        $institutionIds = Institution::whereNotNull('legacy_id')->pluck('id', 'legacy_id');

        $imported = 0;

        foreach ($comments as $c) {
            $institutionId = $institutionIds[(int) ($c['telegram_object_id'] ?? 0)] ?? null;

            if (! $institutionId) {
                continue;
            }

            $body = trim($this->decodeLegacyText((string) ($c['text'] ?? '')));
            $rating = (int) ($c['rate'] ?? 5);
            $rating = max(1, min(5, $rating ?: 5));

            $review = Review::updateOrCreate(
                ['legacy_comment_id' => (int) $c['id']],
                [
                    'institution_id' => $institutionId,
                    'user_id' => null,
                    'guest_name' => filled($c['name'] ?? null) ? $this->decodeLegacyText(trim($c['name'])) : 'Mehmon',
                    'rating' => $rating,
                    'body' => $body !== '' ? $body : null,
                ]
            );

            if (filled($c['created_at'] ?? null)) {
                $review->forceFill(['created_at' => $c['created_at'], 'updated_at' => $c['created_at']])->saveQuietly();
            }

            $imported++;
        }

        foreach ($rates as $r) {
            $institutionId = $institutionIds[(int) ($r['telegram_object_id'] ?? 0)] ?? null;

            if (! $institutionId) {
                continue;
            }

            $rating = (int) round((float) ($r['rate'] ?? 5));
            $rating = max(1, min(5, $rating ?: 5));

            $review = Review::updateOrCreate(
                ['legacy_rate_id' => (int) $r['id']],
                [
                    'institution_id' => $institutionId,
                    'user_id' => null,
                    'guest_name' => 'Mehmon',
                    'rating' => $rating,
                    'body' => null,
                ]
            );

            if (filled($r['created_at'] ?? null)) {
                $review->forceFill(['created_at' => $r['created_at'], 'updated_at' => $r['created_at']])->saveQuietly();
            }

            $imported++;
        }

        $this->command?->info("LegacyReviewSeeder: {$imported} ta sharh/baho import qilindi.");
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

    /**
     * Eski tizim matnni saqlashdan oldin htmlspecialchars() qilib qo'ygan
     * (masalan "zo&#039;r" — asli "zo'r"). Laravel buni qayta chiqarganda
     * ({{ }} avtomatik escape qiladi) "&" belgisi yana escape bo'lib,
     * ekranda literal "&#039;" ko'rinib qoladi — shu sababli import paytida
     * bir marta dekod qilib, haqiqiy belgilarni tiklaymiz.
     */
    private function decodeLegacyText(string $text): string
    {
        return html_entity_decode($text, ENT_QUOTES | ENT_HTML5);
    }
}
