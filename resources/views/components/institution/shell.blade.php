@props([
    'active' => 'dashboard',
    'title' => null,
    'sub' => null,
    'institution' => null,
    'organizations' => [],
    'counts' => ['leads' => 0, 'excursions' => 0, 'conversations' => 0],
    'user' => null,
])

@php
    $title = $title ?? __('cabinet_institution.nav_dashboard');
    $sub = $sub ?? __('cabinet_institution.dashboard_sub');
@endphp

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title }} — {{ __('cabinet_institution.shell_title_suffix') }} — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/institution-dashboard.css') }}" />
</head>
<body>

@unless ($institution)
    {{-- ===== NOT LOGGED IN / WRONG ROLE ===== --}}
    <div class="wrap" style="padding:80px 0;text-align:center">
        <div class="empty">
            <span class="empty-ico"><x-maktabgid.icon name="building" :width="28" :height="28" /></span>
            <p style="font-size:16px;font-weight:700;color:var(--ink)">{{ __('cabinet_institution.shell_login_required_title') }}</p>
            <p>{{ __('cabinet_institution.shell_login_required_text') }}</p>
            <a class="btn btn-primary" href="{{ route('welcome') }}">
                <x-maktabgid.icon name="building" :width="17" :height="17" /> {{ __('cabinet_institution.back_to_home') }}
            </a>
        </div>
    </div>
@else
    @php
        $navItems = [
            'boshqaruv' => [
                ['key' => 'dashboard', 'route' => 'institution.cabinet', 'icon' => 'grid', 'label' => __('cabinet_institution.nav_dashboard')],
                ['key' => 'leads', 'route' => 'institution.cabinet.leads', 'icon' => 'users', 'label' => __('cabinet_institution.nav_leads'), 'count' => $counts['leads'] ?? null],
                ['key' => 'excursions', 'route' => 'institution.cabinet.excursions', 'icon' => 'ticket', 'label' => __('cabinet_institution.nav_excursions'), 'count' => $counts['excursions'] ?? null],
                ['key' => 'conversations', 'route' => 'institution.cabinet.conversations', 'icon' => 'chat', 'label' => __('cabinet_institution.nav_conversations'), 'count' => $counts['conversations'] ?? null],
                ['key' => 'analytics', 'route' => 'institution.cabinet.analytics', 'icon' => 'trending', 'label' => __('cabinet_institution.nav_analytics')],
            ],
            'kontent' => [
                ['key' => 'teachers', 'route' => 'institution.cabinet.teachers', 'icon' => 'user', 'label' => __('cabinet_institution.nav_teachers')],
                ['key' => 'achievements', 'route' => 'institution.cabinet.achievements', 'icon' => 'trophy', 'label' => __('cabinet_institution.nav_achievements')],
                ['key' => 'gallery', 'route' => 'institution.cabinet.gallery', 'icon' => 'image', 'label' => __('cabinet_institution.nav_gallery')],
                ['key' => 'vacancies', 'route' => 'institution.cabinet.vacancies', 'icon' => 'bag', 'label' => __('cabinet_institution.nav_vacancies'), 'count' => $counts['vacancies'] ?? null],
            ],
            'muassasa' => [
                ['key' => 'profile', 'route' => 'institution.cabinet.profile', 'icon' => 'building', 'label' => __('cabinet_institution.nav_profile')],
                ['key' => 'plans', 'route' => 'institution.cabinet.plans', 'icon' => 'card', 'label' => __('cabinet_institution.nav_plans')],
            ],
        ];
        $userDisplayName = $user?->name ?: $institution->name;
        // Tepadagi tashkilot tugmasi doim "hozir faol" muassasani ko'rsatishi kerak —
        // organizations[0] emas (ko'p filial bo'lsa almashtirilgach shu yerda bilinishi
        // uchun, 2026-07-15).
        $activeOrg = collect($organizations)->firstWhere('active', true) ?? ($organizations[0] ?? null);
    @endphp

    <div class="idash-shell">
        {{-- ===== SIDEBAR ===== --}}
        <aside class="idash-sidebar">
            <a class="idash-logo" href="{{ route('welcome') }}">
                <span class="logo-mark"><x-maktabgid.icon name="school" :width="18" :height="18" /></span>
                Maktab<b>GID</b>
            </a>

            {{-- ===== Tashkilot select (bir nechta filial uchun tayyor komponent) ===== --}}
            <div class="idash-org-wrap">
                <button type="button" class="idash-org-btn" data-dd-toggle="idash-org-menu">
                    <span class="idash-org-mono">{{ $activeOrg['mono'] ?? '—' }}</span>
                    <span class="idash-org-info">
                        <b>{{ $activeOrg['name'] ?? 'Tashkilot' }}</b>
                        <span>{{ $activeOrg['meta'] ?? '' }}</span>
                    </span>
                    <x-maktabgid.icon name="chevron" :width="16" :height="16" />
                </button>

                <div class="idash-dd" id="idash-org-menu" data-dd-menu hidden>
                    <div class="idash-dd-label">{{ __('cabinet_institution.organizations') }}</div>
                    @foreach ($organizations as $org)
                        <button type="button" class="idash-org-item js-org-switch" data-institution-id="{{ $org['id'] }}" @disabled($org['active'] ?? false)>
                            <span class="idash-org-mono">{{ $org['mono'] }}</span>
                            <span style="flex:1;min-width:0">
                                <b>{{ $org['name'] }}</b>
                                <span>{{ $org['meta'] }}</span>
                            </span>
                            @if ($org['active'] ?? false)
                                <span class="check"><x-maktabgid.icon name="check" :width="16" :height="16" /></span>
                            @endif
                        </button>
                    @endforeach
                    <div class="idash-dd-sep"></div>
                    <button type="button" class="idash-org-add" data-modal-open="idash-org-add-modal">
                        <span class="ico-wrap"><x-maktabgid.icon name="plus" :width="16" :height="16" /></span>
                        {{ __('cabinet_institution.add_new_organization') }}
                    </button>
                </div>
            </div>

            {{-- ===== Nav ===== --}}
            <nav class="idash-navgroup">
                <span class="idash-navlabel">{{ __('cabinet_institution.navgroup_management') }}</span>
                @foreach ($navItems['boshqaruv'] as $item)
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
                <span class="idash-navlabel">{{ __('cabinet_institution.navgroup_content') }}</span>
                @foreach ($navItems['kontent'] as $item)
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
                <span class="idash-navlabel">{{ __('cabinet_institution.navgroup_institution') }}</span>
                @foreach ($navItems['muassasa'] as $item)
                    <a href="{{ route($item['route']) }}" class="idash-navlink{{ $active === $item['key'] ? ' on' : '' }}">
                        <x-maktabgid.icon :name="$item['icon']" :width="18" :height="18" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
                <button type="button" class="idash-navlink danger js-logout-trigger">
                    <x-maktabgid.icon name="logout" :width="18" :height="18" />
                    {{ __('cabinet_institution.logout') }}
                </button>
            </nav>

            <div class="idash-upsell">
                <span class="idash-upsell-tag">{{ __('cabinet_institution.free_tag') }}</span>
                <b>{{ __('cabinet_institution.listing_limited') }}</b>
                <p>{{ __('cabinet_institution.listing_limited_text') }}</p>
                <a class="btn btn-primary sm" style="justify-content:center" href="{{ route('institution.cabinet.plans') }}">
                    <x-maktabgid.icon name="sparkle" :width="15" :height="15" /> {{ __('cabinet_institution.get_package') }}
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
                    <input type="text" placeholder="{{ __('cabinet_institution.search_placeholder') }}" />
                </label>

                <button type="button" class="idash-iconbtn" title="{{ __('cabinet_institution.notifications') }}">
                    <x-maktabgid.icon name="bell" :width="18" :height="18" />
                    <span class="idash-dot"></span>
                </button>

                <div class="idash-user-wrap">
                    <button type="button" class="idash-user-btn" data-dd-toggle="idash-user-menu">
                        <span class="idash-user-ava">{{ \App\Support\MaktabgidData::monogram($userDisplayName) }}</span>
                        <span style="text-align:left">
                            <b>{{ explode(' ', trim($userDisplayName))[0] }}</b>
                            <span>{{ __('cabinet_institution.role_advertiser') }}</span>
                        </span>
                        <x-maktabgid.icon name="chevron" :width="14" :height="14" />
                    </button>
                    <div class="idash-dd idash-user-menu" id="idash-user-menu" data-dd-menu hidden>
                        <a href="{{ route('institution.cabinet.profile') }}" class="idash-org-item">
                            <x-maktabgid.icon name="building" :width="16" :height="16" /> {{ __('cabinet_institution.nav_profile') }}
                        </a>
                        <button type="button" class="idash-org-item js-logout-trigger" style="color:#d4504e">
                            <x-maktabgid.icon name="logout" :width="16" :height="16" /> {{ __('cabinet_institution.logout') }}
                        </button>
                    </div>
                </div>
            </header>

            <div class="idash-content">
                {{ $slot }}
            </div>
        </div>
    </div>

    {{-- ===== "Yangi muassasa qo'shish" — real POST /ajax/institution/me/organizations
         (ko'p-filial qo'llab-quvvatlash, 2026-07-15). Qo'shilgan zahoti "faol" tashkilot
         bo'ladi va sahifa shu profilni ko'rsatadi (profilni to'ldirish uchun). ===== --}}
    <x-maktabgid.modal-shell id="idash-org-add-modal" :width="440">
        <div class="js-modal-body">
            <div class="modal-head">
                <h3>{{ __('cabinet_institution.add_new_institution_title') }}</h3>
                <p>{{ __('cabinet_institution.add_new_institution_text') }}</p>
            </div>
            <form class="form js-org-add-form">
                <div class="js-form-error" style="display:none;padding:10px 14px;background:#fdecec;color:#d4504e;border-radius:var(--r-md);font-size:13px;font-weight:700"></div>

                <x-maktabgid.field :label="__('cabinet_institution.field_institution_name')" icon="building">
                    <input type="text" name="name" required placeholder="Masalan, Yangi Avlod maktabi" />
                </x-maktabgid.field>
                <x-maktabgid.field :label="__('cabinet_institution.field_type')" icon="school">
                    <select name="type">
                        <option value="maktab">{{ __('cabinet_institution.kind_school') }}</option>
                        <option value="bogcha">{{ __('cabinet_institution.kind_kindergarten') }}</option>
                        <option value="markaz">{{ __('cabinet_institution.kind_center') }}</option>
                    </select>
                </x-maktabgid.field>
                <x-maktabgid.field :label="__('cabinet_institution.field_district')" icon="pin">
                    <select name="district">
                        @foreach (\App\Support\MaktabgidData::districts() as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </x-maktabgid.field>
                <button class="btn btn-primary form-submit" type="submit">
                    <x-maktabgid.icon name="plus" :width="16" :height="16" /> {{ __('cabinet_institution.add_and_fill_profile') }}
                </button>
                <p class="form-note"><x-maktabgid.icon name="shield" :width="15" :height="15" /> {{ __('cabinet_institution.draft_note') }}</p>
            </form>
        </div>
    </x-maktabgid.modal-shell>
@endunless

<script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>
