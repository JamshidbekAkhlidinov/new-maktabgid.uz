@php
    // Real galereya — App\Models\InstitutionMedia (type=gallery). Yuklash/o'chirish
    // /ajax/institution/me/media orqali ishlaydi (ADR-0002, Faza 1).
    $maxGallery = 10;
@endphp

<x-institution.shell
    active="gallery"
    title="{{ __('cabinet_institution.nav_gallery') }}"
    sub="{{ __('cabinet_institution.gallery_sub') }}"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-toolbar">
        <span class="idash-chart-meta" id="js-media-count">{{ __('cabinet_institution.gallery_toolbar_meta', ['count' => $galleryMedia->count(), 'max' => $maxGallery]) }}</span>
    </div>

    <div class="idash-gallery">
        @foreach ($galleryMedia as $m)
            <div class="idash-gallery-tile" style="background-image:url('{{ $m->url }}');background-size:cover;background-position:center">
                <span class="idash-gallery-cap">{{ $m->caption ?? $institution->name }}</span>
                <button type="button" class="idash-gallery-del js-media-delete" data-media-id="{{ $m->id }}" title="{{ __('cabinet_institution.delete') }}">
                    <x-maktabgid.icon name="close" :width="14" :height="14" />
                </button>
            </div>
        @endforeach

        @if ($galleryMedia->count() < $maxGallery)
            <label class="idash-gallery-add js-media-upload" data-media-type="gallery" data-reload="1">
                <input type="file" accept="image/*" hidden />
                <x-maktabgid.icon name="upload" :width="26" :height="26" />
                <span>{{ __('cabinet_institution.add_more_photos', ['count' => $maxGallery - $galleryMedia->count()]) }}</span>
            </label>
        @endif
    </div>

    @if ($galleryMedia->isEmpty())
        <div class="idash-badge-soft">
            <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> {{ __('cabinet_institution.gallery_empty_notice') }}
        </div>
    @endif

    @endif
</x-institution.shell>
