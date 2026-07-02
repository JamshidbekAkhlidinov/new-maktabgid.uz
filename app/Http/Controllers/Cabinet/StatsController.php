<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** Ota-ona kabineti (Profil tabidagi cab-stats) uchun real hisob — backend.md Phase 4. */
class StatsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'stats' => [
                'favorites' => $user->favorites()->count(),
                'applications' => $user->applications()->count(),
                'conversations' => $user->conversations()->count(),
            ],
        ]);
    }
}
