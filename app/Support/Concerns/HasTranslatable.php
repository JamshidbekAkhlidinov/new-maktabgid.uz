<?php

namespace App\Support\Concerns;

/**
 * Yengil "tarjima qilinadigan ustun" mexanizmi (2026-08-06, uch tillilik).
 *
 * Nima uchun tayyor paket (masalan spatie/laravel-translatable) o'rniga o'zimiznikini
 * yozdik: bu sandboxda `composer require` ishlatib bo'lmaydi (PHP runtime yo'q),
 * shu sababli vendor'ga yangi paket qo'sha olmaymiz. Quyidagi trait aynan o'sha
 * paketning asosiy g'oyasini takrorlaydi — DB ustunida JSON `{"uz":"...","ru":"...","en":"..."}`
 * saqlanadi, lekin oddiy `$model->name` orqali o'qilganda joriy tilga mos matn
 * avtomatik qaytadi (fallback zanjiri: joriy til → uz → ru → en → bo'sh).
 *
 * Modelda ishlatish:
 *   use HasTranslatable;
 *   protected array $translatable = ['name', 'about'];
 *   protected function casts(): array { return ['name' => 'array', 'about' => 'array']; }
 *
 * Eski (bitta tilli) ma'lumotlar bilan orqaga moslik: agar ustunda oddiy string
 * saqlangan bo'lsa (JSON emas), u ham to'g'ri qaytariladi — hech narsa buzilmaydi.
 */
trait HasTranslatable
{
    /** Joriy tilga mos matnni qaytaradi ($model->name kabi oddiy xossaga kirilganda). */
    public function getAttributeValue($key)
    {
        $value = parent::getAttributeValue($key);

        if (! in_array($key, $this->translatable ?? [], true)) {
            return $value;
        }

        return $this->resolveTranslation($value);
    }

    protected function resolveTranslation(mixed $value): ?string
    {
        if (! is_array($value)) {
            // Eski bitta-tilli ma'lumot yoki oddiy null/string — o'zgarishsiz.
            return $value;
        }

        $locale = app()->getLocale();

        if (! empty($value[$locale])) {
            return $value[$locale];
        }

        foreach (['uz', 'ru', 'en'] as $fallback) {
            if (! empty($value[$fallback])) {
                return $value[$fallback];
            }
        }

        return null;
    }

    /** Ushbu modelda tarjima qilinadigan ustunlar ro'yxati (modeldan tashqarida ham foydalanish uchun). */
    public function getTranslatable(): array
    {
        return $this->translatable ?? [];
    }

    /** Berilgan ustunning barcha tillardagi variantlarini xom holda qaytaradi (admin forma uchun). */
    public function getTranslations(string $field): array
    {
        $raw = $this->attributes[$field] ?? null;
        $decoded = is_string($raw) ? json_decode($raw, true) : $raw;

        if (is_array($decoded)) {
            return $decoded;
        }

        return $decoded !== null ? ['uz' => $decoded] : [];
    }

    /** Bitta tildagi xom qiymatni qaytaradi (fallback'siz — admin formada bo'sh maydonlar bo'sh ko'rinishi kerak). */
    public function getTranslation(string $field, string $locale): ?string
    {
        return $this->getTranslations($field)[$locale] ?? null;
    }

    /** Bitta ustunning barcha tillarini bir vaqtda o'rnatadi: ['uz' => '...', 'ru' => '...', 'en' => '...']. */
    public function setTranslations(string $field, array $translations): static
    {
        $clean = array_filter($translations, fn ($v) => $v !== null && $v !== '');
        $this->attributes[$field] = json_encode((object) $clean, JSON_UNESCAPED_UNICODE);

        return $this;
    }
}
