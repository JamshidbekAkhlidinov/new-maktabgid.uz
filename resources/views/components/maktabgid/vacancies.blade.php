@props(['vacancies' => []])

@php use App\Support\MaktabgidData; @endphp

<section class="section alt" id="vakansiyalar">
    <div class="wrap">
        <div class="sec-head">
            <div>
                <h2>Vakansiyalar</h2>
                <p>Taʼlim sohasidagi eng soʻnggi ish oʻrinlari</p>
            </div>
            <a class="more-link" href="#">Barchasi <x-maktabgid.icon name="arrowR" :width="17" :height="17" /></a>
        </div>
        <div class="vac-grid">
            @foreach ($vacancies as $v)
                <article class="vac-card">
                    <span class="vac-type">{{ $v['type'] }}</span>
                    <h3>{{ $v['title'] }}</h3>
                    <div class="vac-org"><span class="av">{{ MaktabgidData::monogram($v['org']) }}</span>{{ $v['org'] }}</div>
                    <div class="vac-foot">
                        <div class="vac-salary">{{ $v['salary'] }} <span>UZS</span></div>
                        <span class="vac-until"><x-maktabgid.icon name="cal" :width="14" :height="14" /> {{ $v['until'] }}</span>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
