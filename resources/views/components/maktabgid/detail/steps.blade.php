@props(['steps'])

@if (count($steps))
<section class="card-block">
    <h3><x-maktabgid.icon name="badge" :width="19" :height="19" /> Qabul bosqichlari</h3>
    <ol class="steps">
        @foreach ($steps as $i => $st)
            <li><span class="step-n">{{ $i + 1 }}</span><div><b>{{ $st['t'] }}</b><span>{{ $st['d'] }}</span></div></li>
        @endforeach
    </ol>
</section>
@endif
