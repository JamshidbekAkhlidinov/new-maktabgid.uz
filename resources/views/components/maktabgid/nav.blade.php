@props(['categories' => []])

<header class="nav">
    <div class="wrap nav-inner">
        <a class="logo" href="#top">
            <span class="logo-mark"><x-maktabgid.icon name="school" :width="22" :height="22" /></span>
            Maktab<b>GID</b>
        </a>
        <nav class="nav-links">
            @foreach ($categories as $c)
                <button type="button" class="nav-link js-cat{{ $loop->first ? ' on' : '' }}" data-cat="{{ $c['key'] }}">
                    {{ $c['short'] }}
                </button>
            @endforeach
            <a class="nav-link" href="#vakansiyalar">Vakansiyalar</a>
            <a class="nav-link" href="#blog">Blog</a>
        </nav>
        <div class="nav-right">
            <button class="lang" type="button">
                <svg width="16" height="11" viewBox="0 0 16 11"><rect width="16" height="11" rx="2" fill="#0099B5"/><rect y="4" width="16" height="3" fill="#fff"/><rect y="4.6" width="16" height="1.8" fill="#1EB53A"/><circle cx="3.2" cy="2.2" r="1.1" fill="#fff"/></svg>
                Oʻzbekcha
                <x-maktabgid.icon name="chevron" :width="14" :height="14" />
            </button>
            <a class="btn btn-tg" href="#"><x-maktabgid.icon name="send" :width="17" :height="17" /> Telegram bot</a>
        </div>
    </div>
</header>
