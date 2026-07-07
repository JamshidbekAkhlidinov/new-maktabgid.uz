@extends('admin.layout')

@section('title', 'Maqolalar')

@section('content')
    <x-admin.page-header :total="$articles->total()" itemLabel="maqola" createRoute="admin.articles.create" createLabel="Maqola qo'shish" />

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
                    <th class="px-4 py-3">Muallif</th>
                    <th class="px-4 py-3">Chop etilgan</th>
                    <th class="px-4 py-3">Tanlangan</th>
                    <th class="px-4 py-3 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($articles as $article)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $article->title }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $article->author_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $article->published_at?->format('d.m.Y') }}</td>
                        <td class="px-4 py-3">{{ $article->featured ? '⭐' : '—' }}</td>
                        <td class="px-4 py-3">
                            <x-admin.row-actions
                                :editRoute="route('admin.articles.edit', $article)" editPermission="articles.update"
                                :deleteRoute="route('admin.articles.destroy', $article)" deletePermission="articles.delete" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Maqolalar topilmadi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>

    <div class="mt-4">{{ $articles->links() }}</div>
@endsection
