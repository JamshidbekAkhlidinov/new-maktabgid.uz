@props(['priceBands' => [], 'distanceBands' => [], 'districts' => []])

<aside class="filters">
    <div class="filter-block">
        <div class="filter-head">
            <span>Filtrlar</span>
            <button type="button" class="reset" id="js-reset">Tozalash</button>
        </div>
        <div class="switch-row">
            <span style="font-size:13.5px;font-weight:600;color:var(--ink-2)">Shanba kuni ishlaydiganlar</span>
            <button type="button" class="switch" id="js-filter-sat" aria-label="Shanba"></button>
        </div>
    </div>

    <div class="filter-block">
        <div class="filter-head"><span>Masofa</span></div>
        <div class="chip-row" id="js-filter-distance">
            @foreach ($distanceBands as $b)
                <button type="button" class="chip" data-value="{{ $b['key'] }}">{{ $b['label'] }}</button>
            @endforeach
        </div>
    </div>

    <div class="filter-block">
        <div class="filter-head"><span>Oylik narx</span></div>
        <div class="chip-row" id="js-filter-price">
            @foreach ($priceBands as $b)
                <button type="button" class="chip" data-value="{{ $b['key'] }}">{{ $b['label'] }}</button>
            @endforeach
        </div>
    </div>

    <div class="filter-block">
        <div class="filter-head">
            <span>Tuman</span>
            <button type="button" class="reset" id="js-districts-clear" hidden>Bekor</button>
        </div>
        <div class="dist-list" id="js-filter-districts">
            @foreach ($districts as $d)
                <button type="button" class="dist-item" data-value="{{ $d }}">
                    <span class="tickbox"><x-maktabgid.icon name="check" :width="13" :height="13" /></span>
                    {{ $d }}
                </button>
            @endforeach
        </div>
    </div>
</aside>
