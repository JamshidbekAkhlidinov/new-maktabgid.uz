@props(['achievements'])

@if (! empty($achievements))
    @php
        $levelMeta = [
            'intl' => ['label' => __('school.level_intl'), 'class' => 'intl'],
            'national' => ['label' => __('school.level_national'), 'class' => 'national'],
            'regional' => ['label' => __('school.level_regional'), 'class' => 'regional'],
            'city' => ['label' => __('school.level_city'), 'class' => 'city'],
        ];
    @endphp
    <section class="card-block">
        <h3><x-maktabgid.icon name="trophy" :width="19" :height="19" /> {{ __('school.achievements_title') }}</h3>
        <div class="ach-grid">
            @foreach ($achievements as $a)
                <div class="ach-card">
                    <span class="ach-card-ico"><x-maktabgid.icon name="trophy" :width="20" :height="20" /></span>
                    <div class="ach-card-body">
                        <b>{{ $a['title'] }}</b>
                        <span>{{ collect([$a['student'], $a['year'] ? __('school.year_label', ['year' => $a['year']]) : null, $a['type']])->filter()->implode(' · ') }}</span>
                    </div>
                    <em class="ach-card-level {{ $levelMeta[$a['level']]['class'] ?? 'city' }}">{{ $levelMeta[$a['level']]['label'] ?? $a['level'] }}</em>
                </div>
            @endforeach
        </div>
    </section>
@endif
