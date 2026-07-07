@props(['route', 'permission' => null, 'icon' => null])

@php
    $show = ! $permission || auth()->user()?->can($permission);
    $active = request()->routeIs(str($route)->before('.index') . '*');
@endphp

@if ($show)
    <a href="{{ route($route) }}"
       class="flex items-center gap-2.5 pl-4 pr-3 py-2 mx-2 rounded-lg border-l-2 transition
              {{ $active ? 'bg-white/10 text-white border-amber-400' : 'text-slate-300 border-transparent hover:bg-white/5 hover:text-white' }}">
        <span class="text-base leading-none">{{ $icon }}</span>
        <span>{{ $slot }}</span>
    </a>
@endif
