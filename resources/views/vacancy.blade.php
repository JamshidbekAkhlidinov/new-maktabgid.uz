<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $vacancy['title'] }} — {{ __('careers.page_title') }} — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php use App\Support\MaktabgidData; @endphp

    <x-maktabgid.nav :categories="MaktabgidData::categories()" />

    <div class="wrap" style="padding-top:20px">
        <x-maktabgid.back-link href="{{ route('careers.index') }}" label="{{ __('careers.back_to_vacancies') }}" />
    </div>

    <div class="wrap detail-grid">

        {{-- ===== MAIN ===== --}}
        <div class="detail-main">
            <article class="card-block" style="padding:28px 30px">
                <span class="vac-type">{{ $vacancy['type'] }}</span>
                <h1 style="margin-top:12px;font-size:clamp(22px,3vw,30px)">{{ $vacancy['title'] }}</h1>
                <div class="vac-org" style="margin-top:10px">
                    <span class="av" style="width:36px;height:36px;border-radius:10px;background:var(--primary-soft);color:var(--primary-ink);display:grid;place-items:center;font-weight:800;font-size:15px;font-family:var(--font-display)">{{ mb_substr($vacancy['org'], 0, 1) }}</span>
                    {{ $vacancy['org'] }}
                </div>
            </article>

            <article class="card-block" style="padding:28px 30px">
                <h3 style="margin-bottom:14px">{{ __('careers.about_position') }}</h3>
                <p style="line-height:1.75;margin-bottom:14px">{{ __('careers.position_intro', ['org' => $vacancy['org'], 'title' => $vacancy['title']]) }}</p>
                <p style="line-height:1.75">{{ __('careers.position_requirements_intro') }}</p>
            </article>

            <article class="card-block" style="padding:28px 30px">
                <h3 style="margin-bottom:14px">{{ __('careers.requirements') }}</h3>
                <ul class="side-facts" style="gap:13px">
                    <li><x-maktabgid.icon name="check" :width="17" :height="17" /> {{ __('careers.req_degree') }}</li>
                    <li><x-maktabgid.icon name="check" :width="17" :height="17" /> {{ __('careers.req_experience') }}</li>
                    <li><x-maktabgid.icon name="check" :width="17" :height="17" /> {{ __('careers.req_psychology') }}</li>
                    <li><x-maktabgid.icon name="check" :width="17" :height="17" /> {{ __('careers.req_teamwork') }}</li>
                </ul>
            </article>

            <article class="card-block" id="ariza" style="padding:28px 30px">
                <h3 style="margin-bottom:4px">{{ __('careers.apply') }}</h3>
                <p style="color:var(--ink-2);margin-bottom:20px;font-size:14px">{{ __('careers.apply_sub') }}</p>
                <div class="js-inline-enroll">
                    <form class="enroll-form js-vacancy-apply-form" data-vacancy-id="{{ $vacancy['id'] }}" enctype="multipart/form-data" style="gap:14px">
                        <div class="form-row2">
                            <x-maktabgid.field label="{{ __('careers.full_name_label') }}" icon="user"><input name="full_name" required placeholder="{{ __('careers.full_name_placeholder') }}" /></x-maktabgid.field>
                            <x-maktabgid.field label="{{ __('careers.phone_label') }}" icon="phone"><input name="phone" required placeholder="{{ __('careers.phone_placeholder') }}" /></x-maktabgid.field>
                        </div>
                        <x-maktabgid.field label="{{ __('careers.message_label') }}" icon="edit"><textarea name="note" rows="3" placeholder="{{ __('careers.message_placeholder') }}"></textarea></x-maktabgid.field>
                        <x-maktabgid.field label="{{ __('careers.resume_label') }}" icon="paperclip" hint="{{ __('careers.resume_hint') }}">
                            <input type="file" name="resume" accept=".pdf,.doc,.docx" />
                        </x-maktabgid.field>
                        <div>
                            <button class="btn btn-primary" type="submit"><x-maktabgid.icon name="send" :width="16" :height="16" /> {{ __('careers.apply') }}</button>
                        </div>
                    </form>
                    <x-maktabgid.success-note title="{{ __('careers.application_accepted_title') }}" class="js-fake-success" style="display:none">
                        {{ __('careers.application_accepted_body') }}
                    </x-maktabgid.success-note>
                </div>
            </article>
        </div>

        {{-- ===== SIDEBAR ===== --}}
        <aside class="detail-side">
            <div class="side-card">
                <div class="side-price">
                    <b>{{ $vacancy['salary'] }}</b>
                    <span>{{ __('careers.currency_per_month') }}</span>
                </div>

                <a href="#ariza" class="btn btn-primary side-cta">
                    <x-maktabgid.icon name="send" :width="16" :height="16" /> {{ __('careers.apply') }}
                </a>

                <ul class="side-facts">
                    <li>
                        <x-maktabgid.icon name="bag" :width="17" :height="17" />
                        {{ $vacancy['type'] }}
                    </li>
                    <li>
                        <x-maktabgid.icon name="building" :width="17" :height="17" />
                        {{ $vacancy['org'] }}
                    </li>
                    <li>
                        <x-maktabgid.icon name="clock" :width="17" :height="17" />
                        {{ __('careers.deadline', ['date' => $vacancy['until']]) }}
                    </li>
                </ul>
            </div>
        </aside>

    </div>

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    <script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>
