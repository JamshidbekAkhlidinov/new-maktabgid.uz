<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            // Muassasa kabinetidan kiritiladigan tafsilot bo'limlari — bo'sh bo'lsa
            // MaktabgidData namoyish uchun preset (mock) kontentga tushadi (backward-compat).
            $table->json('facilities')->nullable()->after('badge'); // tanlangan qulayliklar kalitlari
            $table->json('teachers')->nullable()->after('facilities'); // [{n, role, exp}]
            $table->json('programs')->nullable()->after('teachers'); // [{t, d}]
            $table->json('lessons')->nullable()->after('programs'); // [{label}]
            $table->json('videos')->nullable()->after('lessons'); // [{title, dur, sub}]
            $table->json('admission_steps')->nullable()->after('videos'); // [{t, d}]

            $table->string('stat_class_size')->nullable()->after('admission_steps');
            $table->string('stat_experience_years')->nullable()->after('stat_class_size');
            $table->string('stat_admission_rate')->nullable()->after('stat_experience_years');
            $table->string('stat_first_grade_seats')->nullable()->after('stat_admission_rate');
        });
    }

    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn([
                'facilities', 'teachers', 'programs', 'lessons', 'videos', 'admission_steps',
                'stat_class_size', 'stat_experience_years', 'stat_admission_rate', 'stat_first_grade_seats',
            ]);
        });
    }
};
