@props(['tabs', 'active'])

<div {{ $attributes->merge(['class' => 'segmented']) }}>
    @foreach ($tabs as $t)
        <a href="{{ $t['href'] }}" class="seg{{ $active === $t['key'] ? ' on' : '' }}">
            @if (!empty($t['icon']))
                <x-maktabgid.icon :name="$t['icon']" :width="16" :height="16" />
            @endif
            {{ $t['label'] }}
        </a>
    @endforeach
</div>
