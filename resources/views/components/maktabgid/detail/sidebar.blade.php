@props(['school'])

@php use App\Support\MaktabgidData; @endphp

<aside class="detail-side">
    <div class="side-card">
        <div class="side-price">
            @if ($school['price'])
                <b>{{ MaktabgidData::formatPrice($school['price']) }}</b><span>soʻm / oy</span>
            @else
                <b>Narx kelishilgan</b>
            @endif
        </div>
        <a href="#joylashtirish" class="btn btn-primary side-cta"><x-maktabgid.icon name="edit" :width="18" :height="18" /> Oʻquvchini joylashtirish</a>
        <button type="button" class="btn btn-outline side-cta" data-modal-open="excursion-modal"><x-maktabgid.icon name="ticket" :width="18" :height="18" /> Ekskursiyaga yozilish</button>
        <button type="button" class="btn btn-ghost side-cta"><x-maktabgid.icon name="chat" :width="18" :height="18" /> Suhbat boshlash</button>
        <div class="side-divide"></div>
        <ul class="side-facts">
            <li><x-maktabgid.icon name="users" :width="16" :height="16" /> {{ $school['grades'] }}</li>
            <li><x-maktabgid.icon name="globe" :width="16" :height="16" /> {{ $school['lang'] }} tili</li>
            <li><x-maktabgid.icon name="clock" :width="16" :height="16" /> {{ $school['sat'] ? 'Du–Sh, 08:00–18:00' : 'Du–Ju, 08:00–18:00' }}</li>
            <li><x-maktabgid.icon name="pin" :width="16" :height="16" /> {{ $school['district'] }}, markazdan {{ $school['dist'] }} km</li>
            @if (!empty($school['address']))
                <li><x-maktabgid.icon name="pin" :width="16" :height="16" /> {{ $school['address'] }}</li>
            @endif
        </ul>
    </div>
    <div class="side-map">
        <div id="js-yandex-map-single" class="map-canvas"
             data-lat="{{ $school['lat'] }}" data-lng="{{ $school['lng'] }}" data-label="{{ $school['district'] }}"></div>
    </div>
</aside>
