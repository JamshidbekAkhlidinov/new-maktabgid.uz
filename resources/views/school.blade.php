<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $school['name'] }} — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php
        use App\Support\MaktabgidData;
        $s = $school;
        $catLabel = MaktabgidData::categoryLabel($s['cat']);
        $media = MaktabgidData::mediaFor($s['cat']);
        $stats = $s['stats'];
        $facilities = $s['facilities'];
        $teachers = $s['teachers'];
        $steps = $s['steps'];
        $reviews = MaktabgidData::reviews($s['id']);
        $ratingBars = MaktabgidData::ratingBars($s['id']);
    @endphp

    <x-maktabgid.nav :categories="MaktabgidData::categories()" />

    <div class="detail">
        <div class="wrap detail-topbar">
            <x-maktabgid.back-link href="{{ route('welcome') }}" label="{{ __('school.back_to_catalog') }}" />
            <div class="detail-topact">
                <button type="button" class="iconbtn js-fav" aria-label="{{ __('school.save') }}"><x-maktabgid.icon name="heart" :width="18" :height="18" /></button>
                <button type="button" class="iconbtn" aria-label="{{ __('school.share') }}"><x-maktabgid.icon name="send" :width="17" :height="17" /></button>
            </div>
        </div>

        <x-maktabgid.detail.gallery :media="$media" :photos="$s['photos']" />
        <x-maktabgid.detail.title-card :school="$s" :cat-label="$catLabel" :stats="$stats" />

        <div class="wrap detail-grid">
            <div class="detail-main">
                <x-maktabgid.detail.about :school="$s" :cat-label="$catLabel" />
                <x-maktabgid.detail.programs :programs="$s['programs']" />
                <x-maktabgid.detail.prices :prices="$s['prices']" />
                <x-maktabgid.detail.lessons :lessons="$s['lessons']" />
                <x-maktabgid.detail.videos :videos="$s['videos']" />
                <x-maktabgid.detail.facilities :facilities="$facilities" />
                <x-maktabgid.detail.teachers :teachers="$teachers" />
                <x-maktabgid.detail.achievements :achievements="$s['achievements']" />
                <x-maktabgid.detail.steps :steps="$steps" />
                <x-maktabgid.detail.enroll-form :school="$s" />
                <x-maktabgid.detail.reviews :school="$s" :reviews="$reviews" :rating-bars="$ratingBars" />
            </div>

            <x-maktabgid.detail.sidebar :school="$s" />
        </div>
    </div>

    <x-maktabgid.detail.excursion-modal :school="$s" />

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU{{ config('services.yandex.key') ? '&apikey='.config('services.yandex.key') : '' }}"></script>
    <script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>
