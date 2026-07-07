@extends('admin.layout')

@section('title', 'Tumanlar')

@section('content')
    <x-admin.page-header :total="$districts->total()" itemLabel="tuman" createRoute="admin.districts.create" createLabel="Tuman qo'shish" />

    <x-admin.card class="!p-0 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3">Nomi</th>
                    <th class="px-4 py-3">Tashkilotlar soni</th>
                    <th class="px-4 py-3 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($districts as $district)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $district->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $district->institutions_count }}</td>
                        <td class="px-4 py-3">
                            <x-admin.row-actions
                                :editRoute="route('admin.districts.edit', $district)" editPermission="districts.update"
                                :deleteRoute="route('admin.districts.destroy', $district)" deletePermission="districts.delete" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-slate-500">Tumanlar topilmadi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>

    <div class="mt-4">{{ $districts->links() }}</div>
@endsection
