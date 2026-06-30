@props([])

<svg {{ $attributes->merge(['class' => 'map-canvas']) }} viewBox="0 0 600 700" preserveAspectRatio="xMidYMid slice">
    <rect width="600" height="700" fill="#e6ebec" />
    <g fill="#eef2f2">
        <rect x="30" y="40" width="150" height="120" rx="6"/><rect x="210" y="30" width="180" height="100" rx="6"/>
        <rect x="420" y="50" width="150" height="130" rx="6"/><rect x="40" y="200" width="130" height="150" rx="6"/>
        <rect x="430" y="220" width="140" height="120" rx="6"/><rect x="60" y="400" width="160" height="120" rx="6"/>
        <rect x="250" y="430" width="150" height="140" rx="6"/><rect x="440" y="420" width="130" height="150" rx="6"/>
        <rect x="240" y="560" width="200" height="110" rx="6"/><rect x="40" y="560" width="160" height="110" rx="6"/>
    </g>
    <path d="M210 170 q70 -30 180 10 q40 60 -10 120 q-90 50 -180 -10 q-40 -70 10 -120z" fill="#d6e7d2" />
    <ellipse cx="500" cy="640" rx="80" ry="50" fill="#d6e7d2" />
    <path d="M-20 300 C 120 260, 200 380, 360 350 S 560 420, 640 380" stroke="#bcd6e6" stroke-width="16" fill="none" stroke-linecap="round" />
    <g stroke="#ffffff" stroke-width="9" stroke-linecap="round">
        <path d="M0 180 H600" /><path d="M0 370 H600" /><path d="M0 540 H600" />
        <path d="M195 0 V700" /><path d="M410 0 V700" />
    </g>
    <g stroke="#f4f7f7" stroke-width="4">
        <path d="M95 0 V700" /><path d="M300 0 V700" /><path d="M510 0 V700" />
        <path d="M0 90 H600" /><path d="M0 460 H600" /><path d="M0 630 H600" />
    </g>
</svg>
