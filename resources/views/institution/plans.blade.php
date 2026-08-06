@php
    // Mock: billing/obuna tizimi hali ulanmagan — pastdagi "Hozir bepul rejimdasiz" holati va
    // to'lovlar tarixi ko'rinish uchun demo. Tariflar ro'yxati o'zi endi controllerdagi
    // InstitutionCabinetController::planCatalog() dan keladi — checkout sahifasi bilan bir xil
    // manba (narx/xususiyatlar ikki joyda mos kelishini kafolatlash uchun).
    $orgName = $institution->name ?? __('cabinet_institution.institution_fallback');
    $payments = [
        ['inv' => 'INV-2048', 'plan' => 'Gold · 1 oy', 'method' => 'Humo · 8842', 'date' => '27 May 2026', 'sum' => '299 000'],
        ['inv' => 'INV-1990', 'plan' => 'Gold · 1 oy', 'method' => 'Uzcard · 1207', 'date' => '27 Apr 2026', 'sum' => '299 000'],
        ['inv' => 'INV-1903', 'plan' => 'Standard · 7 kun', 'method' => 'Humo · 8842', 'date' => '12 Apr 2026', 'sum' => '99 000'],
        ['inv' => 'INV-1840', 'plan' => 'Gold · 1 oy', 'method' => 'Uzcard · 1207', 'date' => '27 Mar 2026', 'sum' => '299 000'],
    ];
@endphp

<x-institution.shell
    active="plans"
    title="{{ __('cabinet_institution.nav_plans') }}"
    sub="{{ __('cabinet_institution.plans_sub') }}"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-alert">
        <span class="idash-alert-ico"><x-maktabgid.icon name="lock" :width="20" :height="20" /></span>
        <div>
            <b>{{ __('cabinet_institution.currently_free_plan') }}</b>
            <p>{{ __('cabinet_institution.free_plan_limit_text') }}</p>
        </div>
    </div>

    <div class="idash-plans-head">
        <h2>{{ __('cabinet_institution.choose_plan') }}</h2>
        <p>{{ __('cabinet_institution.plans_durations_note') }}</p>
    </div>

    <div class="idash-plans">
        @foreach ($plans as $plan)
            <div class="idash-plan{{ !empty($plan['highlight']) ? ' on' : '' }}">
                @if (! empty($plan['badge']))
                    <span class="idash-plan-badge-top{{ ($plan['badgeColor'] ?? '') === 'orange' ? ' orange' : '' }}">{{ $plan['badge'] }}</span>
                @endif

                <div class="idash-plan-name-row">
                    <h3>{{ $plan['name'] }}</h3>
                    <span class="idash-plan-dur">{{ $plan['dur'] }}</span>
                </div>
                <div class="idash-plan-sub">{{ $plan['sub'] }}</div>

                <div class="idash-plan-price-row">
                    <b>{{ $plan['price'] }}</b> <span>{{ __('cabinet_institution.currency_sum') }}</span>
                </div>
                <div class="idash-plan-perday">≈ {{ $plan['perDay'] }} {{ __('cabinet_institution.per_day_sum') }}</div>

                <div class="idash-plan-divider"></div>

                <ul class="idash-plan-feat">
                    @foreach ($plan['features'] as [$label, $included])
                        <li class="{{ $included ? '' : 'off' }}">
                            <x-maktabgid.icon :name="$included ? 'check' : 'minus'" :width="16" :height="16" />
                            {{ $label }}
                        </li>
                    @endforeach
                </ul>

                <a href="{{ route('institution.cabinet.checkout', $plan['key']) }}" class="idash-plan-cta{{ !empty($plan['highlight']) ? ' filled' : '' }}">
                    {{ __('cabinet_institution.choose_plan_named', ['name' => $plan['name']]) }} <x-maktabgid.icon name="arrowR" :width="16" :height="16" />
                </a>
            </div>
        @endforeach
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3 style="font-size:16.5px"><x-maktabgid.icon name="card" :width="17" :height="17" /> {{ __('cabinet_institution.payment_history') }}</h3>
            <span class="idash-chart-meta">{{ __('cabinet_institution.payments_count', ['count' => count($payments)]) }}</span>
        </div>

        <div class="idash-ptable">
            <div class="idash-ptable-head">
                <span>{{ __('cabinet_institution.col_invoice') }}</span>
                <span>{{ __('cabinet_institution.col_institution_plan') }}</span>
                <span>{{ __('cabinet_institution.col_date') }}</span>
                <span>{{ __('cabinet_institution.col_status') }}</span>
                <span style="text-align:right">{{ __('cabinet_institution.col_amount') }}</span>
            </div>
            @foreach ($payments as $p)
                <div class="idash-ptable-row">
                    <b>{{ $p['inv'] }}</b>
                    <div class="idash-ptable-org">
                        <b>{{ $orgName }}</b>
                        <span>{{ $p['plan'] }} · {{ $p['method'] }}</span>
                    </div>
                    <span>{{ $p['date'] }}</span>
                    <span class="idash-pstatus"><span class="ondot"></span> {{ __('cabinet_institution.paid') }}</span>
                    <span class="idash-ptable-sum">{{ $p['sum'] }} {{ __('cabinet_institution.currency_sum') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> {{ __('cabinet_institution.plans_demo_notice') }}
    </div>

    @endif
</x-institution.shell>
