@extends('admin.layout')

@section('title', 'Rollar')

@section('content')
    <x-admin.page-header :total="$roles->total()" itemLabel="rol" createRoute="admin.roles.create" createLabel="Rol qo'shish" />

    <x-admin.card class="!p-0 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3">Nomi</th>
                    <th class="px-4 py-3">Huquqlar soni</th>
                    <th class="px-4 py-3">Foydalanuvchilar soni</th>
                    <th class="px-4 py-3 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($roles as $role)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">
                            {{ $role->name }}
                            @if ($role->name === \App\Http\Controllers\Admin\RoleController::PROTECTED_ROLE)
                                <span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700">himoyalangan</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $role->permissions_count }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $role->users_count }}</td>
                        <td class="px-4 py-3">
                            @php($isProtected = $role->name === \App\Http\Controllers\Admin\RoleController::PROTECTED_ROLE)
                            <x-admin.row-actions
                                :editRoute="route('admin.roles.edit', $role)" editPermission="roles.update"
                                :deleteRoute="$isProtected ? null : route('admin.roles.destroy', $role)" deletePermission="roles.delete" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-slate-500">Rollar topilmadi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>

    <div class="mt-4">{{ $roles->links() }}</div>
@endsection
