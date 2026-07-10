@php
    // Mock: alohida "Lidlar" (sayt/Telegram/qidiruv orqali qiziqish bildirganlar — ariza
    // qoldirmagan bo'lishi ham mumkin) modeli hali loyihada yo'q. Quyidagi ro'yxat shu bo'lim
    // qanday ishlashini ko'rsatish uchun namuna — real modul ulanganda controller shu joyni
    // bazadan hisoblangan qatorlar bilan almashtiradi.
    $statusMeta = [
        'new' => ['label' => 'Yangi', 'class' => 'new'],
        'contact' => ['label' => 'Aloqada', 'class' => 'contact'],
        'excursion' => ['label' => 'Ekskursiyaga yozildi', 'class' => 'excursion'],
        'placed' => ['label' => 'Joylashdi', 'class' => 'placed'],
    ];

    $mockLeads = [
        ['name' => 'Dilnoza Murodova', 'child' => 'Asadbek, 8 yosh', 'interest' => 'Ingliz tili', 'phone' => '+998 90 123 45 67', 'source' => 'Katalog', 'status' => 'new', 'time' => '32 daqiqa oldin'],
        ['name' => 'Sardor Tursunov', 'child' => 'Madina, 11 yosh', 'interest' => 'IELTS tayyorlov', 'phone' => '+998 91 234 56 78', 'source' => 'Qidiruv', 'status' => 'new', 'time' => '2 soat oldin'],
        ['name' => 'Gulnora Aliyeva', 'child' => 'Jasur, 10 yosh', 'interest' => 'IT / dasturlash', 'phone' => '+998 93 345 67 89', 'source' => 'Telegram bot', 'status' => 'new', 'time' => '5 soat oldin'],
        ['name' => 'Bekzod Umarov', 'child' => 'Sevinch, 9 yosh', 'interest' => 'Ingliz tili', 'phone' => '+998 94 456 78 90', 'source' => 'Katalog', 'status' => 'contact', 'time' => 'Kecha'],
        ['name' => 'Nodira Saidova', 'child' => 'Aziz, 13 yosh', 'interest' => 'SAT tayyorlov', 'phone' => '+998 90 567 89 01', 'source' => 'Tavsiya', 'status' => 'contact', 'time' => 'Kecha'],
        ['name' => 'Jamshid Karimov', 'child' => 'Laylo, 7 yosh', 'interest' => 'Ingliz tili', 'phone' => '+998 97 678 90 12', 'source' => 'Katalog', 'status' => 'excursion', 'time' => '2 kun oldin'],
        ['name' => 'Kamola Rashidova', 'child' => 'Sardor, 12 yosh', 'interest' => 'IT / dasturlash', 'phone' => '+998 99 789 01 23', 'source' => 'Instagram', 'status' => 'new', 'time' => '2 kun oldin'],
        ['name' => 'Zarina Yusupova', 'child' => 'Malika, 6 yosh', 'interest' => 'Rus tili', 'phone' => '+998 95 123 44 55', 'source' => 'Qidiruv', 'status' => 'new', 'time' => '3 kun oldin'],
        ['name' => 'Otabek Ergashev', 'child' => 'Sherzod, 14 yosh', 'interest' => 'Matematika', 'phone' => '+998 90 234 55 66', 'source' => 'Katalog', 'status' => 'new', 'time' => '3 kun oldin'],
        ['name' => 'Malika Nazarova', 'child' => 'Ozoda, 8 yosh', 'interest' => 'Ingliz tili', 'phone' => '+998 93 456 66 77', 'source' => 'Telegram bot', 'status' => 'contact', 'time' => '4 kun oldin'],
        ['name' => 'Farrux Tojiyev', 'child' => 'Diyor, 10 yosh', 'interest' => 'IT / dasturlash', 'phone' => '+998 97 567 77 88', 'source' => 'Instagram', 'status' => 'contact', 'time' => '4 kun oldin'],
        ['name' => 'Shahnoza Qodirova', 'child' => 'Nilufar, 9 yosh', 'interest' => 'SAT tayyorlov', 'phone' => '+998 91 678 88 99', 'source' => 'Tavsiya', 'status' => 'excursion', 'time' => '5 kun oldin'],
        ['name' => 'Rustam Aliqulov', 'child' => 'Bekzod, 11 yosh', 'interest' => 'Ingliz tili', 'phone' => '+998 94 789 99 00', 'source' => 'Katalog', 'status' => 'placed', 'time' => '6 kun oldin'],
        ['name' => 'Dilshod Norqobilov', 'child' => 'Madina, 7 yosh', 'interest' => 'Rus tili', 'phone' => '+998 90 890 00 11', 'source' => 'Qidiruv', 'status' => 'placed', 'time' => '6 kun oldin'],
    ];

    // Diqqat: bu o'zgaruvchi atayin "$counts" emas "$leadCounts" — controllerdan keladigan
    // $counts (sidebar badge sonlari: leads/excursions/conversations) bilan nom to'qnashmasligi
    // uchun, aks holda x-institution.shell'ga noto'g'ri massiv o'tib ketardi.
    $leadCounts = [
        'all' => count($mockLeads),
        'new' => collect($mockLeads)->where('status', 'new')->count(),
        'contact' => collect($mockLeads)->where('status', 'contact')->count(),
        'excursion' => collect($mockLeads)->where('status', 'excursion')->count(),
        'placed' => collect($mockLeads)->where('status', 'placed')->count(),
    ];

    $tabs = [
        ['key' => 'all', 'label' => 'Hammasi'],
        ['key' => 'new', 'label' => 'Yangi'],
        ['key' => 'contact', 'label' => 'Aloqada'],
        ['key' => 'excursion', 'label' => 'Ekskursiya'],
        ['key' => 'placed', 'label' => 'Joylashdi'],
    ];
@endphp

<x-institution.shell
    active="leads"
    title="Lidlar"
    sub="Farzandini bermoqchi bo'lgan ota-onalar"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Bu bo'lim demo ma'lumot bilan ko'rsatilmoqda — tez orada real lidlar bilan ishga tushadi
    </div>

    <div class="idash-stats">
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:var(--primary-soft);color:var(--primary)"><x-maktabgid.icon name="users" :width="18" :height="18" /></span>
                <span class="idash-stat-delta"><x-maktabgid.icon name="trending" :width="12" :height="12" /> +8</span>
            </div>
            <div><b>{{ $leadCounts['all'] }}</b><span>Jami lidlar</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:#ece9fc;color:#5145d8"><x-maktabgid.icon name="sparkle" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $leadCounts['new'] }}</b><span>Yangi (javob kutmoqda)</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:var(--accent-soft);color:#b45309"><x-maktabgid.icon name="chat" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $leadCounts['excursion'] }}</b><span>Ekskursiyaga yozildi</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:#fde7f3;color:#c2247a"><x-maktabgid.icon name="check" :width="18" :height="18" /></span>
                <span class="idash-stat-delta"><x-maktabgid.icon name="trending" :width="12" :height="12" /> +2</span>
            </div>
            <div><b>{{ $leadCounts['placed'] }}</b><span>Joylashdi</span></div>
        </div>
    </div>

    <div class="idash-lead-toolbar">
        <div class="idash-lead-tabs">
            @foreach ($tabs as $t)
                <button type="button" class="idash-lead-tab js-filter-tab{{ $t['key'] === 'all' ? ' on' : '' }}" data-status="{{ $t['key'] }}">
                    {{ $t['label'] }} <em>{{ $leadCounts[$t['key']] }}</em>
                </button>
            @endforeach
        </div>
        <label class="idash-lead-search">
            <x-maktabgid.icon name="search" :width="16" :height="16" />
            <input type="text" class="js-filter-search" placeholder="Ism yoki qiziqish bo'yicha…" />
        </label>
        <button type="button" class="btn btn-ghost sm">
            <x-maktabgid.icon name="download" :width="15" :height="15" /> Excel
        </button>
    </div>

    <div class="panel">
        <div class="idash-lead-table">
            <div class="idash-lead-head">
                <span>Ota-ona</span>
                <span>Farzand / qiziqish</span>
                <span>Telefon</span>
                <span>Manba</span>
                <span>Holat</span>
                <span>Amal</span>
            </div>

            @foreach ($mockLeads as $lead)
                <div class="idash-lead-row js-filter-row" data-status="{{ $lead['status'] }}" data-search="{{ strtolower($lead['name'].' '.$lead['interest']) }}">
                    <div class="idash-lead-parent">
                        <x-maktabgid.avatar :name="$lead['name']" :size="38" />
                        <div>
                            <b>{{ $lead['name'] }}</b>
                            <span>{{ $lead['time'] }}</span>
                        </div>
                    </div>
                    <div class="idash-lead-child">
                        <b>{{ $lead['child'] }}</b>
                        <span>{{ $lead['interest'] }}</span>
                    </div>
                    <div class="idash-lead-phone">
                        <x-maktabgid.icon name="phone" :width="14" :height="14" /> {{ $lead['phone'] }}
                    </div>
                    <div><span class="idash-lead-source">{{ $lead['source'] }}</span></div>
                    <div><span class="idash-lead-status {{ $statusMeta[$lead['status']]['class'] }}">{{ $statusMeta[$lead['status']]['label'] }}</span></div>
                    <div class="idash-lead-actions">
                        <button type="button" class="idash-lead-iconbtn" title="Chatga o'tish"><x-maktabgid.icon name="chat" :width="16" :height="16" /></button>
                        <a class="idash-lead-iconbtn" href="tel:{{ $lead['phone'] }}" title="Qo'ng'iroq qilish"><x-maktabgid.icon name="phone" :width="16" :height="16" /></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @endif
</x-institution.shell>
