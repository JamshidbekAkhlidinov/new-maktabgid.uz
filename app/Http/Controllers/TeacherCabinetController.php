<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FormatsUzbekDates;
use App\Models\Resume;
use App\Models\Vacancy;
use App\Models\VacancyApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Ustoz (o'qituvchi) kabineti — "Boshqaruv paneli" dashboard qobig'i.
 *
 * Auth/rol tekshiruvi real: joriy foydalanuvchi User::ROLE_TEACHER bo'lsagina
 * $teacher massivi to'ldiriladi, aks holda x-teacher.shell "kirish kerak"
 * holatini ko'rsatadi (x-institution.shell/x-parent.shell bilan bir xil andoza).
 *
 * "Rezyumelarim" (Resume.owner_user_id), "Vakansiyalar" (bozor ro'yxati) va
 * "Takliflar" (VacancyApplication.teacher_user_id — yuborgan arizalar va ularning
 * holati) endi real (ADR-0002, Faza 2). "Suhbatlar" ham endi real —
 * Conversation.teacher_user_id orqali (ADR-0003, ADR-0002'da kechiktirilgan
 * bo'shliq). Yaratish/rezyume-joylash formasi hali pullik-demo (to'lov tizimi
 * ulanmagani uchun).
 */
class TeacherCabinetController extends Controller
{
    use FormatsUzbekDates;

    public function dashboard(Request $request): View
    {
        return view('teacher.dashboard', $this->context());
    }

    public function resumes(Request $request): View
    {
        $authUser = Auth::user();
        $resumes = ($authUser && $authUser->isTeacher())
            ? Resume::where('owner_user_id', $authUser->id)->latest()->get()
            : collect();

        return view('teacher.resumes', $this->context() + [
            'resumes' => $resumes,
        ]);
    }

    public function vacancies(Request $request): View
    {
        $vacancies = Vacancy::query()
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
            ->latest()
            ->get();

        return view('teacher.vacancies', $this->context() + [
            'vacancies' => $vacancies,
        ]);
    }

    /** "Takliflar" — ustoz yuborgan arizalar va muassasa javobi (qabul/kutilmoqda/rad). */
    public function offers(Request $request): View
    {
        $authUser = Auth::user();
        $offers = ($authUser && $authUser->isTeacher())
            ? VacancyApplication::where('teacher_user_id', $authUser->id)
                ->with('vacancy')
                ->latest()
                ->get()
            : collect();

        return view('teacher.offers', $this->context() + [
            'offers' => $offers,
        ]);
    }

    public function conversations(Request $request): View
    {
        $ctx = $this->context();
        $user = ($authUser = Auth::user()) && $authUser->isTeacher() ? $authUser : null;

        $unreadFromInstitution = fn ($q) => $q->where('sender_type', 'institution')->whereNull('read_at');

        $conversations = $user
            ? $user->teacherConversations()
                ->with(['institution', 'messages' => fn ($q) => $q->latest()->limit(1)])
                ->withCount(['messages as unread_count' => $unreadFromInstitution])
                ->latest('last_message_at')
                ->get()
            : collect();

        // ?c={id} orqali tanlangan suhbat — bo'lmasa eng so'nggisi ochiladi (parent/institution
        // Suhbatlar sahifasi bilan bir xil andoza).
        $active = $conversations->firstWhere('id', (int) $request->query('c')) ?? $conversations->first();

        $activeMessages = collect();

        if ($active) {
            // Ustoz suhbatni ochganda muassasadan kelgan o'qilmagan xabarlar real belgilanadi.
            $active->messages()->where('sender_type', 'institution')->whereNull('read_at')->update(['read_at' => now()]);
            $active->unread_count = 0;

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

        return view('teacher.conversations', $ctx + [
            'conversations' => $conversations,
            'active' => $active,
            'activeMessages' => $activeMessages,
        ]);
    }

    public function tariffs(Request $request): View
    {
        return view('teacher.tariffs', $this->context());
    }

    /**
     * Barcha ustoz kabineti sahifalari uchun umumiy kontekst — real foydalanuvchi
     * (agar ustoz bo'lsa) + sidebar badge sonlari (institution/parent kabinetidagi
     * context() bilan bir xil vazifani bajaradi).
     */
    private function context(): array
    {
        $authUser = Auth::user();
        $user = ($authUser && $authUser->isTeacher()) ? $authUser->loadMissing('district') : null;

        $teacher = $user ? [
            'name' => $user->name,
            'phone' => $user->phone,
            // Mock: fan/tajriba ro'yxatdan o'tishda so'ralmaydi — "Rezyumelarim" bo'limida
            // to'ldiriladi (bu bo'lim hali qurilmagan, shuning uchun umumiy yorliq).
            'role' => 'Ustoz',
            'exp' => $user->district?->name ? $user->district->name.' tumani' : 'Yangi a\'zo',
            'completeness' => 40,
        ] : null;

        return [
            'teacher' => $teacher,
            'counts' => [
                // Real: bozordagi ochiq (muddati o'tmagan) vakansiyalar soni.
                'vacancies' => Vacancy::query()
                    ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
                    ->count(),
                // Real: yuborilgan arizalardan hali javob kelmagan (kutilmoqda) sonlari.
                'offers' => $user
                    ? VacancyApplication::where('teacher_user_id', $user->id)->where('status', 'pending')->count()
                    : 0,
                // Real: muassasadan o'qilmagan xabari bor suhbatlar soni (ADR-0003).
                'conversations' => $user
                    ? $user->teacherConversations()
                        ->whereHas('messages', fn ($q) => $q->where('sender_type', 'institution')->whereNull('read_at'))
                        ->count()
                    : 0,
            ],
        ];
    }
}
