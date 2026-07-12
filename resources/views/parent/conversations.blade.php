@php use App\Support\MaktabgidData; @endphp

<x-parent.shell active="conversations" title="Suhbatlar" sub="Muassasalar bilan yozishma" :user="$user" :stats="$stats">

    <div class="panel">
        <div class="panel-head">
            <h3>Suhbatlar</h3>
            <a href="{{ route('chat.index') }}" class="btn btn-ghost">
                Hammasini ochish <x-maktabgid.icon name="arrowR" :width="15" :height="15" />
            </a>
        </div>
        @if ($conversations->isEmpty())
            <div class="empty">
                <span class="empty-ico"><x-maktabgid.icon name="chat" :width="26" :height="26" /></span>
                <p>Hali suhbat yo'q.</p>
            </div>
        @else
            <div class="cab-list">
                @foreach ($conversations as $conv)
                    @continue(! $conv->institution)
                    <a href="{{ route('chat.index') }}" class="cab-item">
                        <span class="avatar" style="width:46px;height:46px;border-radius:10px;font-size:18px;background:linear-gradient(140deg,#6c63ff,#3b82f6)">{{ MaktabgidData::monogram($conv->institution->name) }}</span>
                        <div class="cab-item-main">
                            <b>{{ $conv->institution->name }}</b>
                        </div>
                        <x-maktabgid.icon name="chevronR" :width="18" :height="18" />
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</x-parent.shell>
