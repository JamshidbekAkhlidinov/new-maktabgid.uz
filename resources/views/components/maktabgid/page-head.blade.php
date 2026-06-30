@props(['icon' => null, 'kicker', 'title', 'sub' => null, 'accent' => false])

<section {{ $attributes->merge(['class' => 'pagehead' . ($accent ? ' accent' : '')]) }}>
    <div class="pagehead-bg"></div>
    <div class="wrap pagehead-inner">
        <span class="eyebrow">
            @if ($icon)
                <x-maktabgid.icon :name="$icon" :width="15" :height="15" />
            @endif
            {{ $kicker }}
        </span>
        <h1>{{ $title }}</h1>
        @if ($sub)
            <p class="pagehead-sub">{{ $sub }}</p>
        @endif
        {{ $slot }}
    </div>
</section>
