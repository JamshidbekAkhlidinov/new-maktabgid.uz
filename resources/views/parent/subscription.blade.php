@php
    // Mock: to'lov/obuna tizimi hali ulanmagan (InstitutionCabinetController::planCatalog()
    // dagi kabi — billing qo'shilganda shu bo'lim real ma'lumotdan olinadi).
    $parentUsed = 3;
    $parentUsedPct = 60;
    $parentCompare = [
        ['feat' => __('cabinet_parent.compare_feat_view'), 'free' => __('cabinet_parent.compare_free_view'), 'prem' => __('cabinet_parent.compare_unlimited')],
        ['feat' => __('cabinet_parent.compare_feat_ai'), 'free' => __('cabinet_parent.compare_free_ai'), 'prem' => __('cabinet_parent.compare_unlimited')],
        ['feat' => __('cabinet_parent.compare_feat_price_notif'), 'free' => __('cabinet_parent.compare_no'), 'prem' => __('cabinet_parent.compare_yes')],
        ['feat' => __('cabinet_parent.compare_feat_excursion'), 'free' => __('cabinet_parent.compare_yes'), 'prem' => __('cabinet_parent.compare_priority_queue')],
        ['feat' => __('cabinet_parent.compare_feat_ads'), 'free' => __('cabinet_parent.compare_yes'), 'prem' => __('cabinet_parent.compare_no')],
    ];
@endphp

<x-parent.shell active="subscription" title="{{ __('cabinet_parent.nav_subscription') }}" sub="{{ __('cabinet_parent.subscription_sub') }}" :user="$user" :stats="$stats">

    <div class="cab-sub-row">
        <div class="panel">
            <h3 style="margin-bottom:4px">{{ __('cabinet_parent.compare_title') }}</h3>
            <p style="font-size:13px;color:var(--ink-3);font-weight:600;margin-bottom:0">{{ __('cabinet_parent.free_plan_usage', ['count' => $parentUsed]) }}</p>
            <div class="cab-sub-progress"><i style="width:{{ $parentUsedPct }}%"></i></div>
            <div class="cab-compare-head">
                <span>{{ __('cabinet_parent.compare_head_feature') }}</span><span>{{ __('cabinet_parent.compare_head_free') }}</span><span>{{ __('cabinet_parent.compare_head_premium') }}</span>
            </div>
            @foreach ($parentCompare as $c)
                <div class="cab-compare-row">
                    <span>{{ $c['feat'] }}</span><span>{{ $c['free'] }}</span><span>{{ $c['prem'] }}</span>
                </div>
            @endforeach
        </div>
        <div class="cab-premium">
            <span class="cab-premium-tag">{{ __('cabinet_parent.recommended_tag') }}</span>
            <span class="cab-premium-ico"><x-maktabgid.icon name="shield" :width="24" :height="24" /></span>
            <b class="name">{{ __('cabinet_parent.premium_name') }}</b>
            <div class="cab-premium-price"><b>39 000</b><span>{{ __('cabinet_parent.price_per_month') }}</span></div>
            <div class="cab-premium-feat">
                <span><x-maktabgid.icon name="check" :width="16" :height="16" /> {{ __('cabinet_parent.premium_feat_view') }}</span>
                <span><x-maktabgid.icon name="check" :width="16" :height="16" /> {{ __('cabinet_parent.premium_feat_ai') }}</span>
                <span><x-maktabgid.icon name="check" :width="16" :height="16" /> {{ __('cabinet_parent.premium_feat_price_notif') }}</span>
                <span><x-maktabgid.icon name="check" :width="16" :height="16" /> {{ __('cabinet_parent.premium_feat_no_ads') }}</span>
            </div>
            <button type="button" class="cab-premium-cta">{{ __('cabinet_parent.premium_cta') }}</button>
            <span class="cab-premium-note">{{ __('cabinet_parent.premium_note') }}</span>
        </div>
    </div>

    <div class="idash-badge-soft" style="margin-top:16px">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> {{ __('cabinet_parent.payment_soon_notice') }}
    </div>

</x-parent.shell>
