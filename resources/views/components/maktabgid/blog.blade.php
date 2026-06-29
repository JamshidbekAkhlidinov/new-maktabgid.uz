@props(['posts' => []])

<section class="section" id="blog">
    <div class="wrap">
        <div class="sec-head">
            <div>
                <h2>Blog va yangiliklar</h2>
                <p>Taʼlim, qabul va ota-onalar uchun foydali maslahatlar</p>
            </div>
            <a class="more-link" href="#">Barchasi <x-maktabgid.icon name="arrowR" :width="17" :height="17" /></a>
        </div>
        <div class="blog-grid">
            @foreach ($posts as $b)
                <article class="blog-card">
                    <div class="blog-media" style="background: linear-gradient(140deg, {{ $b['g'][0] }}, {{ $b['g'][1] }})">
                        <span class="blog-tag">{{ $b['tag'] }}</span>
                    </div>
                    <div class="blog-body">
                        <h3>{{ $b['title'] }}</h3>
                        <p>{{ $b['excerpt'] }}</p>
                        <span class="blog-date">{{ $b['date'] }}</span>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
