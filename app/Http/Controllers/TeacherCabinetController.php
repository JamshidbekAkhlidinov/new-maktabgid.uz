<?php

namespace App\Http\Controllers;

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
 * holati) endi real (ADR-0002, Faza 2). Yaratish/rezyume-joylash formasi hali
 * pullik-demo (to'lov tizimi ulanmagani uchun). "Suhbatlar" hali mock —
 * ustoz↔muassasa suhbat modeli hali qurilmagan (ADR-0002, Faza 3).
 */
class TeacherCabinetController extends Controller
{
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
        return view('teacher.conversations', $this->context());
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
                // Mock: "Suhbatlar" — ustoz↔muassasa suhbat modeli hali qurilmagan (ADR-0002, Faza 3).
                'conversations' => 2,
            ],
        ];
    }
}
