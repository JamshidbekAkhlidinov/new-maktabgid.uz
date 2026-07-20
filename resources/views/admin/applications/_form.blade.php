@php($application = $application ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <x-admin.select name="institution_id" label="Tashkilot" placeholder="— Tanlang —" required
        :value="$application?->institution_id" :options="$institutions->pluck('name', 'id')" />

    <x-admin.select name="parent_user_id" label="Ota-ona (foydalanuvchi)" placeholder="— Mehmon —"
        :value="$application?->parent_user_id" :options="$parents->pluck('name', 'id')" />

    <x-admin.select name="type" label="Ariza turi" required :value="$application?->type" :options="[
        'excursion' => 'Ekskursiya',
        'enrollment' => 'Joylashtirish',
    ]" />

    <x-admin.select name="status" label="Status" required :value="$application?->status ?? 'pending'" :options="[
        'pending' => 'Kutilmoqda',
        'confirmed' => 'Tasdiqlangan',
        'completed' => 'Bo\'lib o\'tgan',
        'rejected' => 'Rad etilgan',
    ]" />

    <x-admin.input name="child_name" label="Bola ismi" :value="$application?->child_name" required />
    <x-admin.input name="child_birth_date" label="Bola tug'ilgan sanasi" type="date"
        :value="$application?->child_birth_date?->format('Y-m-d')" />
    <x-admin.input name="child_age" label="Bola yoshi" type="number" :value="$application?->child_age" />
    <x-admin.input name="current_grade" label="Hozirgi sinf" :value="$application?->current_grade" />
    <x-admin.input name="target_grade" label="Maqsad sinf" :value="$application?->target_grade" />
    <x-admin.input name="previous_school" label="Oldingi maktab" :value="$application?->previous_school" />

    <x-admin.input name="parent_name" label="Ota-ona ismi" :value="$application?->parent_name" required />
    <x-admin.input name="parent_phone" label="Ota-ona telefoni" :value="$application?->parent_phone" required />
    <x-admin.input name="preferred_start" label="Boshlanish sanasi" :value="$application?->preferred_start" />
    <x-admin.input name="scheduled_at" label="Ekskursiya kuni/soati" type="datetime-local"
        :value="$application?->scheduled_at?->format('Y-m-d\TH:i')" />
</div>

<div class="mt-5">
    <x-admin.textarea name="note" label="Izoh" :value="$application?->note" rows="3" />
</div>
