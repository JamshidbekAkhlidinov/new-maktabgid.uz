@props(['lessons'])

@if (count($lessons))
<section class="card-block">
    <h3><x-maktabgid.icon name="camera" :width="19" :height="19" /> {{ __('school.lessons_title') }}</h3>
    <div class="lesson-grid">
        @foreach ($lessons as $i => $l)
            <x-maktabgid.detail.photo-tile :icon="$l['icon']" :label="$l['label']" :gi="$i + 2" />
        @endforeach
    </div>
</section>
@endif
