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
            // Admin panelidan qo'lda beriladigan "Tasdiqlangan" holati — landing
            // sahifadagi muassasa kartochkasida success badge sifatida ko'rinadi.
            $table->boolean('is_verified')->default(false)->after('sort_order')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn('is_verified');
        });
    }
};
