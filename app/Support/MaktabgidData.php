<?php

namespace App\Support;

class MaktabgidData
{
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
            ['key' => 'maktab', 'label' => 'Xususiy maktablar', 'short' => 'Maktablar', 'icon' => 'school'],
            ['key' => 'bogcha', 'label' => 'Xususiy bogʻchalar', 'short' => 'Bogʻchalar', 'icon' => 'teddy'],
            ['key' => 'markaz', 'label' => 'Oʻquv markazlari', 'short' => 'Markazlar', 'icon' => 'book'],
            ['key' => 'mutaxassis', 'label' => 'Bolalar mutaxassislari', 'short' => 'Mutaxassislar', 'icon' => 'heart'],
        ];
    }

    public static function categoryLabel(string $key): string
    {
        return [
            'maktab' => 'Maktab',
            'bogcha' => 'Bogʻcha',
            'markaz' => 'Oʻquv markazi',
            'mutaxassis' => 'Mutaxassis',
        ][$key] ?? $key;
    }

    public static function districts(): array
    {
        return [
            'Yunusobod', 'Mirzo Ulugʻbek', 'Mirobod', 'Shayxontohur', 'Olmazor',
            'Sergeli', 'Yakkasaroy', 'Yashnobod', 'Yangihayot', 'Chilonzor', 'Uchtepa',
        ];
    }

    public static function priceBands(): array
    {
        return [
            ['key' => 'lt2', 'label' => '2 mln dan kam', 'min' => 0, 'max' => 2000000],
            ['key' => '2-3.5', 'label' => '2 – 3.5 mln', 'min' => 2000000, 'max' => 3500000],
            ['key' => '3.5-5', 'label' => '3.5 – 5 mln', 'min' => 3500000, 'max' => 5000000],
            ['key' => '5-7', 'label' => '5 – 7 mln', 'min' => 5000000, 'max' => 7000000],
            ['key' => '7+', 'label' => '7 mln+', 'min' => 7000000, 'max' => PHP_INT_MAX],
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

    public static function schools(): array
    {
        $schools = [
            ['id' => 1, 'name' => 'CIS Tashkent', 'cat' => 'maktab', 'district' => 'Mirzo Ulugʻbek', 'dist' => 4.2, 'price' => 208818000, 'rating' => 4.9, 'reviews' => 214, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => false, 'x' => 64, 'y' => 30, 'badge' => 'Premium'],
            ['id' => 2, 'name' => 'Sodiq School', 'cat' => 'maktab', 'district' => 'Chilonzor', 'dist' => 1.1, 'price' => 6590000, 'rating' => 4.7, 'reviews' => 156, 'grades' => '1–11', 'lang' => 'Oʻzbek / Ingliz', 'sat' => true, 'x' => 33, 'y' => 58],
            ['id' => 3, 'name' => 'Diplomat International School', 'cat' => 'maktab', 'district' => 'Yunusobod', 'dist' => 2.4, 'price' => 6900000, 'rating' => 4.8, 'reviews' => 189, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => true, 'x' => 52, 'y' => 20],
            ['id' => 4, 'name' => 'Artel Technical School', 'cat' => 'maktab', 'district' => 'Yashnobod', 'dist' => 5.6, 'price' => 4250000, 'rating' => 4.5, 'reviews' => 87, 'grades' => '5–11', 'lang' => 'Oʻzbek', 'sat' => false, 'x' => 76, 'y' => 64],
            ['id' => 5, 'name' => 'IT Park School', 'cat' => 'maktab', 'district' => 'Mirzo Ulugʻbek', 'dist' => 3.8, 'price' => 41000000, 'rating' => 4.9, 'reviews' => 132, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => false, 'x' => 70, 'y' => 42, 'badge' => 'Premium'],
            ['id' => 6, 'name' => 'Cambridge School', 'cat' => 'maktab', 'district' => 'Mirobod', 'dist' => 2.0, 'price' => 5800000, 'rating' => 4.6, 'reviews' => 143, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => true, 'x' => 48, 'y' => 48],
            ['id' => 7, 'name' => 'Vosiq International School', 'cat' => 'maktab', 'district' => 'Olmazor', 'dist' => 3.1, 'price' => 5500000, 'rating' => 4.7, 'reviews' => 98, 'grades' => '1–9', 'lang' => 'Oʻzbek / Ingliz', 'sat' => true, 'x' => 40, 'y' => 28],
            ['id' => 8, 'name' => 'Interhouse Lyceum', 'cat' => 'maktab', 'district' => 'Yakkasaroy', 'dist' => 1.7, 'price' => 6500000, 'rating' => 4.8, 'reviews' => 176, 'grades' => '5–11', 'lang' => 'Ingliz', 'sat' => false, 'x' => 45, 'y' => 62],
            ['id' => 9, 'name' => 'Maple Bear Canadian School', 'cat' => 'bogcha', 'district' => 'Yunusobod', 'dist' => 2.9, 'price' => 14000000, 'rating' => 4.9, 'reviews' => 205, 'grades' => '3–7 yosh', 'lang' => 'Ingliz', 'sat' => false, 'x' => 56, 'y' => 24, 'badge' => 'Premium'],
            ['id' => 10, 'name' => 'Milestone International School', 'cat' => 'maktab', 'district' => 'Mirzo Ulugʻbek', 'dist' => 4.5, 'price' => 6750000, 'rating' => 4.6, 'reviews' => 121, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => true, 'x' => 67, 'y' => 36],
            ['id' => 11, 'name' => 'Al-Beruniy School', 'cat' => 'maktab', 'district' => 'Shayxontohur', 'dist' => 3.3, 'price' => 6800000, 'rating' => 4.7, 'reviews' => 110, 'grades' => '1–11', 'lang' => 'Oʻzbek', 'sat' => true, 'x' => 28, 'y' => 44],
            ['id' => 12, 'name' => 'Invento School', 'cat' => 'maktab', 'district' => 'Yunusobod', 'dist' => 5.2, 'price' => 132000000, 'rating' => 5.0, 'reviews' => 64, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => false, 'x' => 58, 'y' => 14, 'badge' => 'Premium'],
            ['id' => 13, 'name' => 'Jalaliddin International School', 'cat' => 'maktab', 'district' => 'Sergeli', 'dist' => 6.8, 'price' => 7778000, 'rating' => 4.5, 'reviews' => 73, 'grades' => '1–11', 'lang' => 'Ingliz', 'sat' => true, 'x' => 50, 'y' => 80],
            ['id' => 14, 'name' => 'Rahimov School', 'cat' => 'maktab', 'district' => 'Chilonzor', 'dist' => 2.6, 'price' => 6200000, 'rating' => 4.6, 'reviews' => 134, 'grades' => '1–11', 'lang' => 'Oʻzbek / Ingliz', 'sat' => true, 'x' => 36, 'y' => 52],
            ['id' => 15, 'name' => 'Little Stars Bogʻcha', 'cat' => 'bogcha', 'district' => 'Yakkasaroy', 'dist' => 0.9, 'price' => 3200000, 'rating' => 4.8, 'reviews' => 96, 'grades' => '2–6 yosh', 'lang' => 'Oʻzbek / Ingliz', 'sat' => true, 'x' => 43, 'y' => 56],
            ['id' => 16, 'name' => 'Bright Kids Markazi', 'cat' => 'markaz', 'district' => 'Mirobod', 'dist' => 1.4, 'price' => 1500000, 'rating' => 4.7, 'reviews' => 152, 'grades' => '6–16 yosh', 'lang' => 'Ingliz', 'sat' => true, 'x' => 49, 'y' => 50],
        ];

        $gradients = self::gradients();
        $specPlan = self::specPlan();
        foreach ($schools as $i => &$school) {
            $school['g'] = $gradients[$i % count($gradients)];
            $school['specs'] = $specPlan[$i % count($specPlan)];
        }

        return $schools;
    }

    public static function school(int $id): ?array
    {
        foreach (self::schools() as $school) {
            if ($school['id'] === $id) {
                return $school;
            }
        }

        return null;
    }

    /** Specializations catalogue (matches data.jsx SPECIALIZATIONS) */
    public static function specializations(): array
    {
        return [
            ['key' => 'stem', 'label' => 'STEM / Matematika', 'icon' => 'flask'],
            ['key' => 'english', 'label' => 'Ingliz tili', 'icon' => 'globe'],
            ['key' => 'it', 'label' => 'IT va dasturlash', 'icon' => 'code'],
            ['key' => 'art', 'label' => 'Sanʼat va dizayn', 'icon' => 'palette'],
            ['key' => 'music', 'label' => 'Musiqa', 'icon' => 'music'],
            ['key' => 'sport', 'label' => 'Sport', 'icon' => 'dumbbell'],
            ['key' => 'science', 'label' => 'Tabiiy fanlar', 'icon' => 'flask'],
            ['key' => 'olympiad', 'label' => 'Olimpiadaga tayyorlov', 'icon' => 'trophy'],
            ['key' => 'ielts', 'label' => 'IELTS / SAT', 'icon' => 'award'],
            ['key' => 'early', 'label' => 'Erta rivojlanish', 'icon' => 'teddy'],
        ];
    }

    public static function specializationLabel(string $key): ?array
    {
        foreach (self::specializations() as $sp) {
            if ($sp['key'] === $key) {
                return $sp;
            }
        }

        return null;
    }

    /** Deterministic 2–3 specs per institution (matches data.jsx SPEC_PLAN) */
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

    public static function vacancies(): array
    {
        return [
            ['id' => 3, 'title' => 'Ingliz tili oʻqituvchisi', 'org' => 'Yakubovs School', 'type' => 'Toʻliq stavka', 'salary' => '10 – 12 mln', 'until' => '19 Apr 2027'],
            ['id' => 2, 'title' => 'Ingliz tili oʻqituvchisi', 'org' => 'New Tone School', 'type' => 'Toʻliq stavka', 'salary' => '6 – 18 mln', 'until' => '19 Apr 2027'],
            ['id' => 1, 'title' => 'Boshlangʻich sinf ustozi', 'org' => 'Baby Akademiya', 'type' => 'Toʻliq stavka', 'salary' => '4 – 7 mln', 'until' => '19 Apr 2027'],
        ];
    }

    public static function blog(): array
    {
        return [
            ['id' => 2, 'tag' => 'Yangilik', 'title' => 'Direktorlar uchun har oylik 20% mukofot joriy etiladi', 'excerpt' => '2026-yil 1-yanvardan boshlab umumtaʼlim maktablari direktorlari va oʻrinbosarlariga KPI natijalari asosida mukofotlar belgilanadi.', 'date' => '15 Apr 2026', 'g' => ['#0EA5A0', '#0B7E8C']],
            ['id' => 1, 'tag' => 'Qabul', 'title' => 'Invento maktabida 5–6 yoshli bolalar uchun yangi guruh ochildi', 'excerpt' => 'Arizalar qabuli 30-aprelgacha davom etadi. Joylar soni cheklangan.', 'date' => '15 Apr 2026', 'g' => ['#6366F1', '#4338CA']],
            ['id' => 3, 'tag' => 'Maslahat', 'title' => 'Farzandingizga mos maktabni qanday tanlash kerak?', 'excerpt' => 'Narx, masofa, taʼlim tili va dasturlarni solishtirishda eʼtibor beriladigan 7 ta mezon.', 'date' => '10 Apr 2026', 'g' => ['#F59E0B', '#D97706']],
        ];
    }

    public static function formatPrice(int|float $n): string
    {
        if ($n >= 1000000) {
            $m = $n / 1000000;
            $s = (floor($m) == $m) ? (string) (int) $m : number_format($m, 1);
            return str_replace('.', ',', $s) . ' mln';
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

    public static function facilities(): array
    {
        return [
            ['i' => 'book', 't' => 'Zamonaviy sinfxonalar'], ['i' => 'flask', 't' => 'Ilmiy laboratoriya'],
            ['i' => 'code', 't' => 'IT va robototexnika'], ['i' => 'dumbbell', 't' => 'Sport zali va maydon'],
            ['i' => 'book', 't' => 'Kutubxona'], ['i' => 'cup', 't' => 'Issiq ovqat / oshxona'],
            ['i' => 'cross', 't' => 'Tibbiyot xonasi'], ['i' => 'bus', 't' => 'Maktab avtobusi'], ['i' => 'wifi', 't' => 'Wi-Fi va xavfsizlik'],
        ];
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

    public static function reviews(): array
    {
        return [
            ['n' => 'Kamola R.', 'r' => 5, 'ago' => '1 hafta oldin', 't' => 'Farzandim shu yerda 2-yil. Oʻqituvchilar eʼtiborli, ingliz tili sezilarli oʻsdi. Tavsiya qilaman.'],
            ['n' => 'Bekzod U.', 'r' => 4, 'ago' => '3 hafta oldin', 't' => 'Daraja yaxshi, lekin transport jadvalini biroz yaxshilash kerak. Umuman mamnunmiz.'],
            ['n' => 'Nodira S.', 'r' => 5, 'ago' => '1 oy oldin', 't' => 'Joylashtirish jarayoni juda qulay boʻldi. Hammasi onlayn, qoʻshimcha yugur-yugursiz.'],
        ];
    }

    public static function ratingBars(): array
    {
        return [
            ['s' => 5, 'p' => 78], ['s' => 4, 'p' => 16], ['s' => 3, 'p' => 4], ['s' => 2, 'p' => 1], ['s' => 1, 'p' => 1],
        ];
    }

    /** ---------------- FORUM ---------------- */

    public static function forumCategories(): array
    {
        return ['Hammasi', 'Maktab tanlash', 'Bogʻcha', 'Oʻquv markazi', 'Narx va toʻlov', 'Maslahat'];
    }

    public static function forumThreads(): array
    {
        return [
            ['id' => 1, 'cat' => 'Maktab tanlash', 'title' => 'Yunusobodda 1-sinf uchun qaysi maktab yaxshi?', 'author' => 'Dilnoza M.', 'ago' => '2 soat oldin', 'replies' => 14, 'views' => 312, 'likes' => 23,
                'body' => 'Assalomu alaykum! Farzandim kelasi yil 1-sinfga boradi. Yunusobod tumanida, oyiga 6 mln atrofida, ingliz tili kuchli maktab izlayapman. Tajribangiz bilan oʻrtoqlashsangiz.'],
            ['id' => 2, 'cat' => 'Bogʻcha', 'title' => '3 yoshli bola uchun bogʻcha — moslashish qancha davom etadi?', 'author' => 'Sardor T.', 'ago' => '5 soat oldin', 'replies' => 9, 'views' => 188, 'likes' => 17,
                'body' => 'Qizimni yangi bogʻchaga berdik, har kuni yigʻlaydi. Necha kunda koʻnikadi? Sizlarda qanday boʻlgan?'],
            ['id' => 3, 'cat' => 'Narx va toʻlov', 'title' => 'Xususiy maktab toʻlovlarini boʻlib toʻlash mumkinmi?', 'author' => 'Gulnora A.', 'ago' => '1 kun oldin', 'replies' => 21, 'views' => 540, 'likes' => 31,
                'body' => 'Koʻp maktablar yillik toʻlovni bir yoʻla soʻrayapti. Oylik yoki choraklik toʻlov qabul qiladigan maktablarni bilasizmi?'],
            ['id' => 4, 'cat' => 'Oʻquv markazi', 'title' => 'IELTS 7.0 ga qaysi markaz real tayyorlaydi?', 'author' => 'Jasur K.', 'ago' => '1 kun oldin', 'replies' => 18, 'views' => 421, 'likes' => 12,
                'body' => 'Kattaqizimga IELTS kerak, 3 oyda 7.0. Reklama emas, real natija bergan markazlarni tavsiya qiling.'],
            ['id' => 5, 'cat' => 'Maslahat', 'title' => 'Maktab avtobusi xavfsizligini qanday tekshirasiz?', 'author' => 'Nodira S.', 'ago' => '2 kun oldin', 'replies' => 7, 'views' => 156, 'likes' => 9,
                'body' => 'Maktab transport xizmati taklif qilyapti lekin xavfsizligi haqida oʻylayapman. Nimalarga eʼtibor berish kerak?'],
        ];
    }

    public static function forumThread(int $id): ?array
    {
        foreach (self::forumThreads() as $t) {
            if ($t['id'] === $id) {
                return $t;
            }
        }

        return null;
    }

    public static function forumReplies(): array
    {
        return [
            ['id' => 1, 'author' => 'Kamola R.', 'ago' => '1 soat oldin', 'likes' => 8, 'body' => 'Diplomat International School ni koʻrib chiqing — ingliz tili juda kuchli, Yunusobodda. Biz 2 yildan beri qatnaymiz, mamnunmiz.'],
            ['id' => 2, 'author' => 'Bekzod U.', 'ago' => '45 daqiqa oldin', 'likes' => 3, 'body' => 'Vosiq International ham yaxshi variant, narxi biroz arzonroq. Ekskursiyaga yozilib, oʻzingiz koʻrib keling.'],
            ['id' => 3, 'author' => 'Dilnoza M.', 'ago' => '20 daqiqa oldin', 'likes' => 1, 'body' => 'Rahmat! Ikkalasiga ham ekskursiyaga yozildim platforma orqali.'],
        ];
    }

    /** ---------------- BLOG (articles) ---------------- */

    public static function articles(): array
    {
        return [
            ['id' => 1, 'tag' => 'Tanlov', 'title' => 'Farzandingizga mos maktabni qanday tanlash kerak? 7 ta mezon', 'excerpt' => 'Narx, masofa, taʼlim tili, dastur va sharhlarni solishtirishda eʼtibor beriladigan asosiy mezonlar.', 'read' => '6 daqiqa', 'author' => 'Dr. Malika Yusupova', 'date' => '2 Iyun 2026', 'g' => ['#0EA5A0', '#0B7E8C'], 'feat' => true],
            ['id' => 2, 'tag' => 'Psixologiya', 'title' => 'Maktabga moslashish: birinchi oydagi qiyinchiliklar', 'excerpt' => 'Bola yangi muhitga qanday koʻnikadi va ota-ona unga qanday yordam berishi mumkin.', 'read' => '5 daqiqa', 'author' => 'Nasiba Qodirova', 'date' => '30 May 2026', 'g' => ['#6366F1', '#4338CA']],
            ['id' => 3, 'tag' => 'Moliya', 'title' => 'Taʼlim byudjetini rejalashtirish: oilaviy hisob-kitob', 'excerpt' => 'Oylik toʻlov, qoʻshimcha xarajatlar va tejash imkoniyatlarini hisoblash boʻyicha qoʻllanma.', 'read' => '7 daqiqa', 'author' => 'Sardor Tursunov', 'date' => '27 May 2026', 'g' => ['#F59E0B', '#D97706']],
            ['id' => 4, 'tag' => 'Salomatlik', 'title' => 'Maktab yoshidagi bolalar uchun toʻgʻri ovqatlanish', 'excerpt' => 'Diqqat va xotirani yaxshilaydigan ratsion. Tushlik qutisiga nima solish kerak?', 'read' => '4 daqiqa', 'author' => 'Dr. Kamola R.', 'date' => '24 May 2026', 'g' => ['#10B981', '#047857']],
            ['id' => 5, 'tag' => 'Tillar', 'title' => 'Bir vaqtda 2 ta tilni oʻrgatish bolaga zararmi?', 'excerpt' => 'Ikki tillilik haqidagi afsonalar va ilmiy dalillar. Mutaxassis fikri.', 'read' => '6 daqiqa', 'author' => 'Prof. Anvar Yoʻldoshev', 'date' => '21 May 2026', 'g' => ['#8B5CF6', '#6D28D9']],
            ['id' => 6, 'tag' => 'Texnologiya', 'title' => 'Ekran vaqti: bolaga telefonni qachon va qancha berish kerak?', 'excerpt' => 'Yoshga qarab tavsiya etilgan ekran vaqti va sogʻlom raqamli odatlar.', 'read' => '5 daqiqa', 'author' => 'Nasiba Qodirova', 'date' => '18 May 2026', 'g' => ['#EC4899', '#BE185D']],
        ];
    }

    public static function article(int $id): ?array
    {
        foreach (self::articles() as $a) {
            if ($a['id'] === $id) {
                return $a;
            }
        }

        return null;
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

    /** ---------------- NEWS ---------------- */

    public static function news(): array
    {
        return [
            ['id' => 1, 'tag' => 'Taʼlim siyosati', 'title' => '2026–2027 oʻquv yili: xususiy maktablar uchun yangi litsenziya qoidalari', 'excerpt' => 'Vazirlik xususiy taʼlim muassasalari uchun akkreditatsiya talablarini yangiladi. Asosiy oʻzgarishlar va muddatlar.', 'date' => '3 Iyun 2026', 'source' => 'MaktabGID tahririyati', 'g' => ['#0EA5A0', '#0B7E8C'], 'hot' => true],
            ['id' => 2, 'tag' => 'Qabul', 'title' => 'Toshkentda 12 ta yangi xususiy bogʻcha ochilmoqda', 'excerpt' => 'Shahar boʻylab yangi bogʻchalar roʻyxati va arizalar boshlanish sanalari eʼlon qilindi.', 'date' => '1 Iyun 2026', 'source' => 'Toshkent IBBM', 'g' => ['#6366F1', '#4338CA']],
            ['id' => 3, 'tag' => 'Imtihon', 'title' => 'Milliy sertifikat imtihoni jadvali maʼlum boʻldi', 'excerpt' => 'Ingliz tili va boshqa fanlardan milliy sertifikat imtihonlari sanalari joylandi.', 'date' => '28 May 2026', 'source' => 'Davlat test markazi', 'g' => ['#F59E0B', '#D97706']],
            ['id' => 4, 'tag' => 'Grant', 'title' => 'Iqtidorli oʻquvchilar uchun 500 ta toʻliq grant', 'excerpt' => 'Bir qancha xususiy maktablar ijtimoiy himoyaga muhtoj oilalar farzandlari uchun grant eʼlon qildi.', 'date' => '25 May 2026', 'source' => 'MaktabGID tahririyati', 'g' => ['#10B981', '#047857']],
            ['id' => 5, 'tag' => 'Texnologiya', 'title' => 'Maktablarda AI-yordamchi: pilot loyiha 30 ta maktabda boshlandi', 'excerpt' => 'Sunʼiy intellekt asosidagi oʻquv yordamchilari sinov tariqasida joriy etilmoqda.', 'date' => '20 May 2026', 'source' => 'IT Park', 'g' => ['#8B5CF6', '#6D28D9']],
            ['id' => 6, 'tag' => 'Tadbir', 'title' => '«Taʼlim EXPO 2026» koʻrgazmasi 15-iyunda boʻlib oʻtadi', 'excerpt' => '100 dan ortiq muassasa qatnashadi. Ota-onalar uchun bepul tashrif va konsultatsiyalar.', 'date' => '18 May 2026', 'source' => 'Taʼlim EXPO', 'g' => ['#EC4899', '#BE185D']],
        ];
    }

    public static function newsItem(int $id): ?array
    {
        foreach (self::news() as $n) {
            if ($n['id'] === $id) {
                return $n;
            }
        }

        return null;
    }

    /** ---------------- CAREERS (vacancies + resumes) ---------------- */

    public static function careerVacancies(): array
    {
        $main = array_map(fn ($v) => $v + ['spec' => 'english'], self::vacancies());

        $more = [
            ['id' => 101, 'title' => 'Matematika oʻqituvchisi', 'org' => 'Diplomat International', 'type' => 'Toʻliq stavka', 'salary' => '9 – 14 mln', 'until' => '20 Iyun 2026', 'spec' => 'stem'],
            ['id' => 102, 'title' => 'Bogʻcha tarbiyachisi', 'org' => 'Maple Bear', 'type' => 'Toʻliq stavka', 'salary' => '5 – 8 mln', 'until' => '25 Iyun 2026', 'spec' => 'early'],
            ['id' => 103, 'title' => 'IT / Robototexnika ustozi', 'org' => 'IT Park School', 'type' => 'Yarim stavka', 'salary' => '8 – 12 mln', 'until' => '30 Iyun 2026', 'spec' => 'it'],
            ['id' => 104, 'title' => 'IELTS instruktori', 'org' => 'Bright Kids', 'type' => 'Toʻliq stavka', 'salary' => '10 – 16 mln', 'until' => '18 Iyun 2026', 'spec' => 'ielts'],
        ];

        return [...$main, ...$more];
    }

    public static function careerVacancy(int $id): ?array
    {
        foreach (self::careerVacancies() as $v) {
            if ($v['id'] === $id) {
                return $v;
            }
        }

        return null;
    }

    public static function resumes(): array
    {
        return [
            ['id' => 1, 'name' => 'Madina Yusupova', 'role' => 'Ingliz tili oʻqituvchisi', 'exp' => '6 yil tajriba', 'spec' => 'english', 'salary' => '8 – 12 mln', 'district' => 'Yunusobod', 'langs' => 'Ingliz (C1), Oʻzbek', 'ago' => '2 kun oldin'],
            ['id' => 2, 'name' => 'Aziz Rahimov', 'role' => 'Matematika oʻqituvchisi', 'exp' => '10 yil tajriba', 'spec' => 'stem', 'salary' => '10 – 15 mln', 'district' => 'Chilonzor', 'langs' => 'Oʻzbek, Rus', 'ago' => '3 kun oldin'],
            ['id' => 3, 'name' => 'Sevara Tosheva', 'role' => 'Boshlangʻich sinf ustozi', 'exp' => '4 yil tajriba', 'spec' => 'early', 'salary' => '5 – 8 mln', 'district' => 'Mirobod', 'langs' => 'Oʻzbek, Ingliz (B2)', 'ago' => '1 kun oldin'],
            ['id' => 4, 'name' => 'Jamshid Karimov', 'role' => 'IT / Dasturlash oʻqituvchisi', 'exp' => '7 yil tajriba', 'spec' => 'it', 'salary' => '12 – 20 mln', 'district' => 'Mirzo Ulugʻbek', 'langs' => 'Ingliz (C1), Rus', 'ago' => '4 soat oldin'],
            ['id' => 5, 'name' => 'Nigora Aliyeva', 'role' => 'Bogʻcha tarbiyachisi', 'exp' => '8 yil tajriba', 'spec' => 'early', 'salary' => '4 – 6 mln', 'district' => 'Yakkasaroy', 'langs' => 'Oʻzbek, Rus', 'ago' => '5 kun oldin'],
            ['id' => 6, 'name' => 'Otabek Saidov', 'role' => 'Sport / jismoniy tarbiya', 'exp' => '5 yil tajriba', 'spec' => 'sport', 'salary' => '6 – 9 mln', 'district' => 'Sergeli', 'langs' => 'Oʻzbek', 'ago' => '6 kun oldin'],
        ];
    }
}
