<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Generik key-value sozlamalar jadvali (SEO va boshqa sayt darajasidagi
 * konfiglar). Har bir qator App\Enums\SettingKey'dagi bitta case'ga mos
 * keladi — yangi sozlama qo'shish uchun shu enumga case qo'shish yetarli,
 * bu jadvalga yangi ustun qo'shish shart emas.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
