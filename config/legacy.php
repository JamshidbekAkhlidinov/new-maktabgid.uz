<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Eski MaktabGID (Yii2/CMS) ma'lumotlarini import qilish sozlamalari
    |--------------------------------------------------------------------------
    |
    | `old_data_maktab.sql` dagi jadvallar rasm/fayl uchun faqat nisbiy yo'l
    | saqlagan (masalan "/uploads/IMG_1234.jpeg"), haqiqiy fayllar bu loyihada
    | yo'q. Import seederlari (Legacy* — backend/database/seeders/Legacy*.php)
    | shu manzillarni App\Support\LegacyMedia::url() orqali hal qiladi:
    |
    |   - "url"      — fayl ko'chirilmaydi, faqat LEGACY_MEDIA_BASE_URL + eski
    |                  yo'l birlashtirilib, tashqi havola sifatida saqlanadi
    |                  (institution_media.url, path=null — MediaUploadService
    |                  bilan bir xil "tashqi havola" qoidasi). Tez va ishonchli,
    |                  lekin eski server o'chsa rasm ko'rinmay qoladi.
    |   - "download"  — seed vaqtida har bir fayl LEGACY_MEDIA_BASE_URL dan
    |                  yuklab olinib, MEDIA_DISK (config('filesystems.media_disk'))
    |                  ga yoziladi va endi loyihaning o'z fayli bo'ladi. Sekinroq
    |                  (1700+ fayl) va tarmoqqa bog'liq — muvaffaqiyatsiz bo'lsa
    |                  shu fayl uchun avtomatik "url" rejimiga qaytiladi.
    |
    | Boshqarish: faqat .env dagi LEGACY_MEDIA_MODE ni almashtiring, kodga
    | tegish shart emas (MediaUploadService/R2 andozasi bilan bir xil printsip).
    |
    */

    'media_mode' => env('LEGACY_MEDIA_MODE', 'url'),

    'media_base_url' => rtrim(env('LEGACY_MEDIA_BASE_URL', 'https://maktabgid.uz'), '/'),

];
