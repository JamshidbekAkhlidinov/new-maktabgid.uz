@extends('admin.layout')

@section('title', 'Tashkilotni tahrirlash')

@section('content')
    <x-admin.page-header title="Tashkilotni tahrirlash: {{ $institution->name }}" />

    <div class="flex flex-wrap gap-3 mb-5">
        <a href="{{ route('admin.institutions.media.index', $institution) }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm font-medium px-4 py-2.5 hover:bg-slate-50">
            <x-admin.icon name="upload" class="w-4 h-4" /> Galereya va videolar
        </a>
        <a href="{{ route('admin.institutions.achievements.index', $institution) }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm font-medium px-4 py-2.5 hover:bg-slate-50">
            <x-admin.icon name="star" class="w-4 h-4" /> O'quvchilar yutuqlari
        </a>
        <a href="{{ route('maktabgid.school', $institution) }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm font-medium px-4 py-2.5 hover:bg-slate-50">
            <x-admin.icon name="external-link" class="w-4 h-4" /> Ommaviy profilni ko'rish
        </a>
    </div>

    <x-admin.card>
        <form method="POST" action="{{ route('admin.institutions.update', $institution) }}">
            @csrf
            @method('PUT')
            @include('admin.institutions._form')

            <div class="mt-6 flex gap-3">
                <button class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5">Saqlash</button>
                <a href="{{ route('admin.institutions.index') }}" class="rounded-lg border border-slate-300 text-slate-700 text-sm font-medium px-5 py-2.5">Bekor qilish</a>
            </div>
        </form>
    </x-admin.card>
@endsection
