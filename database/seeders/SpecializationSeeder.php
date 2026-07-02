<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    public static function list(): array
    {
        return [
            ['key' => 'stem', 'label' => 'STEM / Matematika', 'icon' => 'flask'],
            ['key' => 'english', 'label' => 'Ingliz tili', 'icon' => 'globe'],
            ['key' => 'it', 'label' => 'IT va dasturlash', 'icon' => 'code'],
            ['key' => 'art', 'label' => 'San\'at va dizayn', 'icon' => 'palette'],
            ['key' => 'music', 'label' => 'Musiqa', 'icon' => 'music'],
            ['key' => 'sport', 'label' => 'Sport', 'icon' => 'dumbbell'],
            ['key' => 'science', 'label' => 'Tabiiy fanlar', 'icon' => 'flask'],
            ['key' => 'olympiad', 'label' => 'Olimpiadaga tayyorlov', 'icon' => 'trophy'],
            ['key' => 'ielts', 'label' => 'IELTS / SAT', 'icon' => 'award'],
            ['key' => 'early', 'label' => 'Erta rivojlanish', 'icon' => 'teddy'],
        ];
    }

    public function run(): void
    {
        foreach (self::list() as $spec) {
            Specialization::updateOrCreate(['key' => $spec['key']], $spec);
        }
    }
}
