@php($institutionType = $institutionType ?? null)

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <x-admin.input name="key" label="Kalit (key)" :value="$institutionType?->key" placeholder="maktab" required />
    <x-admin.icon-picker name="icon" label="Ikonka" :value="$institutionType?->icon" required />
</div>

{{-- Uch tillilik (2026-08-08) — nomi har bir til uchun alohida --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
    <x-admin.input name="label_uz" label="Nomi (o'zbekcha)" :value="$institutionType?->getTranslation('label', 'uz')" required />
    <x-admin.input name="label_ru" label="Nomi (ruscha)" :value="$institutionType?->getTranslation('label', 'ru')" />
    <x-admin.input name="label_en" label="Nomi (inglizcha)" :value="$institutionType?->getTranslation('label', 'en')" />
</div>

{{-- Holat (2026-08-08) — faqat "faol" turlar bosh sahifadagi kategoriya qatorida ko'rinadi. --}}
<div class="mt-5">
    <x-admin.checkbox name="is_active" label="Faol (bosh sahifada ko'rinadi)" :checked="$institutionType ? (bool) $institutionType->is_active : true" />
</div>
