@props(['id', 'width' => 480])

<div class="modal-scrim js-modal" id="{{ $id }}" hidden>
    <div class="modal-card" style="max-width:{{ $width }}px">
        <button type="button" class="modal-x js-modal-close" aria-label="Yopish"><x-maktabgid.icon name="close" :width="20" :height="20" /></button>
        {{ $slot }}
    </div>
</div>
