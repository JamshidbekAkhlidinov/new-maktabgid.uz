<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Requests\Institution\UpdateProfileRequest;
use App\Http\Resources\InstitutionResource;
use App\Models\District;
use App\Models\Specialization;
use App\Services\Geo\TwoGisGeocodingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $institution = $request->user()->institution()
            ->with(['district', 'specializations', 'media'])
            ->firstOrFail();

        $this->authorize('view', $institution);

        return response()->json(['institution' => new InstitutionResource($institution)]);
    }

    public function update(UpdateProfileRequest $request, TwoGisGeocodingService $geocoder): JsonResponse
    {
        $institution = $request->user()->institution()->firstOrFail();

        $this->authorize('update', $institution);

        $data = $request->validated();

        $district = isset($data['district'])
            ? District::firstOrCreate(['name' => $data['district']])
            : null;

        $institution->fill(array_filter([
            'name' => $data['name'] ?? null,
            'type' => $data['type'] ?? null,
            'about' => $data['about'] ?? null,
            'lang' => $data['lang'] ?? null,
            'district_id' => $district?->id,
            'address' => $data['address'] ?? null,
            'grades' => $data['grades'] ?? null,
            'work_hours' => $data['work_hours'] ?? null,
        ], fn ($v) => $v !== null));

        if (array_key_exists('monthly_price', $data)) {
            $institution->monthly_price = $data['monthly_price'];
        }

        if (array_key_exists('works_saturday', $data)) {
            $institution->works_saturday = (bool) $data['works_saturday'];
        }

        // Manzil o'zgargan yoki koordinata hali yo'q bo'lsa — 2GIS orqali avtomatik geocoding
        if ($institution->isDirty('address') && filled($institution->address)) {
            $coords = $geocoder->geocode($institution->address, $institution->district?->name);
            if ($coords) {
                $institution->lat = $coords['lat'];
                $institution->lng = $coords['lng'];
            }
        }

        $institution->save();

        if (isset($data['specializations'])) {
            $ids = Specialization::whereIn('key', $data['specializations'])->pluck('id');
            $institution->specializations()->sync($ids);
        }

        return response()->json([
            'institution' => new InstitutionResource($institution->fresh(['district', 'specializations', 'media'])),
        ]);
    }
}
