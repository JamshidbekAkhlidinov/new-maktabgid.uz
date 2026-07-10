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
        @case('chat')
            <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v9A1.5 1.5 0 0 1 18.5 16H9l-4 4v-4H5.5A1.5 1.5 0 0 1 4 14.5z" /><path d="M8 9h8" /><path d="M8 12h5" />
            @break
        @case('robot')
            <rect x="4" y="8" width="16" height="11" rx="3" /><path d="M12 8V4.5" /><circle cx="12" cy="3.5" r="1.2" />
            <circle cx="9" cy="13" r="1.1" fill="currentColor" stroke="none" /><circle cx="15" cy="13" r="1.1" fill="currentColor" stroke="none" />
            <path d="M2 12v3" /><path d="M22 12v3" />
            @break
        @case('news')
            <rect x="3.5" y="5" width="13" height="14" rx="1.5" /><path d="M16.5 9H20a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H6" />
            <path d="M6.5 8.5h6" /><path d="M6.5 12h6" /><path d="M6.5 15.5h4" />
            @break
        @case('forum')
            <path d="M3 6.5A1.5 1.5 0 0 1 4.5 5h10A1.5 1.5 0 0 1 16 6.5v5A1.5 1.5 0 0 1 14.5 13H8l-3 3v-3H4.5A1.5 1.5 0 0 1 3 11.5z" />
            <path d="M9 19h7.5l3 2.5v-2.5h.5a1.5 1.5 0 0 0 1.5-1.5V13" />
            @break
        @case('edit')
            <path d="M4 20h4l10-10-4-4L4 16z" /><path d="M13.5 6.5l4 4" />
            @break
        @case('mail')
            <rect x="3" y="5" width="18" height="14" rx="2" /><path d="M4 7l8 6 8-6" />
            @break
        @case('lock')
            <rect x="5" y="10" width="14" height="10" rx="2" /><path d="M8 10V7.5a4 4 0 0 1 8 0V10" />
            @break
        @case('user')
            <circle cx="12" cy="8" r="3.6" /><path d="M5 20a7 7 0 0 1 14 0" />
            @break
        @case('arrowL')
            <path d="M19 12H5" /><path d="M11 6l-6 6 6 6" />
            @break
        @case('building')
            <rect x="5" y="3.5" width="14" height="17" rx="1.5" /><path d="M9 8h2" /><path d="M13 8h2" /><path d="M9 12h2" /><path d="M13 12h2" /><path d="M10 20.5v-3.5h4v3.5" />
            @break
        @case('upload')
            <path d="M12 16V4" /><path d="M8 8l4-4 4 4" /><path d="M4 16v2.5A1.5 1.5 0 0 0 5.5 20h13a1.5 1.5 0 0 0 1.5-1.5V16" />
            @break
        @case('ticket')
            <path d="M4 7.5A1.5 1.5 0 0 1 5.5 6h13A1.5 1.5 0 0 1 20 7.5V10a2 2 0 0 0 0 4v2.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 16.5V14a2 2 0 0 0 0-4z" /><path d="M13 6v12" />
            @break
        @case('like')
            <path d="M7 11v8H4.5A1.5 1.5 0 0 1 3 17.5V12a1 1 0 0 1 1-1z" /><path d="M7 11l3.5-7A2 2 0 0 1 14 5.5V9h4.5a2 2 0 0 1 2 2.4l-1.3 6A2 2 0 0 1 17 19H7" />
            @break
        @case('logout')
            <path d="M9 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h3" /><path d="M15 8l4 4-4 4" /><path d="M19 12H9" />
            @break
        @case('eye')
            <path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z" /><circle cx="12" cy="12" r="2.6" />
            @break
        @case('award')
            <circle cx="12" cy="9" r="5" /><path d="M9 13.5L7.5 21l4.5-2.5L16.5 21 15 13.5" />
            @break
        @case('flask')
            <path d="M9 3h6" /><path d="M10 3v5.5L5.5 17a2 2 0 0 0 1.8 3h9.4a2 2 0 0 0 1.8-3L14 8.5V3" /><path d="M7.5 14h9" />
            @break
        @case('palette')
            <path d="M12 3a9 9 0 0 0 0 18c1.5 0 2-1 2-2 0-1.5 1-2 2.5-2H18a3 3 0 0 0 3-3c0-5-4-9-9-9z" />
            <circle cx="7.5" cy="11" r="1" fill="currentColor" stroke="none" /><circle cx="10" cy="7.5" r="1" fill="currentColor" stroke="none" /><circle cx="14.5" cy="7.5" r="1" fill="currentColor" stroke="none" />
            @break
        @case('code')
            <path d="M9 8l-4 4 4 4" /><path d="M15 8l4 4-4 4" />
            @break
        @case('music')
            <path d="M9 18V6l10-2v12" /><circle cx="6.5" cy="18" r="2.5" /><circle cx="16.5" cy="16" r="2.5" />
            @break
        @case('trophy')
            <path d="M7 4h10v4a5 5 0 0 1-10 0z" /><path d="M7 6H4v1.5A3.5 3.5 0 0 0 7 11" /><path d="M17 6h3v1.5A3.5 3.5 0 0 1 17 11" /><path d="M10 13h4l.5 4h-5z" /><path d="M8.5 20h7" />
            @break
        @case('dumbbell')
            <path d="M6.5 8v8" /><path d="M4 9.5v5" /><path d="M17.5 8v8" /><path d="M20 9.5v5" /><path d="M6.5 12h11" />
            @break
        @case('paperclip')
            <path d="M19 11l-7.5 7.5a4 4 0 0 1-5.7-5.7l8-8a2.6 2.6 0 0 1 3.7 3.7l-8 8a1.2 1.2 0 0 1-1.7-1.7l7-7" />
            @break
        @case('play')
            <path d="M8 5.5v13l11-6.5z" />
            @break
        @case('camera')
            <path d="M4 8.5A1.5 1.5 0 0 1 5.5 7h2L9 5h6l1.5 2h2A1.5 1.5 0 0 1 20 8.5v9A1.5 1.5 0 0 1 18.5 19h-13A1.5 1.5 0 0 1 4 17.5z" /><circle cx="12" cy="13" r="3.2" />
            @break
        @case('image')
            <rect x="4" y="5" width="16" height="14" rx="2" /><circle cx="9" cy="10" r="1.6" /><path d="M5 17l4.5-4 3 2.5L16 11l3 3.5" />
            @break
        @case('bus')
            <rect x="4" y="4.5" width="16" height="13" rx="2.5" /><path d="M4 11h16" /><path d="M4 8h16" /><circle cx="8" cy="17.5" r="1.6" /><circle cx="16" cy="17.5" r="1.6" /><path d="M7 20l1-2.5" /><path d="M17 20l-1-2.5" />
            @break
        @case('cup')
            <path d="M5 8h11v5a5 5 0 0 1-5 5H10a5 5 0 0 1-5-5z" /><path d="M16 9h2.5a2 2 0 0 1 0 4H16" /><path d="M7 3v2" /><path d="M10.5 3v2" /><path d="M14 3v2" />
            @break
        @case('cross')
            <rect x="4" y="4" width="16" height="16" rx="4" /><path d="M12 8.5v7" /><path d="M8.5 12h7" />
            @break
        @case('wifi')
            <path d="M3.5 9.5a13 13 0 0 1 17 0" /><path d="M6.5 13a8.5 8.5 0 0 1 11 0" /><path d="M9.5 16.3a4 4 0 0 1 5 0" /><circle cx="12" cy="19" r="1" fill="currentColor" stroke="none" />
            @break
        @case('leaf')
            <path d="M20 4c0 9-5.5 14-14 14 0-9 5.5-14 14-14z" /><path d="M9 15c3-3 6-4 9-4" />
            @break
        @case('target')
            <circle cx="12" cy="12" r="8.5" /><circle cx="12" cy="12" r="4.5" /><circle cx="12" cy="12" r="1" fill="currentColor" stroke="none" />
            @break
        @case('badge')
            <circle cx="12" cy="9" r="5.5" /><path d="M12 6.5l.9 1.8 2 .3-1.4 1.4.3 2-1.8-1-1.8 1 .3-2L9.1 8.6l2-.3z" /><path d="M8 14l-1 7 5-2.5L17 21l-1-7" />
            @break
        @case('trending')
            <path d="M3 17l6-6 4 4 8-8" /><path d="M15 7h6v6" />
            @break
        @case('card')
            <rect x="3" y="5.5" width="18" height="13" rx="2.2" /><path d="M3 10h18" /><path d="M7 14.5h4" />
            @break
        @case('bell')
            <path d="M6 10a6 6 0 0 1 12 0c0 4 1.5 5.5 2 6H4c.5-.5 2-2 2-6z" /><path d="M10 19a2 2 0 0 0 4 0" />
            @break
        @case('layers')
            <path d="M12 3.5l8.5 4.5-8.5 4.5-8.5-4.5z" /><path d="M3.5 13l8.5 4.5 8.5-4.5" /><path d="M3.5 17l8.5 4.5 8.5-4.5" />
            @break
        @case('download')
            <path d="M12 4v12" /><path d="M8 12l4 4 4-4" /><path d="M4 16v2.5A1.5 1.5 0 0 0 5.5 20h13a1.5 1.5 0 0 0 1.5-1.5V16" />
            @break
    @endswitch
</svg>
