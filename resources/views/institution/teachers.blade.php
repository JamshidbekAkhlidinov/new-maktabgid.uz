@php
    // Mock: kabinet ichidagi "O'qituvchilar" boshqaruvi — hozircha namunaviy ro'yxat.
    // Real ma'lumot manbasi allaqachon mavjud: Institution::$teachers (json, profil
    // sahifasidagi "O'qituvchilar" bo'limida ishlatiladi — InstitutionCabinetController::profile()
    // dagi $teachersText'ga qarang). Keyingi bosqichda shu sahifa o'sha maydondan o'qiydigan
    // va CRUD qiladigan bo'ladi.
    $mockTeachers = [
        ['name' => 'Aziza Karimova', 'subject' => 'Ingliz tili', 'exp' => '8 yil tajriba', 'edu' => 'CELTA sertifikati · Toshkent davlat chet tillar instituti', 'ach' => ["IELTS 8.5 — 12 ta o'quvchi", 'Eng yaxshi ustoz 2025']],
        ['name' => 'Bekzod Rashidov', 'subject' => 'Matematika', 'exp' => '12 yil tajriba', 'edu' => "O'zMU, amaliy matematika", 'ach' => ["Respublika olimpiadasi — 3 g'olib"]],
        ['name' => 'Nilufar Tosheva', 'subject' => 'Boshlang\'ich ta\'lim', 'exp' => '6 yil tajriba', 'edu' => 'Nizomiy nomidagi TDPU', 'ach' => ['Montessori metodikasi bo\'yicha sertifikat']],
        ['name' => 'Sardor Yo\'ldoshev', 'subject' => 'IT / dasturlash', 'exp' => '5 yil tajriba', 'edu' => 'IT Park Academy, mentor', 'ach' => ["O'quvchilar hackathon g'olibi", 'Google sertifikati']],
    ];
@endphp

<x-institution.shell
    active="teachers"
    title="O'qituvchilar"
    sub="Muassasangiz ustozlari — profil sahifasida ko'rinadi"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-toolbar">
        <span class="idash-chart-meta">{{ count($mockTeachers) }} ta o'qituvchi · profil sahifasida ko'rinadi</span>
        <button type="button" class="btn btn-primary sm">
            <x-maktabgid.icon name="plus" :width="15" :height="15" /> O'qituvchi qo'shish
        </button>
    </div>

    <div class="idash-grid2">
        @foreach ($mockTeachers as $t)
            <div class="idash-tcard">
                <x-maktabgid.avatar :name="$t['name']" :size="64" :square="true" />
                <div class="idash-tcard-main">
                    <div class="idash-tcard-head">
                        <div>
                            <b>{{ $t['name'] }}</b>
                            <span>{{ $t['subject'] }} · {{ $t['exp'] }}</span>
                        </div>
                        <div class="idash-card-actions">
                            <button type="button" class="idash-lead-iconbtn" title="Tahrirlash"><x-maktabgid.icon name="edit" :width="14" :height="14" /></button>
                            <button type="button" class="idash-lead-iconbtn danger" title="O'chirish"><x-maktabgid.icon name="close" :width="14" :height="14" /></button>
                        </div>
                    </div>
                    <span class="idash-tcard-edu">{{ $t['edu'] }}</span>
                    <div class="idash-tcard-ach">
                        @foreach ($t['ach'] as $a)
                            <span><x-maktabgid.icon name="award" :width="14" :height="14" /> {{ $a }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Bu bo'lim demo ma'lumot bilan ko'rsatilmoqda — tez orada muassasa profilidagi real o'qituvchilar ro'yxati bilan sinxronlashadi
    </div>

    @endif
</x-institution.shell>
