@extends('admin.layout')

@section('title', 'Reklama qo\'shish')

@section('content')
    <x-admin.page-header title="Reklama qo'shish" />

    <x-admin.card>
        <form method="POST" action="{{ route('admin.advertisements.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.advertisements._form')

            <div class="mt-6 flex gap-3">
                <button class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5">Saqlash</button>
                <a href="{{ route('admin.advertisements.index') }}" class="rounded-lg border border-slate-300 text-slate-700 text-sm font-medium px-5 py-2.5">Bekor qilish</a>
            </div>
        </form>
    </x-admin.card>
@endsection
