<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Muassasa profili ko'rishlari — /maktab/{id} sahifasi ochilganda bitta qator
        // qo'shiladi (ADR-0002, Faza 2: "Analitika" sahifasidagi "Jami ko'rishlar" va
        // haftalik dinamika endi shu jadvaldan hisoblanadi). Bir martalik hisoblash
        // (IP/sessiya bo'yicha dedupe) hozircha yo'q — forum_threads.view_count bilan
        // bir xil darajada sodda, keyinroq kengaytirilishi mumkin.
        Schema::create('institution_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('viewer_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['institution_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_views');
    }
};
