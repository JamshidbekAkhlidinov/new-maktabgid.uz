<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ota-ona kabineti — "Farzandlarim" (AI Tanlovchi uchun farzand profillari).
 * Ilgari ParentCabinetController::context()dagi qattiq kodlangan
 * $mockChildren massivi shu jadvalga ko'chadi — real qo'shish/tahrirlash/
 * o'chirish endi mumkin bo'ladi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->unsignedTinyInteger('age');
            $table->string('gender'); // 'ogil' | 'qiz'
            $table->json('interests')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
