@php($specialization = $specialization ?? null)

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <x-admin.input name="key" label="Kalit (key)" :value="$specialization?->key" placeholder="ingliz_tili" required />
    <x-admin.icon-picker name="icon" label="Ikonka" :value="$specialization?->icon" required />
</div>

{{-- Uch tillilik (2026-08-06) — nomi har bir til uchun alohida --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
    <x-admin.input name="label_uz" label="Nomi (o'zbekcha)" :value="$specialization?->getTranslation('label', 'uz')" required />
    <x-admin.input name="label_ru" label="Nomi (ruscha)" :value="$specialization?->getTranslation('label', 'ru')" />
    <x-admin.input name="label_en" label="Nomi (inglizcha)" :value="$specialization?->getTranslation('label', 'en')" />
</div>
