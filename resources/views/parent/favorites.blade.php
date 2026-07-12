@php use App\Support\MaktabgidData; @endphp

<x-parent.shell active="favorites" title="Saqlanganlar" sub="Yoqtirgan muassasalaringiz" :user="$user" :stats="$stats">

    <div class="panel">
        <div class="panel-head"><h3>Saqlangan muassasalar</h3></div>
        @if ($favorites->isEmpty())
            <div class="empty">
                <span class="empty-ico"><x-maktabgid.icon name="heart" :width="26" :height="26" /></span>
                <p>Hali muassasa saqlamadingiz.</p>
                <a href="{{ route('welcome') }}" class="btn btn-primary">Katalogga o'tish</a>
            </div>
        @else
            <div class="cab-list">
                @foreach ($favorites as $fav)
                    @continue(! $fav->institution)
                    <a href="{{ route('maktabgid.school', $fav->institution->id) }}" class="cab-item">
                        <span class="avatar" style="width:46px;height:46px;border-radius:10px;font-size:18px;background:linear-gradient(140deg,var(--primary),var(--primary-700))">{{ MaktabgidData::monogram($fav->institution->name) }}</span>
                        <div class="cab-item-main">
                            <b>{{ $fav->institution->name }}</b>
                            <span>{{ $fav->institution->district?->name ?? '' }}</span>
                        </div>
                        <x-maktabgid.icon name="chevronR" :width="18" :height="18" />
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</x-parent.shell>
