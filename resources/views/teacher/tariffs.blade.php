@php
    // Mock: bir martalik rezyume to'lovi — billing hali ulanmagan (institution.cabinet.plans
    // sahifasidagi kabi yondashuv).
    $payMethods = [
        ['key' => 'humo', 'label' => 'Humo · 8842', 'dot' => '#2aabee', 'on' => true],
        ['key' => 'payme', 'label' => 'Payme', 'dot' => '#3fc4e8', 'on' => false],
    ];
@endphp

<x-teacher.shell active="tariffs" title="{{ __('cabinet_teacher.nav_tariffs') }}" sub="{{ __('cabinet_teacher.tariffs_sub') }}" :teacher="$teacher" :counts="$counts">

    <div class="panel" style="max-width:640px">
        <h3 style="font-size:20px">{{ __('cabinet_teacher.complete_payment') }}</h3>

        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;background:var(--surface-2);border:1px solid var(--line-2);border-radius:var(--r-md);padding:16px 18px;margin-top:18px">
            <div>
                <span style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--ink-3)">{{ __('cabinet_teacher.selected_service') }}</span>
                <b style="display:block;font-family:var(--font-display);font-size:16px;margin-top:3px">{{ __('cabinet_teacher.resume_posting_30days') }}</b>
            </div>
            <b style="font-family:var(--font-display);font-size:24px;color:var(--primary-ink);white-space:nowrap">30 000 {{ __('cabinet_teacher.currency_sum') }}</b>
        </div>

        <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin:18px 0 10px">{{ __('cabinet_teacher.payment_method') }}</span>
        <div class="idash-paymethods">
            @foreach ($payMethods as $pm)
                <button type="button" class="idash-paymethod{{ $pm['on'] ? ' on' : '' }}">
                    <i style="background:{{ $pm['dot'] }}"></i> {{ $pm['label'] }}
                    @if ($pm['on'])
                        <span class="check"><x-maktabgid.icon name="check" :width="16" :height="16" /></span>
                    @endif
                </button>
            @endforeach
        </div>

        <button type="button" class="btn btn-primary" style="width:100%;margin-top:20px;justify-content:center;padding:14px">{{ __('cabinet_teacher.make_payment') }}</button>

        <div style="display:flex;flex-direction:column;gap:9px;margin-top:18px">
            <span style="display:inline-flex;align-items:center;gap:8px;font-size:12.5px;font-weight:600;color:var(--ink-2)"><x-maktabgid.icon name="lock" :width="15" :height="15" /> {{ __('cabinet_teacher.ssl_note') }}</span>
            <span style="display:inline-flex;align-items:center;gap:8px;font-size:12.5px;font-weight:600;color:var(--ink-2)"><x-maktabgid.icon name="check" :width="15" :height="15" /> {{ __('cabinet_teacher.payment_success_note') }}</span>
            <span style="display:inline-flex;align-items:center;gap:8px;font-size:12.5px;font-weight:600;color:var(--ink-2)"><x-maktabgid.icon name="mail" :width="15" :height="15" /> {{ __('cabinet_teacher.receipt_note') }}</span>
        </div>
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> {{ __('cabinet_teacher.payment_soon_notice') }}
    </div>

</x-teacher.shell>
