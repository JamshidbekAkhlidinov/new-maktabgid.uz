@php
    // Real galereya — App\Models\InstitutionMedia (type=gallery). Yuklash/o'chirish
    // /ajax/institution/me/media orqali ishlaydi (ADR-0002, Faza 1).
    $maxGallery = 10;
@endphp

<x-institution.shell
    active="gallery"
    title="Rasmlar"
    sub="Muassasangiz galereyasi — profil sahifasida ko'rinadi"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-toolbar">
        <span class="idash-chart-meta" id="js-media-count">{{ $galleryMedia->count() }} / {{ $maxGallery }} rasm yuklangan · JPG, PNG, WebP — maks 5 MB</span>
    </div>

    <div class="idash-gallery">
        @foreach ($galleryMedia as $m)
            <div class="idash-gallery-tile" style="background-image:url('{{ $m->url }}');background-size:cover;background-position:center">
                <span class="idash-gallery-cap">{{ $m->caption ?? $institution->name }}</span>
                <button type="button" class="idash-gallery-del js-media-delete" data-media-id="{{ $m->id }}" title="O'chirish">
                    <x-maktabgid.icon name="close" :width="14" :height="14" />
                </button>
            </div>
        @endforeach

        @if ($galleryMedia->count() < $maxGallery)
            <label class="idash-gallery-add js-media-upload" data-media-type="gallery" data-reload="1">
                <input type="file" accept="image/*" hidden />
                <x-maktabgid.icon name="upload" :width="26" :height="26" />
                <span>Yana {{ $maxGallery - $galleryMedia->count() }} ta rasm qo'shish mumkin</span>
            </label>
        @endif
    </div>

    @if ($galleryMedia->isEmpty())
        <div class="idash-badge-soft">
            <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Hali rasm yuklanmagan — yuqoridagi katakka bosib birinchi rasmingizni qo'shing
        </div>
    @endif

    @endif
</x-institution.shell>
