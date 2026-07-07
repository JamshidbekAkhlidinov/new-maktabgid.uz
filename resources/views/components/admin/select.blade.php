@props(['name', 'label' => null, 'options' => [], 'value' => null, 'required' => false, 'placeholder' => null])

@php($selected = old($name, $value))

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-1">
            {{ $label }} @if($required)<span class="text-rose-500">*</span>@endif
        </label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'w-full rounded-lg border px-3.5 py-2 text-sm bg-white focus:outline-none focus:ring-1 ' . ($errors->has($name) ? 'border-rose-400 focus:ring-rose-400' : 'border-slate-300 focus:ring-indigo-500 focus:border-indigo-500')]) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @if (count($options))
            @foreach ($options as $optValue => $optLabel)
                <option value="{{ $optValue }}" @selected((string) $selected === (string) $optValue)>{{ $optLabel }}</option>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>

    @error($name)
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
    @enderror
</div>
