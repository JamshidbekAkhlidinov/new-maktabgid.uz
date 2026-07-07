@extends('admin.layout')

@section('title', 'Bosh sahifa')

@section('content')
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <x-admin.stat-card label="Foydalanuvchilar" :value="$stats['users']" icon="👤" />
        <x-admin.stat-card label="Tashkilotlar" :value="$stats['institutions']" icon="🏫" />
        <x-admin.stat-card label="Vakansiyalar" :value="$stats['vacancies']" icon="💼" />
        <x-admin.stat-card label="Kutilayotgan arizalar" :value="$stats['applications_pending']" icon="📝" />
        <x-admin.stat-card label="Sharhlar" :value="$stats['reviews']" icon="⭐" />
        <x-admin.stat-card label="Rezyumelar" :value="$stats['resumes']" icon="📄" />
        <x-admin.stat-card label="Yangiliklar" :value="$stats['news']" icon="📰" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <h2 class="font-semibold text-slate-900 mb-3">So'nggi arizalar</h2>
            <div class="divide-y divide-slate-100">
                @forelse ($latestApplications as $app)
                    <div class="py-2.5 flex items-center justify-between text-sm">
                        <div>
                            <p class="font-medium text-slate-800">{{ $app->child_name }}</p>
                            <p class="text-slate-500">{{ $app->institution?->name }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full bg-amber-50 text-amber-700">{{ $app->status }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 py-2">Hozircha arizalar yo'q.</p>
                @endforelse
            </div>
            @can('applications.view')
                <a href="{{ route('admin.applications.index') }}" class="inline-block mt-3 text-sm text-indigo-600 hover:underline">Barchasini ko'rish →</a>
            @endcan
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <h2 class="font-semibold text-slate-900 mb-3">So'nggi tashkilotlar</h2>
            <div class="divide-y divide-slate-100">
                @forelse ($latestInstitutions as $inst)
                    <div class="py-2.5 flex items-center justify-between text-sm">
                        <div>
                            <p class="font-medium text-slate-800">{{ $inst->name }}</p>
                            <p class="text-slate-500">{{ $inst->type }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full {{ $inst->accepting ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $inst->accepting ? 'Qabul bor' : 'Qabul yo\'q' }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 py-2">Hozircha tashkilotlar yo'q.</p>
                @endforelse
            </div>
            @can('institutions.view')
                <a href="{{ route('admin.institutions.index') }}" class="inline-block mt-3 text-sm text-indigo-600 hover:underline">Barchasini ko'rish →</a>
            @endcan
        </div>
    </div>
@endsection
