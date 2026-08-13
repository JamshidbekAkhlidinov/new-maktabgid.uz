@props([
    'name' => 'image',
    'label' => null,
    'value' => null,
    'hint' => null,
])

@php($inputId = 'img-upload-'.$name)

<div>
    @if ($label)
        <label class="block text-sm font-medium text-slate-700 mb-1">{{ $label }}</label>
    @endif

    <label
        for="{{ $inputId }}"
        class="group relative flex h-40 w-full max-w-sm cursor-pointer flex-col items-center justify-center gap-1.5 overflow-hidden rounded-lg border-2 border-dashed bg-slate-50 bg-cover bg-center text-center transition hover:border-indigo-400 hover:bg-indigo-50/40 {{ $errors->has($name) ? 'border-rose-300' : 'border-slate-300' }}"
        @if ($value) style="background-image:url('{{ $value }}')" @endif
        data-image-upload
    >
        <div data-image-upload-placeholder class="flex flex-col items-center gap-1.5 px-4 py-3 {{ $value ? 'rounded-lg bg-white/90' : '' }}">
            <x-admin.icon name="upload" class="w-6 h-6 text-slate-400 group-hover:text-indigo-500" />
            <span data-image-upload-text class="text-xs font-medium text-slate-500 group-hover:text-indigo-600">
                {{ $value ? "Rasmni almashtirish uchun bosing" : 'Rasm tanlash uchun bosing' }}
            </span>
            <span data-image-upload-filename class="text-[11px] text-slate-400"></span>
        </div>

        <input type="file" id="{{ $inputId }}" name="{{ $name }}" accept="image/*" class="hidden" data-image-upload-input />
    </label>

    @if ($value)
        <p class="mt-1.5 max-w-sm truncate text-xs text-slate-400">
            <a href="{{ $value }}" target="_blank" rel="noopener" class="hover:text-indigo-600 hover:underline">{{ $value }}</a>
        </p>
    @endif

    @if ($hint)
        <p class="mt-1 text-xs text-slate-400">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
    @enderror
</div>

@once
    <script>
        document.addEventListener('change', function (e) {
            if (!e.target.matches('[data-image-upload-input]')) return;

            const input = e.target;
            const box = input.closest('[data-image-upload]');
            const file = input.files && input.files[0];
            if (!box || !file) return;

            const reader = new FileReader();
            reader.onload = function (ev) {
                box.style.backgroundImage = `url('${ev.target.result}')`;

                const placeholder = box.querySelector('[data-image-upload-placeholder]');
                placeholder.classList.add('rounded-lg', 'bg-white/90');

                const textEl = box.querySelector('[data-image-upload-text]');
                if (textEl) textEl.textContent = 'Rasmni almashtirish uchun bosing';

                const filenameEl = box.querySelector('[data-image-upload-filename]');
                if (filenameEl) filenameEl.textContent = file.name;
            };
            reader.readAsDataURL(file);
        });
    </script>
@endonce
