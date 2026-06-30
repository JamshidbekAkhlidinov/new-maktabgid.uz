@props(['facilities'])

<section class="card-block">
    <h3><x-maktabgid.icon name="grid" :width="19" :height="19" /> Infratuzilma va qulayliklar</h3>
    <div class="fac-grid">
        @foreach ($facilities as $ft)
            <div class="fac-item"><span class="fac-ico"><x-maktabgid.icon :name="$ft['i']" :width="18" :height="18" /></span>{{ $ft['t'] }}</div>
        @endforeach
    </div>
</section>
