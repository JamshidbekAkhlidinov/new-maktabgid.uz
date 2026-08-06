<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Uch tillilik (2026-08-06) — yo'nalish nomlari (masalan "Ingliz tili") ham 3 tilda. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('specializations', function (Blueprint $table) {
            $table->json('label')->nullable()->change();
        });

        DB::table('specializations')->select(['id', 'label'])->orderBy('id')->get()->each(function ($row) {
            if (! $row->label) {
                return;
            }
            $decoded = json_decode($row->label, true);
            if (is_array($decoded)) {
                return;
            }
            DB::table('specializations')->where('id', $row->id)->update([
                'label' => json_encode(['uz' => $row->label], JSON_UNESCAPED_UNICODE),
            ]);
        });
    }

    public function down(): void
    {
        DB::table('specializations')->select(['id', 'label'])->orderBy('id')->get()->each(function ($row) {
            if (! $row->label) {
                return;
            }
            $decoded = json_decode($row->label, true);
            if (! is_array($decoded)) {
                return;
            }
            DB::table('specializations')->where('id', $row->id)->update([
                'label' => $decoded['uz'] ?? $decoded['ru'] ?? $decoded['en'] ?? array_values($decoded)[0] ?? null,
            ]);
        });

        Schema::table('specializations', function (Blueprint $table) {
            $table->string('label')->nullable(false)->change();
        });
    }
};
