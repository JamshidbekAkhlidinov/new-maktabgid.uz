<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            // Admin panelidan qo'lda belgilanadigan tartib raqami — kichigi oldin
            // chiqadi, bosh sahifadagi "Tavsiya etiladi" saralashi ham shu bo'yicha.
            $table->unsignedInteger('sort_order')->default(0)->after('work_schedule')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
