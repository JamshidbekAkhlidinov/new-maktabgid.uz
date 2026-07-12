@extends('admin.layout')

@section('title', 'Foydalanuvchilar')

@section('content')
    <x-admin.page-header :total="$users->total()" itemLabel="foydalanuvchi" createRoute="admin.users.create" createLabel="Foydalanuvchi qo'shish" />

    <form method="GET" class="flex flex-wrap gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Ism, telefon yoki email bo'yicha qidirish..."
               class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm w-72 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
        <select name="role" class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm bg-white">
            <option value="">Barcha rollar</option>
            <option value="parent" @selected(request('role') === 'parent')>Ota-ona</option>
            <option value="institution" @selected(request('role') === 'institution')>Muassasa</option>
            <option value="teacher" @selected(request('role') === 'teacher')>Ustoz</option>
            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
        </select>
        <button class="rounded-lg bg-slate-800 text-white text-sm px-4 py-2">Qidirish</button>
    </form>

    <x-admin.card class="!p-0 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3">Ism</th>
                    <th class="px-4 py-3">Telefon</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Rol</th>
                    <th class="px-4 py-3">Spatie rollar</th>
                    <th class="px-4 py-3">Tuman</th>
                    <th class="px-4 py-3 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->phone }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-700">{{ $user->role }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $user->getRoleNames()->implode(', ') ?: '—' }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $user->district?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <x-admin.row-actions
                                :editRoute="route('admin.users.edit', $user)" editPermission="users.update"
                                :deleteRoute="route('admin.users.destroy', $user)" deletePermission="users.delete" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-500">Foydalanuvchilar topilmadi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>

    <div class="mt-4">{{ $users->links() }}</div>
@endsection
