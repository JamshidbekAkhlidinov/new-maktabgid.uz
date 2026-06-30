@props(['categories' => []])

@php
    $links = [
        ['route' => 'welcome',      'label' => 'Katalog'],
        ['route' => 'forum.index',  'label' => 'Forum'],
        ['route' => 'blog.index',   'label' => 'Blog'],
        ['route' => 'news.index',   'label' => 'Yangiliklar'],
        ['route' => 'careers.index','label' => 'Vakansiyalar'],
    ];
@endphp

<header class="nav">
    <div class="wrap nav-inner">
        <a class="logo" href="{{ route('welcome') }}">
            <span class="logo-mark"><x-maktabgid.icon name="school" :width="22" :height="22" /></span>
            Maktab<b>GID</b>
        </a>

        <nav class="nav-links">
            @foreach ($links as $l)
                <a href="{{ route($l['route']) }}" class="nav-link{{ request()->routeIs($l['route']) ? ' on' : '' }}">
                    {{ $l['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="nav-right">
            <button class="lang" type="button">
                <svg width="16" height="11" viewBox="0 0 16 11"><rect width="16" height="11" rx="2" fill="#0099B5"/><rect y="4" width="16" height="3" fill="#fff"/><rect y="4.6" width="16" height="1.8" fill="#1EB53A"/><circle cx="3.2" cy="2.2" r="1.1" fill="#fff"/></svg>
                Oʻzbekcha
                <x-maktabgid.icon name="chevron" :width="14" :height="14" />
            </button>
            <button class="btn btn-ghost" type="button"><x-maktabgid.icon name="user" :width="17" :height="17" /> Kirish</button>
            <a class="btn btn-tg" href="#"><x-maktabgid.icon name="send" :width="17" :height="17" /> Telegram bot</a>
        </div>
    </div>
</header>
