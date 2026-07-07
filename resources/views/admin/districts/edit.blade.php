@extends('admin.layout')

@section('title', 'Tumanni tahrirlash')

@section('content')
    <x-admin.page-header title="Tumanni tahrirlash: {{ $district->name }}" />

    <x-admin.card class="max-w-lg">
        <form method="POST" action="{{ route('admin.districts.update', $district) }}">
            @csrf
            @method('PUT')
            @include('admin.districts._form')

            <div class="mt-6 flex gap-3">
                <button class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5">Saqlash</button>
                <a href="{{ route('admin.districts.index') }}" class="rounded-lg border border-slate-300 text-slate-700 text-sm font-medium px-5 py-2.5">Bekor qilish</a>
            </div>
        </form>
    </x-admin.card>
@endsection
