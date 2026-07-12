<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Frontenddagi eski `mg_user` (localStorage) shakliga mos JSON — Blade JS shu formatga moslashtiriladi.
 */
class AuthUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kind' => $this->role, // parent | institution | admin | teacher
            'name' => $this->name,
            'phone' => $this->phone,
            'age' => $this->age,
            'district' => $this->whenLoaded('district', fn () => $this->district?->name, $this->district?->name),
            'org' => $this->whenLoaded('institution', fn () => $this->institution?->name, $this->institution?->name),
            'orgKind' => $this->whenLoaded('institution', fn () => $this->institution?->type, $this->institution?->type),
            'phoneVerified' => (bool) $this->phone_verified_at,
        ];
    }
}
