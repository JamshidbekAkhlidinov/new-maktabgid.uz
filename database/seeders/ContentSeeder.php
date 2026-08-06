<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

/**
 * Diqqat: bu seeder ilgari News'ni ham to'ldirardi — endi News eski `post`
 * jadvalidan real import qilinadi (LegacyNewsSeeder, 2026-08-06). Article
 * (blog) uchun eski bazada mos manba yo'q edi, shu sababli demo mazmun
 * saqlanib qoldi (aks holda blog bo'limi butunlay bo'sh ko'rinardi).
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            ['tag' => 'Tanlov', 'title' => 'Farzandingizga mos maktabni qanday tanlash kerak? 7 ta mezon', 'excerpt' => 'Narx, masofa, ta\'lim tili, dastur va sharhlarni solishtirishda e\'tibor beriladigan asosiy mezonlar.', 'author_name' => 'Dr. Malika Yusupova', 'read_minutes' => 6, 'featured' => true],
            ['tag' => 'Psixologiya', 'title' => 'Maktabga moslashish: birinchi oydagi qiyinchiliklar', 'excerpt' => 'Bola yangi muhitga qanday ko\'nikadi va ota-ona unga qanday yordam berishi mumkin.', 'author_name' => 'Nasiba Qodirova', 'read_minutes' => 5, 'featured' => false],
            ['tag' => 'Moliya', 'title' => 'Ta\'lim byudjetini rejalashtirish: oilaviy hisob-kitob', 'excerpt' => 'Oylik to\'lov, qo\'shimcha xarajatlar va tejash imkoniyatlarini hisoblash bo\'yicha qo\'llanma.', 'author_name' => 'Sardor Tursunov', 'read_minutes' => 7, 'featured' => false],
            ['tag' => 'Salomatlik', 'title' => 'Maktab yoshidagi bolalar uchun to\'g\'ri ovqatlanish', 'excerpt' => 'Diqqat va xotirani yaxshilaydigan ratsion. Tushlik qutisiga nima solish kerak?', 'author_name' => 'Dr. Kamola R.', 'read_minutes' => 4, 'featured' => false],
            ['tag' => 'Tillar', 'title' => 'Bir vaqtda 2 ta tilni o\'rgatish bolaga zararmi?', 'excerpt' => 'Ikki tillilik haqidagi afsonalar va ilmiy dalillar. Mutaxassis fikri.', 'author_name' => 'Prof. Anvar Yo\'ldoshev', 'read_minutes' => 6, 'featured' => false],
            ['tag' => 'Texnologiya', 'title' => 'Ekran vaqti: bolaga telefonni qachon va qancha berish kerak?', 'excerpt' => 'Yoshga qarab tavsiya etilgan ekran vaqti va sog\'lom raqamli odatlar.', 'author_name' => 'Nasiba Qodirova', 'read_minutes' => 5, 'featured' => false],
        ];

        foreach ($articles as $i => $a) {
            Article::updateOrCreate(['title' => $a['title']], $a + [
                'body' => $a['excerpt'],
                'published_at' => now()->subDays($i * 3 + 1),
            ]);
        }
    }
}
