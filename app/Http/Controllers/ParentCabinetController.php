<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Ota-ona kabineti — "Kabinet" dashboard qobig'i.
 *
 * Har bir sahifa (profil, farzandlarim, saqlanganlar, arizalarim, suhbatlar,
 * obuna) shu controllerdan keladi va bitta umumiy qobiq (x-parent.shell)
 * ichida render bo'ladi — InstitutionCabinetController/x-institution.shell
 * bilan bir xil andozada, saytdagi barcha kabinetlar vizual jihatdan izchil
 * bo'lishi uchun.
 *
 * Eslatma: "Obuna" uchun hali alohida DB jadvali yo'q — shu sahifa hozircha
 * mock ma'lumot bilan ishlaydi (InstitutionCabinetController dagi "Tariflar"
 * bilan bir xil yondashuv). Qolgan barcha sahifalar (Profil, Farzandlarim,
 * Saqlanganlar, Arizalarim, Suhbatlar) real bazadagi ma'lumot bilan ishlaydi.
 */
class ParentCabinetController extends Controller
{
    public function dashboard(Request $request): View
    {
        $ctx = $this->context();

        return view('parent.dashboard', $ctx);
    }

    /** Farzand profillari (AI Tanlovchi uchun) — real jadval, ADR 2026-07-14. */
    public function children(Request $request): View
    {
        return view('parent.children', $this->context());
    }

    /**
     * PUT /ajax/me — "Profilni tahrirlash" modali (parent/dashboard.blade.php).
     * Faqat ism, telefon va tumanni yangilaydi; parol/rol shu yerdan o'zgarmaydi.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user && $user->isParent(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($user->id)],
            'district' => ['sometimes', 'nullable', 'string', 'max:150'],
        ]);

        $district = filled($data['district'] ?? null)
            ? District::firstOrCreate(['name' => $data['district']])
            : null;

        $user->fill([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'district_id' => $district?->id,
        ])->save();

        return response()->json(['user' => $user->fresh('district')]);
    }

    public function favorites(Request $request): View
    {
        $ctx = $this->context();
        $user = $ctx['user'];

        $favorites = $user
            ? $user->favorites()->with('institution.district')->latest()->get()
            : collect();

        return view('parent.favorites', $ctx + ['favorites' => $favorites]);
    }

    public function applications(Request $request): View
    {
        $ctx = $this->context();
        $user = $ctx['user'];

        $applications = $user
            ? $user->applications()->with('institution')->latest()->get()
            : collect();

        return view('parent.applications', $ctx + [
            'applications' => $applications,
            'statusLabels' => ['pending' => "Ko'rib chiqilmoqda", 'confirmed' => 'Tasdiqlandi', 'rejected' => 'Rad etildi'],
            'statusClass' => ['pending' => 'pending', 'confirmed' => 'done', 'rejected' => 'rejected'],
        ]);
    }

    public function conversations(Request $request): View
    {
        $ctx = $this->context();
        $user = $ctx['user'];

        $conversations = $user
            ? $user->conversations()->with('institution')->latest('last_message_at')->get()
            : collect();

        return view('parent.conversations', $ctx + ['conversations' => $conversations]);
    }

    /** Mock: obuna/billing tizimi hali ulanmagan (InstitutionCabinetController::plans() dagi kabi). */
    public function subscription(Request $request): View
    {
        return view('parent.subscription', $this->context());
    }

    /**
     * Barcha ota-ona kabineti sahifalari uchun umumiy kontekst: joriy foydalanuvchi
     * (agar ota-ona bo'lsa) va sidebar badge sonlari. Mehmon yoki boshqa rol bo'lsa
     * $user null qaytadi — x-parent.shell shu holatda "kirish kerak" ekranini ko'rsatadi
     * (avval web.php'dagi closure shu logikani o'zida qattiq kodlagan edi).
     */
    private function context(): array
    {
        $authUser = Auth::user();
        $user = ($authUser && $authUser->isParent()) ? $authUser->loadMissing('district') : null;

        $stats = [
            'favorites' => $user ? $user->favorites()->count() : 0,
            'applications' => $user ? $user->applications()->count() : 0,
            'conversations' => $user ? $user->conversations()->count() : 0,
            // Mock: profil ko'rilishi hali kuzatilmaydi (view-tracking jadvali yo'q).
            'profile_views' => 48,
        ];

        return [
            'user' => $user,
            'stats' => $stats,
            // Real jadval (children) — dashboard va "Farzandlarim" sahifasi shu bir xil
            // ro'yxatdan foydalanadi.
            'children' => $user ? $user->children()->orderBy('id')->get() : collect(),
        ];
    }
}
