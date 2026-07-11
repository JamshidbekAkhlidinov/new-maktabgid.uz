<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Admin panelga kirish — {{ config('app.name', 'MaktabGID') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen relative overflow-hidden bg-gradient-to-br from-teal-50 via-white to-teal-100 flex flex-col items-center justify-center px-4 py-12">
    {{-- Fon uchun yumshoq dekorativ bo'yoqlar --}}
    <div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 rounded-full bg-teal-200/40 blur-3xl"></div>
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-teal-200/40 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-32 -left-24 w-96 h-96 rounded-full bg-teal-100/60 blur-3xl"></div>

    <div class="w-full max-w-md relative">
        <a href="{{ route('welcome') }}" class="flex items-center justify-center gap-2.5 mb-8">
            <span class="flex items-center justify-center w-10 h-10 rounded-2xl bg-[#0d6e6a] text-white shrink-0">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 10l9-5 9 5-9 5-9-5z"></path>
                    <path d="M7 12.5V17c0 1 2.2 2.5 5 2.5s5-1.5 5-2.5v-4.5"></path>
                    <path d="M21 10v5"></path>
                </svg>
            </span>
            <span class="text-2xl font-semibold text-slate-900">Maktab<b class="font-extrabold">GID</b></span>
        </a>

        <div class="bg-white rounded-3xl shadow-2xl shadow-teal-900/20 p-8">
            <h1 class="text-2xl font-bold text-slate-900">Xush kelibsiz!</h1>
            <p class="text-sm text-slate-500 mt-1 mb-6">Hisobingizga kiring</p>

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
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email manzil</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus
                           class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm placeholder:text-slate-400 focus:border-teal-600 focus:ring-teal-600 focus:outline-none" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Parol</label>
                    <input type="password" name="password" placeholder="Kamida 8 ta belgi" required
                           class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm placeholder:text-slate-400 focus:border-teal-600 focus:ring-teal-600 focus:outline-none" />
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#0d6e6a] focus:ring-teal-600" />
                    Meni eslab qol
                </label>

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#0d6e6a] hover:bg-[#0a5754] text-white font-semibold py-3 text-sm transition">
                    Kirish <span aria-hidden="true">→</span>
                </button>
            </form>

            <div class="flex items-center gap-3 my-6">
                <span class="flex-1 h-px bg-slate-200"></span>
                <span class="text-xs text-slate-400">yoki</span>
                <span class="flex-1 h-px bg-slate-200"></span>
            </div>

            <a href="{{ route('admin.google.redirect') }}"
               class="w-full flex items-center justify-center gap-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-medium py-3 text-sm transition">
                <svg class="w-4.5 h-4.5" width="18" height="18" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M23.49 12.27c0-.79-.07-1.54-.2-2.27H12v4.51h6.47a5.54 5.54 0 0 1-2.4 3.63v3.02h3.88c2.27-2.09 3.54-5.17 3.54-8.89z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.07 7.94-2.91l-3.88-3.02c-1.08.72-2.45 1.15-4.06 1.15-3.13 0-5.78-2.11-6.73-4.96H1.27v3.11A11.99 11.99 0 0 0 12 24z"/>
                    <path fill="#FBBC05" d="M5.27 14.26A7.2 7.2 0 0 1 4.89 12c0-.78.14-1.55.38-2.26V6.63H1.27A11.99 11.99 0 0 0 0 12c0 1.94.46 3.77 1.27 5.37l4-3.11z"/>
                    <path fill="#EA4335" d="M12 4.77c1.76 0 3.35.61 4.6 1.8l3.44-3.44C17.94 1.19 15.24 0 12 0 7.31 0 3.26 2.69 1.27 6.63l4 3.11C6.22 6.88 8.87 4.77 12 4.77z"/>
                </svg>
                Google bilan kirish
            </a>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">
            © {{ now()->year }} MaktabGID. Barcha huquqlar himoyalangan.
        </p>
    </div>
</body>
</html>
