@php($user = $user ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <x-admin.input name="name" label="To'liq ism" :value="$user?->name" required />
    <x-admin.input name="phone" label="Telefon raqami" :value="$user?->phone" placeholder="+998901234567" required />
    <x-admin.input name="email" label="Email" type="email" :value="$user?->email" required />
    <x-admin.input name="password" :label="$user ? 'Yangi parol (ixtiyoriy)' : 'Parol'" type="password" :required="! $user" />

    <x-admin.select name="role" label="Asosiy rol (saytdagi)" :value="$user?->role" required :options="[
        'parent' => 'Ota-ona',
        'institution' => 'Muassasa',
        'teacher' => 'Ustoz',
        'admin' => 'Admin',
    ]" />

    <x-admin.select name="district_id" label="Tuman" :value="$user?->district_id" placeholder="— Tanlanmagan —"
        :options="$districts->pluck('name', 'id')" />

    <x-admin.input name="age" label="Yosh" type="number" :value="$user?->age" />

    <x-admin.select name="institution_id" label="Biriktirilgan tashkilot (muassasa egasi)" placeholder="— Yo'q —"
        :value="$user?->institution?->id" :options="$freeInstitutions->pluck('name', 'id')" />
</div>

<div class="mt-5">
    <p class="block text-sm font-medium text-slate-700 mb-2">Admin panel rollari (Spatie — dinamik huquqlar)</p>
    <div class="flex flex-wrap gap-4 rounded-lg border border-slate-200 p-4">
        @forelse ($roles as $role)
            @php($isChecked = $user?->roles?->pluck('name')->contains($role->name))
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="roles[]" value="{{ $role->name }}" @checked($isChecked)
                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                {{ $role->name }}
            </label>
        @empty
            <p class="text-sm text-slate-500">Hozircha rollar yaratilmagan. Avval "Rollar" bo'limidan rol yarating.</p>
        @endforelse
    </div>
</div>
