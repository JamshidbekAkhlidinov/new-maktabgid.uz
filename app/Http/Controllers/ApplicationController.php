<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationStoreRequest;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /** Mehmon ham, ro'yxatdan o'tgan ota-ona ham yubora oladi. */
    public function store(ApplicationStoreRequest $request): JsonResponse
    {
        $application = Application::create($request->validated() + [
            'parent_user_id' => $request->user()?->id,
            'status' => 'pending',
        ]);

        // TODO (Phase 5): muassasa egasiga NewApplicationNotification yuboriladi.
        return response()->json(['application' => $application->load('institution')], 201);
    }

    /** role:parent — o'z arizalarini status bilan ko'rish. */
    public function mine(Request $request): JsonResponse
    {
        $applications = $request->user()->applications()
            ->with('institution')
            ->latest()
            ->get();

        return response()->json(['applications' => $applications]);
    }
}
