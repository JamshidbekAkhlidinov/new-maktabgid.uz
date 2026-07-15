<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Institution;
use Illuminate\Database\Seeder;

/** Demo "O'quvchilar yutuqlari" — kabinet va ommaviy profil sahifasi bo'sh ko'rinmasligi uchun (ADR-0002, Faza 2). */
class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $sample = [
            ['title' => 'IELTS 8.5 ball', 'student_name' => 'Sardor Aliyev', 'year' => 2025, 'type' => 'Xalqaro imtihon', 'level' => 'intl'],
            ['title' => "Respublika matematika olimpiadasi — 1-o'rin", 'student_name' => 'Madina Yusupova', 'year' => 2025, 'type' => 'Olimpiada', 'level' => 'national'],
            ['title' => "Robototexnika tanlovi — g'olib", 'student_name' => 'Jasur Kamolov', 'year' => 2024, 'type' => 'Tanlov', 'level' => 'national'],
            ['title' => "Viloyat she'riyat kechasi — 2-o'rin", 'student_name' => 'Laylo Ergasheva', 'year' => 2024, 'type' => 'Ijodiy tanlov', 'level' => 'regional'],
        ];

        $institutions = Institution::orderBy('id')->limit(4)->get();

        foreach ($institutions as $i => $institution) {
            foreach (array_slice($sample, 0, 2 + ($i % 3)) as $achievement) {
                Achievement::updateOrCreate(
                    ['institution_id' => $institution->id, 'title' => $achievement['title']],
                    $achievement
                );
            }
        }
    }
}
