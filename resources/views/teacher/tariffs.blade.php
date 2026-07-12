@php
    // Mock: bir martalik rezyume to'lovi — billing hali ulanmagan (institution.cabinet.plans
    // sahifasidagi kabi yondashuv).
    $payMethods = [
        ['key' => 'humo', 'label' => 'Humo · 8842', 'dot' => '#2aabee', 'on' => true],
        ['key' => 'payme', 'label' => 'Payme', 'dot' => '#3fc4e8', 'on' => false],
    ];
@endphp

<x-teacher.shell active="tariffs" title="Rezyume to'lovi" sub="Bir martalik to'lov — Payme & Click" :teacher="$teacher" :counts="$counts">

    <div class="panel" style="max-width:640px">
        <h3 style="font-size:20px">To'lovni amalga oshiring</h3>

        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;background:var(--surface-2);border:1px solid var(--line-2);border-radius:var(--r-md);padding:16px 18px;margin-top:18px">
            <div>
                <span style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--ink-3)">Tanlagan xizmat</span>
                <b style="display:block;font-family:var(--font-display);font-size:16px;margin-top:3px">Rezyume joylash · 30 kun</b>
            </div>
            <b style="font-family:var(--font-display);font-size:24px;color:var(--primary-ink);white-space:nowrap">30 000 so'm</b>
        </div>

        <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin:18px 0 10px">To'lov usuli</span>
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

        <button type="button" class="btn btn-primary" style="width:100%;margin-top:20px;justify-content:center;padding:14px">To'lovni amalga oshirish</button>

        <div style="display:flex;flex-direction:column;gap:9px;margin-top:18px">
            <span style="display:inline-flex;align-items:center;gap:8px;font-size:12.5px;font-weight:600;color:var(--ink-2)"><x-maktabgid.icon name="lock" :width="15" :height="15" /> Ma'lumotlaringiz 256-bit SSL bilan himoyalangan</span>
            <span style="display:inline-flex;align-items:center;gap:8px;font-size:12.5px;font-weight:600;color:var(--ink-2)"><x-maktabgid.icon name="check" :width="15" :height="15" /> To'lov muvaffaqiyatli bo'lsa, rezyume darhol e'lon qilinadi</span>
            <span style="display:inline-flex;align-items:center;gap:8px;font-size:12.5px;font-weight:600;color:var(--ink-2)"><x-maktabgid.icon name="mail" :width="15" :height="15" /> Tasdiqlash cheki emailingizga yuboriladi</span>
        </div>
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> To'lov tizimi tez orada ulanadi — hozircha demo ko'rinish
    </div>

</x-teacher.shell>
