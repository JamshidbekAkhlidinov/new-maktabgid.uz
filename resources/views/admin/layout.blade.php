<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Boshqaruv paneli') — {{ config('app.name', 'MaktabGID') }} Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    @php
        $u = auth()->user();
        $initials = collect(explode(' ', trim($u?->name ?? '')))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
        $initials = $initials !== '' ? mb_strtoupper($initials) : 'SA';
    @endphp

    {{-- ================= SIDEBAR (fixed, mustaqil scroll) ================= --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 flex flex-col">
        {{-- Logotip — header bilan balandligi teng (h-16), scroll bo'lmaydi --}}
        <div class="h-16 shrink-0 flex items-center gap-2.5 px-5 border-b border-slate-100">
            <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-[#0d6e6a] text-white shrink-0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 10l9-5 9 5-9 5-9-5z"></path>
                    <path d="M7 12.5V17c0 1 2.2 2.5 5 2.5s5-1.5 5-2.5v-4.5"></path>
                    <path d="M21 10v5"></path>
                </svg>
            </span>
            <a href="{{ route('admin.dashboard') }}" class="text-base font-semibold text-slate-900 leading-tight">
                Maktab<b class="font-extrabold">GID</b>
            </a>
        </div>

        <nav class="flex-1 min-h-0 overflow-y-auto py-3 text-sm">
            <x-admin.nav-link route="admin.dashboard" permission="dashboard.view" icon="home">Bosh sahifa</x-admin.nav-link>

            <x-admin.nav-group label="Boshqaruv">
                <x-admin.nav-link route="admin.institutions.index" permission="institutions.view" icon="building"
                    :badge="$institutionsPending ?? null" badge-color="bg-amber-50 text-amber-700">Muassasalar</x-admin.nav-link>
                <x-admin.nav-link route="admin.users.index" permission="users.view" icon="user">Foydalanuvchilar</x-admin.nav-link>
                <x-admin.nav-link route="admin.applications.index" permission="applications.view" icon="clipboard">Arizalar</x-admin.nav-link>
                <x-admin.nav-link route="admin.reviews.index" permission="reviews.view" icon="chat"
                    :badge="$recentReviews ?? null" badge-color="bg-rose-50 text-rose-700">Sharhlar</x-admin.nav-link>
                <x-admin.nav-link route="admin.vacancies.index" permission="vacancies.view" icon="briefcase">Vakansiyalar</x-admin.nav-link>
                <x-admin.nav-link route="admin.resumes.index" permission="resumes.view" icon="document">Rezyumelar</x-admin.nav-link>
                <x-admin.nav-link route="admin.news.index" permission="news.view" icon="newspaper">Yangiliklar</x-admin.nav-link>
                <x-admin.nav-link route="admin.articles.index" permission="articles.view" icon="book">Blog postlar</x-admin.nav-link>
                <x-admin.nav-link route="admin.group.index" icon="users">Guruh</x-admin.nav-link>
                <x-admin.nav-link route="admin.specializations.index" permission="specializations.view" icon="tag">Kategoriyalar</x-admin.nav-link>
                <x-admin.nav-link route="admin.districts.index" permission="districts.view" icon="map-pin">Tumanlar</x-admin.nav-link>
                <x-admin.nav-link route="admin.tags.index" icon="hash">Teglar</x-admin.nav-link>
            </x-admin.nav-group>

            <x-admin.nav-group label="Billing">
                <x-admin.nav-link route="admin.subscriptions.index" icon="credit-card">Obunalar</x-admin.nav-link>
                <x-admin.nav-link route="admin.tariffs.index" icon="tag">Tariflar</x-admin.nav-link>
                <x-admin.nav-link route="admin.billing-settings.index" icon="cog">Billing sozlamalari</x-admin.nav-link>
            </x-admin.nav-group>

            <x-admin.nav-group label="Tizim">
                <x-admin.nav-link route="admin.roles.index" permission="roles.view" icon="shield">Rollar</x-admin.nav-link>
                <x-admin.nav-link route="admin.permissions.index" permission="permissions.view" icon="key">Huquqlar</x-admin.nav-link>
            </x-admin.nav-group>

            <x-admin.nav-group label="Boshqa">
                <x-admin.nav-link route="admin.settings.index" icon="cog">Sozlamalar</x-admin.nav-link>
                <a href="{{ route('welcome') }}" target="_blank" rel="noopener"
                   class="flex items-center gap-2.5 pl-3.5 pr-3 py-2 mx-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                    <x-admin.icon name="external-link" class="w-[18px] h-[18px] shrink-0" />
                    <span class="flex-1 truncate">Saytga qaytish</span>
                </a>
            </x-admin.nav-group>
        </nav>
    </aside>

    {{-- ================= HEADER (fixed, sidebar bilan balandligi teng) ================= --}}
    <header class="fixed top-0 left-64 right-0 z-30 h-16 bg-white border-b border-slate-200 px-6 flex items-center gap-4">
        <form action="{{ route('admin.institutions.index') }}" method="GET" class="flex-1 max-w-xl">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16Z" />
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Muassasa, foydalanuvchi izlash..."
                       class="w-full rounded-lg border border-slate-200 bg-slate-50 pl-9 pr-3 py-2 text-sm placeholder:text-slate-400 focus:bg-white focus:border-teal-600 focus:ring-teal-600 focus:outline-none" />
            </div>
        </form>

        <div class="flex items-center gap-3 shrink-0 ml-auto">
            {{-- Bildirishnomalar --}}
            <details class="relative">
                <summary class="list-none cursor-pointer select-none flex items-center justify-center w-9 h-9 rounded-full hover:bg-slate-100 transition [&::-webkit-details-marker]:hidden">
                    <span class="relative">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-rose-500"></span>
                    </span>
                </summary>
                <div class="absolute right-0 mt-2 w-72 bg-white rounded-xl border border-slate-200 shadow-lg py-3 px-4 text-sm text-slate-500 z-50">
                    Hozircha yangi bildirishnoma yo'q.
                </div>
            </details>

            <span class="w-px h-6 bg-slate-200"></span>

            {{-- Profil --}}
            <details class="relative">
                <summary class="list-none cursor-pointer select-none flex items-center gap-2 px-1.5 py-1 rounded-lg hover:bg-slate-100 transition [&::-webkit-details-marker]:hidden">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#0d6e6a] text-white text-xs font-semibold shrink-0">{{ $initials }}</span>
                    <span class="text-sm font-medium text-slate-700">{{ $u?->name }}</span>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </summary>

                <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl border border-slate-200 shadow-lg py-2 z-50">
                    <div class="px-4 py-2 border-b border-slate-100">
                        <p class="text-sm font-semibold text-slate-900">{{ $u?->name }}</p>
                        <p class="text-xs text-slate-500">Super Admin</p>
                    </div>

                    <a href="{{ route('welcome') }}" target="_blank" rel="noopener"
                       class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                        <x-admin.icon name="external-link" class="w-4 h-4" /> Saytga qaytish
                    </a>
                    <a href="{{ route('admin.settings.index') }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                        <x-admin.icon name="cog" class="w-4 h-4" /> Sozlamalar
                    </a>

                    <form method="POST" action="{{ route('admin.logout') }}" class="border-t border-slate-100 mt-1 pt-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">
                            <x-admin.icon name="logout" class="w-4 h-4" /> Chiqish
                        </button>
                    </form>
                </div>
            </details>
        </div>
    </header>

    {{-- ================= MAIN (sidebar+header uchun joy qoldiriladi) ================= --}}
    <div class="pl-64 pt-16 min-h-screen">
        <main class="p-6">
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
