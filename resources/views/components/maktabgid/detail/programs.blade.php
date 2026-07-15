@props(['programs'])

@if (count($programs))
<section class="card-block">
    <h3><x-maktabgid.icon name="target" :width="19" :height="19" /> Yoʻnalishlar va dastur</h3>
    <div class="dir-grid">
        @php $grads = \App\Support\MaktabgidData::tileGradients(); @endphp
        @foreach ($programs as $i => $p)
            <div class="dir-card">
                <span class="dir-ico" style="background:linear-gradient(140deg, {{ $grads[$i % count($grads)][0] }}, {{ $grads[$i % count($grads)][1] }})">
                    <x-maktabgid.icon :name="$p['icon']" :width="22" :height="22" />
                </span>
                <div><b>{{ $p['t'] }}</b><span>{{ $p['d'] }}</span></div>
            </div>
        @endforeach
    </div>
</section>
@endif
