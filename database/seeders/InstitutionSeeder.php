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
