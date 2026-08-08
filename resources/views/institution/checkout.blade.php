<x-institution.shell
    active="plans"
    :title="__('cabinet_institution.nav_plans')"
    :sub="__('cabinet_institution.plans_sub')"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <a href="{{ route('institution.cabinet.plans') }}" class="idash-back-link">
        <x-maktabgid.icon name="arrowL" :width="16" :height="16" /> {{ __('cabinet_institution.back_to_plans') }}
    </a>

    <div class="js-checkout-wrap">
        <form class="js-fake-form">
            <div class="idash-checkout-row">
                <div class="panel">
                    <div class="panel-head js-fake-form-head" style="display:block">
                        <h3>{{ __('cabinet_institution.complete_payment') }}</h3>
                        <p>{{ __('cabinet_institution.choose_payment_method') }}</p>
                    </div>

                    <div class="idash-pay-list">
                        @foreach ($paymentMethods as $m)
                            <label class="idash-pay-item{{ !empty($m['selected']) ? ' on' : '' }}">
                                <input type="radio" name="method" value="{{ $m['key'] }}" @checked(!empty($m['selected'])) />
                                <span class="idash-pay-badge" style="background:{{ $m['color'] }}">{{ $m['badge'] }}</span>
                                <span class="idash-pay-name">{{ $m['name'] }}</span>
                                <span class="idash-pay-radio"></span>
                            </label>
                        @endforeach
                    </div>

                    <button type="button" class="idash-pay-add">
                        <x-maktabgid.icon name="plus" :width="16" :height="16" /> {{ __('cabinet_institution.add_new_card') }}
                    </button>

                    <p class="form-note" style="margin-top:18px">
                        <x-maktabgid.icon name="shield" :width="15" :height="15" />
                        {{ __('cabinet_institution.payment_secure_note') }}
                    </p>
                </div>

                <div class="idash-order-card">
                    <h3>{{ __('cabinet_institution.order') }}</h3>
                    <div class="idash-order-row"><span>{{ __('cabinet_institution.institution_word') }}</span><span>{{ $institution->name }}</span></div>
                    <div class="idash-order-row"><span>{{ __('cabinet_institution.plan_word') }}</span><span>{{ $plan['name'] }} · {{ $plan['dur'] }}</span></div>
                    <div class="idash-order-row"><span>{{ __('cabinet_institution.validity') }}</span><span>{{ __('cabinet_institution.days_count', ['count' => $plan['days']]) }}</span></div>
                    <div class="idash-order-row"><span>{{ __('cabinet_institution.nav_leads') }}</span><span>{{ $plan['leadsLabel'] }}</span></div>

                    <div class="idash-order-total">
                        <b>{{ __('cabinet_institution.total') }}</b>
                        <b>{{ $plan['price'] }} <span style="font-family:var(--font-sans);font-size:13px;font-weight:600;color:var(--ink-3)">{{ __('cabinet_institution.currency_sum') }}</span></b>
                    </div>

                    <button type="submit" class="btn btn-primary idash-order-cta">
                        <x-maktabgid.icon name="lock" :width="16" :height="16" /> {{ __('cabinet_institution.pay_amount', ['amount' => $plan['price']]) }}
                    </button>
                </div>
            </div>
        </form>

        <x-maktabgid.success-note :title="__('cabinet_institution.payment_success_title')" class="js-fake-success" style="display:none">
            <b>{{ $plan['name'] }}</b> {{ __('cabinet_institution.payment_success_body') }}
            <br /><br />
            <a href="{{ route('institution.cabinet') }}" class="btn btn-primary">{{ __('cabinet_institution.back_to_dashboard') }}</a>
        </x-maktabgid.success-note>
    </div>

    @endif
</x-institution.shell>
