<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Eski `telegram_object_photo` (1700+ qator) qayta seed qilinganda dublikat
 * yaratmasligi uchun tez qidiriladigan legacy_id (LegacyInstitutionSeeder).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institution_media', function (Blueprint $table) {
            $table->unsignedInteger('legacy_id')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('institution_media', function (Blueprint $table) {
            $table->dropColumn('legacy_id');
        });
    }
};
