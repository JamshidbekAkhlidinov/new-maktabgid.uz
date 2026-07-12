<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Ustoz (o'qituvchi) kabineti — "Boshqaruv paneli" dashboard qobig'i.
 *
 * Auth/rol tekshiruvi endi real: joriy foydalanuvchi User::ROLE_TEACHER
 * bo'lsagina $teacher massivi to'ldiriladi, aks holda x-teacher.shell
 * "kirish kerak" holatini ko'rsatadi (x-institution.shell/x-parent.shell
 * bilan bir xil andoza).
 *
 * Rezyume/vakansiya/taklif ro'yxatlari va sonlari hali mock — bular uchun
 * hali maxsus munosabat (owner_user_id orqali Resume/Vacancy bilan bog'lash,
 * taklif/suhbat modeli) qurilmagan. Faqat profil ma'lumotlari (ism, tuman)
 * endi real ro'yxatdan o'tgan foydalanuvchidan olinadi.
 */
class TeacherCabinetController extends Controller
{
    public function dashboard(Request $request): View
    {
        return view('teacher.dashboard', $this->context());
    }

    public function resumes(Request $request): View
    {
        return view('teacher.resumes', $this->context());
    }

    public function vacancies(Request $request): View
    {
        return view('teacher.vacancies', $this->context());
    }

    public function offers(Request $request): View
    {
        return view('teacher.offers', $this->context());
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
                // Mock: rezyume/vakansiya/taklif hali shu foydalanuvchiga bog'lanmagan.
                'vacancies' => 12,
                'offers' => 4,
                'conversations' => 2,
            ],
        ];
    }
}
