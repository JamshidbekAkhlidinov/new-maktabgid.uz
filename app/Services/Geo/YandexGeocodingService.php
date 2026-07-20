<?php

namespace App\Services\Geo;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Yandex Geocoder API bilan manzildan lat/lng topadi (backend.md §9).
 * YANDEX_MAPS_API_KEY sozlanmagan bo'lsa jim null qaytaradi — profil baribir saqlanadi,
 * faqat xaritada ko'rinmaydi (keyinroq qo'lda ham to'ldirish mumkin).
 */
class YandexGeocodingService
{
    protected const ENDPOINT = 'https://geocode-maps.yandex.ru/1.x/';

    public function isConfigured(): bool
    {
        return filled(config('services.yandex.key'));
    }

    /** @return array{lat: float, lng: float}|null */
    public function geocode(?string $address, ?string $district = null): ?array
    {
        if (! $this->isConfigured() || blank($address)) {
            return null;
        }

        $query = trim("Toshkent, {$district} {$address}");

        $response = Http::timeout(6)->get(self::ENDPOINT, [
            'apikey' => config('services.yandex.key'),
            'geocode' => $query,
            'format' => 'json',
            'results' => 1,
        ]);

        if (! $response->successful()) {
            Log::warning('Yandex geocoding muvaffaqiyatsiz.', ['query' => $query, 'status' => $response->status()]);

            return null;
        }

        $pos = data_get($response->json(), 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos');

        if (! $pos) {
            return null;
        }

        [$lng, $lat] = explode(' ', $pos);

        return ['lat' => (float) $lat, 'lng' => (float) $lng];
    }
}
