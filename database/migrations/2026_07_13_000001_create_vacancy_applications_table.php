<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Vakansiyaga ariza — `applications` (ota-ona/ekskursiya) bilan bir xil andozada:
     * mehmon ham yuborishi mumkin (`teacher_user_id` nullable), lekin ro'yxatdan
     * o'tgan ustoz yuborsa haqiqiy hisobiga bog'lanadi — shu orqali ustoz kabineti
     * "Takliflar" bo'limida o'z arizalarini ko'radi (ADR-0002, Faza 2).
     */
    public function up(): void
    {
        Schema::create('vacancy_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('full_name');
            $table->string('phone');
            $table->text('note')->nullable();
            $table->string('status')->default('pending'); // pending|accepted|rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancy_applications');
    }
};
