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
        foreach ($schools as $i => &$school) {
            $school['g'] = $gradients[$i % count($gradients)];
        }

        return $schools;
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
}
