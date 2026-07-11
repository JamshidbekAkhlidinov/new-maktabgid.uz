@props(['label'])

<details class="group mt-2" open>
    <summary class="flex items-center justify-between px-5 py-2 text-xs font-semibold uppercase tracking-wide text-slate-400 cursor-pointer select-none list-none [&::-webkit-details-marker]:hidden hover:text-slate-600">
        <span>{{ $label }}</span>
        <svg class="w-3.5 h-3.5 shrink-0 transition-transform duration-150 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </summary>

    <div class="space-y-0.5 pb-1">
        {{ $slot }}
    </div>
</details>
