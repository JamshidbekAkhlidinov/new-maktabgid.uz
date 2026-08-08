@props(['school', 'reviews', 'ratingBars'])

<section class="card-block">
    <div class="rev-summary">
        <div class="rev-score">
            <b>{{ $school['rating'] }}</b>
            <div class="rev-stars-lg">{{ str_repeat('★', (int) round($school['rating'])) }}<em>{{ str_repeat('★', 5 - (int) round($school['rating'])) }}</em></div>
            <span>{{ __('school.reviews_total', ['count' => $school['reviews']]) }}</span>
        </div>
        <div class="rev-bars">
            @foreach ($ratingBars as $rb)
                <div class="rev-bar-row"><span>{{ $rb['s'] }}★</span><span class="rev-bar"><i style="width:{{ $rb['p'] }}%"></i></span><em>{{ $rb['p'] }}%</em></div>
            @endforeach
        </div>
    </div>
    <div class="rev-list" style="margin-top:22px">
        @foreach ($reviews as $rv)
            <div class="rev">
                <x-maktabgid.avatar :name="$rv['n']" :size="42" />
                <div>
                    <div class="rev-top"><b>{{ $rv['n'] }}</b><span class="rev-stars">{{ str_repeat('★', $rv['r']) }}<em>{{ str_repeat('★', 5 - $rv['r']) }}</em></span><time>{{ $rv['ago'] }}</time></div>
                    <p>{{ $rv['t'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
