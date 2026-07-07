<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Boshqaruv paneli') — {{ config('app.name', 'MaktabGID') }} Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    @php($u = auth()->user())

    <div class="flex min-h-screen">
        {{-- ================= SIDEBAR ================= --}}
        <aside class="w-72 shrink-0 bg-[#0f172a] text-slate-200 flex flex-col">
            <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
                <span class="flex items-center justify-center w-9 h-9 rounded-full bg-amber-50 text-lg shrink-0">🎓</span>
                <a href="{{ route('admin.dashboard') }}" class="text-base font-bold text-white leading-tight">
                    MaktabGID <span class="text-amber-400">Admin</span>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto py-3 text-sm">
                <x-admin.nav-link route="admin.dashboard" permission="dashboard.view" icon="🏠">Bosh sahifa</x-admin.nav-link>
                <x-admin.nav-link route="admin.applications.index" permission="applications.view" icon="📝">Arizalar</x-admin.nav-link>

                <x-admin.nav-group label="Kontent">
                    <x-admin.nav-link route="admin.institutions.index" permission="institutions.view" icon="🏫">Tashkilotlar</x-admin.nav-link>
                    <x-admin.nav-link route="admin.vacancies.index" permission="vacancies.view" icon="💼">Vakansiyalar</x-admin.nav-link>
                    <x-admin.nav-link route="admin.resumes.index" permission="resumes.view" icon="📄">Rezyumelar</x-admin.nav-link>
                    <x-admin.nav-link route="admin.reviews.index" permission="reviews.view" icon="⭐">Sharhlar</x-admin.nav-link>
                    <x-admin.nav-link route="admin.news.index" permission="news.view" icon="📰">Yangiliklar</x-admin.nav-link>
                    <x-admin.nav-link route="admin.articles.index" permission="articles.view" icon="📚">Maqolalar</x-admin.nav-link>
                </x-admin.nav-group>

                <x-admin.nav-group label="Spravochnik">
                    <x-admin.nav-link route="admin.specializations.index" permission="specializations.view" icon="🏷️">Kategoriyalar</x-admin.nav-link>
                    <x-admin.nav-link route="admin.districts.index" permission="districts.view" icon="📍">Tumanlar</x-admin.nav-link>
                </x-admin.nav-group>

                <x-admin.nav-group label="Boshqaruv">
                    <x-admin.nav-link route="admin.users.index" permission="users.view" icon="👤">Foydalanuvchilar</x-admin.nav-link>
                    <x-admin.nav-link route="admin.roles.index" permission="roles.view" icon="🛡️">Rollar</x-admin.nav-link>
                    <x-admin.nav-link route="admin.permissions.index" permission="permissions.view" icon="🔑">Huquqlar</x-admin.nav-link>
                </x-admin.nav-group>
            </nav>
        </aside>

        {{-- ================= MAIN ================= --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-slate-200 px-6 py-3.5 flex items-center justify-between">
                <h1 class="text-lg font-semibold text-slate-900">@yield('title', 'Bosh sahifa')</h1>

                <div class="flex items-center gap-4 text-sm text-slate-600">
                    <a href="{{ route('welcome') }}" target="_blank" rel="noopener"
                       class="flex items-center gap-1.5 hover:text-indigo-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Saytni ko'rish
                    </a>

                    <span class="w-px h-4 bg-slate-200"></span>

                    <span class="font-medium text-slate-700">{{ $u?->name }}</span>

                    <span class="w-px h-4 bg-slate-200"></span>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 hover:text-rose-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0110.5 3h6a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0116.5 21h-6a2.25 2.25 0 01-2.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3H15" />
                            </svg>
                            Chiqish
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 p-6">
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
    </div>
</body>
</html>
