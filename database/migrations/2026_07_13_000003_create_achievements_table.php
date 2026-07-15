<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // "O'quvchilar yutuqlari" — muassasa kabinetida qo'shiladi, ota-onalar uchun
        // ishonch belgisi sifatida ommaviy profil sahifasida ham ko'rinadi (ADR-0002,
        // Faza 2). Sertifikat/rasm ixtiyoriy — institution_media'dan alohida saqlanadi,
        // chunki bu yozuv InstitutionMedia'ning "gallery/lesson/video" turkumlariga
        // tegishli emas.
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('student_name')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('type')->nullable(); // masalan: "Olimpiada", "Xalqaro imtihon"
            $table->string('level')->default('city'); // intl|national|regional|city
            $table->string('disk')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
