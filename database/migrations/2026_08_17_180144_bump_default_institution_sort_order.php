<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 'sort_order' standart qiymati endi 0 emas, 1000 (Institution::$attributes,
     * doctrine/dbal o'rnatilmagani uchun DB ustunining o'zi o'zgartirilmaydi).
     * Hozircha 0 bo'lgan barcha yozuvlar — admin hali qo'lda tartib
     * bermagan "belgilanmagan" holat, shuning uchun 1000ga ko'chiriladi
     * (2026-08-17, foydalanuvchi so'rovi: "0 lar hisob bo'lmasin").
     */
    public function up(): void
    {
        DB::table('institutions')->where('sort_order', 0)->update(['sort_order' => 1000]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('institutions')->where('sort_order', 1000)->update(['sort_order' => 0]);
    }
};
