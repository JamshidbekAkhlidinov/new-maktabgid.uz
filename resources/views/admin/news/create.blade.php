@extends('admin.layout')

@section('title', 'Yangilik qo\'shish')

@section('content')
    <x-admin.page-header title="Yangilik qo'shish" />

    <x-admin.card>
        <form method="POST" action="{{ route('admin.news.store') }}">
            @csrf
            @include('admin.news._form')

            <div class="mt-6 flex gap-3">
                <button class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5">Saqlash</button>
                <a href="{{ route('admin.news.index') }}" class="rounded-lg border border-slate-300 text-slate-700 text-sm font-medium px-5 py-2.5">Bekor qilish</a>
            </div>
        </form>
    </x-admin.card>
@endsection
