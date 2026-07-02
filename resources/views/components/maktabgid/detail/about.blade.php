@props(['school', 'catLabel'])

<section class="card-block">
    <h3><x-maktabgid.icon name="school" :width="19" :height="19" /> Muassasa haqida</h3>
    @if (!empty($school['about']))
        <p style="white-space:pre-line">{{ $school['about'] }}</p>
    @else
        <p>{{ $school['name'] }} — {{ $school['district'] }} tumanidagi zamonaviy {{ mb_strtolower($catLabel) }}. {{ $school['lang'] }} tilida taʼlim beradi va {{ $school['grades'] }} ni qamrab oladi. Tajribali ustozlar, qulay infratuzilma va bolaga individual yondashuv bilan ajralib turadi. Ota-onalarning oʻrtacha bahosi {{ $school['rating'] }} ball.</p>
    @endif
    <div class="spec-row" style="margin-top:16px">
        @foreach ($school['specs'] as $key)
            <x-maktabgid.spec-badge :spec-key="$key" />
        @endforeach
    </div>
</section>
