<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Institution;
use Illuminate\Http\Request;

/**
 * Ko'p-filial qo'llab-quvvatlash (2026-07-15) — bitta foydalanuvchi bir nechta
 * Institution yozuviga (owner_user_id) egalik qilishi mumkin. Har qanday
 * so'rov paytida "hozir qaysi filial bilan ishlanayotgani" session'da
 * (`active_institution_id`) saqlanadi va shu trait orqali barcha muassasa
 * kabineti kontrollerlarida bir xil tarzda hal qilinadi.
 *
 * Avval har bir controller to'g'ridan-to'g'ri `$request->user()->institution()
 * ->firstOrFail()` chaqirardi — bu hasOne bog'lanish DB darajasida bir nechta
 * muassasani qo'llab-quvvatlasa ham, doim tasodifiy bittasini qaytarardi va
 * "Yangi muassasa qo'shish"/tashkilot almashtirish tugmalari real ishlay
 * olmasdi. Shu trait ularning barchasini almashtiradi.
 */
trait ResolvesActiveInstitution
{
    /** Joriy "faol" muassasa — mavjud bo'lmasa (mehmon, boshqa rol, hali muassasa yaratmagan) null. */
    protected function activeInstitution(Request $request, array $with = []): ?Institution
    {
        $user = $request->user();

        if (! $user) {
            return null;
        }

        $ids = $user->institutions()->pluck('id');

        if ($ids->isEmpty()) {
            return null;
        }

        $activeId = (int) $request->session()->get('active_institution_id');

        if (! $activeId || ! $ids->contains($activeId)) {
            $activeId = $ids->first();
            $request->session()->put('active_institution_id', $activeId);
        }

        return Institution::with($with)->find($activeId);
    }

    /** `->firstOrFail()`ning o'rnini bosadi — faol muassasa topilmasa 404. */
    protected function activeInstitutionOrFail(Request $request, array $with = []): Institution
    {
        $institution = $this->activeInstitution($request, $with);

        abort_unless($institution, 404);

        return $institution;
    }

    /**
     * Session'dagi faol muassasani boshqasiga almashtiradi — foydalanuvchi
     * shu muassasaning egasi bo'lishi shart (aks holda 403).
     */
    protected function setActiveInstitution(Request $request, int $institutionId): Institution
    {
        $user = $request->user();

        $institution = $user->institutions()->whereKey($institutionId)->first();

        abort_unless($institution, 403);

        $request->session()->put('active_institution_id', $institution->id);

        return $institution;
    }
}
