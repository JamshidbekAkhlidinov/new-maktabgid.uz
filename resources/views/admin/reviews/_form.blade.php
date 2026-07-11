@php($review = $review ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <x-admin.select name="institution_id" label="Tashkilot" placeholder="— Tanlang —" required
        :value="$review?->institution_id" :options="$institutions->pluck('name', 'id')" />

    <x-admin.select name="user_id" label="Muallif" placeholder="— Tanlang —" required
        :value="$review?->user_id" :options="$users->pluck('name', 'id')" />

    <x-admin.select name="rating" label="Baho" required :value="$review?->rating" :options="[
        1 => '1 yulduz', 2 => '2 yulduz', 3 => '3 yulduz', 4 => '4 yulduz', 5 => '5 yulduz',
    ]" />
</div>

<div class="mt-5">
    <x-admin.textarea name="body" label="Sharh matni" :value="$review?->body" rows="4" required />
</div>
