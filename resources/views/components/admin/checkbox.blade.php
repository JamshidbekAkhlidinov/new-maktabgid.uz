@props(['name', 'label' => null, 'checked' => false, 'value' => '1'])

@php($isChecked = old($name, $checked))

<label class="flex items-center gap-2 text-sm text-slate-700">
    <input type="hidden" name="{{ $name }}" value="0" />
    <input
        type="checkbox"
        name="{{ $name }}"
        value="{{ $value }}"
        @checked($isChecked)
        {{ $attributes->merge(['class' => 'rounded border-slate-300 text-indigo-600 focus:ring-indigo-500']) }}
    />
    {{ $label }}
</label>
