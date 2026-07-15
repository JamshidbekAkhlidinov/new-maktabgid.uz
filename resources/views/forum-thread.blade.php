<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $thread['title'] }} — Forum — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php use App\Support\MaktabgidData; @endphp

    <x-maktabgid.nav :categories="MaktabgidData::categories()" />

    <div class="forum">
        <x-maktabgid.page-head icon="forum" kicker="Ota-onalar forumi" title="Mavzu" />

        <div class="wrap forum-body">
            <div class="forum-thread">
                <x-maktabgid.back-link href="{{ route('forum.index') }}" label="Forumga qaytish" />

                <article class="thread-post">
                    <span class="thread-cat">{{ $thread['cat'] }}</span>
                    <h2>{{ $thread['title'] }}</h2>
                    <div class="thread-by">
                        <x-maktabgid.avatar :name="$thread['author']" :size="36" />
                        <div><b>{{ $thread['author'] }}</b><span>{{ $thread['ago'] }} · {{ $thread['views'] }} koʻrildi</span></div>
                    </div>
                    <p>{{ $thread['body'] }}</p>
                    <button type="button" class="reply-like js-forum-like" data-thread-id="{{ $thread['id'] }}">
                        <x-maktabgid.icon name="like" :width="15" :height="15" /> <span class="js-like-count">{{ $thread['likes'] }}</span>
                    </button>
                </article>

                <h3 class="reply-head">{{ count($replies) }} ta javob</h3>
                <div class="reply-list">
                    @foreach ($replies as $r)
                        <div class="reply">
                            <x-maktabgid.avatar :name="$r['author']" :size="38" />
                            <div class="reply-main">
                                <div class="reply-top"><b>{{ $r['author'] }}</b><time>{{ $r['ago'] }}</time></div>
                                <p>{{ $r['body'] }}</p>
                                <button type="button" class="reply-like js-forum-like" data-reply-id="{{ $r['id'] }}">
                                    <x-maktabgid.icon name="like" :width="15" :height="15" /> <span class="js-like-count">{{ $r['likes'] }}</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form class="reply-box js-reply-form" data-thread-id="{{ $thread['id'] }}">
                    <textarea name="body" rows="3" required placeholder="Javobingizni yozing…"></textarea>
                    <button class="btn btn-primary" type="submit"><x-maktabgid.icon name="send" :width="16" :height="16" /> Javob berish</button>
                </form>
            </div>
        </div>
    </div>

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    <script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>
