<?php

namespace App\Support;

use App\Models\Article;
use App\Models\District;
use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\Institution;
use App\Models\News as NewsModel;
use App\Models\Resume;
use App\Models\Review;
use App\Models\Specialization;
use App\Models\Vacancy;
use Illuminate\Support\Carbon;

/**
 * Frontend (Blade) uchun yagona ma'lumot darvozasi.
 *
 * Ikki turdagi metodlar bor:
 *  - STATIK KONFIG/PRESET — DB'da emas, dizayn/UI konstantalari (kategoriyalar, narx-oraliqlari,
 *    gradientlar, kategoriya bo'yicha umumiy galereya/dars presetlari va h.k.). Bular backend.md §3
 *    izohiga ko'ra ataylab DB'ga ko'chirilmagan.
 *  - ENTITY (DB'DAN) — muassasalar, tumanlar, ixtisosliklar, forum, yangilik/maqola, vakansiya/rezyume,
 *    sharhlar — hammasi endi Eloquent orqali real bazadan o'qiladi. Metod imzolari (nom, parametr,
 *    qaytariladigan massiv shakli) eski mock bilan bir xil qoldirilgan — shunga barcha Blade
 *    fayllar o'zgarishsiz ishlayveradi, faqat manba DB bo'ldi.
 */
class MaktabgidData
{
    /* ==================================================================
     * STATIK KONFIG / UI PRESETLARI (o'zgarmadi)
     * ================================================================== */

    /** Gradient pairs for monogram tiles (same order as data.jsx) */
    public static function gradients(): array
    {
        return [
            ['#0EA5A0', '#0B7E8C'], ['#6366F1', '#4338CA'], ['#F59E0B', '#D97706'],
            ['#EC4899', '#BE185D'], ['#10B981', '#047857'], ['#3B82F6', '#1D4ED8'],
            ['#8B5CF6', '#6D28D9'], ['#F97316', '#C2410C'], ['#14B8A6', '#0F766E'],
            ['#0EA5E9', '#0369A1'], ['#84CC16', '#4D7C0F'], ['#A855F7', '#7E22CE'],
            ['#EF4444', '#B91C1C'], ['#06B6D4', '#0E7490'],
        ];
    }

    public static function categories(): array
    {
        return [
            ['key' => 'maktab', 'label' => __('data.cat_maktab_label'), 'short' => __('data.cat_maktab_short'), 'icon' => 'school'],
            ['key' => 'bogcha', 'label' => __('data.cat_bogcha_label'), 'short' => __('data.cat_bogcha_short'), 'icon' => 'teddy'],
            ['key' => 'markaz', 'label' => __('data.cat_markaz_label'), 'short' => __('data.cat_markaz_short'), 'icon' => 'book'],
            ['key' => 'mutaxassis', 'label' => __('data.cat_mutaxassis_label'), 'short' => __('data.cat_mutaxassis_short'), 'icon' => 'heart'],
        ];
    }

    public static function categoryLabel(string $key): string
    {
        return [
            'maktab' => __('data.cat_label_maktab'),
            'bogcha' => __('data.cat_label_bogcha'),
            'markaz' => __('data.cat_label_markaz'),
            'mutaxassis' => __('data.cat_label_mutaxassis'),
        ][$key] ?? $key;
    }

    public static function priceBands(): array
    {
        return [
            ['key' => 'lt2', 'label' => __('data.price_lt2'), 'min' => 0, 'max' => 2000000],
            ['key' => '2-3.5', 'label' => __('data.price_2_3_5'), 'min' => 2000000, 'max' => 3500000],
            ['key' => '3.5-5', 'label' => __('data.price_3_5_5'), 'min' => 3500000, 'max' => 5000000],
            ['key' => '5-7', 'label' => __('data.price_5_7'), 'min' => 5000000, 'max' => 7000000],
            ['key' => '7+', 'label' => __('data.price_7_plus'), 'min' => 7000000, 'max' => PHP_INT_MAX],
        ];
    }

    public static function distanceBands(): array
    {
        return [
            ['key' => '1', 'label' => '1 km', 'max' => 1],
            ['key' => '3', 'label' => '3 km', 'max' => 3],
            ['key' => '5', 'label' => '5 km', 'max' => 5],
            ['key' => '5+', 'label' => '5+ km', 'max' => PHP_INT_MAX],
        ];
    }

    public static function formatPrice(int|float|null $n): string
    {
        if ($n === null) {
            return __('data.price_negotiated');
        }

        if ($n >= 1000000) {
            $m = $n / 1000000;
            $s = (floor($m) == $m) ? (string) (int) $m : number_format($m, 1);

            return str_replace('.', ',', $s).' mln';
        }

        return number_format($n, 0, ',', ' ');
    }

    public static function monogram(string $name): string
    {
        $words = array_values(array_filter(preg_split('/\s+/', $name)));
        $words = array_slice($words, 0, 2);
        $letters = array_map(fn ($w) => mb_substr($w, 0, 1), $words);

        return mb_strtoupper(implode('', $letters));
    }

    /** Tonal gradients used for the school-detail page media tiles (TILE_GRADS) */
    public static function tileGradients(): array
    {
        return [
            ['#0EA5A0', '#0B6E78'], ['#5468E8', '#3730A3'], ['#F0973A', '#C2540B'],
            ['#16A37B', '#0E6E52'], ['#E85D9E', '#9D2D6E'], ['#3B82F6', '#1D4ED8'],
            ['#8B5CF6', '#6D28D9'], ['#14B8A6', '#0F6E66'], ['#EAB308', '#A16207'],
        ];
    }

    /** Gallery / lesson / video / program presets per category (mirrors mediaFor in pages-school.jsx) */
    public static function mediaFor(string $cat): array
    {
        if ($cat === 'bogcha') {
            return [
                'gallery' => [
                    ['icon' => 'building', 'label' => 'Bino va hovli'], ['icon' => 'teddy', 'label' => 'Oʻyin xonasi'],
                    ['icon' => 'palette', 'label' => 'Ijod burchagi'], ['icon' => 'book', 'label' => 'Mutolaa zonasi'],
                    ['icon' => 'cup', 'label' => 'Oshxona'], ['icon' => 'leaf', 'label' => 'Yashil maydon'],
                ],
                'lessons' => [
                    ['icon' => 'palette', 'label' => 'Rasm chizish'], ['icon' => 'music', 'label' => 'Musiqa mashgʻuloti'],
                    ['icon' => 'teddy', 'label' => 'Erta rivojlanish'], ['icon' => 'globe', 'label' => 'Ingliz tili oʻyini'],
                    ['icon' => 'dumbbell', 'label' => 'Jismoniy faollik'], ['icon' => 'book', 'label' => 'Ertak vaqti'],
                ],
                'videos' => [
                    ['title' => 'Bogʻcha bilan tanishuv', 'dur' => '2:05', 'sub' => 'Virtual sayohat'],
                    ['title' => 'Bir kun bogʻchada', 'dur' => '3:12', 'sub' => 'Kun tartibi'],
                    ['title' => 'Ota-onalar fikri', 'dur' => '1:48', 'sub' => 'Sharhlar'],
                ],
                'programs' => [
                    ['icon' => 'teddy', 't' => 'Erta rivojlanish', 'd' => 'Yosh xususiyatiga mos kompleks dastur'],
                    ['icon' => 'globe', 't' => 'Ikki tilli muhit', 'd' => 'Oʻzbek va ingliz tilida muloqot'],
                    ['icon' => 'palette', 't' => 'Ijodiy ustaxonalar', 'd' => 'Rasm, musiqa, qoʻl mehnati'],
                    ['icon' => 'leaf', 't' => 'Montessori yondashuv', 'd' => 'Mustaqillik va tabiiy oʻrganish'],
                ],
            ];
        }

        if ($cat === 'markaz') {
            return [
                'gallery' => [
                    ['icon' => 'building', 'label' => 'Markaz binosi'], ['icon' => 'book', 'label' => 'Oʻquv xonalari'],
                    ['icon' => 'code', 'label' => 'Kompyuter klassi'], ['icon' => 'users', 'label' => 'Speaking club'],
                    ['icon' => 'globe', 'label' => 'Til muhiti'], ['icon' => 'cup', 'label' => 'Dam olish zonasi'],
                ],
                'lessons' => [
                    ['icon' => 'globe', 'label' => 'Ingliz tili darsi'], ['icon' => 'target', 'label' => 'IELTS mock test'],
                    ['icon' => 'users', 'label' => 'Speaking club'], ['icon' => 'code', 'label' => 'Dasturlash'],
                    ['icon' => 'book', 'label' => 'Grammatika'], ['icon' => 'award', 'label' => 'Imtihon tahlili'],
                ],
                'videos' => [
                    ['title' => 'Markaz bilan tanishuv', 'dur' => '1:58', 'sub' => 'Virtual sayohat'],
                    ['title' => 'Dars jarayoni', 'dur' => '2:46', 'sub' => 'Mashgʻulot lavhasi'],
                    ['title' => 'Oʻquvchilar natijasi', 'dur' => '2:10', 'sub' => 'Muvaffaqiyat tarixi'],
                ],
                'programs' => [
                    ['icon' => 'globe', 't' => 'IELTS / SAT tayyorlov', 'd' => 'Maqsadli bal kafolati bilan'],
                    ['icon' => 'users', 't' => 'Speaking club', 'd' => 'Native speaker bilan amaliyot'],
                    ['icon' => 'target', 't' => 'Individual mashgʻulot', 'd' => 'Shaxsiy reja asosida'],
                    ['icon' => 'code', 't' => 'Onlayn platforma', 'd' => 'Uy vazifasi va kuzatuv tizimi'],
                ],
            ];
        }

        return [
            'gallery' => [
                ['icon' => 'building', 'label' => 'Bino tashqi koʻrinishi'], ['icon' => 'school', 'label' => 'Asosiy kirish'],
                ['icon' => 'book', 'label' => 'Zamonaviy sinfxona'], ['icon' => 'flask', 'label' => 'Laboratoriya'],
                ['icon' => 'dumbbell', 'label' => 'Sport zali'], ['icon' => 'leaf', 'label' => 'Hovli va maydon'],
            ],
            'lessons' => [
                ['icon' => 'target', 'label' => 'Matematika darsi'], ['icon' => 'globe', 'label' => 'Ingliz tili'],
                ['icon' => 'flask', 'label' => 'Ilmiy laboratoriya'], ['icon' => 'code', 'label' => 'Robototexnika'],
                ['icon' => 'palette', 'label' => 'Sanʼat studiyasi'], ['icon' => 'dumbbell', 'label' => 'Jismoniy tarbiya'],
            ],
            'videos' => [
                ['title' => 'Maktab bilan virtual tanishuv', 'dur' => '2:14', 'sub' => '360° sayohat'],
                ['title' => 'Bir kun maktabda', 'dur' => '3:40', 'sub' => 'Oʻquvchi kuni'],
                ['title' => 'Ota-onalar fikri', 'dur' => '1:55', 'sub' => 'Sharhlar'],
            ],
            'programs' => [
                ['icon' => 'award', 't' => 'Cambridge dasturi', 'd' => 'Xalqaro standart va sertifikat'],
                ['icon' => 'flask', 't' => 'STEM laboratoriyasi', 'd' => 'Amaliy fan va tajribalar'],
                ['icon' => 'code', 't' => 'Robototexnika', 'd' => 'Dasturlash va muhandislik toʻgaragi'],
                ['icon' => 'trophy', 't' => 'Olimpiadaga tayyorlov', 'd' => 'Iqtidorli oʻquvchilar uchun'],
            ],
        ];
    }

    /** Title-card stat tiles per category */
    public static function detailStats(string $cat): array
    {
        if ($cat === 'bogcha') {
            return [
                ['v' => '12', 'k' => 'Guruhda (oʻrtacha)'], ['v' => '3–7', 'k' => 'Yosh oraligʻi'],
                ['v' => '8', 'k' => 'Yillik tajriba'], ['v' => '18', 'k' => 'Boʻsh joylar'],
            ];
        }

        if ($cat === 'markaz' || $cat === 'mutaxassis') {
            return [
                ['v' => '6', 'k' => 'Guruhda (oʻrtacha)'], ['v' => '7.0+', 'k' => 'Oʻrtacha IELTS'],
                ['v' => '9', 'k' => 'Yillik tajriba'], ['v' => '12', 'k' => 'Yoʻnalishlar'],
            ];
        }

        return [
            ['v' => '16', 'k' => 'Bir sinfda (oʻrtacha)'], ['v' => '12', 'k' => 'Yillik tajriba'],
            ['v' => '98%', 'k' => 'Oliygohga kirish'], ['v' => '24', 'k' => '1-sinf joylari'],
        ];
    }

    /** Muassasa kabinetida tanlanadigan qulayliklar katalogi (barqaror kalitlar bilan). */
    public static function facilityCatalog(): array
    {
        return [
            ['key' => 'classrooms', 'i' => 'book', 't' => 'Zamonaviy sinfxonalar'],
            ['key' => 'lab', 'i' => 'flask', 't' => 'Ilmiy laboratoriya'],
            ['key' => 'it', 'i' => 'code', 't' => 'IT va robototexnika'],
            ['key' => 'sport', 'i' => 'dumbbell', 't' => 'Sport zali va maydon'],
            ['key' => 'library', 'i' => 'book', 't' => 'Kutubxona'],
            ['key' => 'canteen', 'i' => 'cup', 't' => 'Issiq ovqat / oshxona'],
            ['key' => 'medical', 'i' => 'cross', 't' => 'Tibbiyot xonasi'],
            ['key' => 'bus', 'i' => 'bus', 't' => 'Maktab avtobusi'],
            ['key' => 'wifi', 'i' => 'wifi', 't' => 'Wi-Fi va xavfsizlik'],
        ];
    }

    public static function facilities(): array
    {
        return array_map(fn ($f) => ['i' => $f['i'], 't' => $f['t']], self::facilityCatalog());
    }

    public static function teachers(): array
    {
        return [
            ['n' => 'Madina Yusupova', 'role' => 'Ingliz tili', 'exp' => '6 yil', 'g' => ['#0EA5A0', '#0B6E78']],
            ['n' => 'Aziz Rahimov', 'role' => 'Matematika', 'exp' => '10 yil', 'g' => ['#5468E8', '#3730A3']],
            ['n' => 'Sevara Tosheva', 'role' => 'Boshlangʻich sinf', 'exp' => '4 yil', 'g' => ['#E85D9E', '#9D2D6E']],
            ['n' => 'Jamshid Karimov', 'role' => 'IT / Robototexnika', 'exp' => '7 yil', 'g' => ['#16A37B', '#0E6E52']],
        ];
    }

    public static function admissionSteps(): array
    {
        return [
            ['t' => 'Ariza qoldirish', 'd' => 'Onlayn forma orqali ariza yuborasiz'],
            ['t' => 'Tanishuv / ekskursiya', 'd' => 'Maktabga tashrif buyurib, muhit bilan tanishasiz'],
            ['t' => 'Suhbat va kirish testi', 'd' => 'Bola bilan qisqa suhbat oʻtkaziladi'],
            ['t' => 'Shartnoma va joylashtirish', 'd' => 'Hujjatlar rasmiylashtirilib, oʻquvchi qabul qilinadi'],
        ];
    }

    public static function forumCategories(): array
    {
        return ['Hammasi', 'Maktab tanlash', 'Bogʻcha', 'Oʻquv markazi', 'Narx va toʻlov', 'Maslahat'];
    }

    /** Generic continuation paragraphs for article/news detail pages (demo content has no full body) */
    public static function articleBody(array $item): array
    {
        return [
            $item['excerpt'],
            'Mutaxassislar fikricha, bu mavzuda shoshma-shosharlik qarorlar koʻpincha keyinchalik qoʻshimcha qiyinchilik tugʻdiradi. Shu sababli, ota-onalarga avval bir nechta manbadan maʼlumot toʻplash, oʻz oilasining ehtiyojlarini aniq belgilab olish va imkon qadar amaliy tajribaga asoslanish tavsiya etiladi.',
            'Amaliyotda koʻrsatilishicha, toʻgʻri tanlov va izchil yondashuv bola va oila uchun uzoq muddatda sezilarli foyda beradi. Savol va takliflaringiz boʻlsa, MaktabGID forumida boshqa ota-onalar bilan tajriba almashishingiz mumkin.',
        ];
    }

    /* ==================================================================
     * ENTITY — endi real bazadan (backend.md asosida qurilgan Phase 1-5)
     * ================================================================== */

    /** Toshkent shahar markazi (Yandex Maps xaritasi shu nuqta atrofida markazlashadi). */
    private const TASHKENT_LAT = 41.311081;

    private const TASHKENT_LNG = 69.240562;

    /** Haqiqiy Yandex geolokatsiya hali ulanmagan muassasalar uchun barqaror (id'ga bog'liq) namoyish koordinatasi. */
    private static function pseudoLatLng(int $id): array
    {
        $lat = self::TASHKENT_LAT + ((($id * 29) % 76) - 38) / 1000;
        $lng = self::TASHKENT_LNG + ((($id * 53) % 70) - 35) / 1000;

        return [$lat, $lng];
    }

    /** Ikki koordinata orasidagi masofa (km), Haversine formulasi. */
    private static function distanceKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadiusKm = 6371;

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lngDelta / 2) ** 2;

        return round($earthRadiusKm * 2 * atan2(sqrt($a), sqrt(1 - $a)), 1);
    }

    private static function mapInstitution(Institution $institution): array
    {
        $gradients = self::gradients();

        [$pseudoLat, $pseudoLng] = self::pseudoLatLng($institution->id);
        $lat = $institution->lat !== null ? (float) $institution->lat : $pseudoLat;
        $lng = $institution->lng !== null ? (float) $institution->lng : $pseudoLng;
        $dist = self::distanceKm(self::TASHKENT_LAT, self::TASHKENT_LNG, $lat, $lng);

        $photos = $institution->relationLoaded('media')
            ? $institution->media->where('type', 'gallery')->pluck('url')->values()->all()
            : [];

        return [
            'id' => $institution->id,
            'slug' => $institution->slug,
            'name' => $institution->name,
            'cat' => $institution->type,
            'about' => $institution->about,
            'district' => $institution->district?->name ?? '',
            'address' => $institution->address,
            'lat' => $lat,
            'lng' => $lng,
            'dist' => $dist,
            'price' => $institution->monthly_price,
            'rating' => (float) $institution->rating,
            'reviews' => $institution->review_count,
            'grades' => $institution->grades,
            'lang' => $institution->lang,
            'sat' => (bool) $institution->works_saturday,
            'badge' => $institution->badge,
            'g' => $gradients[$institution->id % count($gradients)],
            'specs' => $institution->specializations->pluck('key')->all(),
            'photos' => $photos,
            'facilities' => self::resolveFacilities($institution),
            'teachers' => self::resolveTeachers($institution),
            'programs' => self::resolvePrograms($institution),
            'prices' => self::resolvePrices($institution),
            'lessons' => self::resolveLessons($institution),
            'videos' => self::resolveVideos($institution),
            'steps' => self::resolveAdmissionSteps($institution),
            'stats' => self::resolveStats($institution),
            'achievements' => self::resolveAchievements($institution),
        ];
    }

    /** O'quvchilar yutuqlari — real Achievement yozuvlari (ADR-0002, Faza 2). Bo'sh bo'lsa
     *  ommaviy profilda bo'lim umuman ko'rsatilmaydi (teachers/programs kabi mock fallback yo'q). */
    private static function resolveAchievements(Institution $institution): array
    {
        $achievements = $institution->relationLoaded('achievements')
            ? $institution->achievements
            : $institution->achievements()->get();

        return $achievements->map(fn ($a) => [
            'title' => $a->title,
            'student' => $a->student_name,
            'year' => $a->year,
            'type' => $a->type,
            'level' => $a->level,
            'imageUrl' => $a->image_url,
        ])->all();
    }

    /** Muassasa oʻzi tanlagan qulayliklar — faqat shulardan. Toʻliq katalogdan avtomatik
     *  toʻldirish (eski xatti-harakat) olib tashlandi: muassasa hech narsa tanlamagan boʻlsa
     *  boʻsh massiv qaytadi va bo'lim sahifada ko'rsatilmaydi (achievements/prices bilan bir
     *  xil qoida, 2026-07-15). */
    private static function resolveFacilities(Institution $institution): array
    {
        if (empty($institution->facilities)) {
            return [];
        }

        $selected = $institution->facilities;

        return array_values(array_map(
            fn ($f) => ['i' => $f['i'], 't' => $f['t']],
            array_filter(self::facilityCatalog(), fn ($f) => in_array($f['key'], $selected, true))
        ));
    }

    /** Ustozlar — real Institution.teachers boʻlsa shulardan, aks holda boʻsh (avval hamma
     *  muassasa uchun bir xil "Madina Yusupova" kabi namunaviy roʻyxat chiqardi — endi yoʻq). */
    private static function resolveTeachers(Institution $institution): array
    {
        if (empty($institution->teachers)) {
            return [];
        }

        $gradients = self::gradients();

        return collect($institution->teachers)->values()->map(fn ($t, $i) => [
            'n' => $t['n'] ?? '',
            'role' => $t['role'] ?? '',
            'exp' => $t['exp'] ?? '',
            'g' => $gradients[$i % count($gradients)],
        ])->all();
    }

    /** Yoʻnalish/dastur — real Institution.programs boʻlsa shulardan, aks holda boʻsh
     *  (kategoriya boʻyicha umumiy dastur roʻyxati endi ishlatilmaydi). */
    private static function resolvePrograms(Institution $institution): array
    {
        if (empty($institution->programs)) {
            return [];
        }

        $icons = ['award', 'flask', 'code', 'trophy', 'target', 'book', 'globe', 'users'];

        return collect($institution->programs)->values()->map(fn ($p, $i) => [
            'icon' => $icons[$i % count($icons)],
            't' => $p['t'] ?? '',
            'd' => $p['d'] ?? '',
        ])->all();
    }

    /** "Narxlar" — real InstitutionPrice qatorlari (sinf/guruh + til + narx + chegirma).
     *  Achievements kabi mock fallback yo'q — hali qo'shilmagan bo'lsa bo'lim ko'rsatilmaydi (2026-07-15). */
    private static function resolvePrices(Institution $institution): array
    {
        $prices = $institution->relationLoaded('prices') ? $institution->prices : $institution->prices()->get();

        return $prices->map(fn ($p) => [
            'grade' => $p->grade,
            'lang' => $p->lang,
            'price' => $p->monthly_price,
            'discount' => $p->discount,
        ])->all();
    }

    /** Oʻquv jarayoni lavhalari — real Institution.lessons boʻlsa shulardan, aks holda boʻsh
     *  (kategoriya boʻyicha umumiy roʻyxat endi ishlatilmaydi, 2026-07-15). */
    private static function resolveLessons(Institution $institution): array
    {
        if (empty($institution->lessons)) {
            return [];
        }

        $icons = ['camera', 'book', 'target', 'globe', 'dumbbell', 'palette', 'code', 'users'];

        return collect($institution->lessons)->values()->map(fn ($l, $i) => [
            'icon' => $icons[$i % count($icons)],
            'label' => is_array($l) ? ($l['label'] ?? '') : $l,
        ])->all();
    }

    /** "Videolar" — real InstitutionMedia (type=video) yozuvlari, haqiqiy fayl yoki tashqi
     *  havola bilan. Prices/achievements kabi mock fallback yo'q — hali qo'shilmagan bo'lsa
     *  bo'lim ko'rsatilmaydi (avval `institution->videos` json'dan o'qirdi, 2026-07-15).
     *  "photos" bilan bir xil qoida: 'media' eager-load qilinmagan bo'lsa (masalan katalog
     *  ro'yxati) N+1 so'rovning oldini olish uchun bo'sh massiv qaytadi — faqat bitta
     *  muassasa sahifasida ('media' bilan yuklanadi) real ro'yxat chiqadi. */
    private static function resolveVideos(Institution $institution): array
    {
        if (! $institution->relationLoaded('media')) {
            return [];
        }

        return $institution->media->where('type', 'video')->values()->map(fn ($v) => [
            'title' => $v->caption ?? '',
            'dur' => $v->duration ?? '',
            'sub' => $v->description ?? '',
            'url' => $v->url,
        ])->all();
    }

    /** Qabul bosqichlari — real Institution.admission_steps boʻlsa shulardan, aks holda boʻsh
     *  (avval hamma muassasa uchun bir xil umumiy 4 bosqich koʻrsatilardi — endi mock yoʻq). */
    private static function resolveAdmissionSteps(Institution $institution): array
    {
        if (empty($institution->admission_steps)) {
            return [];
        }

        return collect($institution->admission_steps)->values()->map(fn ($s) => [
            't' => $s['t'] ?? '',
            'd' => $s['d'] ?? '',
        ])->all();
    }

    /** 4 ta koʻrsatkich — faqat muassasa oʻzi kiritgan qiymatlar (stat_*). Kategoriya boʻyicha
     *  umumiy default (masalan har doim "16 / 12 / 98% / 24") endi ishlatilmaydi — bu raqamlar
     *  muassasaga tegishli emas edi. Hech qaysi maydon toʻldirilmagan boʻlsa boʻsh massiv
     *  qaytadi va title-card koʻrsatkichlar qatorini umuman koʻrsatmaydi (2026-07-15). */
    private static function resolveStats(Institution $institution): array
    {
        $labels = self::detailStats($institution->type);

        $overrides = [
            $institution->stat_class_size,
            $institution->stat_experience_years,
            $institution->stat_admission_rate,
            $institution->stat_first_grade_seats,
        ];

        $stats = [];
        foreach ($overrides as $i => $v) {
            if (filled($v)) {
                $stats[] = ['v' => $v, 'k' => $labels[$i]['k']];
            }
        }

        return $stats;
    }

    public static function schools(): array
    {
        // is_active=false — eski (yoki admin tomonidan) yopilgan yozuv, ommaviy katalogda
        // ko'rsatilmaydi (2026-08-06, LegacyInstitutionSeeder). Admin panel buni hisobga
        // olmaydi — Admin\InstitutionController barcha yozuvlarni ko'rsatadi/boshqaradi.
        //
        // 'media' ham shu yerda eager-load qilinadi (2026-08-06) — kataloq kartochkalarida
        // (school-card) haqiqiy birinchi galereya rasmi ko'rsatilishi uchun (avval faqat
        // bitta muassasa sahifasida yuklanardi, kataloq har doim monogram/gradient
        // ko'rsatardi — real rasm bo'lsa ham). Bitta eager-load so'rovi, N+1 emas.
        return Institution::with(['district', 'specializations', 'achievements', 'prices', 'media'])
            ->where('is_active', true)
            ->orderBy('id')
            ->get()
            ->map(fn (Institution $institution) => self::mapInstitution($institution))
            ->all();
    }

    public static function school(int $id): ?array
    {
        $institution = Institution::with(['district', 'specializations', 'media', 'achievements', 'prices'])
            ->where('is_active', true)
            ->find($id);

        return $institution ? self::mapInstitution($institution) : null;
    }

    /** Ommaviy profil sahifasi endi /{slug} orqali ochiladi (2026-08-06) — /maktab/{id} emas. */
    public static function schoolBySlug(string $slug): ?array
    {
        $institution = Institution::with(['district', 'specializations', 'media', 'achievements', 'prices'])
            ->where('is_active', true)
            ->where('slug', $slug)
            ->first();

        return $institution ? self::mapInstitution($institution) : null;
    }

    public static function districts(): array
    {
        return District::orderBy('name')->pluck('name')->all();
    }

    /** Specializations catalogue (matches data.jsx SPECIALIZATIONS) */
    public static function specializations(): array
    {
        return Specialization::orderBy('id')->get()->map(fn ($s) => [
            'key' => $s->key,
            'label' => $s->label,
            'icon' => $s->icon,
        ])->all();
    }

    public static function specializationLabel(string $key): ?array
    {
        $s = Specialization::where('key', $key)->first();

        return $s ? ['key' => $s->key, 'label' => $s->label, 'icon' => $s->icon] : null;
    }

    /** Muassasaning haqiqiy sharhlari (institutionId berilmasa — bo'sh). */
    public static function reviews(?int $institutionId = null): array
    {
        $query = Review::with('author')->latest();

        if ($institutionId) {
            $query->where('institution_id', $institutionId);
        }

        return $query->get()->map(fn ($r) => [
            'n' => self::shortName($r->author?->name ?? $r->guest_name ?? 'Mehmon'),
            'r' => $r->rating,
            'ago' => $r->created_at?->diffForHumans() ?? '',
            't' => $r->body ?? '',
        ])->all();
    }

    private static function shortName(string $name): string
    {
        $parts = explode(' ', trim($name));

        return count($parts) >= 2 ? $parts[0].' '.mb_substr($parts[1], 0, 1).'.' : $name;
    }

    /** 5→1 yulduz taqsimoti (foizda), haqiqiy sharhlardan hisoblanadi. */
    public static function ratingBars(?int $institutionId = null): array
    {
        $query = Review::query();
        if ($institutionId) {
            $query->where('institution_id', $institutionId);
        }

        $total = (clone $query)->count();

        $bars = [];
        for ($s = 5; $s >= 1; $s--) {
            $count = $total ? (clone $query)->where('rating', $s)->count() : 0;
            $bars[] = ['s' => $s, 'p' => $total ? (int) round($count / $total * 100) : 0];
        }

        return $bars;
    }

    /* ---------------- FORUM ---------------- */

    public static function forumThreads(): array
    {
        return ForumThread::with('author')
            ->withCount('replies')
            ->orderByDesc('id')
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'cat' => $t->category,
                'title' => $t->title,
                'body' => $t->body,
                'author' => $t->author?->name ?? 'Foydalanuvchi',
                'ago' => $t->created_at?->diffForHumans() ?? '',
                'replies' => $t->replies_count,
                'views' => $t->view_count,
                'likes' => $t->like_count,
            ])->all();
    }

    public static function forumThread(int $id): ?array
    {
        $t = ForumThread::with('author')->withCount('replies')->find($id);

        if (! $t) {
            return null;
        }

        return [
            'id' => $t->id,
            'cat' => $t->category,
            'title' => $t->title,
            'body' => $t->body,
            'author' => $t->author?->name ?? 'Foydalanuvchi',
            'ago' => $t->created_at?->diffForHumans() ?? '',
            'replies' => $t->replies_count,
            'views' => $t->view_count,
            'likes' => $t->like_count,
        ];
    }

    /** $threadId berilsa — faqat shu mavzuning javoblari (eski mock hammasiga bir xil ro'yxat qaytarardi — bu tuzatildi). */
    public static function forumReplies(?int $threadId = null): array
    {
        $query = ForumReply::with('author')->orderBy('id');

        if ($threadId) {
            $query->where('thread_id', $threadId);
        }

        return $query->get()->map(fn ($r) => [
            'id' => $r->id,
            'author' => $r->author?->name ?? 'Foydalanuvchi',
            'ago' => $r->created_at?->diffForHumans() ?? '',
            'likes' => $r->like_count,
            'body' => $r->body,
        ])->all();
    }

    /* ---------------- BLOG (articles) ---------------- */

    private static function mapArticle(Article $a): array
    {
        $gradients = self::gradients();

        return [
            'id' => $a->id,
            'tag' => $a->tag,
            'title' => $a->title,
            'excerpt' => $a->excerpt,
            'read' => $a->read_minutes.' daqiqa',
            'author' => $a->author_name,
            'date' => self::formatDateUz($a->published_at),
            'g' => $gradients[$a->id % count($gradients)],
            'feat' => (bool) $a->featured,
            'image' => $a->image_url,
        ];
    }

    public static function articles(): array
    {
        return Article::orderByDesc('published_at')->get()
            ->map(fn (Article $a) => self::mapArticle($a))->all();
    }

    public static function article(int $id): ?array
    {
        $a = Article::find($id);

        return $a ? self::mapArticle($a) : null;
    }

    /** Bosh sahifadagi qisqa blog-teaser (3 ta so'nggi maqola). */
    public static function blog(): array
    {
        return Article::orderByDesc('published_at')->take(3)->get()
            ->map(fn (Article $a) => self::mapArticle($a))->all();
    }

    /* ---------------- NEWS ---------------- */

    private static function mapNews(NewsModel $n): array
    {
        $gradients = self::gradients();

        return [
            'id' => $n->id,
            'tag' => $n->tag,
            'title' => $n->title,
            'excerpt' => $n->excerpt,
            'date' => self::formatDateUz($n->published_at),
            'source' => $n->source,
            'g' => $gradients[$n->id % count($gradients)],
            'hot' => (bool) $n->hot,
        ];
    }

    public static function news(): array
    {
        return NewsModel::orderByDesc('published_at')->get()
            ->map(fn (NewsModel $n) => self::mapNews($n))->all();
    }

    public static function newsItem(int $id): ?array
    {
        $n = NewsModel::find($id);

        return $n ? self::mapNews($n) : null;
    }

    /* ---------------- CAREERS (vacancies + resumes) ---------------- */

    private static function mapVacancy(Vacancy $v): array
    {
        $typeLabels = ['full' => 'Toʻliq stavka', 'part' => 'Yarim stavka', 'hourly' => 'Soatbay'];

        return [
            'id' => $v->id,
            'title' => $v->title,
            'org' => $v->org_name,
            'type' => $typeLabels[$v->employment_type] ?? $v->employment_type,
            'salary' => $v->salary_range,
            'until' => $v->expires_at ? self::formatDateUz($v->expires_at) : '',
            'spec' => $v->specialization_key,
        ];
    }

    /** Bosh sahifadagi qisqa vakansiya-teaser (3 ta so'nggi). */
    public static function vacancies(): array
    {
        return Vacancy::latest()->take(3)->get()
            ->map(fn (Vacancy $v) => self::mapVacancy($v))->all();
    }

    public static function careerVacancies(): array
    {
        return Vacancy::latest()->get()
            ->map(fn (Vacancy $v) => self::mapVacancy($v))->all();
    }

    public static function careerVacancy(int $id): ?array
    {
        $v = Vacancy::find($id);

        return $v ? self::mapVacancy($v) : null;
    }

    public static function resumes(): array
    {
        return Resume::with('district')->latest()->get()
            ->map(fn (Resume $r) => self::mapResume($r))->all();
    }

    public static function careerResume(int $id): ?array
    {
        $r = Resume::with('district')->find($id);

        return $r ? self::mapResume($r) : null;
    }

    /** @return array<string, mixed> */
    private static function mapResume(Resume $r): array
    {
        return [
            'id' => $r->id,
            'name' => $r->full_name,
            'role' => $r->role_title,
            'exp' => $r->experience,
            'spec' => $r->specialization_key,
            'salary' => $r->salary_expectation,
            'district' => $r->district?->name ?? '',
            'langs' => $r->languages,
            'phone' => $r->phone,
            'education' => $r->education,
            'skills' => $r->skills,
            'description' => $r->description,
            'ago' => $r->created_at?->diffForHumans() ?? '',
        ];
    }

    /** Blog/maqola media bloki uchun style: haqiqiy rasm bo'lsa shu, bo'lmasa gradient (eski xatti-harakat). */
    public static function mediaStyle(array $item, int $angle = 140): string
    {
        if (! empty($item['image'])) {
            return "background-image:url('{$item['image']}');background-size:cover;background-position:center";
        }

        return "background:linear-gradient({$angle}deg, {$item['g'][0]}, {$item['g'][1]})";
    }

    /* ---------------- helpers ---------------- */

    private static function formatDateUz(?Carbon $date): string
    {
        if (! $date) {
            return '';
        }

        $months = [
            1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel', 5 => 'May', 6 => 'Iyun',
            7 => 'Iyul', 8 => 'Avgust', 9 => 'Sentyabr', 10 => 'Oktyabr', 11 => 'Noyabr', 12 => 'Dekabr',
        ];

        return $date->day.' '.$months[(int) $date->format('n')].' '.$date->year;
    }
}
