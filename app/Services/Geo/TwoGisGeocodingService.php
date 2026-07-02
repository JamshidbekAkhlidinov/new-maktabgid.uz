<?php

namespace App\Services\Geo;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * 2GIS Geocoder API bilan manzildan lat/lng topadi (backend.md §9).
 * TWOGIS_API_KEY sozlanmagan bo'lsa jim null qaytaradi — profil baribir saqlanadi,
 * faqat xaritada ko'rinmaydi (keyinroq qo'lda ham to'ldirish mumkin).
 */
class TwoGisGeocodingService
{
    protected const ENDPOINT = 'https://catalog.api.2gis.com/3.0/items/geocode';

    public function isConfigured(): bool
    {
        return filled(config('services.twogis.key'));
    }

    /** @return array{lat: float, lng: float}|null */
    public function geocode(?string $address, ?string $district = null): ?array
    {
        if (! $this->isConfigured() || blank($address)) {
            return null;
        }

        $query = trim("Toshkent, {$district} {$address}");

        $response = Http::timeout(6)->get(self::ENDPOINT, [
            'q' => $query,
            'fields' => 'items.point',
            'key' => config('services.twogis.key'),
        ]);

        if (! $response->successful()) {
            Log::warning('2GIS geocoding muvaffaqiyatsiz.', ['query' => $query, 'status' => $response->status()]);

            return null;
        }

        $point = data_get($response->json(), 'result.items.0.point');

        if (! $point || ! isset($point['lat'], $point['lon'])) {
            return null;
        }

        return ['lat' => (float) $point['lat'], 'lng' => (float) $point['lon']];
    }
}
