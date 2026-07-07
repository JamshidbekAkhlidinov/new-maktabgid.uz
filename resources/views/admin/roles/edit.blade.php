@extends('admin.layout')

@section('title', 'Rolni tahrirlash')

@section('content')
    <x-admin.page-header title="Rolni tahrirlash: {{ $role->name }}" />

    @if ($role->name === \App\Http\Controllers\Admin\RoleController::PROTECTED_ROLE)
        <div class="mb-4 rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm text-indigo-700">
            Bu himoyalangan rol — nomi va huquqlari o'zgartirilmaydi, u har doim barcha huquqlarga ega bo'ladi.
        </div>
    @endif

    <x-admin.card>
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf
            @method('PUT')

            <div class="max-w-sm mb-6">
                <x-admin.input name="name" label="Rol nomi" :value="$role->name" required
                    :disabled="$role->name === \App\Http\Controllers\Admin\RoleController::PROTECTED_ROLE" />
            </div>

            <p class="text-sm font-medium text-slate-700 mb-2">Huquqlar</p>
            @include('admin.roles._permissions')

            <div class="mt-6 flex gap-3">
                <button class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5">Saqlash</button>
                <a href="{{ route('admin.roles.index') }}" class="rounded-lg border border-slate-300 text-slate-700 text-sm font-medium px-5 py-2.5">Bekor qilish</a>
            </div>
        </form>
    </x-admin.card>
@endsection
