@props(['name', 'label' => null, 'value' => null, 'required' => false])

@php
    // x-maktabgid.icon komponentidagi barcha ikonka nomlari (resources/views/components/maktabgid/icon.blade.php).
    // Specialization.icon maydoni shu nomlardan birini saqlaydi (spec-badge.blade.php shu orqali chizadi),
    // shu sababli tanlov shu ro'yxat bilan cheklanadi — admin tasodifan noto'g'ri nom kiritmasin.
    $icons = [
        'search', 'pin', 'star', 'heart', 'sliders', 'chevron', 'chevronR', 'close', 'school', 'book',
        'teddy', 'clock', 'globe', 'check', 'arrowR', 'grid', 'map', 'phone', 'send', 'users',
        'bag', 'cal', 'sparkle', 'plus', 'minus', 'shield', 'chat', 'robot', 'news', 'forum',
        'edit', 'mail', 'lock', 'user', 'arrowL', 'building', 'upload', 'ticket', 'like', 'logout',
        'eye', 'award', 'flask', 'palette', 'code', 'music', 'trophy', 'dumbbell', 'paperclip', 'play',
        'camera', 'image', 'bus', 'cup', 'cross', 'wifi', 'leaf', 'target', 'badge', 'trending',
        'card', 'bell', 'layers', 'download',
    ];
    $selected = old($name, $value);
    // Sahifada shu komponent bir necha marta ishlatilsa ham id to'qnashmasin
    // (masalan kelajakda filtr formasida ham qo'llanilsa).
    $pickerId = 'icon-picker-' . $name . '-' . uniqid();
@endphp

<div id="{{ $pickerId }}">
    @if ($label)
        <label class="block text-sm font-medium text-slate-700 mb-1">
            {{ $label }} @if($required)<span class="text-rose-500">*</span>@endif
        </label>
    @endif

    <div class="rounded-lg border {{ $errors->has($name) ? 'border-rose-400' : 'border-slate-300' }} bg-white p-2">
        <div class="grid grid-cols-8 sm:grid-cols-10 gap-1.5 max-h-56 overflow-y-auto p-1" data-icon-picker-grid>
            @foreach ($icons as $iconName)
                <label class="cursor-pointer" title="{{ $iconName }}">
                    <input
                        type="radio"
                        name="{{ $name }}"
                        value="{{ $iconName }}"
                        class="peer sr-only"
                        data-icon-picker-input
                        {{ $selected === $iconName ? 'checked' : '' }}
                        @if($required) required @endif
                    />
                    <span class="flex items-center justify-center w-9 h-9 rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 hover:border-slate-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-600 peer-checked:ring-1 peer-checked:ring-indigo-500 transition">
                        <x-maktabgid.icon :name="$iconName" :width="18" :height="18" />
                    </span>
                </label>
            @endforeach
        </div>
    </div>

    <p class="mt-1 text-xs text-slate-500 flex items-center gap-1.5" data-icon-picker-preview>
        <span>Tanlangan:</span>
        <span class="inline-flex items-center gap-1 font-medium text-slate-700" data-icon-picker-preview-content>
            @if ($selected)
                <x-maktabgid.icon :name="$selected" :width="14" :height="14" /> <span>{{ $selected }}</span>
            @else
                &mdash;
            @endif
        </span>
    </p>

    @error($name)
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
    @enderror
</div>

@once
    <script>
        // Diqqat: admin panelda Alpine.js yo'q (resources/views/admin/layout.blade.php
        // faqat Vite orqali app.css va app.js yuklaydi va boshqa joylarda plain
        // details/summary ishlatiladi), shu sababli bu yerda ham vanilla JS bilan
        // "Tanlangan" ikonka nomini live yangilaymiz. Blade "once" bloki — bir nechta
        // icon-picker komponenti bo'lsa ham bu skript faqat bir marta chiqadi (event
        // delegation document darajasida ishlaydi, har bir picker uchun alohida shart emas).
        document.addEventListener('change', function (e) {
            var input = e.target.closest('[data-icon-picker-input]');
            if (!input) return;
            var picker = input.closest('[id^="icon-picker-"]');
            if (!picker) return;
            var preview = picker.querySelector('[data-icon-picker-preview-content]');
            var chosen = picker.querySelector('[data-icon-picker-grid] input:checked');
            if (!preview || !chosen) return;
            var svg = chosen.parentElement.querySelector('svg');
            preview.innerHTML = '';
            if (svg) {
                preview.appendChild(svg.cloneNode(true));
            }
            var nameSpan = document.createElement('span');
            nameSpan.textContent = chosen.value;
            preview.appendChild(nameSpan);
        });
    </script>
@endonce
