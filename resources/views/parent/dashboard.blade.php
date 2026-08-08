@php
    // Diqqat: bu PHP bloki x-parent.shell componentidan TASHQARIDA, shuning uchun
    // $user null bo'lsa ham (mehmon /cabinet ga kirsa) ishga tushadi. Shu sabab
    // $user->created_at to'g'ridan-to'g'ri o'qilmasligi kerak edi.
    $months = ['yanvar', 'fevral', 'mart', 'aprel', 'may', 'iyun', 'iyul', 'avgust', 'sentabr', 'oktabr', 'noyabr', 'dekabr'];
    $joined = ($user && $user->created_at) ? $user->created_at->year . '-yil ' . $months[$user->created_at->month - 1] . 'dan' : '';
@endphp

<x-parent.shell active="dashboard" :title="__('cabinet_parent.nav_profile')" :sub="__('cabinet_parent.dashboard_sub')" :user="$user" :stats="$stats">

    @if ($user)
    <div class="pprof-grid">
        {{-- ===== chap: shaxsiy ma'lumot kartochkasi ===== --}}
        <div class="pprof-card">
            <x-maktabgid.avatar :name="$user->name" :size="88" />
            <b class="pprof-name">{{ $user->name }}</b>
            <span class="pprof-role">{{ __('cabinet_parent.role_parent') }} · {{ $joined }}</span>

            <div class="pprof-info">
                <div class="pprof-info-row">
                    <span class="pprof-info-ico"><x-maktabgid.icon name="phone" :width="18" :height="18" /></span>
                    <div>
                        <span class="pprof-info-label">{{ __('cabinet_parent.info_phone') }}</span>
                        <b class="pprof-info-value">{{ $user->phone }}</b>
                    </div>
                </div>
                <div class="pprof-info-row">
                    <span class="pprof-info-ico"><x-maktabgid.icon name="mail" :width="18" :height="18" /></span>
                    <div>
                        <span class="pprof-info-label">{{ __('cabinet_parent.info_email') }}</span>
                        <b class="pprof-info-value">{{ $user->email ?: '—' }}</b>
                    </div>
                </div>
                <div class="pprof-info-row">
                    <span class="pprof-info-ico"><x-maktabgid.icon name="pin" :width="18" :height="18" /></span>
                    <div>
                        <span class="pprof-info-label">{{ __('cabinet_parent.info_district') }}</span>
                        <b class="pprof-info-value">{{ $user->district?->name ?? '—' }}</b>
                    </div>
                </div>
            </div>

            <button type="button" class="pprof-editbtn" data-modal-open="edit-parent-profile-modal">
                <x-maktabgid.icon name="edit" :width="16" :height="16" /> {{ __('cabinet_parent.edit_profile') }}
            </button>
        </div>

        {{-- ===== o'ng: statistika + farzandlar ===== --}}
        <div style="display:flex;flex-direction:column;gap:18px">
            <div class="pprof-stats">
                <div class="pprof-stat"><b>{{ $stats['favorites'] }}</b><span>{{ __('cabinet_parent.stat_favorites') }}</span></div>
                <div class="pprof-stat"><b>{{ $stats['applications'] }}</b><span>{{ __('cabinet_parent.stat_applications') }}</span></div>
                <div class="pprof-stat"><b>{{ $stats['conversations'] }}</b><span>{{ __('cabinet_parent.nav_conversations') }}</span></div>
                <div class="pprof-stat"><b>{{ $stats['profile_views'] }}</b><span>{{ __('cabinet_parent.stat_views') }}</span></div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <h3>{{ __('cabinet_parent.nav_children') }}</h3>
                    <a href="{{ route('cabinet.children') }}" class="form-addlink" style="padding:0">
                        <x-maktabgid.icon name="plus" :width="15" :height="15" /> {{ __('cabinet_parent.add_link') }}
                    </a>
                </div>
                <div class="cab-child-grid">
                    @php $genderLabel = ['ogil' => __('cabinet_parent.gender_boy_full'), 'qiz' => __('cabinet_parent.gender_girl_full')]; @endphp
                    @foreach ($children as $ch)
                        <div class="cab-child-card">
                            <div class="cab-child-top">
                                <x-maktabgid.avatar :name="$ch->name" :size="48" :square="true" />
                                <div class="cab-child-main">
                                    <b>{{ trim($ch->name . ' ' . $ch->last_name) }}</b>
                                    <span>{{ __('cabinet_parent.age_years', ['age' => $ch->age]) }} · {{ $genderLabel[$ch->gender] ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ===== "Profilni tahrirlash" modali — real PUT /ajax/me ===== --}}
    <x-maktabgid.modal-shell id="edit-parent-profile-modal" :width="440">
        <div class="js-modal-body">
            <div class="modal-head">
                <h3>{{ __('cabinet_parent.edit_profile') }}</h3>
            </div>
            <form class="form js-parent-profile-form">
                <div class="js-form-error" style="display:none;padding:10px 14px;background:#fdecec;color:#d4504e;border-radius:var(--r-md);font-size:13px;font-weight:700"></div>

                <x-maktabgid.field :label="__('cabinet_parent.field_fullname')" icon="user">
                    <input type="text" name="name" value="{{ $user->name }}" required />
                </x-maktabgid.field>
                <x-maktabgid.field :label="__('cabinet_parent.info_phone')" icon="phone">
                    <input type="tel" name="phone" value="{{ $user->phone }}" required />
                </x-maktabgid.field>
                <x-maktabgid.field :label="__('cabinet_parent.info_district')" icon="pin">
                    <select name="district">
                        <option value="">—</option>
                        @foreach (\App\Support\MaktabgidData::districts() as $d)
                            <option value="{{ $d }}" @selected($user->district?->name === $d)>{{ $d }}</option>
                        @endforeach
                    </select>
                </x-maktabgid.field>
                <button class="btn btn-primary form-submit" type="submit">{{ __('cabinet_parent.save') }}</button>
            </form>
        </div>
    </x-maktabgid.modal-shell>
    @endif

</x-parent.shell>
