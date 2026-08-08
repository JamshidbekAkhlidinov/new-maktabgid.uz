@props(['school'])

@php use App\Support\MaktabgidData; @endphp

<aside class="detail-side">
    <div class="side-card">
        <div class="side-price">
            @if ($school['price'])
                <b>{{ MaktabgidData::formatPrice($school['price']) }}</b><span>{{ __('school.sum_per_month_side') }}</span>
            @else
                <b>{{ __('school.price_negotiable') }}</b>
            @endif
        </div>
        <a href="#joylashtirish" class="btn btn-primary side-cta"><x-maktabgid.icon name="edit" :width="18" :height="18" /> {{ __('school.enroll_title') }}</a>
        <button type="button" class="btn btn-outline side-cta" data-modal-open="excursion-modal"><x-maktabgid.icon name="ticket" :width="18" :height="18" /> {{ __('school.excursion_title') }}</button>
        <button type="button" class="btn btn-ghost side-cta js-start-chat-btn" data-institution-id="{{ $school['id'] }}"><x-maktabgid.icon name="chat" :width="18" :height="18" /> {{ __('school.start_chat') }}</button>
        <div class="side-divide"></div>
        <ul class="side-facts">
            <li><x-maktabgid.icon name="users" :width="16" :height="16" /> {{ $school['grades'] }}</li>
            <li><x-maktabgid.icon name="globe" :width="16" :height="16" /> {{ __('school.language_label', ['lang' => $school['lang']]) }}</li>
            <li><x-maktabgid.icon name="clock" :width="16" :height="16" /> {{ $school['sat'] ? __('school.hours_sat') : __('school.hours_weekdays') }}</li>
            <li><x-maktabgid.icon name="pin" :width="16" :height="16" /> {{ $school['district'] }}, {{ __('school.from_center', ['km' => $school['dist']]) }}</li>
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
