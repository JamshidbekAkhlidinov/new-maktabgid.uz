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
            // Har bir hafta kuni uchun alohida ish vaqti — {"mon":{"on":true,"hours":"08:00–18:00"},...}.
            // 'work_hours' (uch tilda matn) va 'works_saturday' shundan avtomatik hisoblanadi
            // (ommaviy sahifalar/filtr hozircha shu ikkitasini ishlatishda davom etadi).
            $table->json('work_schedule')->nullable()->after('works_saturday');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn('work_schedule');
        });
    }
};
