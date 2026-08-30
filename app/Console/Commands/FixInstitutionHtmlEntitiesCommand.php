<?php

namespace App\Console\Commands;

use App\Models\Institution;
use Illuminate\Console\Command;

/**
 * Bir martalik tuzatish komandasi: eski (Yii2) importidan qolgan matnlarda
 * HTML entity kodlari (masalan "o&lsquo;rgatishga", "Ta&rsquo;lim") xom holda
 * saqlanib qolgan — bular hech qachon dekodlanmagani uchun Blade `{{ }}` ularni
 * qayta escape qilib, foydalanuvchiga literal "&lsquo;" matni ko'rinardi
 * (2026-08-30, muassasa profilida "Muassasa haqida" bo'limi buzilib chiqqani
 * xabar qilindi). Faqat `Institution.$translatable` ustunlarni tekshiradi —
 * boshqa modellarda (News/Article/Vacancy/Achievement/Specialization/
 * InstitutionType) tekshiruv shu muammoni topmadi.
 */
class FixInstitutionHtmlEntitiesCommand extends Command
{
    protected $signature = 'institutions:fix-html-entities {--dry-run : Faqat nechta yozuv tuzatilishini ko\'rsatadi, DB\'ga yozmaydi}';

    protected $description = 'Institution jadvalining tarjima qilinadigan ustunlaridagi xom HTML entity kodlarini (masalan &lsquo;, &rsquo;) haqiqiy belgilarga dekodlaydi';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        /** @var array<int, string> $columns */
        $columns = (new Institution)->getTranslatable();

        $fixedInstitutions = 0;
        $fixedFields = 0;

        Institution::query()->orderBy('id')->chunkById(100, function ($institutions) use ($columns, $dryRun, &$fixedInstitutions, &$fixedFields) {
            foreach ($institutions as $institution) {
                $changed = false;

                foreach ($columns as $column) {
                    $translations = $institution->getTranslations($column);

                    if ($translations === []) {
                        continue;
                    }

                    $decoded = [];
                    $columnChanged = false;

                    foreach ($translations as $locale => $text) {
                        if (! is_string($text) || $text === '') {
                            $decoded[$locale] = $text;

                            continue;
                        }

                        $newText = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $decoded[$locale] = $newText;

                        if ($newText !== $text) {
                            $columnChanged = true;
                        }
                    }

                    if ($columnChanged) {
                        $fixedFields++;
                        $changed = true;

                        if (! $dryRun) {
                            $institution->setTranslations($column, $decoded);
                        }
                    }
                }

                if ($changed) {
                    $fixedInstitutions++;

                    if (! $dryRun) {
                        $institution->saveQuietly();
                    }
                }
            }
        });

        if ($dryRun) {
            $this->info("[dry-run] {$fixedInstitutions} ta muassasa, {$fixedFields} ta ustun tuzatilgan bo'lardi.");
        } else {
            $this->info("{$fixedInstitutions} ta muassasa, {$fixedFields} ta ustun tuzatildi.");
        }

        return self::SUCCESS;
    }
}
