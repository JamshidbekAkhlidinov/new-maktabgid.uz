@props(['categories' => [], 'schools' => [], 'total' => 103])

@php
    use App\Support\MaktabgidData;
    $byCat = [];
    foreach ($categories as $c) {
        $list = array_values(array_filter($schools, fn ($s) => $s['cat'] === $c['key']));
        $near = $list;
        usort($near, fn ($a, $b) => $a['dist'] <=> $b['dist']);
        $popular = $list;
        usort($popular, fn ($a, $b) => $b['rating'] <=> $a['rating']);
        $byCat[$c['key']] = ['near' => array_slice($near, 0, 6), 'popular' => $popular];
    }
    $catNames = ['maktab' => 'maktab', 'bogcha' => 'bogʻcha', 'markaz' => 'markaz', 'mutaxassis' => 'mutaxassis'];
@endphp

<div class="m-screen">
    <header class="m-header">
        <div class="m-head-row">
            <button type="button" class="m-loc">
                <span class="pin"><x-maktabgid.icon name="pin" :width="20" :height="20" /></span>
                <span>
                    <small>Joylashuv</small>
                    <b>Yunusobod <x-maktabgid.icon name="chevron" :width="15" :height="15" /></b>
                </span>
            </button>
            <div class="m-head-actions">
                <button type="button" class="m-icbtn"><x-maktabgid.icon name="heart" :width="20" :height="20" /></button>
                <button type="button" class="m-icbtn"><span class="m-dot"></span><x-maktabgid.icon name="cal" :width="20" :height="20" /></button>
                <span class="m-avatar" style="background:linear-gradient(140deg,#0EA5A0,#0B7E8C)">AB</span>
            </div>
        </div>
        <div class="m-search">
            <x-maktabgid.icon name="search" :width="20" :height="20" class="mag" />
            <input type="text" id="js-m-query" placeholder="Maktab, bogʻcha qidirish..." />
            <button type="button" class="m-filter"><x-maktabgid.icon name="sliders" :width="18" :height="18" /></button>
        </div>
    </header>

    <div style="flex:1">
        <div class="m-chips">
            @foreach ($categories as $c)
                <button type="button" class="m-chip js-m-cat{{ $loop->first ? ' on' : '' }}" data-cat="{{ $c['key'] }}">
                    <x-maktabgid.icon :name="$c['icon']" :width="17" :height="17" /> {{ $c['short'] }}
                </button>
            @endforeach
        </div>

        <div class="m-promo">
            <span class="pill"><x-maktabgid.icon name="send" :width="13" :height="13" /> Telegram bot</span>
            <h3>Bir necha soniyada ariza qoldiring</h3>
            <p>Yoqqan maktabga toʻgʻridan-toʻgʻri bogʻlaning va joy band qiling.</p>
            <button type="button" class="go">Botni ochish <x-maktabgid.icon name="arrowR" :width="15" :height="15" /></button>
            <div class="m-dots"><i class="on"></i><i></i><i></i></div>
        </div>

        @foreach ($categories as $c)
            <div class="js-m-panel" data-cat="{{ $c['key'] }}" @if (!$loop->first) hidden @endif>
                <section class="m-sec">
                    <div class="m-sec-head">
                        <h2>Sizga yaqin</h2>
                        <a href="#">Barchasi <x-maktabgid.icon name="chevronR" :width="14" :height="14" /></a>
                    </div>
                    <div class="m-near">
                        @foreach ($byCat[$c['key']]['near'] as $s)
                            <a href="{{ route('maktabgid.school', $s['id']) }}" class="m-ncard">
                                <div class="media" style="background:linear-gradient(140deg, {{ $s['g'][0] }}, {{ $s['g'][1] }})">
                                    <span class="mono">{{ MaktabgidData::monogram($s['name']) }}</span>
                                    @if (!empty($s['badge']))
                                        <span class="m-mbadge">{{ $s['badge'] }}</span>
                                    @endif
                                    <button type="button" class="m-fav js-fav"><x-maktabgid.icon name="heart" :width="15" :height="15" /></button>
                                </div>
                                <div class="body">
                                    <div class="nm">{{ $s['name'] }}</div>
                                    <div class="sub"><x-maktabgid.icon name="star" :width="13" :height="13" fill="var(--accent)" stroke="var(--accent)" /> {{ $s['rating'] }} · {{ $s['district'] }} · {{ $s['dist'] }} km</div>
                                    <div class="pr">{{ MaktabgidData::formatPrice($s['price']) }} <span>soʻm/oy</span></div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="m-sec">
                    <div class="m-sec-head">
                        <h2>Mashhur {{ $catNames[$c['key']] }}lar</h2>
                        <a href="#">Barchasi <x-maktabgid.icon name="chevronR" :width="14" :height="14" /></a>
                    </div>
                    <div class="m-list">
                        @foreach ($byCat[$c['key']]['popular'] as $s)
                            <a href="{{ route('maktabgid.school', $s['id']) }}" class="m-row">
                                <div class="media" style="background:linear-gradient(140deg, {{ $s['g'][0] }}, {{ $s['g'][1] }})">
                                    <span class="mono">{{ MaktabgidData::monogram($s['name']) }}</span>
                                    <button type="button" class="m-fav js-fav" style="width:28px;height:28px"><x-maktabgid.icon name="heart" :width="14" :height="14" /></button>
                                </div>
                                <div class="body">
                                    <div class="rtop">
                                        <div class="nm">{{ $s['name'] }}</div>
                                        <span class="rate"><x-maktabgid.icon name="star" :width="13" :height="13" fill="var(--accent)" stroke="var(--accent)" /> {{ $s['rating'] }}</span>
                                    </div>
                                    <div class="meta">
                                        <span class="x"><x-maktabgid.icon name="pin" :width="13" :height="13" /> {{ $s['district'] }}</span>
                                        <span class="x"><x-maktabgid.icon name="map" :width="13" :height="13" /> {{ $s['dist'] }} km</span>
                                    </div>
                                    <div class="m-tags">
                                        <span class="m-tag lang">{{ $s['lang'] }}</span>
                                        @if ($s['sat'])
                                            <span class="m-tag sat">Shanba</span>
                                        @endif
                                    </div>
                                    <div class="rfoot">
                                        <div class="pr">{{ MaktabgidData::formatPrice($s['price']) }} <span>soʻm/oy</span></div>
                                        <span class="chev"><x-maktabgid.icon name="arrowR" :width="16" :height="16" /></span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            </div>
        @endforeach

        <div class="m-end">{{ $total }} ta muassasa · MaktabGID</div>
        <div style="height:14px"></div>
    </div>

    <nav class="m-tabs">
        <a href="#m-top" class="m-tab on"><x-maktabgid.icon name="grid" :width="22" :height="22" />Asosiy</a>
        <a href="#m-map" class="m-tab"><x-maktabgid.icon name="map" :width="22" :height="22" />Xarita</a>
        <a href="#blog" class="m-tab"><x-maktabgid.icon name="heart" :width="22" :height="22" />Saqlangan</a>
        <a href="#top" class="m-tab"><x-maktabgid.icon name="users" :width="22" :height="22" />Profil</a>
    </nav>
</div>
