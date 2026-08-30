@php($advertisement = $advertisement ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <x-admin.input name="title" label="Sarlavha" :value="$advertisement?->title" placeholder="Najot Ta'lim — IT yo'nalishida №1" required />
    <x-admin.input name="initials" label="Avatar harflari (ixtiyoriy)" :value="$advertisement?->initials" placeholder="NT — bo'sh qoldirilsa sarlavhadan avtomatik olinadi" />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
    <x-admin.input name="badge" label="Ustki yorliq" :value="$advertisement?->badge" placeholder="TOP O'QUV MARKAZI" />
    <x-admin.input name="tag" label="Burchak yorlig'i" :value="$advertisement?->tag" placeholder="Yangi guruhlar" />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
    <x-admin.input name="rating" label="Reyting" :value="$advertisement?->rating" placeholder="4.8" />
    <x-admin.input name="cta_label" label="Tugma matni" :value="$advertisement?->cta_label" placeholder="Batafsil" />
</div>

<div class="mt-5">
    <x-admin.textarea name="description" label="Tavsif" :value="$advertisement?->description" rows="2" placeholder="Frontend, backend, dasturlash. Bitiruvchilarning 87% ish bilan ta'minlangan." />
</div>

<div class="mt-5">
    <x-admin.input name="link_url" label="Havola — bosilganda shu manzilga o'tadi (masalan muassasa profili)" :value="$advertisement?->link_url" placeholder="https://new-maktabgid.uz/muassasa-slug" />
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
