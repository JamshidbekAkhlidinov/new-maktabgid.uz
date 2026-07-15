<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Notifications\ApplicationStatusNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Muassasaga kelgan ekskursiya/joylashtirish arizalari — backend.md Phase 5. */
class InboxController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $institution = $this->activeInstitutionOrFail($request);
        $this->authorize('view', $institution);

        $applications = $institution->applications()
            ->with('parent')
            ->latest()
            ->get();

        return response()->json(['applications' => $applications]);
    }

    public function updateStatus(Request $request, Application $application): JsonResponse
    {
        $this->authorize('updateStatus', $application);

        $data = $request->validate([
            // 'completed' — ekskursiya/tashrif bo'lib o'tganini muassasa qo'lda "Yakunlash"
            // orqali belgilaydi (institution-cabinet Ekskursiyalar sahifasi).
            'status' => ['required', Rule::in(['pending', 'confirmed', 'rejected', 'completed'])],
        ]);

        $application->update(['status' => $data['status']]);

        if ($application->parent_user_id && $application->parent) {
            $application->parent->notify(new ApplicationStatusNotification($application));
        }

        return response()->json(['application' => $application->fresh('institution')]);
    }
}
