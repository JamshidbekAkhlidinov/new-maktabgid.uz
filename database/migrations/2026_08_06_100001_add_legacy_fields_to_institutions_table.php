<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Eski (Yii2) `telegram_object` jadvalidan import qilish uchun institutions'da
 * yetishmayotgan ustunlar (LegacyInstitutionSeeder, backend/old_data_maktab.sql).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            // Eski `telegram_object.id` — qayta import qilinganda dublikat yaratmaslik
            // uchun (Institution::updateOrCreate(['legacy_id' => ...])).
            $table->unsignedInteger('legacy_id')->nullable()->unique()->after('id');

            $table->json('phone_numbers')->nullable()->after('address'); // ["+998...", ...]
            $table->json('social_links')->nullable()->after('phone_numbers'); // {instagram, telegram, facebook, ...}
            $table->string('location_url', 1000)->nullable()->after('social_links'); // Yandex/Google Maps havolasi (uzun query-parametrli bo'lishi mumkin)
            $table->string('refer_point')->nullable()->after('location_url'); // mo'ljal ("Farxod bozori" kabi)
            $table->string('slug')->nullable()->unique()->after('refer_point');

            // Eski `status` (1/0) — muassasa umuman faolmi (accepting'dan farqli: accepting
            // "hozir qabul qiladimi", is_active esa "sahifa umuman ko'rsatiladimi").
            $table->boolean('is_active')->default(true)->after('accepting');

            // Eski `view_count` — real institution_views jurnali boshlanishidan oldingi
            // tarixiy hisoblagich (faqat ma'lumot uchun, analitika institution_views'dan hisoblanadi).
            $table->unsignedInteger('legacy_view_count')->default(0)->after('review_count');
        });
    }

    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn([
                'legacy_id', 'phone_numbers', 'social_links', 'location_url',
                'refer_point', 'slug', 'is_active', 'legacy_view_count',
            ]);
        });
    }
};
