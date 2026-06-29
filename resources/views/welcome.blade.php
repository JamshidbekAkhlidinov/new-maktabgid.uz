<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'MaktabGID') }} — Farzandingizga mos maktabni toping</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php
        use App\Support\MaktabgidData;
        $categories = MaktabgidData::categories();
        $districts = MaktabgidData::districts();
        $priceBands = MaktabgidData::priceBands();
        $distanceBands = MaktabgidData::distanceBands();
        $schools = MaktabgidData::schools();
        $vacancies = MaktabgidData::vacancies();
        $blog = MaktabgidData::blog();
        $defaultCat = $categories[0]['key'];
        $defaultResults = array_values(array_filter($schools, fn ($s) => $s['cat'] === $defaultCat));
    @endphp

    {{-- ===================== DESKTOP / TABLET ===================== --}}
    <div class="desktop-shell">
        <x-maktabgid.nav :categories="$categories" />
        <x-maktabgid.hero :categories="$categories" :districts="$districts" :total="103" />

        <main class="results" id="natijalar">
            <div class="wrap">
                <div class="results-grid" id="js-results-grid">
                    <x-maktabgid.filters :price-bands="$priceBands" :distance-bands="$distanceBands" :districts="$districts" />

                    <section>
                        <div class="results-head">
                            <div class="results-count">
                                <b id="js-results-count">{{ count($defaultResults) }} ta {{ MaktabgidData::categoryLabel($defaultCat) }}</b>
                                <span>Toshkent boʻyicha topildi</span>
                            </div>
                            <div class="sortbar">
                                <span class="select-pill">
                                    <x-maktabgid.icon name="sliders" :width="16" :height="16" />
                                    <select id="js-sort">
                                        <option value="rel">Tavsiya etilgan</option>
                                        <option value="priceA">Narx: arzondan</option>
                                        <option value="priceD">Narx: qimmatdan</option>
                                        <option value="dist">Eng yaqin</option>
                                        <option value="rating">Eng yuqori reyting</option>
                                    </select>
                                </span>
                            </div>
                        </div>

                        <div id="js-empty" style="display:none;padding:60px 20px;text-align:center;color:var(--ink-3);background:var(--surface);border:1px solid var(--line);border-radius:var(--r-lg)">
                            <div style="font-weight:700;color:var(--ink);font-family:var(--font-display);font-size:20px;margin-bottom:8px">Hech narsa topilmadi</div>
                            Filtrlarni kengaytirib koʻring yoki <button type="button" id="js-empty-reset" style="color:var(--primary);font-weight:700">tozalang</button>.
                        </div>

                        <div class="card-list" id="js-card-list">
                            @foreach ($schools as $s)
                                <x-maktabgid.school-card :school="$s" />
                            @endforeach
                        </div>
                    </section>

                    <x-maktabgid.map :schools="$schools" />
                </div>
            </div>
        </main>
    </div>

    {{-- ===================== MOBILE (Mobil ilova uslubi) ===================== --}}
    <div class="mobile-shell" id="m-top">
        <x-maktabgid.mobile-app :categories="$categories" :schools="$schools" :total="103" />
    </div>

    <x-maktabgid.trust-strip />
    <x-maktabgid.vacancies :vacancies="$vacancies" />
    <x-maktabgid.blog :posts="$blog" />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    <script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>
