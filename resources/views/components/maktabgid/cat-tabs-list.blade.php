@props(['tabs' => [], 'active' => null, 'interactive' => true])

<div {{ $attributes->merge(['class' => 'cat-tabs cat-tabs-count']) }}>
    @foreach ($tabs as $t)
        <{{ $interactive ? 'button' : 'span' }}
                @if ($interactive) type="button" @endif
                class="cat-tab{{ $interactive ? ' js-cat' : '' }}{{ $active === $t['key'] ? ' on' : '' }}"
                data-cat="{{ $t['key'] }}"
        >
            <span class="ico"><x-maktabgid.icon :name="$t['icon']" :width="18" :height="18" /></span>
            {{ $t['label'] }}
            <span class="cat-tab-count">{{ $t['count'] }}</span>
        </{{ $interactive ? 'button' : 'span' }}>
    @endforeach
</div>
