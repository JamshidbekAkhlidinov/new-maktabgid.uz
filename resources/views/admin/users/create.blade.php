@extends('admin.layout')

@section('title', 'Foydalanuvchi qo\'shish')

@section('content')
    <x-admin.page-header title="Foydalanuvchi qo'shish" />

    <x-admin.card>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            @include('admin.users._form')

            <div class="mt-6 flex gap-3">
                <button class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5">Saqlash</button>
                <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-300 text-slate-700 text-sm font-medium px-5 py-2.5">Bekor qilish</a>
            </div>
        </form>
    </x-admin.card>
@endsection
