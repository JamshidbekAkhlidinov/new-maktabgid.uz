<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    public static function list(): array
    {
        return [
            ['key' => 'stem', 'label' => ['uz' => 'STEM / Matematika', 'ru' => 'STEM / Математика', 'en' => 'STEM / Mathematics'], 'icon' => 'flask'],
            ['key' => 'english', 'label' => ['uz' => 'Ingliz tili', 'ru' => 'Английский язык', 'en' => 'English language'], 'icon' => 'globe'],
            ['key' => 'it', 'label' => ['uz' => 'IT va dasturlash', 'ru' => 'IT и программирование', 'en' => 'IT & programming'], 'icon' => 'code'],
            ['key' => 'art', 'label' => ['uz' => 'San\'at va dizayn', 'ru' => 'Искусство и дизайн', 'en' => 'Art & design'], 'icon' => 'palette'],
            ['key' => 'music', 'label' => ['uz' => 'Musiqa', 'ru' => 'Музыка', 'en' => 'Music'], 'icon' => 'music'],
            ['key' => 'sport', 'label' => ['uz' => 'Sport', 'ru' => 'Спорт', 'en' => 'Sport'], 'icon' => 'dumbbell'],
            ['key' => 'science', 'label' => ['uz' => 'Tabiiy fanlar', 'ru' => 'Естественные науки', 'en' => 'Natural sciences'], 'icon' => 'flask'],
            ['key' => 'olympiad', 'label' => ['uz' => 'Olimpiadaga tayyorlov', 'ru' => 'Подготовка к олимпиадам', 'en' => 'Olympiad preparation'], 'icon' => 'trophy'],
            ['key' => 'ielts', 'label' => ['uz' => 'IELTS / SAT', 'ru' => 'IELTS / SAT', 'en' => 'IELTS / SAT'], 'icon' => 'award'],
            ['key' => 'early', 'label' => ['uz' => 'Erta rivojlanish', 'ru' => 'Раннее развитие', 'en' => 'Early development'], 'icon' => 'teddy'],
        ];
    }

    public function run(): void
    {
        foreach (self::list() as $spec) {
            Specialization::updateOrCreate(['key' => $spec['key']], [
                'icon' => $spec['icon'],
            ])->setTranslations('label', $spec['label'])->save();
        }
    }
}
