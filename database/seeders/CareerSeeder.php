<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Resume;
use Illuminate\Database\Seeder;

/**
 * Diqqat: bu seeder ilgari Vacancy'ni ham to'ldirardi — endi Vacancy eski
 * `vocations` jadvalidan real import qilinadi (LegacyVacancySeeder, 2026-08-06).
 * Resume uchun eski bazada mos manba yo'q edi (faqat ariza/rezyume fayli bor,
 * ochiq rezyume e'loni yo'q), shu sababli demo mazmun saqlanib qoldi.
 */
class CareerSeeder extends Seeder
{
    public function run(): void
    {
        $resumes = [
            ['full_name' => 'Madina Yusupova', 'role_title' => 'Ingliz tili o\'qituvchisi', 'experience' => '6 yil tajriba', 'specialization_key' => 'english', 'salary_expectation' => '8 – 12 mln', 'district' => 'Yunusobod', 'languages' => 'Ingliz (C1), O\'zbek'],
            ['full_name' => 'Aziz Rahimov', 'role_title' => 'Matematika o\'qituvchisi', 'experience' => '10 yil tajriba', 'specialization_key' => 'stem', 'salary_expectation' => '10 – 15 mln', 'district' => 'Chilonzor', 'languages' => 'O\'zbek, Rus'],
            ['full_name' => 'Sevara Tosheva', 'role_title' => 'Boshlang\'ich sinf ustozi', 'experience' => '4 yil tajriba', 'specialization_key' => 'early', 'salary_expectation' => '5 – 8 mln', 'district' => 'Mirobod', 'languages' => 'O\'zbek, Ingliz (B2)'],
            ['full_name' => 'Jamshid Karimov', 'role_title' => 'IT / Dasturlash o\'qituvchisi', 'experience' => '7 yil tajriba', 'specialization_key' => 'it', 'salary_expectation' => '12 – 20 mln', 'district' => 'Mirzo Ulug\'bek', 'languages' => 'Ingliz (C1), Rus'],
            ['full_name' => 'Nigora Aliyeva', 'role_title' => 'Bog\'cha tarbiyachisi', 'experience' => '8 yil tajriba', 'specialization_key' => 'early', 'salary_expectation' => '4 – 6 mln', 'district' => 'Yakkasaroy', 'languages' => 'O\'zbek, Rus'],
            ['full_name' => 'Otabek Saidov', 'role_title' => 'Sport / jismoniy tarbiya', 'experience' => '5 yil tajriba', 'specialization_key' => 'sport', 'salary_expectation' => '6 – 9 mln', 'district' => 'Sergeli', 'languages' => 'O\'zbek'],
        ];

        $districts = District::pluck('id', 'name');

        foreach ($resumes as $r) {
            $districtName = $r['district'];
            unset($r['district']);
            Resume::updateOrCreate(
                ['full_name' => $r['full_name'], 'role_title' => $r['role_title']],
                $r + ['district_id' => $districts[$districtName] ?? null]
            );
        }
    }
}
