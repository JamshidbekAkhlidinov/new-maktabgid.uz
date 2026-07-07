@extends('admin.layout')

@section('title', 'Yangiliklar')

@section('content')
    <x-admin.page-header :total="$newsItems->total()" itemLabel="yangilik" createRoute="admin.news.create" createLabel="Yangilik qo'shish" />

    <form method="GET" class="flex flex-wrap gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Sarlavha bo'yicha qidirish..."
               class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm w-72 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
        <button class="rounded-lg bg-slate-800 text-white text-sm px-4 py-2">Qidirish</button>
    </form>

    <x-admin.card class="!p-0 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3">Sarlavha</th>
                    <th class="px-4 py-3">Teg</th>
                    <th class="px-4 py-3">Chop etilgan</th>
                    <th class="px-4 py-3">Hot</th>
                    <th class="px-4 py-3 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($newsItems as $item)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $item->title }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->tag }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->published_at?->format('d.m.Y') }}</td>
                        <td class="px-4 py-3">{{ $item->hot ? '🔥' : '—' }}</td>
                        <td class="px-4 py-3">
                            <x-admin.row-actions
                                :editRoute="route('admin.news.edit', $item)" editPermission="news.update"
                                :deleteRoute="route('admin.news.destroy', $item)" deletePermission="news.delete" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Yangiliklar topilmadi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>

    <div class="mt-4">{{ $newsItems->links() }}</div>
@endsection
