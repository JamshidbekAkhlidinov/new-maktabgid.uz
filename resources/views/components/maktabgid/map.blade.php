@props(['schools' => []])

@php use App\Support\MaktabgidData; @endphp

<div class="map-col">
    <div class="map-panel">
        <x-maktabgid.map-canvas />
        <div class="map-tag" id="js-map-tag"><span class="dot"></span> {{ count($schools) }} ta natija xaritada</div>
        <div class="map-controls">
            <button type="button" class="map-btn"><x-maktabgid.icon name="plus" :width="18" :height="18" /></button>
            <button type="button" class="map-btn"><x-maktabgid.icon name="minus" :width="18" :height="18" /></button>
            <button type="button" class="map-btn"><x-maktabgid.icon name="pin" :width="18" :height="18" /></button>
        </div>
        @foreach ($schools as $s)
            <div class="map-pin js-map-pin" data-id="{{ $s['id'] }}" data-cat="{{ $s['cat'] }}" style="left:{{ $s['x'] }}%;top:{{ $s['y'] }}%">
                <div class="bubble">{{ MaktabgidData::formatPrice($s['price']) }}</div>
                <div class="stem"></div>
            </div>
        @endforeach
    </div>
</div>
