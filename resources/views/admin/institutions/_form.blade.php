@php($institution = $institution ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <x-admin.input name="name" label="Nomi" :value="$institution?->name" required />

    <x-admin.select name="type" label="Turi" :value="$institution?->type" required :options="[
        'maktab' => 'Maktab',
        'bogcha' => 'Bog\'cha',
        'markaz' => 'Markaz',
        'mutaxassis' => 'Mutaxassis',
    ]" />

    <x-admin.select name="owner_user_id" label="Egasi (foydalanuvchi)" placeholder="— Tanlanmagan —"
        :value="$institution?->owner_user_id" :options="$owners->pluck('name', 'id')" />

    <x-admin.select name="district_id" label="Tuman" placeholder="— Tanlanmagan —"
        :value="$institution?->district_id" :options="$districts->pluck('name', 'id')" />

    <x-admin.input name="address" label="Manzil" :value="$institution?->address" />
    <x-admin.input name="lang" label="Ta'lim tili" :value="$institution?->lang" />

    <x-admin.input name="lat" label="Kenglik (lat)" type="number" :value="$institution?->lat" step="0.0000001" />
    <x-admin.input name="lng" label="Uzunlik (lng)" type="number" :value="$institution?->lng" step="0.0000001" />

    <x-admin.input name="monthly_price" label="Oylik narx (so'm)" type="number" :value="$institution?->monthly_price" />
    <x-admin.input name="grades" label="Sinflar / yosh guruhlari" :value="$institution?->grades" />

    <x-admin.input name="work_hours" label="Ish vaqti" :value="$institution?->work_hours" placeholder="08:00–18:00" />
    <x-admin.input name="badge" label="Belgi (badge)" :value="$institution?->badge" />

    <x-admin.input name="rating" label="Reyting (0–5)" type="number" step="0.1" :value="$institution?->rating" />
    <x-admin.input name="review_count" label="Sharhlar soni" type="number" :value="$institution?->review_count" />
</div>

<div class="mt-5">
    <x-admin.textarea name="about" label="Tavsif" :value="$institution?->about" rows="4" />
</div>

<div class="mt-5 flex flex-wrap gap-6">
    <x-admin.checkbox name="works_saturday" label="Shanba kuni ishlaydi" :checked="(bool) $institution?->works_saturday" />
    <x-admin.checkbox name="accepting" label="Hozir qabul qilmoqda" :checked="$institution ? (bool) $institution->accepting : true" />
</div>

<div class="mt-5">
    <p class="block text-sm font-medium text-slate-700 mb-2">Yo'nalishlar (kategoriyalar)</p>
    <div class="flex flex-wrap gap-4 rounded-lg border border-slate-200 p-4">
        @forelse ($specializations as $spec)
            @php($isChecked = $institution?->specializations?->pluck('id')->contains($spec->id))
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="specializations[]" value="{{ $spec->id }}" @checked($isChecked)
                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                {{ $spec->label }}
            </label>
        @empty
            <p class="text-sm text-slate-500">Kategoriyalar hali qo'shilmagan.</p>
        @endforelse
    </div>
</div>
