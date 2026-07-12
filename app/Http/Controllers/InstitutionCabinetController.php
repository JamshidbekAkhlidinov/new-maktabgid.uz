<?php

namespace App\Http\Controllers;

use App\Support\MaktabgidData;
use Illuminate\Http\Request;
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
 * Eslatma: "Lidlar", "Analitika" va "Tariflar va obuna" uchun hali alohida
 * DB jadvali yo'q — shu sahifalar hozircha vizual/mock ma'lumot bilan
 * ishlaydi (loyihaning shu bosqichida shunday kelishilgan). Qolgan barcha
 * sahifalar (Ekskursiyalar, Suhbatlar, Muassasa profili) real bazadagi
 * ma'lumot bilan ishlaydi.
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
            // Diqqat: "preferred_start" erkin matn maydoni (masalan "Keyingi chorak"), sana emas —
            // shuning uchun so'rov qachon tushgani (created_at) ko'rsatiladi. translatedFormat()
            // o'rniga qo'lda oy nomlari ishlatiladi — APP_LOCALE 'uz' ga sozlanmagan bo'lsa ham
            // (config/app.php'da default 'en') sana doim o'zbekcha chiqishi uchun.
            'text' => "Ekskursiya so'rovi: ".self::uzDate($a->created_at),
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
            'text' => "E'loningiz bugun {$this->mockTodayViews()} marta ko'rildi", // mock — hisoblagich hali yo'q
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

    public function leads(Request $request): View
    {
        // Mock: "Lidlar" — hali alohida lead-generation modeli yo'q.
        return view('institution.leads', $this->context($request));
    }

    public function excursions(Request $request): View
    {
        $ctx = $this->context($request);

        $applications = $ctx['institution']
            ? $ctx['institution']->applications()->latest()->get()
            : collect();

        return view('institution.excursions', $ctx + [
            'applications' => $applications,
            'statusLabels' => [
                'pending' => 'Kutilmoqda',
                'confirmed' => 'Tasdiqlangan',
                // "completed" — muassasa "Yakunlash" tugmasi bilan qo'lda belgilaydi
                // (tashrif haqiqatda bo'lib o'tganidan keyin); real sana/vaqt maydoni
                // hali yo'qligi sababli avtomatik hisoblanmaydi.
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

    public function analytics(Request $request): View
    {
        // Mock: profil ko'rishlar hisoblagichi hali yo'q (StatsController'da ham shunday izohlangan).
        return view('institution.analytics', $this->context($request));
    }

    /** Mock: o'qituvchilar ro'yxati hozircha namunaviy — real ma'lumot uchun
     *  Institution::$teachers (json, profil sahifasida allaqachon ishlatiladi)
     *  keyingi bosqichda shu sahifaga ham ulanadi. */
    public function teachers(Request $request): View
    {
        return view('institution.teachers', $this->context($request));
    }

    /** Mock: "O'quvchilar yutuqlari" — hali alohida DB jadvali yo'q. */
    public function achievements(Request $request): View
    {
        return view('institution.achievements', $this->context($request));
    }

    /** Mock: "Rasmlar" galereyasi — real yuklash InstitutionMedia orqali keyinroq ulanadi
     *  (hozir faqat ko'rinish, InstitutionMedia hozircha video/boshqa turlar uchun ishlatiladi). */
    public function gallery(Request $request): View
    {
        return view('institution.gallery', $this->context($request));
    }

    /** Mock: kabinet ichidagi "Vakansiyalar" boshqaruvi — real Vacancy modeli allaqachon
     *  mavjud (careers sahifasida ishlatiladi), lekin nomzodlar/holat boshqaruvi hali shu
     *  kabinetga ulanmagan — shuning uchun hozircha namunaviy ro'yxat bilan ko'rsatiladi. */
    public function vacancies(Request $request): View
    {
        return view('institution.vacancies', $this->context($request));
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

        $pipeLinesToText = fn ($rows, $keys) => $rows
            ? implode("\n", array_map(fn ($r) => implode(' | ', array_map(fn ($k) => $r[$k] ?? '', $keys)), $rows))
            : '';
        $plainLinesToText = fn ($rows) => $rows ? implode("\n", array_map(fn ($r) => is_array($r) ? ($r['label'] ?? '') : $r, $rows)) : '';

        return view('institution.profile', $ctx + [
            'specializations' => $specializations,
            'districts' => $districts,
            'mySpecs' => $mySpecs,
            'facilityCatalog' => $facilityCatalog,
            'myFacilities' => $myFacilities,
            'statLabels' => $statLabels,
            'teachersText' => $i ? $pipeLinesToText($i->teachers ?? [], ['n', 'role', 'exp']) : '',
            'programsText' => $i ? $pipeLinesToText($i->programs ?? [], ['t', 'd']) : '',
            'lessonsText' => $i ? $plainLinesToText($i->lessons ?? []) : '',
            'videosText' => $i ? $pipeLinesToText($i->videos ?? [], ['title', 'dur', 'sub']) : '',
            'stepsText' => $i ? $pipeLinesToText($i->admission_steps ?? [], ['t', 'd']) : '',
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

    /** Mock: profil ko'rishlar hisoblagichi hali yo'q — "So'nggi harakatlar" oqimidagi
     *  bitta namuna qator uchun ishlatiladi (StatsController'dagi "profileViews" izohiga qarang). */
    private function mockTodayViews(): int
    {
        return 142;
    }

    /** "8-iyun, 14:30" ko'rinishidagi sana — APP_LOCALE'dan mustaqil, doim o'zbekcha oy nomi bilan. */
    private static function uzDate(\Illuminate\Support\Carbon $date): string
    {
        return $date->day.'-'.self::uzMonth($date).', '.$date->format('H:i');
    }

    /** "8-iyun" — vaqtsiz, suhbat sahifasidagi sana bo'linuvchilari uchun. */
    private static function uzDayLabel(\Illuminate\Support\Carbon $date): string
    {
        return $date->day.'-'.self::uzMonth($date);
    }

    private static function uzMonth(\Illuminate\Support\Carbon $date): string
    {
        $months = [1 => 'yanvar', 'fevral', 'mart', 'aprel', 'may', 'iyun', 'iyul', 'avgust', 'sentabr', 'oktabr', 'noyabr', 'dekabr'];

        return $months[$date->month];
    }

    /**
     * Barcha muassasa kabineti sahifalari uchun umumiy kontekst: joriy foydalanuvchi,
     * uning muassasasi, sidebar'dagi badge sonlar va tashkilotlar ro'yxati (select uchun).
     *
     * "organizations" — bitta foydalanuvchi bir nechta filialga ega bo'lish imkoniyati uchun
     * tayyorlangan struktura. Hozircha DB darajasida ko'p-ko'pga bog'lanish yo'q (faqat UI),
     * shuning uchun ro'yxatda foydalanuvchining o'z (yagona) muassasasi chiqadi — lekin
     * komponent va markup allaqachon bir nechta tashkilotni ko'rsatishga tayyor.
     */
    private function context(Request $request): array
    {
        $user = Auth::user();
        $institution = null;

        if ($user && $user->isInstitution()) {
            $institution = $user->institution()->with(['district', 'specializations', 'media'])->first();
        }

        $organizations = $institution ? [[
            'id' => $institution->id,
            'name' => $institution->name,
            'meta' => (['maktab' => "Xususiy maktab", 'bogcha' => "Xususiy bog'cha", 'markaz' => "O'quv markazi"][$institution->type] ?? 'Muassasa').
                ($institution->district?->name ? ' · '.$institution->district->name : ''),
            'mono' => MaktabgidData::monogram($institution->name),
            'active' => true,
        ]] : [];

        return [
            'user' => $user,
            'institution' => $institution,
            'organizations' => $organizations,
            'counts' => [
                'leads' => $institution ? 0 : 0, // Lidlar — hali real hisoblagich yo'q (mock sahifa).
                'excursions' => $institution ? $institution->applications()->where('status', 'pending')->count() : 0,
                // Sidebar badge — jami suhbatlar emas, ota-onadan o'qilmagan xabari bor
                // suhbatlar soni (Message.read_at asosida, real).
                'conversations' => $institution
                    ? $institution->conversations()
                        ->whereHas('messages', fn ($q) => $q->where('sender_type', 'parent')->whereNull('read_at'))
                        ->count()
                    : 0,
                // Mock: "Vakansiyalar" sidebar badge'i — kabinet ichidagi nomzodlar boshqaruvi
                // hali ulanmagani uchun hozircha ko'rsatilmaydi (null => badge chiqmaydi).
                'vacancies' => null,
            ],
        ];
    }
}
