@extends('admin.layout')

@section('title', 'Arizalar (formlar)')

@section('content')
    <x-admin.page-header :total="$applications->total()" itemLabel="ariza" createRoute="admin.applications.create" createLabel="Ariza qo'shish" />

    <form method="GET" class="flex flex-wrap gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Bola ismi bo'yicha qidirish..."
               class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm w-72 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
        <select name="status" class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm bg-white">
            <option value="">Barcha statuslar</option>
            <option value="pending" @selected(request('status') === 'pending')>Kutilmoqda</option>
            <option value="confirmed" @selected(request('status') === 'confirmed')>Tasdiqlangan</option>
            <option value="rejected" @selected(request('status') === 'rejected')>Rad etilgan</option>
        </select>
        <button class="rounded-lg bg-slate-800 text-white text-sm px-4 py-2">Qidirish</button>
    </form>

    <x-admin.card class="!p-0 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3">Bola</th>
                    <th class="px-4 py-3">Tashkilot</th>
                    <th class="px-4 py-3">Ota-ona</th>
                    <th class="px-4 py-3">Turi</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($applications as $app)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $app->child_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $app->institution?->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $app->parent_name }} — {{ $app->parent_phone }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $app->type }}</td>
                        <td class="px-4 py-3">
                            @php($cls = ['pending' => 'bg-amber-50 text-amber-700', 'confirmed' => 'bg-emerald-50 text-emerald-700', 'rejected' => 'bg-rose-50 text-rose-700'][$app->status] ?? 'bg-slate-100 text-slate-600')
                            <span class="text-xs px-2 py-1 rounded-full {{ $cls }}">{{ $app->status }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <x-admin.row-actions
                                :editRoute="route('admin.applications.edit', $app)" editPermission="applications.update"
                                :deleteRoute="route('admin.applications.destroy', $app)" deletePermission="applications.delete" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Arizalar topilmadi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>

    <div class="mt-4">{{ $applications->links() }}</div>
@endsection
