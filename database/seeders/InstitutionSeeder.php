<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Institution;
use App\Models\InstitutionMedia;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    /** MaktabgidData::schools() dan ko'chirilgan demo katalog (16 ta) */
    public static function schools(): array
    {
        return [
            ['id' => 1, 'name' => 'CIS Tashkent', 'cat' => 'maktab', 'district' => 'Mirzo Ulug\'bek', 'price' => 208818000, 'rating' => 4.9, 'reviews' => 214, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => false, 'badge' => 'Premium'],
            ['id' => 2, 'name' => 'Sodiq School', 'cat' => 'maktab', 'district' => 'Chilonzor', 'price' => 6590000, 'rating' => 4.7, 'reviews' => 156, 'grades' => '1–11', 'lang' => 'O\'zbek / Ingliz', 'sat' => true],
            ['id' => 3, 'name' => 'Diplomat International School', 'cat' => 'maktab', 'district' => 'Yunusobod', 'price' => 6900000, 'rating' => 4.8, 'reviews' => 189, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => true],
            ['id' => 4, 'name' => 'Artel Technical School', 'cat' => 'maktab', 'district' => 'Yashnobod', 'price' => 4250000, 'rating' => 4.5, 'reviews' => 87, 'grades' => '5–11', 'lang' => 'O\'zbek', 'sat' => false],
            ['id' => 5, 'name' => 'IT Park School', 'cat' => 'maktab', 'district' => 'Mirzo Ulug\'bek', 'price' => 41000000, 'rating' => 4.9, 'reviews' => 132, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => false, 'badge' => 'Premium'],
            ['id' => 6, 'name' => 'Cambridge School', 'cat' => 'maktab', 'district' => 'Mirobod', 'price' => 5800000, 'rating' => 4.6, 'reviews' => 143, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => true],
            ['id' => 7, 'name' => 'Vosiq International School', 'cat' => 'maktab', 'district' => 'Olmazor', 'price' => 5500000, 'rating' => 4.7, 'reviews' => 98, 'grades' => '1–9', 'lang' => 'O\'zbek / Ingliz', 'sat' => true],
            ['id' => 8, 'name' => 'Interhouse Lyceum', 'cat' => 'maktab', 'district' => 'Yakkasaroy', 'price' => 6500000, 'rating' => 4.8, 'reviews' => 176, 'grades' => '5–11', 'lang' => 'Ingliz', 'sat' => false],
            ['id' => 9, 'name' => 'Maple Bear Canadian School', 'cat' => 'bogcha', 'district' => 'Yunusobod', 'price' => 14000000, 'rating' => 4.9, 'reviews' => 205, 'grades' => '3–7 yosh', 'lang' => 'Ingliz', 'sat' => false, 'badge' => 'Premium'],
            ['id' => 10, 'name' => 'Milestone International School', 'cat' => 'maktab', 'district' => 'Mirzo Ulug\'bek', 'price' => 6750000, 'rating' => 4.6, 'reviews' => 121, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => true],
            ['id' => 11, 'name' => 'Al-Beruniy School', 'cat' => 'maktab', 'district' => 'Shayxontohur', 'price' => 6800000, 'rating' => 4.7, 'reviews' => 110, 'grades' => '1–11', 'lang' => 'O\'zbek', 'sat' => true],
            ['id' => 12, 'name' => 'Invento School', 'cat' => 'maktab', 'district' => 'Yunusobod', 'price' => 132000000, 'rating' => 5.0, 'reviews' => 64, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => false, 'badge' => 'Premium'],
            ['id' => 13, 'name' => 'Jalaliddin International School', 'cat' => 'maktab', 'district' => 'Sergeli', 'price' => 7778000, 'rating' => 4.5, 'reviews' => 73, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => true],
            ['id' => 14, 'name' => 'Rahimov School', 'cat' => 'maktab', 'district' => 'Chilonzor', 'price' => 6200000, 'rating' => 4.6, 'reviews' => 134, 'grades' => '1–11', 'lang' => 'O\'zbek / Ingliz', 'sat' => true],
            ['id' => 15, 'name' => 'Little Stars Bog\'cha', 'cat' => 'bogcha', 'district' => 'Yakkasaroy', 'price' => 3200000, 'rating' => 4.8, 'reviews' => 96, 'grades' => '2–6 yosh', 'lang' => 'O\'zbek / Ingliz', 'sat' => true],
            ['id' => 16, 'name' => 'Bright Kids Markazi', 'cat' => 'markaz', 'district' => 'Mirobod', 'price' => 1500000, 'rating' => 4.7, 'reviews' => 152, 'grades' => '6–16 yosh', 'lang' => 'Ingliz', 'sat' => true],
        ];
    }

    public static function specPlan(): array
    {
        return [
            ['english', 'ielts', 'it'], ['stem', 'english', 'sport'], ['english', 'olympiad', 'it'],
            ['it', 'stem', 'sport'], ['it', 'stem', 'english'], ['english', 'ielts'],
            ['english', 'stem', 'music'], ['stem', 'olympiad', 'ielts'], ['early', 'english', 'art'],
            ['english', 'it', 'science'], ['stem', 'science', 'olympiad'], ['english', 'ielts', 'it'],
            ['english', 'sport', 'music'], ['stem', 'english', 'art'], ['early', 'art', 'music'],
            ['english', 'it', 'art'],
        ];
    }

    /* ==================================================================
     * 2026-07-15: /maktab/{id} sahifasi endi faqat DB'dagi haqiqiy
     * ma'lumotni ko'rsatadi (MaktabgidData::resolve* runtime mock fallback
     * olib tashlandi). Demo katalog bo'sh ko'rinib qolmasligi uchun har
     * bir muassasaga qulaylik/o'qituvchi/dastur/dars/qabul bosqichi/
     * statistika endi shu yerda — haqiqiy seed qatorlari sifatida —
     * beriladi (ilgari MaktabgidData'dagi umumiy presetlar orqali
     * "ko'rinardi", aslida DB'da yo'q edi).
     * ================================================================== */

    private static function facilitiesFor(string $cat): array
    {
        if ($cat === 'bogcha') {
            return ['classrooms', 'sport', 'library', 'canteen', 'medical', 'wifi'];
        }

        if ($cat === 'markaz') {
            return ['classrooms', 'it', 'library', 'wifi', 'canteen'];
        }

        return ['classrooms', 'lab', 'it', 'sport', 'library', 'canteen', 'medical', 'bus', 'wifi'];
    }

    /** 12 ta o'qituvchi shabloni — har bir muassasaga indeksga qarab siljitilgan 4 tasi tanlanadi
     *  (schools() bilan bir xil "index bo'yicha aylanma tanlov" uslubi, specPlan() ga qarang). */
    private static function teacherPool(): array
    {
        return [
            ['n' => 'Madina Yusupova', 'role' => 'Ingliz tili', 'exp' => '6 yil'],
            ['n' => 'Aziz Rahimov', 'role' => 'Matematika', 'exp' => '10 yil'],
            ['n' => 'Sevara Tosheva', 'role' => 'Boshlangʻich sinf', 'exp' => '4 yil'],
            ['n' => 'Jamshid Karimov', 'role' => 'IT / Robototexnika', 'exp' => '7 yil'],
            ['n' => 'Nodira Xolova', 'role' => 'Fizika', 'exp' => '9 yil'],
            ['n' => 'Bekzod Aliyev', 'role' => 'Kimyo', 'exp' => '5 yil'],
            ['n' => 'Gulnora Saidova', 'role' => 'Rus tili', 'exp' => '8 yil'],
            ['n' => 'Otabek Nazarov', 'role' => 'Jismoniy tarbiya', 'exp' => '6 yil'],
            ['n' => 'Zarina Qodirova', 'role' => 'Musiqa', 'exp' => '3 yil'],
            ['n' => 'Sardor Yoʻldoshev', 'role' => 'Tarix', 'exp' => '11 yil'],
            ['n' => 'Malika Ergasheva', 'role' => 'Biologiya', 'exp' => '7 yil'],
            ['n' => 'Farrux Toshpulatov', 'role' => 'IELTS / SAT', 'exp' => '5 yil'],
        ];
    }

    private static function teachersFor(int $i): array
    {
        $pool = self::teacherPool();
        $n = count($pool);
        $offset = ($i * 3) % $n;

        return collect(range(0, 3))->map(fn ($k) => $pool[($offset + $k) % $n])->all();
    }

    private static function programsFor(string $cat): array
    {
        if ($cat === 'bogcha') {
            return [
                ['t' => 'Erta rivojlanish', 'd' => 'Yosh xususiyatiga mos kompleks dastur'],
                ['t' => 'Ikki tilli muhit', 'd' => 'Oʻzbek va ingliz tilida muloqot'],
                ['t' => 'Ijodiy ustaxonalar', 'd' => 'Rasm, musiqa, qoʻl mehnati'],
                ['t' => 'Montessori yondashuv', 'd' => 'Mustaqillik va tabiiy oʻrganish'],
            ];
        }

        if ($cat === 'markaz') {
            return [
                ['t' => 'IELTS / SAT tayyorlov', 'd' => 'Maqsadli bal kafolati bilan'],
                ['t' => 'Speaking club', 'd' => 'Native speaker bilan amaliyot'],
                ['t' => 'Individual mashgʻulot', 'd' => 'Shaxsiy reja asosida'],
                ['t' => 'Onlayn platforma', 'd' => 'Uy vazifasi va kuzatuv tizimi'],
            ];
        }

        return [
            ['t' => 'Cambridge dasturi', 'd' => 'Xalqaro standart va sertifikat'],
            ['t' => 'STEM laboratoriyasi', 'd' => 'Amaliy fan va tajribalar'],
            ['t' => 'Robototexnika', 'd' => 'Dasturlash va muhandislik toʻgaragi'],
            ['t' => 'Olimpiadaga tayyorlov', 'd' => 'Iqtidorli oʻquvchilar uchun'],
        ];
    }

    private static function lessonsFor(string $cat): array
    {
        if ($cat === 'bogcha') {
            return ['Rasm chizish', 'Musiqa mashgʻuloti', 'Erta rivojlanish', 'Ingliz tili oʻyini', 'Jismoniy faollik', 'Ertak vaqti'];
        }

        if ($cat === 'markaz') {
            return ['Ingliz tili darsi', 'IELTS mock test', 'Speaking club', 'Dasturlash', 'Grammatika', 'Imtihon tahlili'];
        }

        return ['Matematika darsi', 'Ingliz tili', 'Ilmiy laboratoriya', 'Robototexnika', 'Sanʼat studiyasi', 'Jismoniy tarbiya'];
    }

    private static function admissionStepsFor(): array
    {
        return [
            ['t' => 'Ariza qoldirish', 'd' => 'Onlayn forma orqali ariza yuborasiz'],
            ['t' => 'Tanishuv / ekskursiya', 'd' => 'Muassasaga tashrif buyurib, muhit bilan tanishasiz'],
            ['t' => 'Suhbat va kirish testi', 'd' => 'Bola bilan qisqa suhbat oʻtkaziladi'],
            ['t' => 'Shartnoma va joylashtirish', 'd' => 'Hujjatlar rasmiylashtirilib, oʻquvchi qabul qilinadi'],
        ];
    }

    /** [sinf hajmi, tajriba yili, qabul foizi, 1-sinf joylari] — kategoriya bazasi + id bo'yicha
     *  kichik tebranish (namoyish uchun, hammasi bir xil ko'rinmasin). */
    private static function statsFor(string $cat, int $id): array
    {
        $base = match ($cat) {
            'bogcha' => [12, 8, null, 18],
            'markaz' => [6, 9, null, 12],
            default => [16, 12, 98, 24],
        };

        $wobble = $id % 5;

        return [
            (string) ($base[0] + ($wobble % 3)),
            (string) ($base[1] + ($wobble % 4)),
            $base[2] !== null ? ($base[2] - $wobble).'%' : null,
            (string) ($base[3] + $wobble),
        ];
    }

    public function run(): void
    {
        $districts = District::pluck('id', 'name');
        $specs = Specialization::pluck('id', 'key');
        $specPlan = self::specPlan();

        // Demo egalar: id=2 (Sodiq School) -> Aziz Karimov, id=3 (Diplomat International) -> Nodira Yusupova
        $owners = [
            2 => User::where('phone', '+998901234503')->value('id'),
            3 => User::where('phone', '+998901234504')->value('id'),
        ];

        foreach (self::schools() as $i => $s) {
            [$classSize, $expYears, $admissionRate, $firstGradeSeats] = self::statsFor($s['cat'], $s['id']);

            $institution = Institution::updateOrCreate(
                ['name' => $s['name']],
                [
                    'owner_user_id' => $owners[$s['id']] ?? null,
                    'type' => $s['cat'],
                    'about' => "{$s['name']} — Toshkent shahridagi ishonchli ta'lim muassasalaridan biri.",
                    'lang' => $s['lang'],
                    'district_id' => $districts[$s['district']] ?? null,
                    'monthly_price' => $s['price'],
                    'grades' => $s['grades'],
                    'work_hours' => '08:00 – 18:00',
                    'works_saturday' => $s['sat'],
                    'accepting' => true,
                    'rating' => $s['rating'],
                    'review_count' => $s['reviews'],
                    'badge' => $s['badge'] ?? null,
                    'facilities' => self::facilitiesFor($s['cat']),
                    'teachers' => self::teachersFor($i),
                    'programs' => self::programsFor($s['cat']),
                    'lessons' => self::lessonsFor($s['cat']),
                    'admission_steps' => self::admissionStepsFor(),
                    'stat_class_size' => $classSize,
                    'stat_experience_years' => $expYears,
                    'stat_admission_rate' => $admissionRate,
                    'stat_first_grade_seats' => $firstGradeSeats,
                ]
            );

            $keys = $specPlan[$i % count($specPlan)];
            $ids = collect($keys)->map(fn ($k) => $specs[$k] ?? null)->filter()->values();
            $institution->specializations()->sync($ids);

            // Egasi bor 2 ta muassasaga namunaviy galereya qo'shamiz (qolganlari bo'sh — o'zi yuklaydi)
            if (isset($owners[$s['id']]) && $owners[$s['id']]) {
                for ($n = 1; $n <= 3; $n++) {
                    InstitutionMedia::updateOrCreate(
                        ['institution_id' => $institution->id, 'sort_order' => $n],
                        [
                            'type' => 'gallery',
                            'disk' => 'local',
                            'url' => "https://picsum.photos/seed/mg-{$institution->id}-{$n}/800/600",
                            'caption' => "{$s['name']} — rasm {$n}",
                        ]
                    );
                }
            }
        }
    }
}
