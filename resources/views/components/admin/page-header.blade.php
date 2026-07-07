@props(['title' => null, 'total' => null, 'itemLabel' => 'yozuv', 'createRoute' => null, 'createLabel' => '+ Qo\'shish'])

<div class="flex items-center justify-between mb-5">
    <div>
        @if ($title)
            <h2 class="text-xl font-semibold text-slate-900">{{ $title }}</h2>
        @elseif (! is_null($total))
            <p class="text-sm text-slate-500">Jami <span class="font-semibold text-slate-700">{{ $total }}</span> ta {{ $itemLabel }}</p>
        @endif
    </div>

    <div class="flex items-center gap-2">
        {{ $slot ?? '' }}

        @if ($createRoute)
            <a href="{{ route($createRoute) }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-4 py-2.5 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                {{ $createLabel }}
            </a>
        @endif
    </div>
</div>
