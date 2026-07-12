@php
    // Mock: galereya — real yuklash/saqlash InstitutionMedia orqali keyinroq ulanadi
    // (hozircha bu model asosan video uchun ishlatiladi — profile.blade.php'ga qarang).
    $mockGallery = [
        ['cap' => 'Bosh bino · fasad', 'grad' => 'linear-gradient(140deg,#0e8a86,#0a625e)'],
        ['cap' => 'Sinf xonasi', 'grad' => 'linear-gradient(140deg,#2f6fed,#1c4fc2)'],
        ['cap' => 'Kompyuter xonasi', 'grad' => 'linear-gradient(140deg,#6d5cf6,#4535c9)'],
        ['cap' => 'Sport zali', 'grad' => 'linear-gradient(140deg,#f0852e,#c2611a)'],
        ['cap' => 'Kutubxona', 'grad' => 'linear-gradient(140deg,#c2247a,#8f1a5a)'],
        ['cap' => 'Hovli va bog\'', 'grad' => 'linear-gradient(140deg,#16a34a,#0e7a37)'],
    ];
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
        <span class="idash-chart-meta">{{ count($mockGallery) }} / 10 rasm yuklangan · JPG, PNG, WebP — maks 5 MB</span>
        <button type="button" class="btn btn-primary sm">
            <x-maktabgid.icon name="upload" :width="15" :height="15" /> Rasm yuklash
        </button>
    </div>

    <div class="idash-gallery">
        @foreach ($mockGallery as $g)
            <div class="idash-gallery-tile" style="background:{{ $g['grad'] }}">
                <span class="idash-gallery-cap">{{ $g['cap'] }}</span>
                <button type="button" class="idash-gallery-del" title="O'chirish"><x-maktabgid.icon name="close" :width="14" :height="14" /></button>
            </div>
        @endforeach
        <button type="button" class="idash-gallery-add">
            <x-maktabgid.icon name="upload" :width="26" :height="26" />
            Yana {{ 10 - count($mockGallery) }} ta rasm qo'shish mumkin
        </button>
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Bu bo'lim demo ko'rinishda — real fayl yuklash tez orada ulanadi
    </div>

    @endif
</x-institution.shell>
