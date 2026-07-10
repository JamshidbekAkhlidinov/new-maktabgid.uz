@props(['categories' => [], 'districts' => [], 'total' => 103])

<section class="hero" id="top">
    <div class="hero-bg"></div>
    <div class="wrap hero-inner">
        <span class="eyebrow"><x-maktabgid.icon name="sparkle" :width="15" :height="15" /> Oʻzbekistondagi №1 taʼlim katalogi</span>
        <h1>Farzandingizga <span class="hl">mos maktabni</span> bir joyda toping</h1>
        <p class="sub">
            Xususiy maktablar, bogʻchalar va oʻquv markazlari — narxi, joylashuvi, dars vaqti va sharhlari bilan.
            Solishtiring, xaritada koʻring va toʻgʻri tanlov qiling.
        </p>

        <div class="hero-mode-toggle">
            <button type="button" class="mode-btn on js-mode-btn" data-mode="manual">
                <x-maktabgid.icon name="search" :width="16" :height="16" /> Oʻzim qidiraman
            </button>
            <button type="button" class="mode-btn js-mode-btn" data-mode="ai">
                <x-maktabgid.icon name="sparkle" :width="16" :height="16" /> AI tanlab bersin
                <span class="tag-new">Yangi</span>
            </button>
        </div>

        <div class="js-mode-panel" data-mode="manual">
            <div class="searchbar">
                <label class="sb-field">
                    <span class="sb-label">Muassasa turi</span>
                    <span class="sb-control">
                        <x-maktabgid.icon name="school" :width="18" :height="18" />
                        <select id="js-cat-select">
                            @foreach ($categories as $c)
                                <option value="{{ $c['key'] }}">{{ $c['short'] }}</option>
                            @endforeach
                        </select>
                    </span>
                </label>
                <label class="sb-field">
                    <span class="sb-label">Nomi boʻyicha</span>
                    <span class="sb-control">
                        <x-maktabgid.icon name="search" :width="18" :height="18" />
                        <input type="text" id="js-query" placeholder="Maktab nomi, masalan «Cambridge»" />
                    </span>
                </label>
                <label class="sb-field">
                    <span class="sb-label">Hudud</span>
                    <span class="sb-control">
                        <x-maktabgid.icon name="pin" :width="18" :height="18" />
                        <select id="js-district">
                            <option value="">Butun Toshkent</option>
                            @foreach ($districts as $d)
                                <option value="{{ $d }}">{{ $d }} tumani</option>
                            @endforeach
                        </select>
                    </span>
                </label>
                <label class="sb-field">
                    <span class="sb-label">Taʼlim tili</span>
                    <span class="sb-control">
                        <x-maktabgid.icon name="globe" :width="18" :height="18" />
                        <select>
                            <option value="">Farqi yoʻq</option>
                            <option>Ingliz</option>
                            <option>Oʻzbek</option>
                            <option>Rus</option>
                        </select>
                    </span>
                </label>
                <button type="button" class="btn btn-primary sb-go" id="js-search-go">
                    <x-maktabgid.icon name="search" :width="18" :height="18" /> Qidirish
                </button>
            </div>
        </div>

        <div class="js-mode-panel" data-mode="ai" hidden>
            <div class="ai-panel-placeholder">
                <x-maktabgid.icon name="sparkle" :width="26" :height="26" />
                <div>
                    <b>AI tez orada sizga mos taʼlim maskanini oʻzi tanlab beradi</b>
                    <p>Bu funksiya ustida ishlayapmiz — hozircha oʻzingiz qidirib koʻring.</p>
                </div>
                <button type="button" class="btn btn-ghost js-mode-back" data-mode="manual">Oʻzim qidiraman</button>
            </div>
        </div>

        <div class="hero-stats">
            <div class="hstat"><b>{{ $total }}+</b><span>taʼlim muassasasi</span></div>
            <div class="hstat"><b>11</b><span>Toshkent tumani</span></div>
            <div class="hstat"><b>4.7★</b><span>oʻrtacha reyting</span></div>
            <div class="hstat"><b>24/7</b><span>onlayn ariza</span></div>
        </div>
    </div>
</section>
