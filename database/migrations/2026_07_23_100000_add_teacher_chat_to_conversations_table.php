<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ustoz ↔ muassasa suhbatini qo'llab-quvvatlash (ADR-0003; ADR-0002'da kechiktirilgan
 * bo'shliq). `parent_user_id` endi nullable (faqat teacher_user_id to'ldirilgan
 * suhbatlar uchun), yangi nullable `teacher_user_id` FK qo'shiladi. Ikkalasi ham
 * bir vaqtda bo'sh/to'la bo'lmasligi ilova darajasida (ConversationController)
 * tekshiriladi — DB darajasida faqat "kimdir bitta ustun bo'yicha institution bilan
 * takror suhbat ochmasin" degan ikkita alohida unique indeks bilan cheklanadi
 * (NULL qiymatlar unique indeksda bir-biriga to'qnashmaydi, shuning uchun ikkala
 * indeks parallel ishlay oladi).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->foreignId('parent_user_id')->nullable()->change();
            $table->foreignId('teacher_user_id')->nullable()->after('parent_user_id')->constrained('users')->nullOnDelete();
            $table->unique(['teacher_user_id', 'institution_id']);
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropUnique(['teacher_user_id', 'institution_id']);
            $table->dropConstrainedForeignId('teacher_user_id');
            $table->foreignId('parent_user_id')->nullable(false)->change();
        });
    }
};
