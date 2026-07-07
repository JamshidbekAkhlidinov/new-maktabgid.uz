@php($vacancy = $vacancy ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <x-admin.input name="title" label="Lavozim nomi" :value="$vacancy?->title" required />
    <x-admin.input name="org_name" label="Tashkilot nomi (matn)" :value="$vacancy?->org_name" required />

    <x-admin.select name="institution_id" label="Bog'langan tashkilot" placeholder="— Yo'q —"
        :value="$vacancy?->institution_id" :options="$institutions->pluck('name', 'id')" />

    <x-admin.select name="posted_by_user_id" label="E'lon qiluvchi" placeholder="— Yo'q —"
        :value="$vacancy?->posted_by_user_id" :options="$users->pluck('name', 'id')" />

    <x-admin.input name="salary_range" label="Maosh oralig'i" :value="$vacancy?->salary_range" placeholder="3–5 mln so'm" />

    <x-admin.select name="employment_type" label="Bandlik turi" required :value="$vacancy?->employment_type" :options="[
        'full' => 'To\'liq stavka',
        'part' => 'Yarim stavka',
        'hourly' => 'Soatbay',
    ]" />

    <x-admin.select name="specialization_key" label="Yo'nalish" placeholder="— Tanlanmagan —"
        :value="$vacancy?->specialization_key" :options="$specializations->pluck('label', 'key')" />

    <x-admin.input name="expires_at" label="Amal qilish muddati" type="date"
        :value="$vacancy?->expires_at?->format('Y-m-d')" />
</div>
