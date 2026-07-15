@php
    // Real ro'yxat — App\Models\VacancyApplication (teacher_user_id = joriy ustoz).
    // "Takliflar" nomi saqlanib qolgan (sidebar/route nomi), lekin mazmuni — ustoz
    // yuborgan arizalar va muassasaning javobi (kutilmoqda/qabul/rad) — ADR-0002, Faza 2.
    $offerStatusStyle = [
        'pending' => ['bg' => 'var(--accent-soft)', 'color' => '#b45309', 'label' => 'Kutilmoqda'],
        'accepted' => ['bg' => 'var(--primary-soft)', 'color' => 'var(--primary-ink)', 'label' => 'Qabul qilindi'],
        'rejected' => ['bg' => 'var(--surface-2)', 'color' => 'var(--ink-3)', 'label' => 'Rad etildi'],
    ];
@endphp

<x-teacher.shell active="offers" title="Takliflar" sub="Yuborgan arizalaringiz va ularning holati" :teacher="$teacher" :counts="$counts">

    <div class="panel">
        <h3 style="font-size:18px;margin-bottom:16px">Vakansiyalarga yuborgan arizalarim</h3>

        @if ($offers->isEmpty())
            <div class="idash-badge-soft">
                <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Hali hech qanday vakansiyaga ariza yubormagansiz — <a href="{{ route('teacher.cabinet.vacancies') }}">Vakansiyalar</a> bo'limidan boshlang
            </div>
        @else
            <div style="display:flex;flex-direction:column;gap:10px">
                @foreach ($offers as $o)
                    @php($st = $offerStatusStyle[$o->status] ?? $offerStatusStyle['pending'])
                    <div class="idash-offer-row" style="grid-template-columns:auto 1fr auto">
                        <span class="idash-offer-ico" style="background:linear-gradient(140deg,#2f6fed,#1c4fc2)">{{ \App\Support\MaktabgidData::monogram($o->vacancy?->org_name ?? '?') }}</span>
                        <div class="idash-offer-main">
                            <b>{{ $o->vacancy?->title ?? "O'chirilgan vakansiya" }}</b>
                            <span>{{ $o->vacancy?->org_name }} · {{ $o->created_at->diffForHumans() }}</span>
                        </div>
                        <span class="idash-status-pill" style="background:{{ $st['bg'] }};color:{{ $st['color'] }}">{{ $st['label'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</x-teacher.shell>
