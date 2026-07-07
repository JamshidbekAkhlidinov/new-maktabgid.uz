@extends('admin.layout')

@section('title', 'Huquqlar')

@section('content')
    <p class="text-sm text-slate-500 mb-5">
        Huquqlar ro'yxati kodda belgilangan va o'zgarmas — ularni rollarga biriktirish esa
        <a href="{{ route('admin.roles.index') }}" class="text-indigo-600 hover:underline">Rollar</a> bo'limidan amalga oshiriladi.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($groups as $group => $permissions)
            <x-admin.card>
                <p class="text-sm font-semibold text-slate-800 mb-2 capitalize">{{ $group }}</p>
                <ul class="space-y-1 text-sm text-slate-600">
                    @foreach ($permissions as $permission)
                        <li>{{ str($permission->name)->after('.') }}</li>
                    @endforeach
                </ul>
            </x-admin.card>
        @endforeach
    </div>
@endsection
