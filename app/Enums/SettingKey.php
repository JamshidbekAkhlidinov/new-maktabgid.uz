<?php

namespace App\Enums;

/**
 * Sayt darajasidagi barcha key-value sozlamalarning yagona ro'yxati.
 *
 * Yangi sozlama qo'shish uchun shu yerga bitta yangi `case` qo'shish va
 * pastdagi match bloklariga mos qiymat yozish yetarli — Setting jadvaliga
 * yangi ustun qo'shish, admin formasiga qo'lda maydon chizish shart emas:
 * Admin\SeoSettingController va admin/settings/edit.blade.php shu enumning
 * `cases()` ro'yxati bo'yicha avtomatik formani hosil qiladi, welcome.blade.php
 * esa Setting::get() orqali xuddi shu enum kaliti bilan o'qiydi.
 */
enum SettingKey: string
{
    case MetaTitle = 'meta_title';
    case MetaDescription = 'meta_description';
    case OgImage = 'og_image';
    case GoogleSiteVerification = 'google_site_verification';
    case YandexVerification = 'yandex_verification';
    case CustomJs = 'custom_js';

    public function label(): string
    {
        return match ($this) {
            self::MetaTitle => 'Meta sarlavha (title)',
            self::MetaDescription => 'Meta tavsif (description)',
            self::OgImage => 'Ijtimoiy tarmoq uchun rasm (og:image)',
            self::GoogleSiteVerification => 'Google Search Console tasdiqlash kodi',
            self::YandexVerification => 'Yandex Webmaster tasdiqlash kodi',
            self::CustomJs => 'Maxsus JS kodi',
        };
    }

    public function inputType(): SettingInputType
    {
        return match ($this) {
            self::MetaDescription, self::CustomJs => SettingInputType::Textarea,
            self::OgImage => SettingInputType::Image,
            default => SettingInputType::Text,
        };
    }

    /** Admin formasida maydonlarni guruhlash uchun (bo'sh joy/sarlavha shu bo'yicha chiziladi). */
    public function group(): string
    {
        return match ($this) {
            self::GoogleSiteVerification, self::YandexVerification => 'Qidiruv tizimlarini tasdiqlash',
            self::CustomJs => 'Maxsus JS kodi',
            default => 'Umumiy',
        };
    }

    /** Guruh sarlavhasi ostida bir marta chiqadigan tushuntirish matni. */
    public static function groupHint(string $group): ?string
    {
        return match ($group) {
            'Qidiruv tizimlarini tasdiqlash' => 'Google Search Console / Yandex Webmaster\'da saytni "HTML teg" usuli bilan tasdiqlashda beriladigan kod — faqat content="..." ichidagi qiymatni kiriting, butun teg emas.',
            'Maxsus JS kodi' => 'Google Analytics, Yandex Metrika va shunga o\'xshash kuzatuv kodlari — to\'liq <script>...</script> teg(lar)i bilan qo\'shing. Sahifa oxirida (</body>dan oldin) o\'zgarishsiz joylashtiriladi, shuning uchun faqat ishonchli manbadan olingan kodni kiriting.',
            default => null,
        };
    }

    public function placeholder(): ?string
    {
        return match ($this) {
            self::MetaTitle => 'MaktabGID — Farzandingizga mos maktabni toping',
            self::MetaDescription => 'Qidiruv natijalarida sahifa nomi ostida chiqadigan qisqa tavsif (150-160 belgi atrofida tavsiya etiladi).',
            self::GoogleSiteVerification => 'masalan: AbCdEfGhIjKlMnOpQrStUvWxYz1234567890',
            self::YandexVerification => 'masalan: 1a2b3c4d5e6f7g8h',
            self::CustomJs => "<script>\n  // Google Analytics, Yandex Metrika va h.k.\n</script>",
            default => null,
        };
    }

    /** Faqat SettingInputType::Image uchun — rasm tanlagichdagi yordamchi matn. */
    public function hint(): ?string
    {
        return match ($this) {
            self::OgImage => "Havola ijtimoiy tarmoqlarda (Telegram, Facebook) ulashilganda ko'rinadigan rasm. Tavsiya etilgan o'lcham: 1200×630.",
            default => null,
        };
    }

    /** Validatsiyada ruxsat etilgan maksimal uzunlik (faqat matn/textarea turlari uchun). */
    public function maxLength(): int
    {
        return match ($this) {
            self::MetaDescription => 500,
            self::CustomJs => 20000,
            default => 255,
        };
    }

    /** Jadvalda qator bo'lmaganda (yoki bo'sh saqlanganda) ishlatiladigan standart qiymat. */
    public function default(): ?string
    {
        return match ($this) {
            self::MetaTitle => 'MaktabGID — Farzandingizga mos maktabni toping',
            self::MetaDescription => "O'zbekistondagi xususiy maktablar, bog'chalar va o'quv markazlarini topish platformasi. Narx, masofa, taʼlim tili va sharhlar bo'yicha solishtiring.",
            default => null,
        };
    }
}
