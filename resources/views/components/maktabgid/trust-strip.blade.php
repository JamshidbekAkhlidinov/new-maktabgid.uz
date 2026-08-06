@php
    $items = [
        ['i' => 'shield', 'h' => __('home.trust_verified_h'), 'p' => __('home.trust_verified_p')],
        ['i' => 'map', 'h' => __('home.trust_map_h'), 'p' => __('home.trust_map_p')],
        ['i' => 'star', 'h' => __('home.trust_reviews_h'), 'p' => __('home.trust_reviews_p')],
        ['i' => 'send', 'h' => __('home.trust_fast_h'), 'p' => __('home.trust_fast_p')],
    ];
@endphp

<section class="section">
    <div class="wrap">
        <div class="trust">
            @foreach ($items as $it)
                <div class="trust-item">
                    <span class="trust-ico"><x-maktabgid.icon :name="$it['i']" :width="22" :height="22" /></span>
                    <div><h4>{{ $it['h'] }}</h4><p>{{ $it['p'] }}</p></div>
                </div>
            @endforeach
        </div>
    </div>
</section>
