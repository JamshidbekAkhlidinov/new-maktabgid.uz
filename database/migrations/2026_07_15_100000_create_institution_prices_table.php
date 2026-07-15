<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Muassasa kabineti — "Narxlar" (har sinf/guruh va o'quv tili uchun alohida
 * narx-chegirma). Avval bu bo'lim faqat vizual edi (yagona `institutions.
 * monthly_price` saqlanardi); endi har bir qator real saqlanadi va
 * `institutions.monthly_price` shular ichidan ENG KICHIGI bilan avtomatik
 * yangilanadi (katalog ro'yxati/filtri o'zgarishsiz "minimal narx"ni ko'rsatadi).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('grade'); // masalan "1-4-sinf (boshlang'ich)"
            $table->string('lang')->nullable(); // O'zbek/Rus/Ingliz
            $table->unsignedBigInteger('monthly_price');
            $table->string('discount')->nullable(); // erkin matn, masalan "10%" yoki "2-farzand uchun -15%"
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_prices');
    }
};
