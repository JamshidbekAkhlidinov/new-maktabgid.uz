@extends('admin.layout')

@section('title', 'Reklamalar')

@section('content')
    <x-admin.page-header :total="$advertisements->total()" itemLabel="reklama" createRoute="admin.advertisements.create" createLabel="Reklama qo'shish" />

    <x-admin.card class="!p-0 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3">Banner</th>
                    <th class="px-4 py-3">Havola</th>
                    <th class="px-4 py-3">Muddat</th>
                    <th class="px-4 py-3">Holati</th>
                    <th class="px-4 py-3 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($advertisements as $ad)
                    <tr>
                        <td class="px-4 py-3">
                            <img src="{{ $ad->image_url }}" alt="" class="w-24 h-14 object-cover rounded-lg border border-slate-200" />
                        </td>
                        <td class="px-4 py-3 text-slate-600 max-w-xs truncate">
                            @if ($ad->link_url)
                                <a href="{{ $ad->link_url }}" target="_blank" rel="noopener" class="text-indigo-600 hover:underline">{{ $ad->link_url }}</a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-600 text-xs">
                            {{ $ad->started_at?->format('d.m.Y') ?? '—' }} – {{ $ad->finished_at?->format('d.m.Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($ad->isCurrentlyRunning())
                                <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium px-2.5 py-1">Faol</span>
                            @elseif ($ad->is_active)
                                <span class="inline-flex items-center rounded-full bg-amber-50 text-amber-700 text-xs font-medium px-2.5 py-1">Muddatdan tashqari</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-100 text-slate-500 text-xs font-medium px-2.5 py-1">O'chirilgan</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <x-admin.row-actions
                                :editRoute="route('admin.advertisements.edit', $ad)" editPermission="advertisements.update"
                                :deleteRoute="route('admin.advertisements.destroy', $ad)" deletePermission="advertisements.delete" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Reklamalar topilmadi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>

    <div class="mt-4">{{ $advertisements->links() }}</div>
@endsection
