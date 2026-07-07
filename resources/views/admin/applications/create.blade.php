@extends('admin.layout')

@section('title', 'Ariza qo\'shish')

@section('content')
    <x-admin.page-header title="Ariza qo'shish" />

    <x-admin.card>
        <form method="POST" action="{{ route('admin.applications.store') }}">
            @csrf
            @include('admin.applications._form')

            <div class="mt-6 flex gap-3">
                <button class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5">Saqlash</button>
                <a href="{{ route('admin.applications.index') }}" class="rounded-lg border border-slate-300 text-slate-700 text-sm font-medium px-5 py-2.5">Bekor qilish</a>
            </div>
        </form>
    </x-admin.card>
@endsection
