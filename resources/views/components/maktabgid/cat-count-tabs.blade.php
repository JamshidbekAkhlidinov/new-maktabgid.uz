@props(['tabs' => [], 'extra' => null, 'active' => null])

<section class="section cat-strip">
    <div class="wrap">
        <div class="cat-tabs cat-tabs-count">
            @foreach ($tabs as $t)
                <button type="button"
                        class="cat-tab js-cat{{ $active === $t['key'] ? ' on' : '' }}"
                        data-cat="{{ $t['key'] }}">
                    <span class="ico"><x-maktabgid.icon :name="$t['icon']" :width="18" :height="18" /></span>
                    {{ $t['label'] }}
                    <span class="cat-tab-count">{{ $t['count'] }}</span>
                </button>
            @endforeach

            @if ($extra)
                {{-- Hozircha real maʼlumot bazasi yoʻq — faqat vizual moslik uchun (bosilmaydi) --}}
                <button type="button" class="cat-tab" disabled>
                    <span class="ico"><x-maktabgid.icon :name="$extra['icon']" :width="18" :height="18" /></span>
                    {{ $extra['label'] }}
                    <span class="cat-tab-count">{{ $extra['count'] }}</span>
                </button>
            @endif
        </div>
    </div>
</section>
