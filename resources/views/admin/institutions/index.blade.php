@extends('admin.layout')

@section('title', 'Tashkilotlar')

@section('content')
    <x-admin.page-header :total="$institutions->total()" itemLabel="tashkilot" createRoute="admin.institutions.create" createLabel="Tashkilot qo'shish" />

    <form method="GET" class="flex flex-wrap gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nomi bo'yicha qidirish..."
               class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm w-72 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
        <select name="type" class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm bg-white">
            <option value="">Barcha turlar</option>
            @foreach ($institutionTypes as $type)
                <option value="{{ $type->key }}" @selected(request('type') === $type->key)>{{ $type->label }}</option>
            @endforeach
        </select>
        <button class="rounded-lg bg-slate-800 text-white text-sm px-4 py-2">Qidirish</button>
    </form>

    <x-admin.card class="!p-0 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3">Nomi</th>
                    <th class="px-4 py-3">Turi</th>
                    <th class="px-4 py-3">Tuman</th>
                    <th class="px-4 py-3">Egasi</th>
                    <th class="px-4 py-3">Reyting</th>
                    <th class="px-4 py-3">Holati</th>
                    <th class="px-4 py-3 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($institutions as $inst)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $inst->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $inst->type }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $inst->district?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $inst->owner?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $inst->rating }} ({{ $inst->review_count }})</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded-full {{ $inst->accepting ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $inst->accepting ? 'Qabul bor' : 'Qabul yo\'q' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1.5">
                                @can('institutions.update')
                                    <a href="{{ route('admin.institutions.media.index', $inst) }}" title="Galereya va videolar"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                        <x-admin.icon name="upload" class="w-4 h-4" />
                                    </a>
                                    <a href="{{ route('admin.institutions.achievements.index', $inst) }}" title="Yutuqlar"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                        <x-admin.icon name="star" class="w-4 h-4" />
                                    </a>
                                @endcan
                                <x-admin.row-actions
                                    :editRoute="route('admin.institutions.edit', $inst)" editPermission="institutions.update"
                                    :deleteRoute="route('admin.institutions.destroy', $inst)" deletePermission="institutions.delete" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-500">Tashkilotlar topilmadi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>

    <div class="mt-4">{{ $institutions->links() }}</div>
@endsection
