@props([
    'active' => 'dashboard',
    'title' => null,
    'sub' => null,
    'teacher' => null,
    'counts' => ['vacancies' => null, 'offers' => null, 'conversations' => null],
])

@php
    $title = $title ?? __('cabinet_teacher.nav_dashboard');
    $sub = $sub ?? __('cabinet_teacher.dashboard_sub');
@endphp

{{--
    Ustoz (o'qituvchi) kabineti qobig'i — x-institution.shell bilan bir xil vizual
    tildan (.idash-* klasslar, institution-dashboard.css) foydalanadi, shu bilan
    barcha kabinet turlari (muassasa/ustoz) bir xil ko'rinishga ega bo'ladi.

    "O'qituvchi" endi User::ROLE_TEACHER sifatida real mavjud (App\Models\User::isTeacher()) —
    TeacherCabinetController joriy foydalanuvchi shu rolda bo'lsagina $teacher massivini
    beradi, aks holda $teacher null bo'ladi va pastdagi @unless gate "kirish kerak" holatini
    ko'rsatadi (x-institution.shell / x-parent.shell bilan bir xil andoza). Rezyume/vakansiya/
    taklif ro'yxatlari va sonlari hali mock — real modellar keyingi bosqichda ulanadi.
--}}

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title }} — {{ __('cabinet_teacher.shell_title_suffix') }} — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/institution-dashboard.css') }}" />
</head>
<body>

@unless ($teacher)
    {{-- ===== NOT LOGGED IN / WRONG ROLE ===== --}}
    <div class="wrap" style="padding:80px 0;text-align:center">
        <div class="empty">
            <span class="empty-ico"><x-maktabgid.icon name="user" :width="28" :height="28" /></span>
            <p style="font-size:16px;font-weight:700;color:var(--ink)">{{ __('cabinet_teacher.shell_login_required_title') }}</p>
            <p>{{ __('cabinet_teacher.shell_login_required_text') }}</p>
            <button class="btn btn-primary" data-modal-open="auth-modal">
                <x-maktabgid.icon name="user" :width="17" :height="17" /> {{ __('cabinet_teacher.shell_login_btn') }}
            </button>
        </div>
    </div>

    <x-maktabgid.auth-modal />
@else
@php
    $navItems = [
        ['key' => 'dashboard', 'route' => 'teacher.cabinet', 'icon' => 'grid', 'label' => __('cabinet_teacher.nav_dashboard')],
        ['key' => 'resumes', 'route' => 'teacher.cabinet.resumes', 'icon' => 'book', 'label' => __('cabinet_teacher.nav_resumes')],
        ['key' => 'vacancies', 'route' => 'teacher.cabinet.vacancies', 'icon' => 'bag', 'label' => __('cabinet_teacher.nav_vacancies'), 'count' => $counts['vacancies'] ?? null],
        ['key' => 'offers', 'route' => 'teacher.cabinet.offers', 'icon' => 'mail', 'label' => __('cabinet_teacher.nav_offers'), 'count' => $counts['offers'] ?? null],
        ['key' => 'conversations', 'route' => 'teacher.cabinet.conversations', 'icon' => 'chat', 'label' => __('cabinet_teacher.nav_conversations'), 'count' => $counts['conversations'] ?? null],
    ];
    $teacherName = $teacher['name'] ?? __('cabinet_teacher.role_teacher');
@endphp

<div class="idash-shell">
    {{-- ===== SIDEBAR ===== --}}
    <aside class="idash-sidebar">
        <a class="idash-logo" href="{{ route('welcome') }}">
            <span class="logo-mark"><x-maktabgid.icon name="school" :width="18" :height="18" /></span>
            Maktab<b>GID</b>
        </a>

        <div class="idash-org-wrap">
            <div class="idash-org-btn" style="cursor:default">
                <span class="idash-org-mono">{{ \App\Support\MaktabgidData::monogram($teacherName) }}</span>
                <span class="idash-org-info">
                    <b>{{ $teacherName }}</b>
                    <span>{{ $teacher['role'] ?? __('cabinet_teacher.role_teacher') }}</span>
                </span>
            </div>
        </div>

        <nav class="idash-navgroup">
            <span class="idash-navlabel">{{ __('cabinet_teacher.navgroup_cabinet') }}</span>
            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}" class="idash-navlink{{ $active === $item['key'] ? ' on' : '' }}">
                    <x-maktabgid.icon :name="$item['icon']" :width="18" :height="18" />
                    {{ $item['label'] }}
                    @if (! empty($item['count']))
                        <em>{{ $item['count'] }}</em>
                    @endif
                </a>
            @endforeach
        </nav>

        <nav class="idash-navgroup">
            <span class="idash-navlabel">{{ __('cabinet_teacher.navgroup_account') }}</span>
            <a href="{{ route('teacher.cabinet.tariffs') }}" class="idash-navlink{{ $active === 'tariffs' ? ' on' : '' }}">
                <x-maktabgid.icon name="shield" :width="18" :height="18" />
                {{ __('cabinet_teacher.nav_tariffs') }}
            </a>
            <button type="button" class="idash-navlink danger" id="js-logout-btn">
                <x-maktabgid.icon name="logout" :width="18" :height="18" />
                {{ __('cabinet_teacher.logout') }}
            </button>
        </nav>

        <div class="idash-upsell">
            <span class="idash-upsell-tag">{{ __('cabinet_teacher.nav_resumes') }}</span>
            <b>{{ __('cabinet_teacher.upsell_title') }}</b>
            <p>{{ __('cabinet_teacher.upsell_text') }}</p>
            <a class="btn btn-primary sm" style="justify-content:center" href="{{ route('teacher.cabinet.resumes') }}">
                <x-maktabgid.icon name="sparkle" :width="15" :height="15" /> {{ __('cabinet_teacher.new_resume') }}
            </a>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="idash-main">
        <header class="idash-topbar">
            <div>
                <h1>{{ $title }}</h1>
                <div class="idash-topbar-sub">{{ $sub }}</div>
            </div>

            <label class="idash-search">
                <x-maktabgid.icon name="search" :width="16" :height="16" />
                <input type="text" placeholder="{{ __('cabinet_teacher.search_placeholder') }}" />
            </label>

            <button type="button" class="idash-iconbtn" title="{{ __('cabinet_teacher.notifications') }}">
                <x-maktabgid.icon name="bell" :width="18" :height="18" />
                <span class="idash-dot"></span>
            </button>

            <div class="idash-user-wrap">
                <button type="button" class="idash-user-btn" data-dd-toggle="idash-user-menu">
                    <span class="idash-user-ava">{{ \App\Support\MaktabgidData::monogram($teacherName) }}</span>
                    <span style="text-align:left">
                        <b>{{ explode(' ', trim($teacherName))[0] }}</b>
                        <span>{{ __('cabinet_teacher.role_teacher') }}</span>
                    </span>
                    <x-maktabgid.icon name="chevron" :width="14" :height="14" />
                </button>
                <div class="idash-dd idash-user-menu" id="idash-user-menu" data-dd-menu hidden>
                    <a href="{{ route('teacher.cabinet') }}" class="idash-org-item">
                        <x-maktabgid.icon name="user" :width="16" :height="16" /> {{ __('cabinet_teacher.my_profile') }}
                    </a>
                    <button type="button" class="idash-org-item js-logout-trigger" style="color:#d4504e">
                        <x-maktabgid.icon name="logout" :width="16" :height="16" /> {{ __('cabinet_teacher.logout') }}
                    </button>
                </div>
            </div>
        </header>

        <div class="idash-content">
            {{ $slot }}
        </div>
    </div>
</div>
@endunless

<script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>
