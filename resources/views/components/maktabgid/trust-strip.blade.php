@php
    $items = [
        ['i' => 'shield', 'h' => 'Tekshirilgan maʼlumot', 'p' => 'Har bir muassasa narxi va xizmatlari qoʻlda tasdiqlanadi.'],
        ['i' => 'map', 'h' => 'Xaritada qulay', 'p' => 'Uyga yaqin variantlarni masofaga qarab darhol koʻring.'],
        ['i' => 'star', 'h' => 'Haqiqiy sharhlar', 'p' => 'Ota-onalarning real baholari va tajribalari.'],
        ['i' => 'send', 'h' => 'Tez ariza', 'p' => 'Telegram bot orqali bir necha soniyada bogʻlaning.'],
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
