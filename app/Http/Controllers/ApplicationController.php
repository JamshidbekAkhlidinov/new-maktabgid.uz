<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationStoreRequest;
use App\Models\Application;
use App\Notifications\NewApplicationNotification;
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

        $application->load('institution.owner');

        $application->institution?->owner?->notify(new NewApplicationNotification($application));

        return response()->json(['application' => $application], 201);
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
