@extends('admin.layout')

@section('title', $title)

@section('content')
    <div class="bg-white rounded-xl border border-slate-200 p-12 flex flex-col items-center text-center">
        <span class="flex items-center justify-center w-16 h-16 rounded-2xl bg-teal-50 text-teal-600 mb-5">
            <x-admin.icon :name="$icon" class="w-7 h-7" />
        </span>
        <h2 class="text-lg font-semibold text-slate-900 mb-2">{{ $title }}</h2>
        <p class="text-sm text-slate-500 max-w-md mb-6">{{ $description }}</p>
        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full mb-6">
            Tez orada
        </span>
        <a href="{{ route('admin.dashboard') }}"
           class="text-sm font-medium text-teal-700 hover:text-teal-800 hover:underline">
            ← Bosh sahifaga qaytish
        </a>
    </div>
@endsection
