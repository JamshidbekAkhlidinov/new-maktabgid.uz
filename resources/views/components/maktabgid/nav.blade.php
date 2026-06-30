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

            {{-- Language dropdown --}}
            <div class="nav-acc" id="js-lang-wrap">
                <button class="lang" type="button" id="js-lang-btn">
                    <svg width="16" height="11" viewBox="0 0 16 11"><rect width="16" height="11" rx="2" fill="#0099B5"/><rect y="4" width="16" height="3" fill="#fff"/><rect y="4.6" width="16" height="1.8" fill="#1EB53A"/><circle cx="3.2" cy="2.2" r="1.1" fill="#fff"/></svg>
                    <span id="js-lang-label">O'zbekcha</span>
                    <x-maktabgid.icon name="chevron" :width="14" :height="14" />
                </button>
                <div class="acc-menu" id="js-lang-menu" style="display:none;min-width:160px">
                    <button type="button" data-lang="uz" data-label="O'zbekcha">
                        <svg width="16" height="11" viewBox="0 0 16 11"><rect width="16" height="11" rx="2" fill="#0099B5"/><rect y="4" width="16" height="3" fill="#fff"/><rect y="4.6" width="16" height="1.8" fill="#1EB53A"/><circle cx="3.2" cy="2.2" r="1.1" fill="#fff"/></svg>
                        O'zbekcha
                    </button>
                    <button type="button" data-lang="ru" data-label="Русский">
                        <svg width="16" height="11" viewBox="0 0 16 11"><rect width="16" height="11" rx="2" fill="#fff"/><rect y="3.67" width="16" height="3.67" fill="#003DA5"/><rect y="7.33" width="16" height="3.67" fill="#E4181C"/></svg>
                        Русский
                    </button>
                    <button type="button" data-lang="en" data-label="English">
                        <svg width="16" height="11" viewBox="0 0 16 11"><rect width="16" height="11" rx="2" fill="#012169"/><path d="M0 0l16 11M16 0L0 11" stroke="#fff" stroke-width="2.2"/><path d="M8 0v11M0 5.5h16" stroke="#fff" stroke-width="4"/><path d="M8 0v11M0 5.5h16" stroke="#C8102E" stroke-width="2.4"/></svg>
                        English
                    </button>
                </div>
            </div>

            {{-- Not logged in: Kirish button --}}
            <button class="btn btn-ghost" type="button" id="js-kirish-btn" data-modal-open="auth-modal">
                <x-maktabgid.icon name="user" :width="17" :height="17" /> Kirish
            </button>

            {{-- Logged in: user menu dropdown --}}
            <div class="nav-acc" id="js-user-nav" style="display:none">
                <button class="acc-btn" type="button" id="js-user-menu-btn">
                    <span class="acc-ava" id="js-nav-avatar"></span>
                    <span id="js-nav-name"></span>
                    <x-maktabgid.icon name="chevron" :width="14" :height="14" />
                </button>
                <div class="acc-menu" id="js-user-menu" style="display:none">
                    <button type="button" id="js-nav-cabinet">
                        <x-maktabgid.icon name="user" :width="16" :height="16" /> Kabinet
                    </button>
                    <button type="button" class="danger" id="js-nav-logout">
                        <x-maktabgid.icon name="logout" :width="16" :height="16" /> Chiqish
                    </button>
                </div>
            </div>

            <a class="btn btn-tg" href="#"><x-maktabgid.icon name="send" :width="17" :height="17" /> Telegram bot</a>
        </div>
    </div>
</header>

<x-maktabgid.auth-modal />
