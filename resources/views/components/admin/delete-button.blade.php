@props(['action', 'confirm' => 'Rostdan ham o\'chirmoqchimisiz?'])

<form method="POST" action="{{ $action }}" onsubmit="return confirm('{{ $confirm }}')">
    @csrf
    @method('DELETE')
    <button type="submit" {{ $attributes->merge(['class' => 'text-rose-600 hover:text-rose-800 text-sm font-medium']) }}>
        O'chirish
    </button>
</form>
