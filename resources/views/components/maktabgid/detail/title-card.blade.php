@props(['school', 'catLabel', 'stats'])

<div class="wrap">
    <div class="title-card">
        <div class="title-main">
            <div class="detail-tags">
                <span class="tag">{{ $catLabel }}</span>
                @if (!empty($school['badge']))
                    <span class="tag" style="background:var(--accent-soft);color:#b45309">{{ $school['badge'] }}</span>
                @endif
                <span class="tag lang">{{ $school['lang'] }}</span>
                @if ($school['sat'])
                    <span class="tag sat">Shanba ish</span>
                @endif
            </div>
            <h1>{{ $school['name'] }}</h1>
            <div class="detail-meta">
                <span><x-maktabgid.icon name="star" :width="16" :height="16" fill="currentColor" class="star" /> <b>{{ $school['rating'] }}</b> ({{ $school['reviews'] }} sharh)</span>
                <span><x-maktabgid.icon name="pin" :width="16" :height="16" /> {{ $school['district'] }} tumani</span>
                <span><x-maktabgid.icon name="map" :width="16" :height="16" /> markazdan {{ $school['dist'] }} km</span>
            </div>
        </div>
        <div class="title-stats">
            @foreach ($stats as $st)
                <div class="tstat"><b>{{ $st['v'] }}</b><span>{{ $st['k'] }}</span></div>
            @endforeach
        </div>
    </div>
</div>
