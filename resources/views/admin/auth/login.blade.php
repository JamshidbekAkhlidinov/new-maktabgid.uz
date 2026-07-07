<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Admin panelga kirish — {{ config('app.name', 'MaktabGID') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-900 flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-white">MaktabGID <span class="text-indigo-400">Admin</span></h1>
            <p class="text-slate-400 text-sm mt-1">Boshqaruv paneliga kirish</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.attempt') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Telefon raqami</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+998901234567" required autofocus
                           class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Parol</label>
                    <input type="password" name="password" required
                           class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none" />
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300" />
                    Meni eslab qol
                </label>

                <button type="submit"
                        class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 text-sm transition">
                    Kirish
                </button>
            </form>
        </div>
    </div>
</body>
</html>
