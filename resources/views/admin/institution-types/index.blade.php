@extends('admin.layout')

@section('title', 'Muassasa turlari')

@section('content')
    <x-admin.page-header :total="$institutionTypes->count()" itemLabel="tur" createRoute="admin.institution-types.create" createLabel="Tur qo'shish" />

    <x-admin.card class="!p-0 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3">Kalit (key)</th>
                    <th class="px-4 py-3">Nomi</th>
                    <th class="px-4 py-3">Ikonka</th>
                    <th class="px-4 py-3">Holati</th>
                    <th class="px-4 py-3 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($institutionTypes as $type)
                    <tr>
                        <td class="px-4 py-3 text-slate-500 font-mono text-xs">{{ $type->key }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $type->label }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            <span class="inline-flex items-center gap-2">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 bg-slate-50 text-slate-600">
                                    <x-maktabgid.icon :name="$type->icon" :width="16" :height="16" />
                                </span>
                                <span class="text-xs text-slate-400 font-mono">{{ $type->icon }}</span>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded-full {{ $type->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $type->is_active ? 'Faol' : 'Nofaol' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <x-admin.row-actions
                                :editRoute="route('admin.institution-types.edit', $type)" editPermission="institution-types.update"
                                :deleteRoute="route('admin.institution-types.destroy', $type)" deletePermission="institution-types.delete" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Muassasa turlari topilmadi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>
@endsection
