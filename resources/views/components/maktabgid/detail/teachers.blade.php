@props(['teachers'])

@if (count($teachers))
<section class="card-block">
    <h3><x-maktabgid.icon name="users" :width="19" :height="19" /> {{ __('school.teachers_title') }}</h3>
    <div class="teach-grid">
        @foreach ($teachers as $tc)
            <div class="teach-card">
                <x-maktabgid.avatar :name="$tc['n']" :g="$tc['g']" :size="56" />
                <b>{{ $tc['n'] }}</b>
                <span>{{ $tc['role'] }}</span>
                <em>{{ __('school.experience', ['exp' => $tc['exp']]) }}</em>
            </div>
        @endforeach
    </div>
</section>
@endif
