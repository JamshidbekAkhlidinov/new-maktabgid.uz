<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Institution;
use App\Models\InstitutionMedia;
use App\Models\Specialization;
use App\Support\LegacyMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Eski (Yii2) `telegram_object` jadvalidan real muassasalarni import qiladi —
 * backend/old_data_maktab.sql (LegacyDataAudit — 2026-08-06). Manba:
 * database/seeders/legacy_fixtures/*.json (extract_legacy_fixtures.py orqali
 * SQL dumpdan bir marta ajratilgan, o'qishga tayyor toza JSON).
 *
 * Ko'lam: faqat Toshkent shahri (region_id=10, ~339 ta) — loyihada hozircha
 * shu shahar tumanlari (districts jadvali) mavjud, boshqa viloyatlardagi ~15 ta
 * yozuv (Andijon/Jizzax) shu sababli o'tkazib yuboriladi.
 *
 * Idempotent: `legacy_id` (institutions) va `legacy_id` (institution_media)
 * bo'yicha updateOrCreate — qayta ishga tushirilsa dublikat yaratmaydi.
 */
class LegacyInstitutionSeeder extends Seeder
{
    private const TASHKENT_CITY_REGION_ID = 10;

    /** Eski `telegram_category.id` (type=1000, muassasa turi) -> institutions.type */
    private const CATEGORY_TYPE_MAP = [
        1 => 'maktab',      // Xususiy maktablar
        2 => 'bogcha',      // Xususiy bog'chalar
        3 => 'markaz',      // O'quv markazlari
        4 => 'mutaxassis',  // Bolalar mutaxassislari
    ];

    /**
     * Eski `telegram_category.id` (type=2000, "yo'nalish/qulaylik") -> yangi
     * facilityCatalog kalitlari (MaktabgidData::facilityCatalog) va/yoki
     * specializations.key (SpecializationSeeder). Har ikkisi ham bo'lmasligi
     * mumkin — masalan "Onlayn videokuzatuv" faqat xavfsizlik/qulaylikka yaqin.
     */
    private const CATEGORY_TAG_MAP = [
        5 => ['facilities' => ['wifi']],                              // Onlayn videokuzatuv
        6 => ['facilities' => ['sport'], 'specializations' => ['sport']], // Futbol
        7 => ['specializations' => ['english']],                      // Ingliz tili
        8 => ['facilities' => ['sport']],                             // Basseyn
        25 => ['facilities' => ['it'], 'specializations' => ['it']],  // IT
        26 => ['specializations' => ['stem']],                        // Matematika
    ];

    /** Eski `district.id` (region_id=10) -> joriy `districts.name` (DistrictSeeder). Bektemir (123) — 0 ta muassasa, kiritilmagan. */
    private const DISTRICT_NAME_MAP = [
        122 => 'Uchtepa',
        124 => 'Yunusobod',
        125 => "Mirzo Ulug'bek",
        126 => 'Mirobod',
        127 => 'Shayxontohur',
        128 => 'Olmazor',
        129 => 'Sergeli',
        130 => 'Yakkasaroy',
        131 => 'Yashnobod',
        132 => 'Yangihayot',
        133 => 'Chilonzor',
    ];

    /** Eski `social_links` obyekt kalitlari (raqamli) -> platforma nomi (haqiqiy namunalardan aniqlangan). */
    private const SOCIAL_KEY_MAP = [
        '1000' => 'instagram',
        '2000' => 'facebook',
        '3000' => 'website',
        '4000' => 'telegram',
        '5000' => 'other',
    ];

    public function run(): void
    {
        $fixtures = __DIR__.'/legacy_fixtures';

        $objects = $this->loadJson("$fixtures/telegram_object.json");
        $photos = $this->loadJson("$fixtures/telegram_object_photo.json");
        $employees = $this->loadJson("$fixtures/telegram_object_employee.json");
        $categories = $this->loadJson("$fixtures/telegram_category.json");
        $professionLinks = $this->loadJson("$fixtures/profession_to_object.json");

        if (empty($objects)) {
            $this->command?->warn('LegacyInstitutionSeeder: legacy_fixtures/telegram_object.json topilmadi yoki bo\'sh — o\'tkazib yuborildi.');

            return;
        }

        // employer_type (category id, type=3000) -> uzl nom ("Administrator", "Hamshira" kabi)
        $roleLabels = collect($categories)
            ->filter(fn ($c) => (int) $c['type'] === 3000)
            ->mapWithKeys(fn ($c) => [(int) $c['id'] => $this->pickLang($c['name']) ?? ''])
            ->all();

        $districtIds = District::pluck('id', 'name');

        $photosByObject = collect($photos)->groupBy('telegram_object_id');
        $employeesByObject = collect($employees)->groupBy('telegram_object_id');
        $extraLinksByObject = collect($professionLinks)->groupBy('telegram_object_id');

        $imported = 0;
        $mediaImported = 0;

        foreach ($objects as $row) {
            if ((int) ($row['region_id'] ?? 0) !== self::TASHKENT_CITY_REGION_ID) {
                continue;
            }

            $legacyId = (int) $row['id'];

            // Uch tillilik (2026-08-06): eski backup'da nom/tavsif/manzil/mo'ljal uchun
            // uzl (o'zbekcha lotin) va ru (ruscha) haqiqiy tarjimalar mavjud edi — avval
            // pickLang() bittasini tanlab, qolganini yo'qotardi. Endi ikkalasini ham
            // saqlaymiz ({"uz":..,"ru":..}); inglizcha eski ma'lumotda umuman yo'q
            // (0% qamrov) — bo'sh qoladi, sayt uz'ga fallback qiladi, admin keyin to'ldiradi.
            $nameMap = $this->pickLangMap($row['name']);
            $name = $nameMap['uz'] ?? $nameMap['ru'] ?? $nameMap['en'] ?? "Muassasa #{$legacyId}";
            if (empty($nameMap)) {
                $nameMap = ['uz' => $name];
            }

            $aboutMap = array_filter(
                array_map(fn ($v) => $this->cleanHtml($v), $this->pickLangMap($row['description'] ?? null)),
                fn ($v) => $v !== null
            );
            $addressMap = $this->pickLangMap($row['address'] ?? null);
            $referMap = $this->pickLangMap($row['refer_point'] ?? null);

            $type = self::CATEGORY_TYPE_MAP[(int) ($row['telegram_category_id'] ?? 0)] ?? 'markaz';

            $districtName = self::DISTRICT_NAME_MAP[(int) ($row['district_id'] ?? 0)] ?? null;
            $districtId = $districtName ? ($districtIds[$districtName] ?? null) : null;

            $workTime = $this->decode($row['work_time'] ?? null);
            [$workHours, $worksSaturday] = $this->resolveWorkHours($workTime);

            $minPrice = $row['min_price'] !== null ? (int) round((float) $row['min_price']) : null;
            $maxPrice = $row['max_price'] !== null ? (int) round((float) $row['max_price']) : null;
            $isActive = (int) ($row['status'] ?? 0) === 1;

            $tags = $this->resolveTags((int) ($row['telegram_category_id'] ?? 0), $legacyId, $extraLinksByObject);

            $institution = Institution::updateOrCreate(
                ['legacy_id' => $legacyId],
                [
                    'name' => $nameMap,
                    'type' => $type,
                    'about' => $aboutMap,
                    'district_id' => $districtId,
                    'address' => $addressMap,
                    'lat' => $row['latitude'] ?? null,
                    'lng' => $row['longitude'] ?? null,
                    'monthly_price' => $minPrice ?? $maxPrice,
                    // work_hours ham tarjima formatida ({"uz":..}) — "08:00–18:00" kabi
                    // qiymat tildan mustaqil, ru/en avtomatik uz'ga fallback qiladi.
                    'work_hours' => $workHours ? ['uz' => $workHours] : null,
                    'works_saturday' => $worksSaturday,
                    'accepting' => $isActive,
                    'is_active' => $isActive,
                    'phone_numbers' => $this->decode($row['phone_numbers'] ?? null) ?: null,
                    'social_links' => $this->resolveSocialLinks($row['social_links'] ?? null),
                    'location_url' => filled($row['location_url'] ?? null) ? $row['location_url'] : null,
                    'refer_point' => $referMap,
                    'slug' => $this->resolveSlug($row['slug'] ?? null, $name, $legacyId),
                    'legacy_view_count' => (int) ($row['view_count'] ?? 0),
                    'facilities' => ! empty($tags['facilities']) ? array_values(array_unique($tags['facilities'])) : [],
                    'teachers' => $this->resolveTeachers($employeesByObject->get($legacyId, collect()), $roleLabels),
                ]
            );

            if (filled($row['created_at'] ?? null)) {
                $institution->forceFill([
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'] ?? $row['created_at'],
                ])->saveQuietly();
            }

            if (! empty($tags['specializations'])) {
                $specIds = Specialization::whereIn('key', array_unique($tags['specializations']))->pluck('id');
                $institution->specializations()->syncWithoutDetaching($specIds);
            }

            $sortOrder = 0;
            foreach ($photosByObject->get($legacyId, []) as $photo) {
                $path = trim((string) ($photo['data'] ?? ''), "\" \t\n\r");

                if ($path === '') {
                    continue;
                }

                $media = LegacyMedia::resolve($path, "institutions/{$institution->id}");

                InstitutionMedia::updateOrCreate(
                    ['legacy_id' => (int) $photo['id']],
                    [
                        'institution_id' => $institution->id,
                        'type' => 'gallery',
                        'disk' => $media['disk'],
                        'url' => $media['url'],
                        'path' => $media['path'],
                        'sort_order' => $sortOrder++,
                    ]
                );
                $mediaImported++;
            }

            $imported++;
        }

        $this->command?->info("LegacyInstitutionSeeder: {$imported} ta muassasa, {$mediaImported} ta galereya rasmi import qilindi.");
    }

    /** @return array{facilities: array<int, string>, specializations: array<int, string>} */
    private function resolveTags(int $categoryId, int $legacyObjectId, Collection $extraLinksByObject): array
    {
        $facilities = [];
        $specializations = [];

        $base = self::CATEGORY_TAG_MAP[$categoryId] ?? [];
        $facilities = array_merge($facilities, $base['facilities'] ?? []);
        $specializations = array_merge($specializations, $base['specializations'] ?? []);

        // profession_to_object — qo'shimcha bog'langan yo'nalishlar (masalan "Ingliz tili")
        foreach ($extraLinksByObject->get($legacyObjectId, []) as $link) {
            $extra = self::CATEGORY_TAG_MAP[(int) ($link['telegram_category_id'] ?? 0)] ?? [];
            $facilities = array_merge($facilities, $extra['facilities'] ?? []);
            $specializations = array_merge($specializations, $extra['specializations'] ?? []);
        }

        return ['facilities' => $facilities, 'specializations' => $specializations];
    }

    /** @return array{0: ?string, 1: bool} [ish_soatlari_matni, dushanba_shanba_ishlaydimi] */
    private function resolveWorkHours(?array $workTime): array
    {
        if (! $workTime) {
            return [null, false];
        }

        $monday = $workTime['monday'] ?? null;
        $hours = null;

        if (is_array($monday) && filled($monday['start'] ?? null) && filled($monday['end'] ?? null)) {
            $hours = "{$monday['start']}–{$monday['end']}";
        }

        $saturday = $workTime['saturday'] ?? null;
        $worksSaturday = is_array($saturday) && filled($saturday['start'] ?? null) && filled($saturday['end'] ?? null);

        return [$hours, $worksSaturday];
    }

    private function resolveSocialLinks(?string $json): ?array
    {
        $decoded = $this->decode($json);

        if (! is_array($decoded)) {
            return null;
        }

        $result = [];
        foreach ($decoded as $key => $value) {
            if (blank($value)) {
                continue;
            }
            $label = self::SOCIAL_KEY_MAP[(string) $key] ?? (string) $key;
            $result[$label] = $value;
        }

        return $result ?: null;
    }

    private function resolveSlug(?string $legacySlug, string $name, int $legacyId): string
    {
        $base = filled($legacySlug) ? $legacySlug : Str::slug($name);
        $base = $base !== '' ? $base : 'muassasa';

        return "{$base}-{$legacyId}";
    }

    /** @return array<int, array{n: string, role: string, exp: string}> */
    private function resolveTeachers(Collection $employeeRows, array $roleLabels): array
    {
        $teachers = [];

        foreach ($employeeRows as $emp) {
            $name = $this->pickLang($emp['full_name'] ?? null);

            if (! $name) {
                continue;
            }

            $role = $roleLabels[(int) ($emp['employer_type'] ?? 0)] ?? null;
            $about = $this->pickLang($emp['about'] ?? null);

            $teachers[] = [
                'n' => $name,
                'role' => $role ?: '',
                'exp' => $about ?: '',
            ];
        }

        return $teachers;
    }

    private function cleanHtml(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $withBreaks = preg_replace('/<\s*(br|\/p|\/div)\s*\/?>/i', "\n", $html);
        $text = trim(strip_tags((string) $withBreaks));
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return filled($text) ? $text : null;
    }

    /** JSON matnni (yoki allaqachon massivni) dekodlaydi — xato bo'lsa null. */
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

    /** Bir nechta tildan (uzl>ru>uzk>en, keyin qolgan har qanday bo'sh bo'lmagan) matn tanlaydi. */
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

    /**
     * pickLang()dan farqli o'laroq — bitta tilni tanlab qolganini tashlamaydi,
     * mavjud bo'lgan barcha tillarni ['uz' => ..., 'ru' => ..., 'en' => ...]
     * ko'rinishida qaytaradi (App\Support\Concerns\HasTranslatable formatiga mos).
     * Eski backup'da 'uzl' (o'zbekcha lotin) — bizning 'uz'ga, 'uzk' (kirill) esa
     * faqat 'uzl' bo'sh bo'lsagina 'uz'ga zaxira sifatida ishlatiladi.
     *
     * @return array<string, string>
     */
    private function pickLangMap(mixed $json): array
    {
        $decoded = $this->decode($json);

        if (! is_array($decoded)) {
            $single = is_string($json) ? trim($json) : null;

            return filled($single) ? ['uz' => $single] : [];
        }

        $uz = $decoded['uzl'] ?? $decoded['uzk'] ?? null;
        $ru = $decoded['ru'] ?? null;
        $en = $decoded['en'] ?? null;

        $map = [];
        if (filled($uz)) {
            $map['uz'] = trim((string) $uz);
        }
        if (filled($ru)) {
            $map['ru'] = trim((string) $ru);
        }
        if (filled($en)) {
            $map['en'] = trim((string) $en);
        }

        if (empty($map)) {
            foreach ($decoded as $value) {
                if (is_string($value) && trim($value) !== '') {
                    $map['uz'] = trim($value);
                    break;
                }
            }
        }

        return $map;
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
