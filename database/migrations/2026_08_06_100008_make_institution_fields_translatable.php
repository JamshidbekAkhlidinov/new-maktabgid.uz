<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Uch tillilik (2026-08-06): institutions jadvalidagi asosiy matnli ustunlar endi
 * JSON {"uz":"...","ru":"...","en":"..."} sifatida saqlanadi (App\Support\Concerns\HasTranslatable
 * shu formatni o'qiydi). Eski qiymatlar avtomatik 'uz' kaliti ostiga o'raladi — hech qanday
 * ma'lumot yo'qolmaydi.
 */
return new class extends Migration
{
    private const FIELDS = ['name', 'about', 'address', 'work_hours', 'grades', 'refer_point', 'badge'];

    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            foreach (self::FIELDS as $field) {
                $table->json($field)->nullable()->change();
            }
        });

        // Eski (bitta tilli) qiymatlarni {"uz": "..."} ko'rinishiga o'rab qo'yamiz.
        DB::table('institutions')->select(['id', ...self::FIELDS])->orderBy('id')->chunkById(200, function ($rows) {
            foreach ($rows as $row) {
                $update = [];
                foreach (self::FIELDS as $field) {
                    $value = $row->{$field};
                    if ($value === null || $value === '') {
                        continue;
                    }
                    // Agar allaqachon JSON obyekt bo'lsa (masalan migratsiya qayta ishga tushirilsa), tegmaymiz.
                    $decoded = json_decode($value, true);
                    if (is_array($decoded)) {
                        continue;
                    }
                    $update[$field] = json_encode(['uz' => $value], JSON_UNESCAPED_UNICODE);
                }
                if ($update) {
                    DB::table('institutions')->where('id', $row->id)->update($update);
                }
            }
        });
    }

    public function down(): void
    {
        // JSON'dan qaytadan bitta tilga (uz ustuvor) tekislaymiz, keyin ustun turini qaytaramiz.
        DB::table('institutions')->select(['id', ...self::FIELDS])->orderBy('id')->chunkById(200, function ($rows) {
            foreach ($rows as $row) {
                $update = [];
                foreach (self::FIELDS as $field) {
                    $value = $row->{$field};
                    if ($value === null || $value === '') {
                        continue;
                    }
                    $decoded = json_decode($value, true);
                    if (! is_array($decoded)) {
                        continue;
                    }
                    $update[$field] = $decoded['uz'] ?? $decoded['ru'] ?? $decoded['en'] ?? array_values($decoded)[0] ?? null;
                }
                if ($update) {
                    DB::table('institutions')->where('id', $row->id)->update($update);
                }
            }
        });

        Schema::table('institutions', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->text('about')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('work_hours')->nullable()->change();
            $table->string('grades')->nullable()->change();
            $table->string('refer_point')->nullable()->change();
            $table->string('badge')->nullable()->change();
        });
    }
};
