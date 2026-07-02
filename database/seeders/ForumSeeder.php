<?php

namespace Database\Seeders;

use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\User;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $parents = User::where('role', User::ROLE_PARENT)->pluck('id')->values();
        if ($parents->isEmpty()) {
            return;
        }

        $threads = [
            ['cat' => 'Maktab tanlash', 'title' => 'Yunusobodda 1-sinf uchun qaysi maktab yaxshi?', 'views' => 312, 'likes' => 23,
                'body' => 'Assalomu alaykum! Farzandim kelasi yil 1-sinfga boradi. Yunusobod tumanida, oyiga 6 mln atrofida, ingliz tili kuchli maktab izlayapman. Tajribangiz bilan o\'rtoqlashsangiz.'],
            ['cat' => 'Bog\'cha', 'title' => '3 yoshli bola uchun bog\'cha — moslashish qancha davom etadi?', 'views' => 188, 'likes' => 17,
                'body' => 'Qizimni yangi bog\'chaga berdik, har kuni yig\'laydi. Necha kunda ko\'nikadi? Sizlarda qanday bo\'lgan?'],
            ['cat' => 'Narx va to\'lov', 'title' => 'Xususiy maktab to\'lovlarini bo\'lib to\'lash mumkinmi?', 'views' => 540, 'likes' => 31,
                'body' => 'Ko\'p maktablar yillik to\'lovni bir yo\'la so\'rayapti. Oylik yoki choraklik to\'lov qabul qiladigan maktablarni bilasizmi?'],
            ['cat' => 'O\'quv markazi', 'title' => 'IELTS 7.0 ga qaysi markaz real tayyorlaydi?', 'views' => 421, 'likes' => 12,
                'body' => 'Kattaqizimga IELTS kerak, 3 oyda 7.0. Reklama emas, real natija bergan markazlarni tavsiya qiling.'],
            ['cat' => 'Maslahat', 'title' => 'Maktab avtobusi xavfsizligini qanday tekshirasiz?', 'views' => 156, 'likes' => 9,
                'body' => 'Maktab transport xizmati taklif qilyapti lekin xavfsizligi haqida o\'ylayapman. Nimalarga e\'tibor berish kerak?'],
        ];

        $replyBodies = [
            'Diplomat International School ni ko\'rib chiqing — ingliz tili juda kuchli, Yunusobodda. Biz 2 yildan beri qatnaymiz, mamnunmiz.',
            'Vosiq International ham yaxshi variant, narxi biroz arzonroq. Ekskursiyaga yozilib, o\'zingiz ko\'rib keling.',
            'Rahmat! Ikkalasiga ham ekskursiyaga yozildim platforma orqali.',
        ];

        foreach ($threads as $i => $t) {
            $thread = ForumThread::updateOrCreate(
                ['title' => $t['title']],
                [
                    'category' => $t['cat'],
                    'body' => $t['body'],
                    'user_id' => $parents[$i % $parents->count()],
                    'view_count' => $t['views'],
                    'like_count' => $t['likes'],
                ]
            );

            if ($i === 0) {
                foreach ($replyBodies as $j => $body) {
                    ForumReply::updateOrCreate(
                        ['thread_id' => $thread->id, 'body' => $body],
                        [
                            'user_id' => $parents[$j % $parents->count()],
                            'like_count' => [8, 3, 1][$j] ?? 0,
                        ]
                    );
                }
            }
        }
    }
}
