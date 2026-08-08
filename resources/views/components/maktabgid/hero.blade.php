@props(['categories' => [], 'districts' => [], 'catTabs' => [], 'active' => null])

<section class="hero" id="top">
    <div class="hero-bg"></div>
    <div class="wrap hero-inner">
        <span class="eyebrow"><x-maktabgid.icon name="sparkle" :width="15" :height="15" /> {{ __('home.hero_eyebrow') }}</span>
        <h1>{{ __('home.hero_title_before') }} <span class="hl">{{ __('home.hero_title_highlight') }}</span> {{ __('home.hero_title_after') }}</h1>
        <p class="sub">
            {{ __('home.hero_sub') }}
        </p>

        <div class="hero-mode-toggle">
            <button type="button" class="mode-btn on js-mode-btn" data-mode="manual">
                <x-maktabgid.icon name="search" :width="16" :height="16" /> {{ __('home.mode_manual') }}
            </button>
            <button type="button" class="mode-btn js-mode-btn" data-mode="ai">
                <x-maktabgid.icon name="sparkle" :width="16" :height="16" /> {{ __('home.mode_ai') }}
                <span class="tag-new">{{ __('home.new_badge') }}</span>
            </button>
        </div>

        <div class="js-mode-panel" data-mode="manual">
            <div class="searchbar">
                <label class="sb-field">
                    <span class="sb-label">{{ __('home.institution_type') }}</span>
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
                    <span class="sb-label">{{ __('home.by_name') }}</span>
                    <span class="sb-control">
                        <x-maktabgid.icon name="search" :width="18" :height="18" />
                        <input type="text" id="js-query" name="query" autocomplete="off" placeholder="{{ __('home.name_search_placeholder') }}" />
                    </span>
                </label>
                <label class="sb-field">
                    <span class="sb-label">{{ __('home.region') }}</span>
                    <span class="sb-control">
                        <x-maktabgid.icon name="pin" :width="18" :height="18" />
                        <select id="js-district">
                            <option value="">{{ __('home.all_tashkent') }}</option>
                            @foreach ($districts as $d)
                                <option value="{{ $d }}">{{ $d }} {{ __('home.district_word') }}</option>
                            @endforeach
                        </select>
                    </span>
                </label>
                <label class="sb-field">
                    <span class="sb-label">{{ __('home.language_of_instruction') }}</span>
                    <span class="sb-control">
                        <x-maktabgid.icon name="globe" :width="18" :height="18" />
                        <select id="js-lang-select" name="lang" autocomplete="language">
                            <option value="">{{ __('home.no_preference') }}</option>
                            <option>{{ __('home.lang_english') }}</option>
                            <option>{{ __('home.lang_uzbek') }}</option>
                            <option>{{ __('home.lang_russian') }}</option>
                        </select>
                    </span>
                </label>
                <button type="button" class="btn btn-primary sb-go" id="js-search-go">
                    <x-maktabgid.icon name="search" :width="18" :height="18" /> {{ __('home.search') }}
                </button>
            </div>
        </div>

        <div class="js-mode-panel" data-mode="ai" hidden>
            <div class="ai-panel-placeholder">
                <x-maktabgid.icon name="sparkle" :width="26" :height="26" />
                <div>
                    <b>{{ __('home.ai_coming_title') }}</b>
                    <p>{{ __('home.ai_coming_sub') }}</p>
                </div>
                <button type="button" class="btn btn-ghost js-mode-back" data-mode="manual">{{ __('home.mode_manual') }}</button>
            </div>
        </div>

        <div class="hero-stats">
            <x-maktabgid.cat-tabs-list :tabs="$catTabs" :active="$active" :interactive="false" class="hero-cat-tabs" />
            <div class="hstat"><b>24/7</b> <span>{{ __('home.stat_online') }}</span></div>
        </div>
    </div>
</section>
