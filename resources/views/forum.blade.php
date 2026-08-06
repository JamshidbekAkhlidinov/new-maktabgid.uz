<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ __('forum.page_title') }} — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php
        use App\Support\MaktabgidData;
        $cats = MaktabgidData::forumCategories();
        $filter = request('cat', 'Hammasi');
        $list = $filter === 'Hammasi' ? $threads : array_values(array_filter($threads, fn ($t) => $t['cat'] === $filter));
    @endphp

    <x-maktabgid.nav :categories="MaktabgidData::categories()" />

    <div class="forum">
        <x-maktabgid.page-head icon="forum" kicker="{{ __('forum.kicker') }}" title="{{ __('forum.title') }}" sub="{{ __('forum.sub') }}">
            <div class="phead-actions">
                <button class="btn btn-white" type="button" id="js-new-thread-btn">
                    <x-maktabgid.icon name="plus" :width="17" :height="17" /> {{ __('forum.new_thread') }}
                </button>
            </div>
        </x-maktabgid.page-head>

        <div class="wrap forum-body">
            <div class="forum-filters">
                @foreach ($cats as $c)
                    <a href="{{ route('forum.index', $c === 'Hammasi' ? [] : ['cat' => $c]) }}" class="chip{{ $filter === $c ? ' on' : '' }}">{{ $c === 'Hammasi' ? __('forum.all_categories') : $c }}</a>
                @endforeach
            </div>

            <div class="thread-list">
                @foreach ($list as $t)
                    <a href="{{ route('forum.show', $t['id']) }}" class="thread-li">
                        <div class="thread-li-main">
                            <span class="thread-cat">{{ $t['cat'] }}</span>
                            <h3>{{ $t['title'] }}</h3>
                            <div class="thread-li-meta"><x-maktabgid.avatar :name="$t['author']" :size="24" /> {{ $t['author'] }} · {{ $t['ago'] }}</div>
                        </div>
                        <div class="thread-li-stats">
                            <span><x-maktabgid.icon name="forum" :width="15" :height="15" /> {{ $t['replies'] }}</span>
                            <span><x-maktabgid.icon name="eye" :width="15" :height="15" /> {{ $t['views'] }}</span>
                            <span><x-maktabgid.icon name="like" :width="15" :height="15" /> {{ $t['likes'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    {{-- ===== MODAL: YANGI MAVZU (real POST /ajax/forum/threads, ADR-0002 Faza 2) ===== --}}
    <div class="modal-scrim js-modal" id="new-thread-modal" hidden>
        <div class="modal-card" style="max-width:560px;width:100%">
            <button class="modal-x js-modal-close" type="button" aria-label="{{ __('forum.close') }}">
                <x-maktabgid.icon name="close" :width="20" :height="20" />
            </button>

            <div class="modal-head">
                <h3>{{ __('forum.new_thread_modal_title') }}</h3>
                <p>{{ __('forum.new_thread_modal_sub') }}</p>
            </div>

            <form class="form js-thread-form" novalidate>
                <label class="field">
                    <span class="field-label">{{ __('forum.category_label') }}</span>
                    <span class="field-control">
                        <select name="category">
                            @foreach (array_diff($cats, ['Hammasi']) as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </span>
                </label>
                <label class="field">
                    <span class="field-label">{{ __('forum.title_label') }}</span>
                    <span class="field-control"><input name="title" required placeholder="{{ __('forum.title_placeholder') }}" /></span>
                </label>
                <label class="field">
                    <span class="field-label">{{ __('forum.body_label') }}</span>
                    <span class="field-control"><textarea name="body" rows="4" required placeholder="{{ __('forum.body_placeholder') }}"></textarea></span>
                </label>
                <button class="btn btn-primary form-submit" type="submit">
                    <x-maktabgid.icon name="send" :width="16" :height="16" /> {{ __('forum.publish_thread') }}
                </button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/maktabgid.js') }}"></script>

    <script>
    (function () {
        var isAuthed = @json(auth()->check());
        var btn = document.getElementById("js-new-thread-btn");
        if (!btn) return;

        btn.addEventListener("click", function () {
            if (!isAuthed) {
                var kirish = document.getElementById("js-kirish-btn");
                if (kirish) kirish.click();
                return;
            }
            var modal = document.getElementById("new-thread-modal");
            if (modal) {
                modal.hidden = false;
                document.body.classList.add("modal-open");
            }
        });
    }());
    </script>
</body>
</html>
