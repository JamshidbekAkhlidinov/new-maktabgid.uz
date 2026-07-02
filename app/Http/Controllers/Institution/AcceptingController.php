<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcceptingController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate(['accepting' => ['required', 'boolean']]);

        $institution = $request->user()->institution()->firstOrFail();

        $this->authorize('update', $institution);

        $institution->update(['accepting' => $data['accepting']]);

        return response()->json(['accepting' => $institution->accepting]);
    }
}
