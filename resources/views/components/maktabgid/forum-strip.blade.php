@props(['threads' => []])

@php use App\Support\MaktabgidData; @endphp

<section class="section forumstrip">
    <div class="wrap">
        <div class="section-head">
            <div>
                <span class="section-kicker"><span class="live-dot"></span> {{ __('home.forum_kicker') }}</span>
                <h2>{{ __('home.forum_title') }}</h2>
            </div>
            <a class="btn btn-ghost" href="{{ route('forum.index') }}">{{ __('home.forum_go') }} <x-maktabgid.icon name="arrowR" :width="16" :height="16" /></a>
        </div>
        <div class="fstrip-grid">
            @foreach (array_slice($threads, 0, 4) as $th)
                <a class="fstrip-card" href="{{ route('forum.show', $th['id']) }}">
                    <span class="fstrip-cat">{{ $th['cat'] }}</span>
                    <h3>{{ $th['title'] }}</h3>
                    <div class="fstrip-foot">
                        <span class="fstrip-author"><span class="fstrip-av">{{ MaktabgidData::monogram($th['author']) }}</span>{{ $th['author'] }}</span>
                        <span class="fstrip-meta"><x-maktabgid.icon name="forum" :width="14" :height="14" /> {{ $th['replies'] }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
