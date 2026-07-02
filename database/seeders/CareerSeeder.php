<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Resume;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        $vacancies = [
            ['title' => 'Ingliz tili o\'qituvchisi', 'org_name' => 'Yakubovs School', 'employment_type' => 'full', 'salary_range' => '10 – 12 mln', 'specialization_key' => 'english'],
            ['title' => 'Ingliz tili o\'qituvchisi', 'org_name' => 'New Tone School', 'employment_type' => 'full', 'salary_range' => '6 – 18 mln', 'specialization_key' => 'english'],
            ['title' => 'Boshlang\'ich sinf ustozi', 'org_name' => 'Baby Akademiya', 'employment_type' => 'full', 'salary_range' => '4 – 7 mln', 'specialization_key' => 'early'],
            ['title' => 'Matematika o\'qituvchisi', 'org_name' => 'Diplomat International', 'employment_type' => 'full', 'salary_range' => '9 – 14 mln', 'specialization_key' => 'stem'],
            ['title' => 'Bog\'cha tarbiyachisi', 'org_name' => 'Maple Bear', 'employment_type' => 'full', 'salary_range' => '5 – 8 mln', 'specialization_key' => 'early'],
            ['title' => 'IT / Robototexnika ustozi', 'org_name' => 'IT Park School', 'employment_type' => 'part', 'salary_range' => '8 – 12 mln', 'specialization_key' => 'it'],
            ['title' => 'IELTS instruktori', 'org_name' => 'Bright Kids', 'employment_type' => 'full', 'salary_range' => '10 – 16 mln', 'specialization_key' => 'ielts'],
        ];

        foreach ($vacancies as $i => $v) {
            Vacancy::updateOrCreate(
                ['title' => $v['title'], 'org_name' => $v['org_name']],
                $v + ['expires_at' => now()->addDays(14 + $i * 3)]
            );
        }

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
