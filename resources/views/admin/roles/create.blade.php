@extends('admin.layout')

@section('title', 'Rol qo\'shish')

@section('content')
    <x-admin.page-header title="Rol qo'shish" />

    <x-admin.card>
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf

            <div class="max-w-sm mb-6">
                <x-admin.input name="name" label="Rol nomi" required placeholder="Masalan: Kontent menejeri" />
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
