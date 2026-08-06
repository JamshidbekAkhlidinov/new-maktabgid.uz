@php($advertisement = $advertisement ?? null)

<div class="mt-1">
    <x-admin.image-upload name="image" label="Banner rasmi" :value="$advertisement?->image_url" hint="JPG, PNG yoki WEBP, maksimal 4 MB." />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
    <x-admin.input name="link_url" label="Havola (bosilganda o'tiladi)" :value="$advertisement?->link_url" placeholder="https://..." />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
    <x-admin.input name="started_at" label="Boshlanish sanasi" type="datetime-local"
        :value="$advertisement?->started_at?->format('Y-m-d\TH:i')" />
    <x-admin.input name="finished_at" label="Tugash sanasi" type="datetime-local"
        :value="$advertisement?->finished_at?->format('Y-m-d\TH:i')" />
</div>

<div class="mt-5">
    <x-admin.checkbox name="is_active" label="Faol" :checked="$advertisement ? (bool) $advertisement->is_active : true" />
</div>
