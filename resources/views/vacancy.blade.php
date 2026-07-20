<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $vacancy['title'] }} — Vakansiyalar — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php use App\Support\MaktabgidData; @endphp

    <x-maktabgid.nav :categories="MaktabgidData::categories()" />

    <div class="wrap" style="padding-top:20px">
        <x-maktabgid.back-link href="{{ route('careers.index') }}" label="Vakansiyalarga qaytish" />
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
                <h3 style="margin-bottom:14px">Lavozim haqida</h3>
                <p style="line-height:1.75;margin-bottom:14px">{{ $vacancy['org'] }} jamoasiga "{{ $vacancy['title'] }}" lavozimi boʻyicha tajribali, mas'uliyatli va bolalar bilan ishlashni yaxshi koʻradigan xodim izlanmoqda. Ish jadvali moslashuvchan, jamoa do'stona muhitda faoliyat yuritadi.</p>
                <p style="line-height:1.75">Muvaffaqiyatli nomzod oʻz sohasida kamida 2 yil ish tajribasiga ega boʻlishi, zamonaviy oʻqitish uslublarini qoʻllashi va bolalar bilan samarali muloqot qila olishi kerak.</p>
            </article>

            <article class="card-block" style="padding:28px 30px">
                <h3 style="margin-bottom:14px">Talablar</h3>
                <ul class="side-facts" style="gap:13px">
                    <li><x-maktabgid.icon name="check" :width="17" :height="17" /> Tegishli yoʻnalish boʻyicha oliy maʼlumot</li>
                    <li><x-maktabgid.icon name="check" :width="17" :height="17" /> Kamida 2 yil ish tajribasi</li>
                    <li><x-maktabgid.icon name="check" :width="17" :height="17" /> Bolalar psixologiyasini tushunish</li>
                    <li><x-maktabgid.icon name="check" :width="17" :height="17" /> Jamoada ishlash va doimiy rivojlanish</li>
                </ul>
            </article>

            <article class="card-block" id="ariza" style="padding:28px 30px">
                <h3 style="margin-bottom:4px">Ariza yuborish</h3>
                <p style="color:var(--ink-2);margin-bottom:20px;font-size:14px">Maʼlumotlaringizni qoldiring — qabul boʻlimi siz bilan bogʻlanadi.</p>
                <div class="js-inline-enroll">
                    <form class="enroll-form js-vacancy-apply-form" data-vacancy-id="{{ $vacancy['id'] }}" enctype="multipart/form-data" style="gap:14px">
                        <div class="form-row2">
                            <x-maktabgid.field label="Toʻliq ism" icon="user"><input name="full_name" required placeholder="Ism Familiya" /></x-maktabgid.field>
                            <x-maktabgid.field label="Telefon raqam" icon="phone"><input name="phone" required placeholder="+998 90 123 45 67" /></x-maktabgid.field>
                        </div>
                        <x-maktabgid.field label="Xabar (ixtiyoriy)" icon="edit"><textarea name="note" rows="3" placeholder="Qisqacha oʻzingiz haqingizda…"></textarea></x-maktabgid.field>
                        <x-maktabgid.field label="Rezyume / CV" icon="paperclip" hint="ixtiyoriy, PDF yoki Word, 5MB gacha">
                            <input type="file" name="resume" accept=".pdf,.doc,.docx" />
                        </x-maktabgid.field>
                        <div>
                            <button class="btn btn-primary" type="submit"><x-maktabgid.icon name="send" :width="16" :height="16" /> Ariza yuborish</button>
                        </div>
                    </form>
                    <x-maktabgid.success-note title="Ariza qabul qilindi!" class="js-fake-success" style="display:none">
                        Tez orada siz bilan bogʻlanamiz.
                    </x-maktabgid.success-note>
                </div>
            </article>
        </div>

        {{-- ===== SIDEBAR ===== --}}
        <aside class="detail-side">
            <div class="side-card">
                <div class="side-price">
                    <b>{{ $vacancy['salary'] }}</b>
                    <span>soʻm / oy</span>
                </div>

                <a href="#ariza" class="btn btn-primary side-cta">
                    <x-maktabgid.icon name="send" :width="16" :height="16" /> Ariza yuborish
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
                        Muddat: {{ $vacancy['until'] }}
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
