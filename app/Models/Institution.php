<?php

namespace App\Models;

use App\Support\Concerns\HasTranslatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Institution extends Model
{
    use HasFactory;
    use HasTranslatable;

    /**
     * Uch tillilik (2026-08-06) — bu ustunlar DB'da JSON {"uz":..,"ru":..,"en":..}
     * sifatida saqlanadi, `$institution->name` kabi oddiy o'qishda joriy tilga mos
     * matn avtomatik qaytariladi (App\Support\Concerns\HasTranslatable).
     */
    protected array $translatable = ['name', 'about', 'address', 'work_hours', 'grades', 'refer_point', 'badge'];

    /**
     * Ommaviy profil URL'i endi /{slug} (masalan /katta-tanaffus-uchtepa-1) — /maktab/{id}
     * emas (2026-08-06). route('maktabgid.school', $institution) shu orqali avtomatik
     * slug'dan foydalanadi (Illuminate\Routing\UrlGenerator — UrlRoutable qatnashuvchilar
     * uchun getRouteKey()ni chaqiradi, route qanday e'lon qilinganidan qat'i nazar).
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Har bir muassasa yaratilganda slug avtomatik to'ldiriladi (agar admin/forma orqali
     * qo'lda kiritilmagan bo'lsa) — getRouteKeyName() 'slug'ga tayanadi, shu sababli bu
     * ustun HECH QACHON bo'sh qolmasligi kerak (aks holda ommaviy profil havolasi buziladi).
     */
    protected static function booted(): void
    {
        static::creating(function (Institution $institution) {
            if (blank($institution->slug)) {
                $institution->slug = static::generateUniqueSlug($institution->name ?: 'muassasa');
            }
        });
    }

    protected static function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'muassasa';
        $slug = $base;
        $attempt = 1;

        while (static::where('slug', $slug)->exists()) {
            $attempt++;
            $slug = "{$base}-{$attempt}";
        }

        return $slug;
    }

    protected $fillable = [
        'owner_user_id', 'name', 'type', 'about', 'lang', 'district_id',
        'address', 'lat', 'lng', 'monthly_price', 'grades', 'work_hours',
        'works_saturday', 'work_schedule', 'accepting', 'rating', 'review_count', 'badge',
        'facilities', 'teachers', 'programs', 'lessons', 'videos', 'admission_steps',
        'stat_class_size', 'stat_experience_years', 'stat_admission_rate', 'stat_first_grade_seats',
        // Eski (Yii2) `telegram_object`dan import qilingan maydonlar (LegacyInstitutionSeeder).
        'legacy_id', 'phone_numbers', 'social_links', 'location_url', 'refer_point',
        'slug', 'is_active', 'legacy_view_count', 'sort_order',
    ];

    /**
     * 'sort_order' uchun standart qiymat 1000 (DB ustunidagi 0 emas) — admin
     * hali qo'lda tartib bermagan yangi muassasalar ro'yxat oxirida qolishi
     * uchun (2026-08-17). Faqat qo'lda kichik raqam (1, 2, 3...) berilganlar
     * yuqoriga chiqadi. `doctrine/dbal` o'rnatilmagani uchun DB ustunining
     * o'zi (`->change()`) o'zgartirilmaydi — standart qiymat shu yerda,
     * model darajasida beriladi.
     */
    protected $attributes = [
        'sort_order' => 1000,
    ];

    /** Haftaning ish vaqti jadvalidagi kun kalitlari, tartib bilan (Dushanbadan boshlab). */
    public const WORK_DAYS = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

    /**
     * Admin/kabinet formasidan kelgan xom "work_schedule" massivini har doim
     * barcha 7 kun uchun to'liq {on,hours} shaklga keltiradi (yo'q/noto'g'ri
     * kunlar standart qiymat bilan to'ldiriladi).
     *
     * @param  array<string, array{on?: bool, hours?: string}>  $raw
     * @return array<string, array{on: bool, hours: string}>
     */
    public static function normalizeWorkSchedule(array $raw): array
    {
        $schedule = [];

        foreach (self::WORK_DAYS as $day) {
            $row = $raw[$day] ?? [];
            $schedule[$day] = [
                'on' => (bool) ($row['on'] ?? false),
                'hours' => trim((string) ($row['hours'] ?? '')) ?: '09:00 – 18:00',
            ];
        }

        return $schedule;
    }

    protected function casts(): array
    {
        return [
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'works_saturday' => 'boolean',
            'work_schedule' => 'array',
            'accepting' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'rating' => 'decimal:1',
            'facilities' => 'array',
            'teachers' => 'array',
            'programs' => 'array',
            'lessons' => 'array',
            'videos' => 'array',
            'admission_steps' => 'array',
            'phone_numbers' => 'array',
            'social_links' => 'array',
            // Uch tillilik uchun JSON'ga o'girilgan ustunlar (2026-08-06):
            'name' => 'array',
            'about' => 'array',
            'address' => 'array',
            'work_hours' => 'array',
            'grades' => 'array',
            'refer_point' => 'array',
            'badge' => 'array',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function specializations(): BelongsToMany
    {
        return $this->belongsToMany(Specialization::class, 'institution_specialization');
    }

    public function media(): HasMany
    {
        return $this->hasMany(InstitutionMedia::class)->orderBy('sort_order');
    }

    /**
     * Admin panelda galereyadan "logo" qilib belgilangan rasm (InstitutionMedia.type='logo').
     * Bo'lmasa null — chaqiruvchi tomon galereyadagi birinchi rasmni standart sifatida
     * ishlatishi kerak (MaktabgidData::mapInstitution() shunday qiladi).
     */
    public function logoMedia(): ?InstitutionMedia
    {
        return $this->media->firstWhere('type', 'logo');
    }

    /** "Narxlar" — sinf/guruh + o'quv tili bo'yicha alohida narx-chegirma (2026-07-15). */
    public function prices(): HasMany
    {
        return $this->hasMany(InstitutionPrice::class)->orderBy('sort_order')->orderBy('id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class);
    }

    /** Real profil ko'rishlar jurnali — "Analitika" sahifasi shu yerdan hisoblanadi (ADR-0002, Faza 2). */
    public function views(): HasMany
    {
        return $this->hasMany(InstitutionView::class);
    }

    /** O'quvchilar yutuqlari — kabinetda qo'shiladi, ommaviy profilda ko'rinadi (ADR-0002, Faza 2). */
    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class)->latest();
    }
}
