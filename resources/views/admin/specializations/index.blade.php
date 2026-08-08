@extends('admin.layout')

@section('title', 'Kategoriyalar')

@section('content')
    <x-admin.page-header :total="$specializations->count()" itemLabel="kategoriya" createRoute="admin.specializations.create" createLabel="Kategoriya qo'shish" />

    <x-admin.card class="!p-0 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3">Kalit (key)</th>
                    <th class="px-4 py-3">Nomi</th>
                    <th class="px-4 py-3">Ikonka</th>
                    <th class="px-4 py-3 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($specializations as $spec)
                    <tr>
                        <td class="px-4 py-3 text-slate-500 font-mono text-xs">{{ $spec->key }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $spec->label }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            <span class="inline-flex items-center gap-2">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 bg-slate-50 text-slate-600">
                                    <x-maktabgid.icon :name="$spec->icon" :width="16" :height="16" />
                                </span>
                                <span class="text-xs text-slate-400 font-mono">{{ $spec->icon }}</span>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <x-admin.row-actions
                                :editRoute="route('admin.specializations.edit', $spec)" editPermission="specializations.update"
                                :deleteRoute="route('admin.specializations.destroy', $spec)" deletePermission="specializations.delete" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-slate-500">Kategoriyalar topilmadi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>
@endsection
