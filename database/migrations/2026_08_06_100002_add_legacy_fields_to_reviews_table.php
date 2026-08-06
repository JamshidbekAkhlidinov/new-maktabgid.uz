<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Eski `telegram_object_comment`/`telegram_object_rate` yozuvlarini import qilish uchun.
 * Ular Telegram-bot foydalanuvchisidan (faqat ism, sayt akkaunti emas) kelgan — shu sababli
 * `user_id` endi ixtiyoriy, mehmon ismi `guest_name`da saqlanadi (ReviewObserver rating/
 * review_count'ni baribir har doim real reviews'dan qayta hisoblaydi, o'zgarishsiz).
 *
 * `body` ham ixtiyoriy qilindi: `telegram_object_rate`da faqat baho bor, matn yo'q — buni
 * sun'iy matn bilan to'ldirish o'rniga (backend.md: faqat real ma'lumot) bo'sh qoldiramiz,
 * Blade tomonida ko'rsatilganda oddiy tekshiruv yetarli.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->text('body')->nullable()->change();

            $table->string('guest_name')->nullable()->after('user_id');

            // Ikkala eski manba alohida ID ketma-ketligiga ega (comment.id va rate.id bir xil
            // raqam bo'lishi mumkin) — shu sababli bitta umumiy legacy_id emas, ikkita alohida
            // ustun (har biri o'z manbasida unique, qayta seed qilishda dublikat bo'lmasligi uchun).
            $table->unsignedInteger('legacy_comment_id')->nullable()->unique()->after('guest_name');
            $table->unsignedInteger('legacy_rate_id')->nullable()->unique()->after('legacy_comment_id');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['guest_name', 'legacy_comment_id', 'legacy_rate_id']);
            $table->text('body')->nullable(false)->change();
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
