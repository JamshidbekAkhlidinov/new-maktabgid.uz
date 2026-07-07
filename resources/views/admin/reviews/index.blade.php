@extends('admin.layout')

@section('title', 'Sharhlar')

@section('content')
    <x-admin.page-header :total="$reviews->total()" itemLabel="sharh" createRoute="admin.reviews.create" createLabel="Sharh qo'shish" />

    <x-admin.card class="!p-0 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3">Tashkilot</th>
                    <th class="px-4 py-3">Muallif</th>
                    <th class="px-4 py-3">Baho</th>
                    <th class="px-4 py-3">Matn</th>
                    <th class="px-4 py-3 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($reviews as $review)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $review->institution?->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $review->author?->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ str_repeat('⭐', $review->rating) }}</td>
                        <td class="px-4 py-3 text-slate-600 max-w-xs truncate">{{ $review->body }}</td>
                        <td class="px-4 py-3">
                            <x-admin.row-actions
                                :editRoute="route('admin.reviews.edit', $review)" editPermission="reviews.update"
                                :deleteRoute="route('admin.reviews.destroy', $review)" deletePermission="reviews.delete" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Sharhlar topilmadi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>

    <div class="mt-4">{{ $reviews->links() }}</div>
@endsection
