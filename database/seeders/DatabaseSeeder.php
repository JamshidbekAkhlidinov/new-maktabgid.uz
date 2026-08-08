<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DistrictSeeder::class,
            SpecializationSeeder::class,
            InstitutionTypeSeeder::class,
            // PermissionSeeder — rollar (Super Admin/Institution Admin/Teacher/Parent)
            // UserSeeder'dan OLDIN ishga tushishi shart, chunki UserSeeder demo
            // foydalanuvchilarga shu rollarni syncRoles() bilan biriktiradi.
            PermissionSeeder::class,
            UserSeeder::class,
            AdminUserSeeder::class,

            // ---- Real ma'lumotlar — eski (Yii2) old_data_maktab.sql importi ----
            // (2026-08-06, backend/database/seeders/legacy_fixtures/*.json manba).
            // Mock InstitutionSeeder/ReviewSeeder/AchievementSeeder shu bilan almashtirildi
            // — Institution/Review endi shu 339 ta real muassasa/sharhga tegishli.
            LegacyAdminUserSeeder::class,
            LegacyInstitutionSeeder::class,
            LegacyReviewSeeder::class,

            ForumSeeder::class,

            // ContentSeeder endi faqat Article (blog) — News eski `post`dan real import
            // qilinadi (LegacyNewsSeeder), Article uchun legacy manba yo'q (demo qoladi).
            ContentSeeder::class,
            LegacyNewsSeeder::class,

            // CareerSeeder endi faqat Resume (demo) — Vacancy eski `vocations`dan real
            // import qilinadi (LegacyVacancySeeder), Resume uchun legacy manba yo'q.
            CareerSeeder::class,
            LegacyVacancySeeder::class,

            LegacyAdvertisementSeeder::class,

            // Sayt darajasidagi sozlamalar (meta description, og:image, JS kodlar va h.k.,
            // ro'yxati App\Enums\SettingKey'da) — admin panelda /admin/settings orqali
            // keyinchalik tahrirlanadi.
            SettingSeeder::class,
        ]);
    }
}
