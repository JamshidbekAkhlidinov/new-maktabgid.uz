@props(['ads'])

@php
    use App\Support\MaktabgidData;
@endphp

@if (count($ads))
    <section class="section" style="padding-top:10px;padding-bottom:20px">
        <div class="wrap">
            <div class="ad-banner-carousel js-ad-carousel">
                @foreach ($ads as $ad)
                    <div class="ad-banner js-ad-slide{{ $loop->first ? ' on' : '' }}">
                        @if ($ad->tag)
                            <span class="ad-banner-tag">{{ $ad->tag }}</span>
                        @endif

                        <div class="ad-banner-avatar">{{ $ad->initials ?: MaktabgidData::monogram($ad->title) }}</div>

                        <div class="ad-banner-body">
                            @if ($ad->badge)
                                <span class="ad-banner-badge">
                                    <x-maktabgid.icon name="sparkle" :width="14" :height="14" />
                                    {{ $ad->badge }}
                                </span>
                            @endif
                            <h2>{{ $ad->title }}</h2>
                            <p>
                                @if ($ad->rating)
                                    <span class="ad-banner-rating">
                                        <x-maktabgid.icon name="star" :width="13" :height="13" fill="#f5b400" stroke="#f5b400" />
                                        {{ $ad->rating }}
                                    </span>
                                @endif
                                {{ $ad->description }}
                            </p>
                        </div>

                        <div class="ad-banner-actions">
                            <a class="btn btn-white" href="{{ $ad->link_url ?: '#' }}">
                                {{ $ad->cta_label ?: __('home.ad_default_cta') }}
                                <x-maktabgid.icon name="arrowR" :width="16" :height="16" />
                            </a>
                            <span class="ad-banner-label">{{ __('home.ad_label') }}</span>
                        </div>
                    </div>
                @endforeach

                @if (count($ads) > 1)
                    <div class="ad-banner-dots">
                        @foreach ($ads as $ad)
                            <button type="button" class="dot js-ad-dot{{ $loop->first ? ' on' : '' }}" aria-label="{{ __('home.ad_aria', ['n' => $loop->iteration]) }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
