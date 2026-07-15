<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "Videolar" bo'limi endi haqiqiy fayl yuklash bilan ishlaydi (2026-07-15) —
 * har bir video uchun sarlavha (`caption`) va davomiyligi (`duration`) allaqachon
 * bor edi, faqat qisqacha izoh (`sub`/description) uchun ustun yetishmayotgan edi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institution_media', function (Blueprint $table) {
            $table->text('description')->nullable()->after('duration');
        });
    }

    public function down(): void
    {
        Schema::table('institution_media', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
