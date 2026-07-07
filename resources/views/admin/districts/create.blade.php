@extends('admin.layout')

@section('title', 'Tuman qo\'shish')

@section('content')
    <x-admin.page-header title="Tuman qo'shish" />

    <x-admin.card class="max-w-lg">
        <form method="POST" action="{{ route('admin.districts.store') }}">
            @csrf
            @include('admin.districts._form')

            <div class="mt-6 flex gap-3">
                <button class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5">Saqlash</button>
                <a href="{{ route('admin.districts.index') }}" class="rounded-lg border border-slate-300 text-slate-700 text-sm font-medium px-5 py-2.5">Bekor qilish</a>
            </div>
        </form>
    </x-admin.card>
@endsection
