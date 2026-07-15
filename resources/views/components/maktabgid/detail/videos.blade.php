@props(['videos'])

@if (count($videos))
<section class="card-block">
    <h3><x-maktabgid.icon name="play" :width="19" :height="19" /> Videolar</h3>
    <div class="video-grid">
        @foreach ($videos as $i => $v)
            <x-maktabgid.detail.video-card :title="$v['title']" :dur="$v['dur']" :sub="$v['sub']" :url="$v['url'] ?? null" :gi="$i * 3 + 1" />
        @endforeach
    </div>
</section>
@endif
