@php
    // Mock: to'lov/obuna tizimi hali ulanmagan (InstitutionCabinetController::planCatalog()
    // dagi kabi — billing qo'shilganda shu bo'lim real ma'lumotdan olinadi).
    $parentUsed = 3;
    $parentUsedPct = 60;
    $parentCompare = [
        ['feat' => 'Muassasa ko\'rish', 'free' => 'Oyiga 5 tagacha', 'prem' => 'Cheksiz'],
        ['feat' => 'AI Tanlovchi', 'free' => '3 ta savol/kun', 'prem' => 'Cheksiz'],
        ['feat' => 'Narx o\'zgarishi bildirishnomasi', 'free' => "Yo'q", 'prem' => 'Bor'],
        ['feat' => 'Ekskursiya arizasi', 'free' => 'Bor', 'prem' => 'Ustuvor navbat'],
        ['feat' => 'Reklama', 'free' => 'Bor', 'prem' => "Yo'q"],
    ];
@endphp

<x-parent.shell active="subscription" title="Obuna" sub="Bepul limit yoki Premium — cheksiz ko'rish" :user="$user" :stats="$stats">

    <div class="cab-sub-row">
        <div class="panel">
            <h3 style="margin-bottom:4px">Bepul va Premium taqqoslash</h3>
            <p style="font-size:13px;color:var(--ink-3);font-weight:600;margin-bottom:0">Bepul rejada bu oy: {{ $parentUsed }} muassasa ko'rildi</p>
            <div class="cab-sub-progress"><i style="width:{{ $parentUsedPct }}%"></i></div>
            <div class="cab-compare-head">
                <span>Imkoniyat</span><span>Bepul</span><span>Premium</span>
            </div>
            @foreach ($parentCompare as $c)
                <div class="cab-compare-row">
                    <span>{{ $c['feat'] }}</span><span>{{ $c['free'] }}</span><span>{{ $c['prem'] }}</span>
                </div>
            @endforeach
        </div>
        <div class="cab-premium">
            <span class="cab-premium-tag">Tavsiya etamiz</span>
            <span class="cab-premium-ico"><x-maktabgid.icon name="shield" :width="24" :height="24" /></span>
            <b class="name">Premium</b>
            <div class="cab-premium-price"><b>39 000</b><span>so'm / oy</span></div>
            <div class="cab-premium-feat">
                <span><x-maktabgid.icon name="check" :width="16" :height="16" /> Cheksiz muassasa ko'rish</span>
                <span><x-maktabgid.icon name="check" :width="16" :height="16" /> AI Tanlovchi — cheksiz</span>
                <span><x-maktabgid.icon name="check" :width="16" :height="16" /> Narx o'zgarishi bildirishnomasi</span>
                <span><x-maktabgid.icon name="check" :width="16" :height="16" /> Reklamasiz interfeys</span>
            </div>
            <button type="button" class="cab-premium-cta">Premium'ga o'tish</button>
            <span class="cab-premium-note">Payme &amp; Click · istagan vaqt bekor qilasiz</span>
        </div>
    </div>

    <div class="idash-badge-soft" style="margin-top:16px">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> To'lov tizimi tez orada ulanadi — hozircha demo ko'rinish
    </div>

</x-parent.shell>
