<?php

namespace App\Models;

use App\Enums\SettingKey;
use Illuminate\Database\Eloquent\Model;

/**
 * Generik key-value sozlama qatori. Kalitlar App\Enums\SettingKey orqali
 * qat'iy belgilanadi — yangi sozlama qo'shish uchun shu enumga case
 * qo'shish yetarli, bu modelga hech narsa o'zgartirish shart emas.
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /** So'rov davomida qayta so'rov yubormaslik uchun keshlangan key => value ro'yxati. */
    private static ?array $cached = null;

    /** @return array<string, string|null> */
    public static function values(): array
    {
        return self::$cached ??= static::query()->pluck('value', 'key')->all();
    }

    public static function get(SettingKey $key): ?string
    {
        return self::values()[$key->value] ?? $key->default();
    }

    public static function set(SettingKey $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key->value], ['value' => $value]);
        self::$cached = null;
    }
}
