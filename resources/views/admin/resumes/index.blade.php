@extends('admin.layout')

@section('title', 'Rezyumelar')

@section('content')
    <x-admin.page-header :total="$resumes->total()" itemLabel="rezyume" createRoute="admin.resumes.create" createLabel="Rezyume qo'shish" />

    <form method="GET" class="flex flex-wrap gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Ism bo'yicha qidirish..."
               class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm w-72 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
        <button class="rounded-lg bg-slate-800 text-white text-sm px-4 py-2">Qidirish</button>
    </form>

    <x-admin.card class="!p-0 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3">Ism</th>
                    <th class="px-4 py-3">Lavozim</th>
                    <th class="px-4 py-3">Tajriba</th>
                    <th class="px-4 py-3">Tuman</th>
                    <th class="px-4 py-3 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($resumes as $resume)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $resume->full_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $resume->role_title }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $resume->experience }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $resume->district?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <x-admin.row-actions
                                :editRoute="route('admin.resumes.edit', $resume)" editPermission="resumes.update"
                                :deleteRoute="route('admin.resumes.destroy', $resume)" deletePermission="resumes.delete" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Rezyumelar topilmadi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>

    <div class="mt-4">{{ $resumes->links() }}</div>
@endsection
