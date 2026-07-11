@props([
    'label',
    'value',
    'icon' => null,
    'iconBg' => 'bg-slate-100',
    'iconColor' => 'text-slate-600',
    'badge' => null,
    'badgeColor' => 'bg-slate-100 text-slate-600',
    'manageHref' => null,
    'manageLabel' => 'Boshqarish',
    'href' => null,
])

@if ($href)
<a href="{{ $href }}" class="block bg-white rounded-xl border border-slate-200 p-4 hover:border-slate-300 hover:shadow-sm transition">
@else
<div class="bg-white rounded-xl border border-slate-200 p-4">
@endif
    <div class="flex items-center justify-between mb-3">
        <span class="flex items-center justify-center w-10 h-10 rounded-full {{ $iconBg }} {{ $iconColor }} shrink-0">
            <x-admin.icon :name="$icon" class="w-5 h-5" />
        </span>

        @if ($manageHref)
            <span class="text-xs font-medium text-teal-700 shrink-0 whitespace-nowrap">{{ $manageLabel }} →</span>
        @elseif ($badge)
            <span class="text-xs font-medium px-2 py-1 rounded-full {{ $badgeColor }} shrink-0 whitespace-nowrap">{{ $badge }}</span>
        @endif
    </div>
    <p class="text-2xl font-bold text-slate-900 leading-none">{{ $value }}</p>
    <p class="mt-1.5 text-sm text-slate-500">{{ $label }}</p>
@if ($href)
</a>
@else
</div>
@endif
