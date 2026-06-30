@props(['specs'])

<section class="specstrip">
    <div class="wrap">
        <div class="specstrip-head">
            <h2>Ixtisoslik boʻyicha qidiring</h2>
            <p>Farzandingiz qiziqishi boʻyicha mos muassasani toping</p>
        </div>
        <div class="spec-tiles">
            @foreach ($specs as $sp)
                <button type="button"
                        class="spec-tile js-spec-tile"
                        data-spec="{{ $sp['key'] }}">
                    <span class="spec-tile-ico">
                        <x-maktabgid.icon :name="$sp['icon']" :width="22" :height="22" />
                    </span>
                    {{ $sp['label'] }}
                </button>
            @endforeach
        </div>
    </div>
</section>
