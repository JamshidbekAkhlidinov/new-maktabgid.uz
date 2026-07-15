@props(['achievements'])

@if (! empty($achievements))
    @php
        $levelMeta = [
            'intl' => ['label' => 'Xalqaro', 'class' => 'intl'],
            'national' => ['label' => 'Respublika', 'class' => 'national'],
            'regional' => ['label' => 'Viloyat', 'class' => 'regional'],
            'city' => ['label' => 'Shahar', 'class' => 'city'],
        ];
    @endphp
    <section class="card-block">
        <h3><x-maktabgid.icon name="trophy" :width="19" :height="19" /> O'quvchilar yutuqlari</h3>
        <div class="ach-grid">
            @foreach ($achievements as $a)
                <div class="ach-card">
                    <span class="ach-card-ico"><x-maktabgid.icon name="trophy" :width="20" :height="20" /></span>
                    <div class="ach-card-body">
                        <b>{{ $a['title'] }}</b>
                        <span>{{ collect([$a['student'], $a['year'] ? "{$a['year']}-yil" : null, $a['type']])->filter()->implode(' · ') }}</span>
                    </div>
                    <em class="ach-card-level {{ $levelMeta[$a['level']]['class'] ?? 'city' }}">{{ $levelMeta[$a['level']]['label'] ?? $a['level'] }}</em>
                </div>
            @endforeach
        </div>
    </section>
@endif
