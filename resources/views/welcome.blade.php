@php
    use App\Enums\SettingKey;
    use App\Models\Setting;
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'MaktabGID') }} — {{ __('home.meta_title_suffix') }}</title>
    <meta name="description" content="{{ Setting::get(SettingKey::MetaDescription) }}" />
    <meta property="og:title" content="{{ Setting::get(SettingKey::MetaTitle) ?: (config('app.name', 'MaktabGID').' — '.__('home.meta_title_suffix')) }}" />
    <meta property="og:description" content="{{ Setting::get(SettingKey::MetaDescription) }}" />
    <meta property="og:type" content="website" />
    @if ($ogImage = Setting::get(SettingKey::OgImage))
        <meta property="og:image" content="{{ $ogImage }}" />
    @endif
    @if ($googleVerification = Setting::get(SettingKey::GoogleSiteVerification))
        <meta name="google-site-verification" content="{{ $googleVerification }}" />
    @endif
    @if ($yandexVerification = Setting::get(SettingKey::YandexVerification))
        <meta name="yandex-verification" content="{{ $yandexVerification }}" />
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php
        use App\Models\InstitutionType;
        use App\Support\MaktabgidData;
        $categories = MaktabgidData::categories();
        $districts = MaktabgidData::districts();
        $priceBands = MaktabgidData::priceBands();
        $distanceBands = MaktabgidData::distanceBands();
        $schools = MaktabgidData::schools();
        $vacancies = MaktabgidData::vacancies();
        $blog = MaktabgidData::blog();
        $forumThreads = MaktabgidData::forumThreads();
        $specializations = MaktabgidData::specializations();
        $defaultCat = $categories[0]['key'];
        $defaultResults = array_values(array_filter($schools, fn ($s) => $s['cat'] === $defaultCat));

        /* Natijalar boʻlimi ustidagi toifa-tab satri (sonlar bilan) — admin "Muassasa turlari"da
           "faol" deb belgilagan turlar, seed qilingan tartibda (id boʻyicha) chiqadi (2026-08-08). */
        $catCounts = collect($schools)->countBy('cat');
        $catTabs = InstitutionType::where('is_active', true)->orderBy('id')->get()
            ->map(fn (InstitutionType $t) => [
                'key' => $t->key,
                'label' => $t->label,
                'icon' => $t->icon,
                'count' => $catCounts->get($t->key, 0),
            ])->all();
    @endphp

    {{-- ===================== DESKTOP / TABLET ===================== --}}
    <div class="desktop-shell">
        <x-maktabgid.nav :categories="$categories" />
        <x-maktabgid.hero :categories="$categories" :districts="$districts" :cat-tabs="$catTabs" :active="$defaultCat" />

        {{-- "Ixtisoslik boʻyicha qidiring" boʻlimi vaqtincha oʻchirilgan, oʻrniga reklama banneri chiqadi --}}
        {{-- <x-maktabgid.spec-strip :specs="$specializations" /> --}}
        <x-maktabgid.ad-banner />

        <x-maktabgid.cat-count-tabs :tabs="$catTabs" :active="$defaultCat" />

        <main class="results" id="natijalar">
            <div class="wrap">
                <div class="results-grid" id="js-results-grid">
                    <x-maktabgid.filters :price-bands="$priceBands" :distance-bands="$distanceBands" :districts="$districts" />

                    <section>
                        <div class="results-head" id="js-results-head">
                            <div class="results-count">
                                <b id="js-results-count">{{ __('home.results_count', ['count' => count($defaultResults), 'cat' => MaktabgidData::categoryLabel($defaultCat)]) }}</b>
                                <span>{{ __('home.found_in_tashkent') }}</span>
                            </div>
                            <div class="sortbar">
                                <span class="select-pill">
                                    <x-maktabgid.icon name="sliders" :width="16" :height="16" />
                                    <select id="js-sort" aria-label="{{ __('home.sort_label') }}">
                                        <option value="rel">{{ __('home.sort_recommended') }}</option>
                                        <option value="priceA">{{ __('home.sort_price_asc') }}</option>
                                        <option value="priceD">{{ __('home.sort_price_desc') }}</option>
                                        <option value="dist">{{ __('home.sort_nearest') }}</option>
                                        <option value="rating">{{ __('home.sort_rating') }}</option>
                                    </select>
                                </span>
                            </div>
                        </div>

                        <div id="js-empty" style="display:none;padding:60px 20px;text-align:center;color:var(--ink-3);background:var(--surface);border:1px solid var(--line);border-radius:var(--r-lg)">
                            <div style="font-weight:700;color:var(--ink);font-family:var(--font-display);font-size:20px;margin-bottom:8px">{{ __('home.empty_title') }}</div>
                            {{ __('home.empty_hint_before') }} <button type="button" id="js-empty-reset" style="color:var(--primary);font-weight:700">{{ __('home.empty_reset') }}</button>.
                        </div>

                        <div class="card-list" id="js-card-list">
                            @foreach ($schools as $s)
                                <x-maktabgid.school-card :school="$s" />
                            @endforeach
                        </div>

                        {{-- Sahifalash — har sahifada 20 tadan natija, istalgan sahifaga toʻgʻridan-toʻgʻri oʻtish mumkin. --}}
                        <nav id="js-pagination" class="pagination" aria-label="{{ __('home.pagination') }}"></nav>
                    </section>

                    <x-maktabgid.map :schools="$schools" />
                </div>
            </div>
        </main>
    </div>

    {{-- Mobil ko'rinish vaqtincha o'chirilgan — keyinroq alohida to'liq mobil dizayn qilinadi --}}

    <x-maktabgid.forum-strip :threads="$forumThreads" />
    <x-maktabgid.trust-strip />
    <x-maktabgid.vacancies :vacancies="$vacancies" />
    <x-maktabgid.blog :posts="$blog" />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU{{ config('services.yandex.key') ? '&apikey='.config('services.yandex.key') : '' }}"></script>
    <script src="{{ asset('js/maktabgid.js') }}"></script>
    {{-- Admin panelda (/admin/settings) kiritilgan xom kod (GA/Metrika) — faqat Super Admin
         tahrirlay oladi, shuning uchun ataylab escape qilinmagan chiqariladi. --}}
    {!! Setting::get(SettingKey::CustomJs) !!}
</body>
</html>
