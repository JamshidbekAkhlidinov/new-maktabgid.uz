@props(['media', 'photos' => []])

<div class="wrap">
    <div class="gallery">
        <x-maktabgid.detail.photo-tile big :icon="$media['gallery'][0]['icon']" :label="$media['gallery'][0]['label']" :gi="0" badge="Asosiy rasm" :url="$photos[0] ?? null" />
        <div class="gallery-grid">
            @foreach (array_slice($media['gallery'], 1, 4) as $i => $g)
                <x-maktabgid.detail.photo-tile :icon="$g['icon']" :label="$g['label']" :gi="$i + 1" :url="$photos[$i + 1] ?? null" />
            @endforeach
        </div>
    </div>
</div>
