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
        Schema::table('advertisements', function (Blueprint $table) {
            // Bosh sahifadagi (gradient fon, rasmsiz) reklama kartochkasi endi to'liq
            // admin panelidan boshqariladi (2026-08-30) — `image_url` bu dizaynda
            // ishlatilmaydi, shu ustunlar kartochka matnini tashkil qiladi.
            $table->string('badge')->nullable()->after('link_url');
            $table->string('tag')->nullable()->after('badge');
            $table->string('initials', 4)->nullable()->after('tag');
            $table->string('title')->nullable()->after('initials');
            $table->string('rating', 5)->nullable()->after('title');
            $table->text('description')->nullable()->after('rating');
            $table->string('cta_label')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn(['badge', 'tag', 'initials', 'title', 'rating', 'description', 'cta_label']);
        });
    }
};
