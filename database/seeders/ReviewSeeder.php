<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $parents = User::where('role', User::ROLE_PARENT)->pluck('id')->values();
        if ($parents->isEmpty()) {
            return;
        }

        $sample = [
            ['rating' => 5, 'body' => 'Farzandim shu yerda 2-yil. O\'qituvchilar e\'tiborli, ingliz tili sezilarli o\'sdi. Tavsiya qilaman.'],
            ['rating' => 4, 'body' => 'Daraja yaxshi, lekin transport jadvalini biroz yaxshilash kerak. Umuman mamnunmiz.'],
            ['rating' => 5, 'body' => 'Joylashtirish jarayoni juda qulay bo\'ldi. Hammasi onlayn, qo\'shimcha yugur-yugursiz.'],
        ];

        $institutions = Institution::orderBy('id')->limit(4)->get();

        foreach ($institutions as $i => $institution) {
            foreach ($sample as $j => $review) {
                Review::updateOrCreate(
                    ['institution_id' => $institution->id, 'user_id' => $parents[($i + $j) % $parents->count()]],
                    ['rating' => $review['rating'], 'body' => $review['body']]
                );
            }
        }
    }
}
