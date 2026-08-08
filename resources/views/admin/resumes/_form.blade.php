@php($resume = $resume ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <x-admin.input name="full_name" label="To'liq ism" :value="$resume?->full_name" required />
    <x-admin.input name="role_title" label="Lavozim" :value="$resume?->role_title" required />
    <x-admin.input name="experience" label="Tajriba" :value="$resume?->experience" placeholder="5 yil" required />
    <x-admin.input name="salary_expectation" label="Kutilayotgan maosh" :value="$resume?->salary_expectation" />

    <x-admin.select name="specialization_key" label="Yo'nalish" placeholder="— Tanlanmagan —"
        :value="$resume?->specialization_key" :options="$specializations->pluck('label', 'key')" />

    <x-admin.select name="district_id" label="Tuman" placeholder="— Tanlanmagan —"
        :value="$resume?->district_id" :options="$districts->pluck('name', 'id')" />

    <x-admin.select name="owner_user_id" label="Egasi (foydalanuvchi)" placeholder="— Yo'q —"
        :value="$resume?->owner_user_id" :options="$users->pluck('name', 'id')" />

    <x-admin.input name="languages" label="Tillar" :value="$resume?->languages" placeholder="o'zbek, rus, ingliz" />

    <x-admin.input name="phone" label="Aloqa telefoni" :value="$resume?->phone" placeholder="+998 90 123 45 67" />

    <x-admin.input name="education" label="Ta'lim" :value="$resume?->education" placeholder="UzSWLU — 2016-yil" />
</div>

<div class="mt-5">
    <x-admin.textarea name="skills" label="Ko'nikmalar" :value="$resume?->skills" placeholder="IELTS 8.0, CELTA…" :rows="3" />
</div>

<div class="mt-5">
    <x-admin.textarea name="description" label="Qo'shimcha ma'lumot (tavsif)" :value="$resume?->description"
        placeholder="O'zi haqida, ish tajribasi tafsilotlari, sertifikatlar, tavsiyalar va boshqa har qanday qo'shimcha ma'lumot…" :rows="5" />
</div>
