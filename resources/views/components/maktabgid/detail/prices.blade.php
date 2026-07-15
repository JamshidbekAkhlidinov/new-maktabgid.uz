@props(['prices'])

@if (count($prices))
<section class="card-block">
    <h3><x-maktabgid.icon name="card" :width="19" :height="19" /> Narxlar</h3>
    <div class="price-list">
        @foreach ($prices as $p)
            <div class="price-list-row">
                <div class="price-list-main">
                    <b>{{ $p['grade'] }}</b>
                    @if ($p['lang'])
                        <span>{{ $p['lang'] }}</span>
                    @endif
                </div>
                <div class="price-list-amount">
                    <b>{{ \App\Support\MaktabgidData::formatPrice($p['price']) }}</b>
                    <span>so'm / oy</span>
                    @if ($p['discount'])
                        <em>{{ $p['discount'] }}</em>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif
