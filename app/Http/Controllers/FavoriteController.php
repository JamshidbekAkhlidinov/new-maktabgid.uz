<?php

namespace App\Http\Controllers;

use App\Http\Resources\InstitutionResource;
use App\Models\Institution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $institutions = Institution::query()
            ->whereHas('favorites', fn ($q) => $q->where('user_id', $request->user()->id))
            ->with(['district', 'specializations'])
            ->get();

        return response()->json(['favorites' => InstitutionResource::collection($institutions)]);
    }

    public function store(Request $request, Institution $institution): JsonResponse
    {
        $request->user()->favorites()->firstOrCreate(['institution_id' => $institution->id]);

        return response()->json(['favorited' => true], 201);
    }

    public function destroy(Request $request, Institution $institution): JsonResponse
    {
        $request->user()->favorites()->where('institution_id', $institution->id)->delete();

        return response()->json(['favorited' => false]);
    }
}
