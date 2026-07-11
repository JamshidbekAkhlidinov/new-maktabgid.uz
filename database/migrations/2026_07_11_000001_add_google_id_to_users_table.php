<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admin panelga Google orqali kirish (faqat Super Admin) uchun.
 * Foydalanuvchi bogʻlanishi email boʻyicha topiladi; google_id birinchi
 * muvaffaqiyatli kirishda saqlab qoʻyiladi (kelajakda tezroq qidirish uchun).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_id');
        });
    }
};
