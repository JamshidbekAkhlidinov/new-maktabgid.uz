@props([
    'active' => 'dashboard',
    'title' => 'Boshqaruv paneli',
    'sub' => "E'loningiz holati bir qarashda",
    'institution' => null,
    'organizations' => [],
    'counts' => ['leads' => 0, 'excursions' => 0, 'conversations' => 0],
    'user' => null,
])

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title }} — Muassasa kabineti — {{ config('app.name', 'MaktabGID') }}</title>

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
            <p style="font-size:16px;font-weight:700;color:var(--ink)">Muassasa kabinetiga kirish kerak</p>
            <p>Muassasa sifatida ro'yxatdan o'ting yoki tizimga kiring.</p>
            <a class="btn btn-primary" href="{{ route('welcome') }}">
                <x-maktabgid.icon name="building" :width="17" :height="17" /> Bosh sahifaga qaytish
            </a>
        </div>
    </div>
@else
    @php
        $navItems = [
            'boshqaruv' => [
                ['key' => 'dashboard', 'route' => 'institution.cabinet', 'icon' => 'grid', 'label' => 'Boshqaruv paneli'],
                ['key' => 'leads', 'route' => 'institution.cabinet.leads', 'icon' => 'users', 'label' => 'Lidlar', 'count' => $counts['leads'] ?? null],
                ['key' => 'excursions', 'route' => 'institution.cabinet.excursions', 'icon' => 'ticket', 'label' => 'Ekskursiyalar', 'count' => $counts['excursions'] ?? null],
                ['key' => 'conversations', 'route' => 'institution.cabinet.conversations', 'icon' => 'chat', 'label' => 'Suhbatlar', 'count' => $counts['conversations'] ?? null],
                ['key' => 'analytics', 'route' => 'institution.cabinet.analytics', 'icon' => 'trending', 'label' => 'Analitika'],
            ],
            'muassasa' => [
                ['key' => 'profile', 'route' => 'institution.cabinet.profile', 'icon' => 'building', 'label' => 'Muassasa profili'],
                ['key' => 'plans', 'route' => 'institution.cabinet.plans', 'icon' => 'card', 'label' => 'Tariflar va obuna'],
            ],
        ];
        $userDisplayName = $user?->name ?: $institution->name;
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
                    <span class="idash-org-mono">{{ $organizations[0]['mono'] ?? '—' }}</span>
                    <span class="idash-org-info">
                        <b>{{ $organizations[0]['name'] ?? 'Tashkilot' }}</b>
                        <span>{{ $organizations[0]['meta'] ?? '' }}</span>
                    </span>
                    <x-maktabgid.icon name="chevron" :width="16" :height="16" />
                </button>

                <div class="idash-dd" id="idash-org-menu" data-dd-menu hidden>
                    <div class="idash-dd-label">Tashkilotlar</div>
                    @foreach ($organizations as $org)
                        <button type="button" class="idash-org-item">
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
                        Yangi tashkilot qo'shish
                    </button>
                </div>
            </div>

            {{-- ===== Nav ===== --}}
            <nav class="idash-navgroup">
                <span class="idash-navlabel">Boshqaruv</span>
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
                <span class="idash-navlabel">Muassasa</span>
                @foreach ($navItems['muassasa'] as $item)
                    <a href="{{ route($item['route']) }}" class="idash-navlink{{ $active === $item['key'] ? ' on' : '' }}">
                        <x-maktabgid.icon :name="$item['icon']" :width="18" :height="18" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
                <button type="button" class="idash-navlink danger js-logout-trigger">
                    <x-maktabgid.icon name="logout" :width="18" :height="18" />
                    Chiqish
                </button>
            </nav>

            <div class="idash-upsell">
                <span class="idash-upsell-tag">Bepul</span>
                <b>E'loningiz cheklangan</b>
                <p>Faqat 4 ta lid ko'rinmoqda. Paket oling — barcha kontaktlar va ko'proq qamrov.</p>
                <a class="btn btn-primary sm" style="justify-content:center" href="{{ route('institution.cabinet.plans') }}">
                    <x-maktabgid.icon name="sparkle" :width="15" :height="15" /> Paket olish
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
                    <input type="text" placeholder="Qidirish…" />
                </label>

                <button type="button" class="idash-iconbtn" title="Xabarlar">
                    <x-maktabgid.icon name="bell" :width="18" :height="18" />
                    <span class="idash-dot"></span>
                </button>

                <div class="idash-user-wrap">
                    <button type="button" class="idash-user-btn" data-dd-toggle="idash-user-menu">
                        <span class="idash-user-ava">{{ \App\Support\MaktabgidData::monogram($userDisplayName) }}</span>
                        <span style="text-align:left">
                            <b>{{ explode(' ', trim($userDisplayName))[0] }}</b>
                            <span>E'lon beruvchi</span>
                        </span>
                        <x-maktabgid.icon name="chevron" :width="14" :height="14" />
                    </button>
                    <div class="idash-dd idash-user-menu" id="idash-user-menu" data-dd-menu hidden>
                        <a href="{{ route('institution.cabinet.profile') }}" class="idash-org-item">
                            <x-maktabgid.icon name="building" :width="16" :height="16" /> Muassasa profili
                        </a>
                        <button type="button" class="idash-org-item js-logout-trigger" style="color:#d4504e">
                            <x-maktabgid.icon name="logout" :width="16" :height="16" /> Chiqish
                        </button>
                    </div>
                </div>
            </header>

            <div class="idash-content">
                {{ $slot }}
            </div>
        </div>
    </div>

    {{-- ===== "Yangi muassasa qo'shish" — forma tayyor, lekin bir nechta filialni real
         boshqarish (backend'da institution_user ko'p-ko'pga bog'lanishi) hali ulanmagan,
         shuning uchun umumiy "fake form" andozasi (js-fake-form → js-fake-success) orqali
         ishlaydi — xuddi saytdagi boshqa hali-backend'siz formalar kabi (masalan
         excursion-modal.blade.php). ===== --}}
    <x-maktabgid.modal-shell id="idash-org-add-modal" :width="440">
        <div class="js-modal-body">
            <div class="modal-head js-fake-form-head">
                <h3>Yangi muassasa qo'shish</h3>
                <p>Maktab, bog'cha yoki o'quv markazingizni platformaga joylashtiring.</p>
            </div>
            <form class="form js-fake-form">
                <x-maktabgid.field label="Muassasa nomi" icon="building">
                    <input type="text" name="name" required placeholder="Masalan, Yangi Avlod maktabi" />
                </x-maktabgid.field>
                <x-maktabgid.field label="Turi" icon="school">
                    <select name="type">
                        <option value="maktab">Xususiy maktab</option>
                        <option value="bogcha">Xususiy bog'cha</option>
                        <option value="markaz">O'quv markazi</option>
                    </select>
                </x-maktabgid.field>
                <x-maktabgid.field label="Tuman" icon="pin">
                    <select name="district">
                        @foreach (\App\Support\MaktabgidData::districts() as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </x-maktabgid.field>
                <button class="btn btn-primary form-submit" type="submit">
                    <x-maktabgid.icon name="plus" :width="16" :height="16" /> Qo'shish va profilni to'ldirish
                </button>
                <p class="form-note"><x-maktabgid.icon name="shield" :width="15" :height="15" /> Qo'shilgach qoralama sifatida saqlanadi. Tarif tanlagach e'lon qilinadi.</p>
            </form>
            <x-maktabgid.success-note title="Muassasa qo'shildi!" :close-target="true" class="js-fake-success" style="display:none">
                Endi profilini to'ldiring va tarif tanlab e'lon qiling — bu bo'lim ko'p filialni real boshqarishni qo'llab-quvvatlaganda avtomatik faollashadi.
            </x-maktabgid.success-note>
        </div>
    </x-maktabgid.modal-shell>
@endunless

<script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>
