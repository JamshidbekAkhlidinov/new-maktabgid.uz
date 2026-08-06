@props(['title', 'cta' => null, 'closeTarget' => null])

@php
    $cta = $cta ?? __('common.close');
@endphp

<div {{ $attributes->merge(['class' => 'success-note']) }}>
    <span class="success-ico"><x-maktabgid.icon name="check" :width="26" :height="26" /></span>
    <h4>{{ $title }}</h4>
    <p>{{ $slot }}</p>
    @if ($closeTarget)
        <button type="button" class="btn btn-primary js-modal-close">{{ $cta }}</button>
    @endif
</div>
