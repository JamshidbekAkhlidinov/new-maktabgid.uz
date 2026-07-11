@extends('admin.layout')

@section('title', 'Bosh sahifa')

@section('content')
    @php
        $avatarColors = ['bg-rose-100 text-rose-700', 'bg-indigo-100 text-indigo-700', 'bg-emerald-100 text-emerald-700', 'bg-amber-100 text-amber-700', 'bg-blue-100 text-blue-700', 'bg-teal-100 text-teal-700'];
    @endphp

    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900">Xush kelibsiz, {{ auth()->user()->name ?? 'Super Admin' }}</h1>
        <p class="text-sm text-slate-500 mt-0.5">{{ now()->format('d.m.Y') }} — MaktabGID admin paneli</p>
    </div>

    {{-- ================= STAT CARDS ================= --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach ($tiles as $tile)
            <x-admin.stat-card
                :label="$tile['label']"
                :value="$tile['value']"
                :icon="$tile['icon']"
                :icon-bg="$tile['iconBg']"
                :icon-color="$tile['iconColor'] ?? 'text-slate-600'"
                :badge="$tile['badge'] ?? null"
                :badge-color="$tile['badgeColor'] ?? 'bg-slate-100 text-slate-600'"
                :manage-href="$tile['manageHref'] ?? null"
                :href="$tile['href'] ?? null"
            />
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- ================= SO'NGGI MUASSASALAR ================= --}}
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold text-slate-900">So'nggi muassasalar</h2>
                @can('institutions.view')
                    <a href="{{ route('admin.institutions.index') }}" class="text-sm text-teal-700 hover:underline">Barchasi →</a>
                @endcan
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($latestInstitutions as $inst)
                    <div class="py-2.5 flex items-center gap-3 text-sm">
                        <span class="flex items-center justify-center w-9 h-9 rounded-full font-semibold text-sm shrink-0 {{ $avatarColors[$inst->id % count($avatarColors)] }}">
                            {{ mb_strtoupper(mb_substr($inst->name, 0, 1)) }}
                        </span>

                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-800 truncate">{{ $inst->name }}</p>
                            <p class="text-slate-500 truncate">{{ $inst->type }} @if($inst->district) · {{ $inst->district->name }} @endif</p>
                        </div>

                        <span class="text-xs px-2.5 py-1 rounded-full shrink-0 {{ $inst->accepting ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $inst->accepting ? 'Faol' : 'Kutilmoqda' }}
                        </span>

                        @can('institutions.update')
                            <a href="{{ route('admin.institutions.edit', $inst) }}" title="Tahrirlash"
                               class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                                </svg>
                            </a>
                        @endcan
                    </div>
                @empty
                    <p class="text-sm text-slate-500 py-2">Hozircha muassasalar yo'q.</p>
                @endforelse
            </div>
        </div>

        {{-- ================= TEZKOR HARAKATLAR + SO'NGGI POSTLAR ================= --}}
        <div class="flex flex-col gap-6">
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <h2 class="font-semibold text-slate-900 mb-3">Tezkor harakatlar</h2>

                <div class="flex flex-col gap-2.5">
                    @can('institutions.create')
                        <a href="{{ route('admin.institutions.create') }}"
                           class="flex items-center justify-center gap-2 rounded-lg bg-[#0d6e6a] hover:bg-[#0a5754] text-white text-sm font-medium py-2.5 transition">
                            <x-admin.icon name="plus" class="w-4 h-4" /> Muassasa qo'shish
                        </a>
                    @endcan

                    @can('news.create')
                        <a href="{{ route('admin.news.create') }}"
                           class="flex items-center justify-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2.5 transition">
                            <x-admin.icon name="plus" class="w-4 h-4" /> Yangilik qo'shish
                        </a>
                    @endcan

                    @can('vacancies.create')
                        <a href="{{ route('admin.vacancies.create') }}"
                           class="flex items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2.5 transition">
                            <x-admin.icon name="plus" class="w-4 h-4" /> Vakansiya qo'shish
                        </a>
                    @endcan

                    @can('reviews.view')
                        <a href="{{ route('admin.reviews.index') }}"
                           class="flex items-center justify-between gap-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-sm font-medium px-4 py-2.5 transition">
                            <span>So'nggi sharhlarni ko'rish</span>
                            @if ($recentReviews)
                                <span class="flex items-center justify-center min-w-[1.5rem] h-6 px-1.5 rounded-full bg-rose-600 text-white text-xs font-semibold">{{ $recentReviews }}</span>
                            @endif
                        </a>
                    @endcan

                    @can('institutions.view')
                        <a href="{{ route('admin.institutions.index') }}"
                           class="flex items-center justify-between gap-2 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm font-medium px-4 py-2.5 transition">
                            <span class="flex items-center gap-1.5"><x-admin.icon name="clock" class="w-4 h-4" /> Kutilayotgan muassasalar</span>
                            @if ($institutionsPending)
                                <span class="flex items-center justify-center min-w-[1.5rem] h-6 px-1.5 rounded-full bg-amber-600 text-white text-xs font-semibold">{{ $institutionsPending }}</span>
                            @endif
                        </a>
                    @endcan
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-semibold text-slate-900">So'nggi postlar</h2>
                    @can('articles.view')
                        <a href="{{ route('admin.articles.index') }}" class="text-sm text-teal-700 hover:underline">Barchasi →</a>
                    @endcan
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($latestArticles as $article)
                        <div class="py-2.5 flex items-center justify-between gap-3 text-sm">
                            <p class="font-medium text-slate-800 truncate">{{ $article->title }}</p>
                            <span class="text-xs px-2.5 py-1 rounded-full shrink-0 {{ $article->published_at && $article->published_at->lte(now()) ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $article->published_at && $article->published_at->lte(now()) ? 'Nashr qilingan' : 'Qoralama' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 py-2">Hozircha postlar yo'q.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
