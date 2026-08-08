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
            ['full_name' => 'Madina Yusupova', 'role_title' => 'Ingliz tili o\'qituvchisi', 'experience' => '6 yil tajriba', 'specialization_key' => 'english', 'salary_expectation' => '8 – 12 mln', 'district' => 'Yunusobod', 'languages' => 'Ingliz (C1), O\'zbek', 'phone' => '+998 90 123 45 01', 'education' => 'O\'zDJTU, Ingliz filologiyasi — 2018-yil', 'skills' => 'IELTS 8.0, CELTA, Cambridge YLE tayyorlov kurslari', 'description' => "6 yildan beri xususiy maktablarda 1-11-sinflarga ingliz tilidan dars beraman. IELTS va CEFR imtihonlariga tayyorlash bo'yicha ixtisoslashganman, o'quvchilarim doimiy ravishda 7.0+ ball olishadi.\n\nOldingi ish joyi: Silk Road International School (2020–2026)."],
            ['full_name' => 'Aziz Rahimov', 'role_title' => 'Matematika o\'qituvchisi', 'experience' => '10 yil tajriba', 'specialization_key' => 'stem', 'salary_expectation' => '10 – 15 mln', 'district' => 'Chilonzor', 'languages' => 'O\'zbek, Rus', 'phone' => '+998 90 123 45 02', 'education' => 'NUU, Amaliy matematika — 2014-yil', 'skills' => 'Olimpiada tayyorlovi, IB Matematika dasturi', 'description' => "10 yillik tajribam davomida shogirdlarim respublika olimpiadalarida 12 marta g'olib bo'lishgan. IB Diploma dasturi bo'yicha sertifikatlangan o'qituvchiman.\n\nHozirgi vaqtda Chilonzor tumanidagi maktabda bosh matematika o'qituvchisi lavozimida ishlayman."],
            ['full_name' => 'Sevara Tosheva', 'role_title' => 'Boshlang\'ich sinf ustozi', 'experience' => '4 yil tajriba', 'specialization_key' => 'early', 'salary_expectation' => '5 – 8 mln', 'district' => 'Mirobod', 'languages' => 'O\'zbek, Ingliz (B2)', 'phone' => '+998 90 123 45 03', 'education' => 'TDPU, Boshlang\'ich ta\'lim — 2021-yil', 'skills' => 'Montessori metodikasi, individual yondashuv', 'description' => "Har bir bolaga individual yondashuvni afzal ko'raman, Montessori metodikasi bo'yicha qo'shimcha kurs o'tganman. Ota-onalar bilan doimiy aloqada bo'lib, haftalik hisobot beraman."],
            ['full_name' => 'Jamshid Karimov', 'role_title' => 'IT / Dasturlash o\'qituvchisi', 'experience' => '7 yil tajriba', 'specialization_key' => 'it', 'salary_expectation' => '12 – 20 mln', 'district' => 'Mirzo Ulug\'bek', 'languages' => 'Ingliz (C1), Rus', 'phone' => '+998 90 123 45 04', 'education' => 'INHA University Tashkent, Kompyuter injiniringi — 2017-yil', 'skills' => 'Python, Scratch, robototexnika to\'garaklari', 'description' => "IT Park bilan hamkorlikda maktab o'quvchilari uchun dasturlash to'garaklarini yuritganman. Robototexnika bo'yicha respublika ko'rgazmalarida jamoamiz 3 marta sovrinli o'rinlarni egallagan."],
            ['full_name' => 'Nigora Aliyeva', 'role_title' => 'Bog\'cha tarbiyachisi', 'experience' => '8 yil tajriba', 'specialization_key' => 'early', 'salary_expectation' => '4 – 6 mln', 'district' => 'Yakkasaroy', 'languages' => 'O\'zbek, Rus', 'phone' => '+998 90 123 45 05', 'education' => 'Nizomiy nomidagi TDPU, Maktabgacha ta\'lim — 2016-yil', 'skills' => 'Erta rivojlantirish dasturlari, ota-onalar bilan ishlash', 'description' => "8 yildan beri xususiy bog'chalarda 3–6 yoshli bolalar bilan ishlayman. Erta rivojlantirish dasturlari (Montessori, Reggio Emilia) bo'yicha malaka oshirish kurslarini tamomlaganman."],
            ['full_name' => 'Otabek Saidov', 'role_title' => 'Sport / jismoniy tarbiya', 'experience' => '5 yil tajriba', 'specialization_key' => 'sport', 'salary_expectation' => '6 – 9 mln', 'district' => 'Sergeli', 'languages' => 'O\'zbek', 'phone' => '+998 90 123 45 06', 'education' => 'O\'zDJTSU, Jismoniy tarbiya va sport — 2019-yil', 'skills' => 'Yengil atletika murabbiyligi, birinchi tibbiy yordam', 'description' => "Yengil atletika bo'yicha sport ustasi nomzodiman, maktab terma jamoasini respublika musobaqalariga tayyorlab kelganman. Birinchi tibbiy yordam bo'yicha sertifikatga egaman."],
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
