@props(['photos' => []])

@if (count($photos))
    <div class="wrap detail-gallery-wrap">
        <div class="gallery">
            <x-maktabgid.detail.photo-tile big :gi="0" :url="$photos[0]" />

            @if (count($photos) > 1)
                <div class="gallery-grid">
                    @foreach (array_slice($photos, 1, 4) as $i => $url)
                        <x-maktabgid.detail.photo-tile :gi="$i + 1" :url="$url" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif
