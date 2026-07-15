@props(['school', 'catLabel'])

<section class="card-block">
    <h3><x-maktabgid.icon name="school" :width="19" :height="19" /> Muassasa haqida</h3>
    {{-- Diqqat: bu yerda avval Institution.about bo'sh bo'lsa "tajribali ustozlar, qulay
         infratuzilma..." kabi umumiy (hamma muassasa uchun bir xil, muassasaga tegishli
         bo'lmagan) reklama matni avtomatik chiqardi. Endi faqat DB'dagi haqiqiy matn
         ko'rsatiladi; bo'sh bo'lsa — muassasa hali to'ldirmagani aytiladi (2026-07-15). --}}
    @if (!empty($school['about']))
        <p style="white-space:pre-line">{{ $school['about'] }}</p>
    @else
        <p style="color:#94a3b8">Muassasa oʻzi haqida maʼlumot hali kiritmagan.</p>
    @endif
    <div class="spec-row" style="margin-top:16px">
        @foreach ($school['specs'] as $key)
            <x-maktabgid.spec-badge :spec-key="$key" />
        @endforeach
    </div>
</section>
