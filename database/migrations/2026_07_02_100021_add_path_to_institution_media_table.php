<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institution_media', function (Blueprint $table) {
            // Diskdagi ichki yo'l (o'chirish uchun) — 'url' esa ko'rsatiladigan ommaviy manzil
            // (mahalliy fayl bo'lsa ham, tashqi video link bo'lsa ham). Tashqi link uchun path=null.
            $table->string('path')->nullable()->after('url');
        });
    }

    public function down(): void
    {
        Schema::table('institution_media', function (Blueprint $table) {
            $table->dropColumn('path');
        });
    }
};
