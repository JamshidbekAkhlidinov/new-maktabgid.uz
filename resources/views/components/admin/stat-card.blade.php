@props(['label', 'value', 'icon' => null])

<div class="bg-white rounded-xl border border-slate-200 p-4">
    <div class="flex items-center justify-between">
        <span class="text-2xl">{{ $icon }}</span>
        <span class="text-2xl font-bold text-slate-900">{{ $value }}</span>
    </div>
    <p class="mt-1 text-sm text-slate-500">{{ $label }}</p>
</div>
