@php
    // Mock: farzand profillari (AI Tanlovchi uchun) hali alohida DB jadvali yo'q —
    // shu bo'lim namunaviy ma'lumot bilan ko'rsatiladi.
    $childWhy = [
        ['icon' => 'target', 'title' => 'Aniqroq tavsiya', 'text' => 'AI Tanlovchi yosh va qiziqishga mos muassasa taklif qiladi.'],
        ['icon' => 'bell', 'title' => 'Muhim eslatmalar', 'text' => "Ekskursiya va ariza holatlari farzand bo'yicha ajratiladi."],
        ['icon' => 'shield', 'title' => 'Xavfsiz va shaxsiy', 'text' => "Ma'lumotlar faqat sizga ko'rinadi, uchinchi shaxsga berilmaydi."],
    ];
    $mockChildren = [
        ['name' => 'Asadbek', 'age' => '8 yosh', 'gender' => "O'g'il bola", 'interests' => ['Ingliz tili', 'Futbol']],
        ['name' => 'Amina', 'age' => '5 yosh', 'gender' => 'Qiz bola', 'interests' => ['Rassomchilik', "Bog'cha"]],
    ];
@endphp

<x-parent.shell active="children" title="Farzandlarim" sub="AI Tanlovchi uchun farzand ma'lumotlari" :user="$user" :stats="$stats">

    <div class="panel">
        <div class="panel-head"><h3>Farzandlarim</h3></div>

        <div class="cab-why">
            <b>Nima uchun farzand qo'shish kerak?</b>
            <div class="cab-why-grid">
                @foreach ($childWhy as $w)
                    <div class="cab-why-item">
                        <span class="cab-why-ico"><x-maktabgid.icon :name="$w['icon']" :width="20" :height="20" /></span>
                        <div><b>{{ $w['title'] }}</b><span>{{ $w['text'] }}</span></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="cab-child-grid">
            @foreach ($mockChildren as $ch)
                <div class="cab-child-card">
                    <div class="cab-child-top">
                        <x-maktabgid.avatar :name="$ch['name']" :size="56" />
                        <div class="cab-child-main">
                            <b>{{ $ch['name'] }}</b>
                            <span>{{ $ch['age'] }} · {{ $ch['gender'] }}</span>
                        </div>
                        <div style="display:flex;gap:6px">
                            <button type="button" class="idash-lead-iconbtn" title="Tahrirlash"><x-maktabgid.icon name="edit" :width="15" :height="15" /></button>
                        </div>
                    </div>
                    <div class="cab-child-tags">
                        @foreach ($ch['interests'] as $i)
                            <span>{{ $i }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <button type="button" class="cab-child-add">
                <x-maktabgid.icon name="plus" :width="22" :height="22" />
                Farzand qo'shish
            </button>
        </div>
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Bu bo'lim demo ma'lumot bilan ko'rsatilmoqda — tez orada real farzand profillari bilan ishga tushadi
    </div>

</x-parent.shell>
