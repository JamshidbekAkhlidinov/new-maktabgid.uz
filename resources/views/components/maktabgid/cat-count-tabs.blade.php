@props(['tabs' => [], 'active' => null])

<section class="section cat-strip">
    <div class="wrap">
        <x-maktabgid.cat-tabs-list :tabs="$tabs" :active="$active" />
    </div>
</section>
