@php
    // Mock: billing/obuna tizimi hali ulanmagan — pastdagi "Hozir bepul rejimdasiz" holati va
    // to'lovlar tarixi ko'rinish uchun demo. Tariflar ro'yxati o'zi endi controllerdagi
    // InstitutionCabinetController::planCatalog() dan keladi — checkout sahifasi bilan bir xil
    // manba (narx/xususiyatlar ikki joyda mos kelishini kafolatlash uchun).
    $orgName = $institution->name ?? 'Muassasa';
    $payments = [
        ['inv' => 'INV-2048', 'plan' => 'Gold · 1 oy', 'method' => 'Humo · 8842', 'date' => '27 May 2026', 'sum' => '299 000'],
        ['inv' => 'INV-1990', 'plan' => 'Gold · 1 oy', 'method' => 'Uzcard · 1207', 'date' => '27 Apr 2026', 'sum' => '299 000'],
        ['inv' => 'INV-1903', 'plan' => 'Standard · 7 kun', 'method' => 'Humo · 8842', 'date' => '12 Apr 2026', 'sum' => '99 000'],
        ['inv' => 'INV-1840', 'plan' => 'Gold · 1 oy', 'method' => 'Uzcard · 1207', 'date' => '27 Mar 2026', 'sum' => '299 000'],
    ];
@endphp

<x-institution.shell
    active="plans"
    title="Tariflar va obuna"
    sub="E'loningizni yuqoriga chiqaring"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-alert">
        <span class="idash-alert-ico"><x-maktabgid.icon name="lock" :width="20" :height="20" /></span>
        <div>
            <b>Hozir bepul rejimdasiz</b>
            <p>E'loningiz cheklangan qamrovda va faqat 4 ta lid kontakti ochiq. Quyidan paket tanlang.</p>
        </div>
    </div>

    <div class="idash-plans-head">
        <h2>Tarifni tanlang</h2>
        <p>Standard 7 kun · Gold 1 oy · Premium 1 yil — istalgan vaqtda yangilash mumkin</p>
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
                    <b>{{ $plan['price'] }}</b> <span>so'm</span>
                </div>
                <div class="idash-plan-perday">≈ {{ $plan['perDay'] }} / kun so'm</div>

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
                    {{ $plan['name'] }} ni tanlash <x-maktabgid.icon name="arrowR" :width="16" :height="16" />
                </a>
            </div>
        @endforeach
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3 style="font-size:16.5px"><x-maktabgid.icon name="card" :width="17" :height="17" /> To'lovlar tarixi</h3>
            <span class="idash-chart-meta">{{ count($payments) }} ta to'lov</span>
        </div>

        <div class="idash-ptable">
            <div class="idash-ptable-head">
                <span>Hisob</span>
                <span>Muassasa / tarif</span>
                <span>Sana</span>
                <span>Holat</span>
                <span style="text-align:right">Summa</span>
            </div>
            @foreach ($payments as $p)
                <div class="idash-ptable-row">
                    <b>{{ $p['inv'] }}</b>
                    <div class="idash-ptable-org">
                        <b>{{ $orgName }}</b>
                        <span>{{ $p['plan'] }} · {{ $p['method'] }}</span>
                    </div>
                    <span>{{ $p['date'] }}</span>
                    <span class="idash-pstatus"><span class="ondot"></span> To'langan</span>
                    <span class="idash-ptable-sum">{{ $p['sum'] }} so'm</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> To'lov tizimi tez orada ulanadi — tariflar, narxlar va to'lovlar tarixi hozircha demo ko'rinishda
    </div>

    @endif
</x-institution.shell>
