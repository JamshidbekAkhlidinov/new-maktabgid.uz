@php use App\Support\MaktabgidData; @endphp

<x-parent.shell active="favorites" title="{{ __('cabinet_parent.nav_favorites') }}" sub="{{ __('cabinet_parent.favorites_sub') }}" :user="$user" :stats="$stats">

    <div class="panel">
        <div class="panel-head"><h3>{{ __('cabinet_parent.favorites_heading') }}</h3></div>
        @if ($favorites->isEmpty())
            <div class="empty">
                <span class="empty-ico"><x-maktabgid.icon name="heart" :width="26" :height="26" /></span>
                <p>{{ __('cabinet_parent.favorites_empty') }}</p>
                <a href="{{ route('welcome') }}" class="btn btn-primary">{{ __('cabinet_parent.go_to_catalog') }}</a>
            </div>
        @else
            <div class="cab-list">
                @foreach ($favorites as $fav)
                    @continue(! $fav->institution)
                    <a href="{{ route('maktabgid.school', $fav->institution) }}" class="cab-item">
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
