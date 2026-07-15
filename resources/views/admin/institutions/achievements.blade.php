@extends('admin.layout')

@section('title', 'Yutuqlar')

@php
    $levelMeta = [
        'intl' => "Xalqaro",
        'national' => 'Respublika',
        'regional' => 'Viloyat',
        'city' => 'Shahar',
    ];
@endphp

@section('content')
    <x-admin.page-header title="O'quvchilar yutuqlari: {{ $institution->name }}" />

    <a href="{{ route('admin.institutions.edit', $institution) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-800 mb-5">
        <x-admin.icon name="arrow-left" class="w-4 h-4" /> Tashkilot tahririga qaytish
    </a>

    <x-admin.card>
        <h3 class="text-base font-semibold text-slate-900 mb-4">Mavjud yutuqlar</h3>

        <div class="divide-y divide-slate-100 mb-2">
            @forelse ($achievements as $a)
                <details class="group py-3">
                    <summary class="flex items-center gap-3 cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                        @if ($a->image_url)
                            <span class="w-9 h-9 rounded-lg bg-cover bg-center border border-slate-200 shrink-0" style="background-image:url('{{ $a->image_url }}')"></span>
                        @else
                            <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-amber-50 text-amber-500 border border-amber-100 shrink-0">
                                <x-admin.icon name="star" class="w-4 h-4" />
                            </span>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 truncate">{{ $a->title }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ collect([$a->student_name, $a->year, $a->type])->filter()->implode(' · ') }}</p>
                        </div>
                        <span class="text-xs font-medium text-slate-500 bg-slate-100 rounded-full px-2.5 py-1">{{ $levelMeta[$a->level] ?? $a->level }}</span>
                        <x-admin.icon name="chevron-down" class="w-4 h-4 text-slate-400 group-open:rotate-180 transition" />
                    </summary>

                    <div class="mt-3 pl-12 space-y-3">
                        <form method="POST" action="{{ route('admin.institutions.achievements.update', [$institution, $a]) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @csrf
                            @method('PUT')
                            <x-admin.input name="title" label="Yutuq / mukofot nomi" :value="$a->title" required />
                            <x-admin.input name="student_name" label="O'quvchi ismi" :value="$a->student_name" />
                            <x-admin.input name="year" label="Yil" type="number" :value="$a->year" />
                            <x-admin.input name="type" label="Turi" :value="$a->type" placeholder="Olimpiada" />
                            <x-admin.select name="level" label="Daraja" :value="$a->level" :options="$levelMeta" required />
                            <x-admin.image-upload name="image" label="Sertifikat / rasm" :value="$a->image_url" hint="Bo'sh qoldirsangiz eskisi saqlanadi" />
                            <div class="md:col-span-2 flex gap-3">
                                <button type="submit" class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5">Saqlash</button>
                            </div>
                        </form>
                        <x-admin.delete-button :action="route('admin.institutions.achievements.destroy', [$institution, $a])" />
                    </div>
                </details>
            @empty
                <p class="text-sm text-slate-500 py-2">Hali yutuq qo'shilmagan.</p>
            @endforelse
        </div>
    </x-admin.card>

    <x-admin.card class="mt-6">
        <h3 class="text-base font-semibold text-slate-900 mb-4">Yangi yutuq qo'shish</h3>

        <form method="POST" action="{{ route('admin.institutions.achievements.store', $institution) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf
            <x-admin.input name="title" label="Yutuq / mukofot nomi" placeholder="Matematika olimpiadasi — 1-o'rin" required />
            <x-admin.input name="student_name" label="O'quvchi ismi" placeholder="Sardor Karimov" />
            <x-admin.input name="year" label="Yil" type="number" :value="now()->year" />
            <x-admin.input name="type" label="Turi" placeholder="Olimpiada" />
            <x-admin.select name="level" label="Daraja" :options="$levelMeta" required />
            <x-admin.image-upload name="image" label="Sertifikat / rasm" hint="Ixtiyoriy" />
            <div class="md:col-span-2">
                <button type="submit" class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5">Qo'shish</button>
            </div>
        </form>
    </x-admin.card>
@endsection
