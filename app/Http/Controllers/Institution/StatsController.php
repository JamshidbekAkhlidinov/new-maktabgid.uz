<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $institution = $request->user()->institution()->firstOrFail();
        $this->authorize('view', $institution);

        return response()->json([
            'stats' => [
                'applications' => [
                    'total' => $institution->applications()->count(),
                    'pending' => $institution->applications()->where('status', 'pending')->count(),
                    'confirmed' => $institution->applications()->where('status', 'confirmed')->count(),
                ],
                'conversations' => $institution->conversations()->count(),
                'favorites' => $institution->favorites()->count(),
                'rating' => (float) $institution->rating,
                'reviewCount' => $institution->review_count,
                // Real (ADR-0002, Faza 2) — App\Models\InstitutionView.
                'profileViews' => $institution->views()->count(),
            ],
        ]);
    }
}
