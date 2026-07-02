<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\News;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $news = [
            ['tag' => 'Ta\'lim siyosati', 'title' => '2026–2027 o\'quv yili: xususiy maktablar uchun yangi litsenziya qoidalari', 'excerpt' => 'Vazirlik xususiy ta\'lim muassasalari uchun akkreditatsiya talablarini yangiladi. Asosiy o\'zgarishlar va muddatlar.', 'source' => 'MaktabGID tahririyati', 'hot' => true],
            ['tag' => 'Qabul', 'title' => 'Toshkentda 12 ta yangi xususiy bog\'cha ochilmoqda', 'excerpt' => 'Shahar bo\'ylab yangi bog\'chalar ro\'yxati va arizalar boshlanish sanalari e\'lon qilindi.', 'source' => 'Toshkent IBBM', 'hot' => false],
            ['tag' => 'Imtihon', 'title' => 'Milliy sertifikat imtihoni jadvali ma\'lum bo\'ldi', 'excerpt' => 'Ingliz tili va boshqa fanlardan milliy sertifikat imtihonlari sanalari joylandi.', 'source' => 'Davlat test markazi', 'hot' => false],
            ['tag' => 'Grant', 'title' => 'Iqtidorli o\'quvchilar uchun 500 ta to\'liq grant', 'excerpt' => 'Bir qancha xususiy maktablar ijtimoiy himoyaga muhtoj oilalar farzandlari uchun grant e\'lon qildi.', 'source' => 'MaktabGID tahririyati', 'hot' => false],
            ['tag' => 'Texnologiya', 'title' => 'Maktablarda AI-yordamchi: pilot loyiha 30 ta maktabda boshlandi', 'excerpt' => 'Sun\'iy intellekt asosidagi o\'quv yordamchilari sinov tariqasida joriy etilmoqda.', 'source' => 'IT Park', 'hot' => false],
            ['tag' => 'Tadbir', 'title' => '«Ta\'lim EXPO 2026» ko\'rgazmasi 15-iyunda bo\'lib o\'tadi', 'excerpt' => '100 dan ortiq muassasa qatnashadi. Ota-onalar uchun bepul tashrif va konsultatsiyalar.', 'source' => 'Ta\'lim EXPO', 'hot' => false],
        ];

        foreach ($news as $i => $n) {
            News::updateOrCreate(['title' => $n['title']], $n + [
                'body' => $n['excerpt'],
                'published_at' => now()->subDays($i * 3),
            ]);
        }

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
