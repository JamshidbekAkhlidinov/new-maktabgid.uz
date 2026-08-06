<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reklama bannerlari — eski `advertisement` jadvalidan import qilinadi
 * (LegacyAdvertisementSeeder). Loyihada bu funksiya avval umuman yo'q edi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('legacy_id')->nullable()->unique();

            $table->string('image_url'); // ko'rsatiladigan manzil (tashqi havola yoki loyiha diski)
            $table->string('disk')->nullable(); // faqat haqiqiy fayl yuklanganda (MediaUploadService andozasi)
            $table->string('image_path')->nullable();

            $table->string('link_url')->nullable(); // bosilganda o'tiladigan manzil
            $table->boolean('is_active')->default(true);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
