<?php

namespace Database\Seeders;

use App\Models\InstitutionType;
use Illuminate\Database\Seeder;

class InstitutionTypeSeeder extends Seeder
{
    public static function list(): array
    {
        return [
            ['key' => 'maktab', 'label' => ['uz' => 'Maktab', 'ru' => 'Школа', 'en' => 'School'], 'icon' => 'school'],
            ['key' => 'bogcha', 'label' => ['uz' => 'Bog\'cha', 'ru' => 'Детский сад', 'en' => 'Kindergarten'], 'icon' => 'teddy'],
            ['key' => 'markaz', 'label' => ['uz' => 'Markaz', 'ru' => 'Центр', 'en' => 'Center'], 'icon' => 'book'],
            ['key' => 'mutaxassis', 'label' => ['uz' => 'Mutaxassis', 'ru' => 'Специалист', 'en' => 'Specialist'], 'icon' => 'heart'],
        ];
    }

    public function run(): void
    {
        foreach (self::list() as $type) {
            InstitutionType::updateOrCreate(['key' => $type['key']], [
                'icon' => $type['icon'],
            ])->setTranslations('label', $type['label'])->save();
        }
    }
}
