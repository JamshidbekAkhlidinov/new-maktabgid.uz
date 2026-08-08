<?php

namespace Database\Seeders;

use App\Enums\SettingKey;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/** Har bir SettingKey uchun (standart qiymati bo'lsa) bitta qator — admin panelda keyin tahrirlanadi. */
class SettingSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SettingKey::cases() as $key) {
            if ($key->default() === null) {
                continue;
            }

            Setting::query()->firstOrCreate(['key' => $key->value], ['value' => $key->default()]);
        }
    }
}
