@props(['schools' => []])

@php
    use App\Support\MaktabgidData;

    $mapPoints = array_map(fn ($s) => [
        'id' => $s['id'],
        'cat' => $s['cat'],
        'lat' => $s['lat'],
        'lng' => $s['lng'],
        'price' => MaktabgidData::formatPrice($s['price']),
    ], $schools);
@endphp

<div class="map-col">
    <div class="map-panel">
        <div id="js-yandex-map" class="map-canvas" data-schools='@json($mapPoints)'></div>
        <div class="map-tag" id="js-map-tag"><span class="dot"></span> {{ __('home.map_results_count', ['count' => count($schools)]) }}</div>
        <div class="map-controls">
            <button type="button" class="map-btn" id="js-map-zoom-in" aria-label="{{ __('home.map_zoom_in') }}"><x-maktabgid.icon name="plus" :width="18" :height="18" /></button>
            <button type="button" class="map-btn" id="js-map-zoom-out" aria-label="{{ __('home.map_zoom_out') }}"><x-maktabgid.icon name="minus" :width="18" :height="18" /></button>
            <button type="button" class="map-btn" id="js-map-locate" aria-label="{{ __('home.map_locate') }}"><x-maktabgid.icon name="pin" :width="18" :height="18" /></button>
        </div>
    </div>
</div>
