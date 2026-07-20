<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Support\MaktabgidData;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Muassasa kabineti — "Boshqaruv paneli" dashboard qobig'i.
 *
 * Har bir sahifa (dashboard, lidlar, ekskursiyalar, suhbatlar, analitika,
 * profil, tariflar) shu controllerdan keladi va bitta umumiy chap panel
 * (x-institution.shell) ichida render bo'ladi — shunga sidebar/topbar/
 * tashkilot select'i barcha sahifalarda bir xil qoladi.
 *
 * Eslatma: "Tariflar va obuna" (billing) uchun hali alohida DB jadvali yo'q —
 * bu sahifa hozircha vizual/mock ma'lumot bilan ishlaydi (to'lov tizimi
 * ulanmagan). "Lidlar" (Application, type=enrollment), "Analitika"
 * (InstitutionView/Favorite/Application) va "O'quvchilar yutuqlari"
 * (Achievement) endi real (ADR-0002, Faza 2); Analitika'dagi trafik-manba va
 * yosh taqsimoti hali mock — bu ikkisi uchun hech qanday hodisa yozib
 * olinmaydi. Qolgan barcha sahifalar (Ekskursiyalar, Suhbatlar, Muassasa
 * profili) real bazadagi ma'lumot bilan ishlaydi.
 */
class InstitutionCabinetController extends Controller
{
    public function dashboard(Request $request): View
    {
        $ctx = $this->context($request);

        $applications = $ctx['institution']
            ? $ctx['institution']->applications()->latest()->take(20)->get()
            : collect();

        $conversations = $ctx['institution']
            ? $ctx['institution']->conversations()->with('parent')->latest('last_message_at')->take(10)->get()
            : collect();

        $reviews = $ctx['institution']
            ? $ctx['institution']->reviews()->with('author')->latest()->take(5)->get()
            : collect();

        // "So'nggi harakatlar" — real arizalar (turi bo'yicha "lid"/"ekskursiya" deb ajratiladi)
        // + suhbatlar + sharhlar bitta oqimga birlashtiriladi. "Ko'rildi" hodisasi — profil
        // ko'rishlar hisoblagichi hali yo'qligi sababli bitta mock qator sifatida qo'shiladi.
        $activity = $applications->map(fn ($a) => $a->type === 'excursion' ? [
            'type' => 'excursion',
            // Ota-ona belgilagan kun/soat (scheduled_at) bo'lsa o'sha ko'rsatiladi;
            // eski (scheduled_at'siz) yozuvlar uchun so'rov qachon tushgani (created_at).
            // translatedFormat() o'rniga qo'lda oy nomlari ishlatiladi — APP_LOCALE 'uz'ga
            // sozlanmagan bo'lsa ham (config/app.php'da default 'en') sana doim o'zbekcha chiqishi uchun.
            'text' => "Ekskursiya so'rovi: ".self::uzDate($a->scheduled_at ?? $a->created_at),
            'time' => $a->created_at,
        ] : [
            'type' => 'lead',
            'text' => "Yangi lid: {$a->parent_name} — {$a->child_name}",
            'time' => $a->created_at,
        ])->concat($conversations->map(fn ($c) => [
            'type' => 'conversation',
            'text' => ($c->parent?->name ?? 'Foydalanuvchi').' chatda yozdi',
            'time' => $c->last_message_at ?? $c->created_at,
        ]))->concat($reviews->map(fn ($r) => [
            'type' => 'review',
            'text' => 'Yangi sharh: '.str_repeat('★', max(0, min(5, (int) $r->rating))).' — '.($r->author?->name ?? 'Foydalanuvchi'),
            'time' => $r->created_at,
        ]))->concat([[
            'type' => 'views',
            'text' => "E'loningiz bugun {$this->todayViews($ctx['institution'])} marta ko'rildi",
            'time' => now()->subHours(3),
            'subtitle' => 'Bugun',
        ]])->sortByDesc('time')->take(6)->values();

        $confirmed = $applications->where('status', 'confirmed')->count();
        $totalApps = $applications->count();

        return view('institution.dashboard', $ctx + [
            'applications' => $applications,
            'activity' => $activity,
            'conversionRate' => $totalApps > 0 ? round($confirmed / $totalApps * 100, 1) : 0,
        ]);
    }

    /**
     * "Lidlar" — real `Application` (`type=enrollment`) yozuvlari. Alohida lead-
     * generation modeli qurishning hojati yo'q edi: sayt/kabinetdagi "Ariza
     * yuborish" formasi allaqachon shu maqsadda ishlatiladi (excursion turi
     * "Ekskursiyalar" sahifasida, enrollment turi shu yerda) — ADR-0002, Faza 2.
     */
    public function leads(Request $request): View
    {
        $ctx = $this->context($request);
        $institution = $ctx['institution'];

        $leads = $institution
            ? $institution->applications()->where('type', 'enrollment')->latest()->get()
            : collect();

        return view('institution.leads', $ctx + [
            'leads' => $leads,
            'statusLabels' => [
                'pending' => 'Yangi',
                'confirmed' => 'Tasdiqlangan',
                'rejected' => 'Rad etilgan',
            ],
            'leadStats' => [
                'total' => $leads->count(),
                'pending' => $leads->where('status', 'pending')->count(),
                'confirmed' => $leads->where('status', 'confirmed')->count(),
                'rejected' => $leads->where('status', 'rejected')->count(),
            ],
        ]);
    }

    public function excursions(Request $request): View
    {
        $ctx = $this->context($request);

        // Diqqat: avval bu yerda TYPE bo'yicha filtr yo'q edi — enrollment (Lidlar)
        // arizalari ham shu ro'yxatga aralashib ketardi. Endi faqat excursion turi
        // ko'rsatiladi (ADR-0002, Faza 2 — Lidlar sahifasi enrollment turini oladi).
        // Jadval (schedule) tartibida — eng yaqin ekskursiya birinchi, scheduled_at
        // bo'lmagan eski yozuvlar oxirida.
        $applications = $ctx['institution']
            ? $ctx['institution']->applications()->where('type', 'excursion')
                ->orderByRaw('scheduled_at is null')
                ->orderBy('scheduled_at')
                ->get()
            : collect();

        return view('institution.excursions', $ctx + [
            'applications' => $applications,
            'statusLabels' => [
                'pending' => 'Kutilmoqda',
                'confirmed' => 'Tasdiqlangan',
                // "completed" — muassasa "Yakunlash" tugmasi bilan qo'lda belgilaydi
                // (tashrif haqiqatda bo'lib o'tganidan keyin).
                'completed' => "Bo'lib o'tdi",
                'rejected' => 'Bekor qilindi',
            ],
            'excursionStats' => [
                'total' => $applications->count(),
                'pending' => $applications->where('status', 'pending')->count(),
                'confirmed' => $applications->where('status', 'confirmed')->count(),
                'completed' => $applications->where('status', 'completed')->count(),
            ],
        ]);
    }

    public function conversations(Request $request): View
    {
        $ctx = $this->context($request);
        $institution = $ctx['institution'];

        $unreadFromParent = fn ($q) => $q->where('sender_type', 'parent')->whereNull('read_at');

        $conversations = $institution
            ? $institution->conversations()
                ->with(['parent', 'messages' => fn ($q) => $q->latest()->limit(1)])
                ->withCount(['messages as unread_count' => $unreadFromParent])
                ->latest('last_message_at')
                ->get()
            : collect();

        // ?c={id} orqali tanlangan suhbat — bo'lmasa eng so'nggisi ochiladi (mock emas, real
        // Message yozuvlari; sahifa GET bo'lgani uchun tanlash oddiy link/query orqali).
        $active = $conversations->firstWhere('id', (int) $request->query('c')) ?? $conversations->first();

        $activeMessages = collect();
        $activeChild = null;

        if ($active) {
            // Muassasa suhbatni ochganda ota-onadan kelgan o'qilmagan xabarlar real
            // belgilanadi (Message.read_at) — badge son shunga qarab kamayadi.
            $active->messages()->where('sender_type', 'parent')->whereNull('read_at')->update(['read_at' => now()]);
            $active->unread_count = 0;

            $activeChild = $institution?->applications()
                ->where('parent_user_id', $active->parent_user_id)
                ->latest()
                ->first();

            // Har bir xabar qaysi kun ostida va o'sha kun bo'linuvchisi ("Bugun"/"Kecha"/sana)
            // ko'rsatilishi kerakligini oldindan hisoblab beramiz — blade faqat chizadi.
            $prevDay = null;
            $activeMessages = $active->messages()->with('sender')->oldest()->get()->map(function ($m) use (&$prevDay) {
                $day = $m->created_at->toDateString();
                $showDivider = $day !== $prevDay;
                $prevDay = $day;

                return [
                    'model' => $m,
                    'showDivider' => $showDivider,
                    'dayLabel' => $m->created_at->isToday() ? 'Bugun' : ($m->created_at->isYesterday() ? 'Kecha' : self::uzDayLabel($m->created_at)),
                ];
            });
        }

        return view('institution.conversations', $ctx + [
            'conversations' => $conversations,
            'active' => $active,
            'activeMessages' => $activeMessages,
            'activeChild' => $activeChild,
        ]);
    }

    /**
     * "Analitika" sahifasi — "Jami ko'rishlar", haftalik ko'rishlar dinamikasi,
     * "Saqlovga qo'shildi" (Favorite) va "Lidga aylanish" (arizalar konversiyasi)
     * endi real `InstitutionView`/`Favorite`/`Application` yozuvlaridan hisoblanadi
     * (ADR-0002, Faza 2). Trafik-manba (donut) va bola yoshi bo'yicha taqsimot hali
     * mock — bu ikkisi uchun hech qanday real ma'lumot manbai yo'q (ko'rish
     * hodisasida "qayerdan kelgani"/"bola yoshi" umuman yozib olinmaydi).
     */
    public function analytics(Request $request): View
    {
        $ctx = $this->context($request);
        $institution = $ctx['institution'];

        $totalViews = $institution ? $institution->views()->count() : 0;
        $totalFavorites = $institution ? $institution->favorites()->count() : 0;
        $totalApps = $institution ? $institution->applications()->count() : 0;
        $confirmedApps = $institution ? $institution->applications()->where('status', 'confirmed')->count() : 0;
        $conversionRate = $totalApps > 0 ? round($confirmedApps / $totalApps * 100, 1) : 0;

        // Haftalik dinamika — oxirgi 7 kun vs undan oldingi 7 kun, kun-kun ko'rishlar soni.
        $days = ['Du', 'Se', 'Ch', 'Pa', 'Ju', 'Sh', 'Ya'];
        $cur = array_fill(0, 7, 0);
        $prev = array_fill(0, 7, 0);

        if ($institution) {
            $since = now()->startOfDay()->subDays(13);
            $rows = $institution->views()
                ->where('created_at', '>=', $since)
                ->get()
                ->groupBy(fn ($v) => $v->created_at->toDateString());

            for ($i = 0; $i < 14; $i++) {
                $date = $since->copy()->addDays($i);
                $count = $rows->get($date->toDateString())?->count() ?? 0;
                // ISO: 0=Du(Mon)...6=Ya(Sun)
                $slot = $date->dayOfWeekIso - 1;

                if ($i < 7) {
                    $prev[$slot] = $count;
                } else {
                    $cur[$slot] = $count;
                }
            }
        }

        $maxVal = max(1, max(array_merge($cur, $prev)));

        return view('institution.analytics', $ctx + [
            'totalViews' => $totalViews,
            'totalFavorites' => $totalFavorites,
            'conversionRate' => $conversionRate,
            'weekDays' => $days,
            'weekChart' => ['cur' => $cur, 'prev' => $prev],
            'weekMax' => $maxVal,
        ]);
    }

    /** Mock: o'qituvchilar ro'yxati hozircha namunaviy — real ma'lumot uchun
     *  Institution::$teachers (json, profil sahifasida allaqachon ishlatiladi)
     *  keyingi bosqichda shu sahifaga ham ulanadi. */
    public function teachers(Request $request): View
    {
        return view('institution.teachers', $this->context($request));
    }

    /** "O'quvchilar yutuqlari" — real `Achievement` (`Institution::achievements()`), ADR-0002 Faza 2. */
    public function achievements(Request $request): View
    {
        $ctx = $this->context($request);
        $institution = $ctx['institution'];

        return view('institution.achievements', $ctx + [
            'achievements' => $institution ? $institution->achievements : collect(),
        ]);
    }

    /**
     * Rasmlar galereyasi — real `InstitutionMedia` (`type=gallery`) bilan ishlaydi.
     * Yuklash/o'chirish infratuzilmasi (`Institution\MediaController`,
     * `/ajax/institution/me/media`) allaqachon mavjud edi — bu sahifa endi shu
     * real ro'yxatni ko'rsatadi (ADR-0002, Faza 1).
     */
    public function gallery(Request $request): View
    {
        $ctx = $this->context($request);
        $institution = $ctx['institution'];

        $galleryMedia = $institution ? $institution->media->where('type', 'gallery')->values() : collect();

        return view('institution.gallery', $ctx + [
            'galleryMedia' => $galleryMedia,
        ]);
    }

    /**
     * Kabinet ichidagi "Vakansiyalar" ro'yxati — real `Vacancy` modeli bilan ishlaydi
     * (`Institution::vacancies()` munosabati). "Vakansiya ochish" (yaratish) formasi
     * hali pullik-demo bo'lib qoladi (ADR-0002: to'lov tizimi ulanmagan) — faqat
     * ro'yxat/o'qish tomoni real qilindi.
     */
    public function vacancies(Request $request): View
    {
        $ctx = $this->context($request);
        $institution = $ctx['institution'];

        // "Nomzodlar" modali uchun real arizalar ham birga yuklanadi (ADR-0002, Faza 2).
        $vacancies = $institution ? $institution->vacancies()->with('applications')->latest()->get() : collect();

        return view('institution.vacancies', $ctx + [
            'vacancies' => $vacancies,
        ]);
    }

    public function profile(Request $request): View
    {
        $ctx = $this->context($request);
        $i = $ctx['institution'];

        $specializations = MaktabgidData::specializations();
        $districts = MaktabgidData::districts();
        $mySpecs = $i ? $i->specializations->pluck('key')->all() : [];
        $facilityCatalog = MaktabgidData::facilityCatalog();
        $myFacilities = $i ? ($i->facilities ?? []) : [];
        $statLabels = array_column(MaktabgidData::detailStats($i?->type ?? 'maktab'), 'k');

        return view('institution.profile', $ctx + [
            'specializations' => $specializations,
            'districts' => $districts,
            'mySpecs' => $mySpecs,
            'facilityCatalog' => $facilityCatalog,
            'myFacilities' => $myFacilities,
            'statLabels' => $statLabels,
            'programRows' => $i ? ($i->programs ?? []) : [],
            'lessonRows' => $i ? ($i->lessons ?? []) : [],
            'stepRows' => $i ? ($i->admission_steps ?? []) : [],
            // "Narxlar" — real jadval (InstitutionPrice), endi vizual-only emas (2026-07-15).
            'priceRows' => $i ? $i->prices : collect(),
            // "Videolar" — endi real fayl yuklanadi (InstitutionMedia type=video), pipe-matnli
            // textarea o'rniga real qo'shish/o'chirish (2026-07-15).
            'videoItems' => $i ? $i->media->where('type', 'video')->values() : collect(),
        ]);
    }

    public function plans(Request $request): View
    {
        // Mock: tariflar/obuna billing tizimi hali ulanmagan.
        return view('institution.plans', $this->context($request) + [
            'plans' => self::planCatalog(),
        ]);
    }

    /** "Tarifni tanlash" → to'lov (checkout) sahifasi. Hali real billing yo'qligi sababli
     *  "To'lash" tugmasi js-fake-form andozasi orqali demo muvaffaqiyat holatini ko'rsatadi. */
    public function checkout(Request $request, string $plan): View
    {
        $ctx = $this->context($request);
        $catalog = self::planCatalog();

        abort_unless(isset($catalog[$plan]), 404);

        return view('institution.checkout', $ctx + [
            'plan' => $catalog[$plan],
            'paymentMethods' => [
                ['key' => 'humo', 'name' => 'Humo · 8842', 'badge' => 'HUMO', 'color' => '#2aabee'],
                ['key' => 'uzcard', 'name' => 'Uzcard · 1207', 'badge' => 'UZ', 'color' => '#1f9d55', 'selected' => true],
                ['key' => 'payme', 'name' => 'Payme', 'badge' => 'P', 'color' => '#3fc4e8'],
                ['key' => 'click', 'name' => 'Click', 'badge' => 'C', 'color' => '#4457e0'],
            ],
        ]);
    }

    /**
     * Mock tariflar katalogi — billing hali ulanmagani uchun narx/xususiyatlar shu yerda
     * qattiq kodlangan. Real to'lov tizimi qo'shilganda bu ma'lumot bazadan (masalan
     * "plans" jadvalidan) olinadigan bo'ladi; kalitlar (standard/gold/premium) va shakl
     * o'sha migratsiyaga ham mos keladi.
     *
     * @return array<string, array>
     */
    private static function planCatalog(): array
    {
        return [
            'standard' => [
                'key' => 'standard', 'name' => 'Standard', 'dur' => '7 kun', 'days' => 7,
                'sub' => "Tez sinab ko'rish uchun", 'price' => '99 000', 'perDay' => '14 100',
                'leadsLabel' => '50 tagacha',
                'features' => [
                    ["E'loningiz 7 kun katalogda ko'rinadi", true],
                    ['50 tagacha lid — to\'liq kontaktlar bilan', true],
                    ["Ota-onalar bilan to'g'ridan-to'g'ri chat", true],
                    ['Asosiy analitika (ko\'rishlar, lidlar)', true],
                    ['Qidiruvda yuqori o\'rin (top-10)', false],
                    ['«Tavsiya etilgan» belgisi', false],
                ],
            ],
            'gold' => [
                'key' => 'gold', 'name' => 'Gold', 'dur' => '1 oy', 'days' => 30,
                'sub' => 'Eng ko\'p tanlanadigan', 'price' => '299 000', 'perDay' => '9 970',
                'badge' => 'Ommabop', 'highlight' => true, 'leadsLabel' => 'Cheksiz',
                'features' => [
                    ["E'loningiz 30 kun katalogda ko'rinadi", true],
                    ['Cheksiz lidlar va kontaktlar', true],
                    ["Ota-onalar bilan to'g'ridan-to'g'ri chat", true],
                    ["To'liq analitika va konversiya hisobi", true],
                    ["Qidiruvda yuqori o'rin (top-10)", true],
                    ['«Tavsiya etilgan» belgisi', true],
                ],
            ],
            'premium' => [
                'key' => 'premium', 'name' => 'Premium', 'dur' => '1 yil', 'days' => 365,
                'sub' => 'Yil davomida xotirjamlik', 'price' => '2 490 000', 'perDay' => '6 820',
                'badge' => 'Eng foydali', 'badgeColor' => 'orange', 'leadsLabel' => 'Cheksiz',
                'features' => [
                    ["E'loningiz 365 kun katalogda ko'rinadi", true],
                    ['Cheksiz lidlar + Excel eksport', true],
                    ['Kengaytirilgan portfolio, video va banner', true],
                    ['Doimiy TOP-3 va bosh sahifa banneri', true],
                    ["Prioritet qo'llab-quvvatlash + shaxsiy menejer", true],
                    ['«Tavsiya etilgan» belgisi', true],
                ],
            ],
        ];
    }

    /** Real: bugungi profil ko'rishlar soni — "So'nggi harakatlar" oqimidagi qator uchun (ADR-0002, Faza 2). */
    private function todayViews(?Institution $institution): int
    {
        return $institution ? $institution->views()->whereDate('created_at', now()->toDateString())->count() : 0;
    }

    /** "8-iyun, 14:30" ko'rinishidagi sana — APP_LOCALE'dan mustaqil, doim o'zbekcha oy nomi bilan. */
    private static function uzDate(Carbon $date): string
    {
        return $date->day.'-'.self::uzMonth($date).', '.$date->format('H:i');
    }

    /** "8-iyun" — vaqtsiz, suhbat sahifasidagi sana bo'linuvchilari uchun. */
    private static function uzDayLabel(Carbon $date): string
    {
        return $date->day.'-'.self::uzMonth($date);
    }

    private static function uzMonth(Carbon $date): string
    {
        $months = [1 => 'yanvar', 'fevral', 'mart', 'aprel', 'may', 'iyun', 'iyul', 'avgust', 'sentabr', 'oktabr', 'noyabr', 'dekabr'];

        return $months[$date->month];
    }

    /**
     * Barcha muassasa kabineti sahifalari uchun umumiy kontekst: joriy foydalanuvchi,
     * uning "faol" muassasasi, sidebar'dagi badge sonlar va tashkilotlar ro'yxati (select uchun).
     *
     * "organizations" — bitta foydalanuvchi bir nechta filialga ega bo'lishi (ko'p-filial
     * qo'llab-quvvatlash, 2026-07-15): $user->institutions() orqali egalik qilingan barcha
     * muassasalar ro'yxati chiqadi, "faol" (session'dagi active_institution_id) belgilanadi.
     * Qolgan barcha kabinet kontrollerlari shu bilan bir xil ID'ni ResolvesActiveInstitution
     * orqali ishlatadi.
     */
    private function context(Request $request): array
    {
        $user = Auth::user();
        $institution = null;
        $organizations = [];

        if ($user && $user->isInstitution()) {
            $institution = $this->activeInstitution($request, ['district', 'specializations', 'media']);

            $typeLabels = ['maktab' => 'Xususiy maktab', 'bogcha' => "Xususiy bog'cha", 'markaz' => "O'quv markazi"];

            $organizations = $user->institutions()->with('district')->get()->map(fn ($org) => [
                'id' => $org->id,
                'name' => $org->name,
                'meta' => ($typeLabels[$org->type] ?? 'Muassasa').
                    ($org->district?->name ? ' · '.$org->district->name : ''),
                'mono' => MaktabgidData::monogram($org->name),
                'active' => $institution && $org->id === $institution->id,
            ])->all();
        }

        return [
            'user' => $user,
            'institution' => $institution,
            'organizations' => $organizations,
            'counts' => [
                // Real: yangi (pending) enrollment arizalari — "Lidlar" sahifasi.
                'leads' => $institution
                    ? $institution->applications()->where('type', 'enrollment')->where('status', 'pending')->count()
                    : 0,
                // Diqqat: faqat excursion turi (enrollment endi "leads" badge'ida hisoblanadi).
                'excursions' => $institution
                    ? $institution->applications()->where('type', 'excursion')->where('status', 'pending')->count()
                    : 0,
                // Sidebar badge — jami suhbatlar emas, ota-onadan o'qilmagan xabari bor
                // suhbatlar soni (Message.read_at asosida, real).
                'conversations' => $institution
                    ? $institution->conversations()
                        ->whereHas('messages', fn ($q) => $q->where('sender_type', 'parent')->whereNull('read_at'))
                        ->count()
                    : 0,
                // Real e'lonlar soni (Vacancy) — nomzodlar/holat boshqaruvi hali ulanmagan
                // bo'lsa ham, "nechta e'lon joylangan" badge sifatida to'g'ri ma'no beradi.
                'vacancies' => $institution ? $institution->vacancies()->count() : null,
            ],
        ];
    }
}
