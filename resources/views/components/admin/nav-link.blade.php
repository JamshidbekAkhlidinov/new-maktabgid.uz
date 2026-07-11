@props(['route', 'permission' => null, 'icon' => null, 'badge' => null, 'badgeColor' => 'bg-slate-100 text-slate-500'])

@php
    $show = ! $permission || auth()->user()?->can($permission);
    $active = request()->routeIs(str($route)->before('.index') . '*');
@endphp

@if ($show)
    <a href="{{ route($route) }}"
       class="flex items-center gap-2.5 pl-3.5 pr-3 py-2 mx-2 rounded-lg text-sm font-medium transition
              {{ $active ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
        <x-admin.icon :name="$icon" class="w-[18px] h-[18px] shrink-0" />
        <span class="flex-1 truncate">{{ $slot }}</span>
        @if ($badge)
            <span class="text-[11px] font-semibold px-1.5 py-0.5 rounded-full {{ $badgeColor }} shrink-0">{{ $badge }}</span>
        @endif
    </a>
@endif
