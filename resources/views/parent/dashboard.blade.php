<x-parent.shell active="dashboard" title="Profilim" sub="Shaxsiy ma'lumotlaringiz" :user="$user" :stats="$stats">

    <div class="panel">
        <div class="panel-head">
            <h3>Shaxsiy ma'lumotlar</h3>
        </div>
        <div class="kv-grid">
            <div class="kv">
                <span>Ism Familiya</span>
                <b>{{ $user->name }}</b>
            </div>
            <div class="kv">
                <span>Telefon raqami</span>
                <b>{{ $user->phone }}</b>
            </div>
            <div class="kv">
                <span>Yosh</span>
                <b>{{ $user->age ?? '—' }}</b>
            </div>
            <div class="kv">
                <span>Yashash tumani</span>
                <b>{{ $user->district?->name ?? '—' }}</b>
            </div>
        </div>
        <div class="cab-stats">
            <div class="cstat"><b>{{ $stats['favorites'] }}</b><span>saqlangan muassasa</span></div>
            <div class="cstat"><b>{{ $stats['applications'] }}</b><span>yuborilgan ariza</span></div>
            <div class="cstat"><b>{{ $stats['conversations'] }}</b><span>faol suhbat</span></div>
        </div>
    </div>

</x-parent.shell>
