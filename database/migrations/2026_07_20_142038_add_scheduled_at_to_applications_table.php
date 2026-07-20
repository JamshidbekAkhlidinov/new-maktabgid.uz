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
        Schema::table('applications', function (Blueprint $table) {
            // Ekskursiya uchun ota-ona belgilagan kun/soat — muassasa kabinetida
            // jadval (schedule) tartibida ko'rsatish uchun (created_at emas).
            $table->dateTime('scheduled_at')->nullable()->after('preferred_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('scheduled_at');
        });
    }
};
