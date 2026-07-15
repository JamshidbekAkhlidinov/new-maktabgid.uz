@php($specialization = $specialization ?? null)

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <x-admin.input name="key" label="Kalit (key)" :value="$specialization?->key" placeholder="ingliz_tili" required />
    <x-admin.input name="label" label="Nomi" :value="$specialization?->label" required />
</div>

<div class="mt-5">
    <x-admin.icon-picker name="icon" label="Ikonka" :value="$specialization?->icon" required />
</div>
