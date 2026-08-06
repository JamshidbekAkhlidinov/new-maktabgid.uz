<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Eski `post` jadvalidan import qilish uchun (LegacyNewsSeeder). Eski yozuvlarda
 * rasm (`post.image`) bor edi, lekin `news` jadvalida hali rasm ustuni yo'q edi —
 * Article'dagi bilan bir xil andoza qo'shildi (disk/image_path/image_url).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->unsignedInteger('legacy_id')->nullable()->unique()->after('id');

            $table->string('disk')->nullable()->after('body');
            $table->string('image_path')->nullable()->after('disk');
            $table->string('image_url')->nullable()->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['legacy_id', 'disk', 'image_path', 'image_url']);
        });
    }
};
