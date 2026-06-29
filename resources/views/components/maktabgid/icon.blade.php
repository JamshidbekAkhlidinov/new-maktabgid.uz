@props([
    'name',
    'width' => 20,
    'height' => 20,
    'fill' => 'none',
    'stroke' => 'currentColor',
])

<svg
    {{ $attributes->merge(['width' => $width, 'height' => $height, 'viewBox' => '0 0 24 24', 'fill' => $fill, 'stroke' => $stroke, 'stroke-width' => 1.8, 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round']) }}
>
    @switch($name)
        @case('search')
            <circle cx="11" cy="11" r="7" /><path d="M21 21l-4.3-4.3" />
            @break
        @case('pin')
            <path d="M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11z" /><circle cx="12" cy="10" r="2.6" />
            @break
        @case('star')
            <path d="M12 3.5l2.5 5.2 5.7.8-4.1 4 1 5.7L12 16.9 6.9 19.2l1-5.7-4.1-4 5.7-.8z" />
            @break
        @case('heart')
            <path d="M12 20s-7-4.4-9.3-9C1.4 8 3 5 6 5c2 0 3.2 1.3 4 2.5C10.8 6.3 12 5 14 5c3 0 4.6 3 3.3 6-2.3 4.6-9.3 9-9.3 9z" />
            @break
        @case('sliders')
            <path d="M4 6h10" /><path d="M18 6h2" /><path d="M8 6a2 2 0 1 0 4 0 2 2 0 1 0-4 0" />
            <path d="M4 18h6" /><path d="M14 18h6" /><path d="M10 18a2 2 0 1 0 4 0 2 2 0 1 0-4 0" />
            <path d="M16 12h4" /><path d="M4 12h8" /><path d="M12 12a2 2 0 1 0 4 0 2 2 0 1 0-4 0" />
            @break
        @case('chevron')
            <path d="M6 9l6 6 6-6" />
            @break
        @case('chevronR')
            <path d="M9 6l6 6-6 6" />
            @break
        @case('close')
            <path d="M6 6l12 12" /><path d="M18 6L6 18" />
            @break
        @case('school')
            <path d="M3 10l9-5 9 5-9 5-9-5z" /><path d="M7 12.5V17c0 1 2.2 2.5 5 2.5s5-1.5 5-2.5v-4.5" /><path d="M21 10v5" />
            @break
        @case('book')
            <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H6.5A2.5 2.5 0 0 0 4 20.5z" /><path d="M4 20.5A2.5 2.5 0 0 1 6.5 18H20v3H6.5" />
            @break
        @case('teddy')
            <circle cx="12" cy="13" r="6" /><circle cx="6.5" cy="7.5" r="2" /><circle cx="17.5" cy="7.5" r="2" />
            <circle cx="10" cy="12" r="0.6" /><circle cx="14" cy="12" r="0.6" /><path d="M10.5 15.5c.8.7 2.2.7 3 0" />
            @break
        @case('clock')
            <circle cx="12" cy="12" r="8.5" /><path d="M12 7.5V12l3 1.8" />
            @break
        @case('globe')
            <circle cx="12" cy="12" r="8.5" /><path d="M3.5 12h17" /><path d="M12 3.5c2.5 2.4 2.5 14.6 0 17M12 3.5c-2.5 2.4-2.5 14.6 0 17" />
            @break
        @case('check')
            <path d="M5 12.5l4.5 4.5L19 7" />
            @break
        @case('arrowR')
            <path d="M5 12h14" /><path d="M13 6l6 6-6 6" />
            @break
        @case('grid')
            <rect x="4" y="4" width="7" height="7" rx="1.5" /><rect x="13" y="4" width="7" height="7" rx="1.5" />
            <rect x="4" y="13" width="7" height="7" rx="1.5" /><rect x="13" y="13" width="7" height="7" rx="1.5" />
            @break
        @case('map')
            <path d="M9 4L3 6.5v13L9 17l6 2.5 6-2.5v-13L15 6.5 9 4z" /><path d="M9 4v13" /><path d="M15 6.5v13" />
            @break
        @case('phone')
            <path d="M5 4h3l1.5 4-2 1.5a11 11 0 0 0 5 5l1.5-2 4 1.5v3a2 2 0 0 1-2 2A15 15 0 0 1 3 6a2 2 0 0 1 2-2z" />
            @break
        @case('send')
            <path d="M21 4L3 11l6 2.5L12 20l3-7 6-9z" /><path d="M9 13.5L21 4" />
            @break
        @case('users')
            <circle cx="9" cy="8" r="3.2" /><path d="M3.5 19a5.5 5.5 0 0 1 11 0" /><path d="M16 5.5a3 3 0 0 1 0 5.6" /><path d="M17.5 19a5.2 5.2 0 0 0-3-4.7" />
            @break
        @case('bag')
            <rect x="3.5" y="7.5" width="17" height="12" rx="2" /><path d="M8.5 7.5V6a3 3 0 0 1 6 0v1.5" />
            @break
        @case('cal')
            <rect x="3.5" y="5" width="17" height="16" rx="2" /><path d="M3.5 9.5h17" /><path d="M8 3v4" /><path d="M16 3v4" />
            @break
        @case('sparkle')
            <path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8z" />
            @break
        @case('plus')
            <path d="M12 5v14" /><path d="M5 12h14" />
            @break
        @case('minus')
            <path d="M5 12h14" />
            @break
        @case('shield')
            <path d="M12 3l7 2.5v5c0 4.6-3 8.4-7 9.5-4-1.1-7-4.9-7-9.5v-5L12 3z" /><path d="M9 12l2 2 4-4" />
            @break
    @endswitch
</svg>
