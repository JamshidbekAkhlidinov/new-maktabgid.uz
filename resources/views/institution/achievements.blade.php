@php
    // Mock: "O'quvchilar yutuqlari" — hali alohida DB jadvali yo'q, shuning uchun
    // namunaviy ro'yxat bilan ko'rsatiladi (leads.blade.php dagi kabi yondashuv).
    $levelMeta = [
        'intl' => ['label' => 'Xalqaro', 'class' => 'intl'],
        'national' => ['label' => 'Respublika', 'class' => 'national'],
        'regional' => ['label' => 'Viloyat', 'class' => 'regional'],
    ];
    $mockAchievements = [
        ['title' => 'IELTS 8.5 ball', 'student' => 'Sardor Aliyev', 'year' => 2025, 'type' => 'Xalqaro imtihon', 'level' => 'intl'],
        ['title' => 'Respublika matematika olimpiadasi — 1-o\'rin', 'student' => 'Madina Yusupova', 'year' => 2025, 'type' => 'Olimpiada', 'level' => 'national'],
        ['title' => 'Robototexnika tanlovi — g\'olib', 'student' => 'Jasur Kamolov', 'year' => 2024, 'type' => 'Tanlov', 'level' => 'national'],
        ['title' => 'Viloyat she\'riyat kechasi — 2-o\'rin', 'student' => 'Laylo Ergasheva', 'year' => 2024, 'type' => 'Ijodiy tanlov', 'level' => 'regional'],
    ];
@endphp

<x-institution.shell
    active="achievements"
    title="O'quvchilar yutuqlari"
    sub="Ota-onalar uchun ishonch — profil sahifasida ko'rinadi"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-toolbar">
        <span class="idash-chart-meta">Yutuqlar profil sahifasida ota-onalarga ko'rinadi — ishonchni oshiradi</span>
        <button type="button" class="btn btn-primary sm">
            <x-maktabgid.icon name="plus" :width="15" :height="15" /> Yutuq qo'shish
        </button>
    </div>

    <div class="panel" style="padding:10px">
        <div class="idash-ach-table">
            @foreach ($mockAchievements as $a)
                <div class="idash-ach-row">
                    <span class="idash-ach-ico"><x-maktabgid.icon name="trophy" :width="24" :height="24" /></span>
                    <div class="idash-ach-main">
                        <b>{{ $a['title'] }}</b>
                        <span>{{ $a['student'] }} · {{ $a['year'] }}-yil · <a href="#">Sertifikat ko'rish</a></span>
                    </div>
                    <span class="idash-pill-neutral">{{ $a['type'] }}</span>
                    <span class="idash-pill-level {{ $levelMeta[$a['level']]['class'] }}">{{ $levelMeta[$a['level']]['label'] }}</span>
                    <div class="idash-card-actions">
                        <button type="button" class="idash-lead-iconbtn" title="Tahrirlash"><x-maktabgid.icon name="edit" :width="14" :height="14" /></button>
                        <button type="button" class="idash-lead-iconbtn danger" title="O'chirish"><x-maktabgid.icon name="close" :width="14" :height="14" /></button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Bu bo'lim demo ma'lumot bilan ko'rsatilmoqda — tez orada real yutuqlar bazasi bilan ishga tushadi
    </div>

    @endif
</x-institution.shell>
