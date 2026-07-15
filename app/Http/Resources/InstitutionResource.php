<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstitutionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'about' => $this->about,
            'lang' => $this->lang,
            'district' => $this->whenLoaded('district', fn () => $this->district?->name, $this->district?->name),
            'address' => $this->address,
            'lat' => $this->lat !== null ? (float) $this->lat : null,
            'lng' => $this->lng !== null ? (float) $this->lng : null,
            'monthlyPrice' => $this->monthly_price,
            'grades' => $this->grades,
            'workHours' => $this->work_hours,
            'worksSaturday' => (bool) $this->works_saturday,
            'accepting' => (bool) $this->accepting,
            'rating' => (float) $this->rating,
            'reviewCount' => $this->review_count,
            'badge' => $this->badge,
            'facilities' => $this->facilities ?? [],
            'teachers' => $this->teachers ?? [],
            'programs' => $this->programs ?? [],
            'lessons' => $this->lessons ?? [],
            'videos' => $this->videos ?? [],
            'admissionSteps' => $this->admission_steps ?? [],
            'statClassSize' => $this->stat_class_size,
            'statExperienceYears' => $this->stat_experience_years,
            'statAdmissionRate' => $this->stat_admission_rate,
            'statFirstGradeSeats' => $this->stat_first_grade_seats,
            'specializations' => $this->whenLoaded(
                'specializations',
                fn () => $this->specializations->pluck('key')->values()
            ),
            'media' => $this->whenLoaded(
                'media',
                fn () => $this->media->map(fn ($m) => [
                    'id' => $m->id,
                    'type' => $m->type,
                    'url' => $m->url,
                    'caption' => $m->caption,
                    'duration' => $m->duration,
                    'sortOrder' => $m->sort_order,
                ])->values()
            ),
            'prices' => $this->whenLoaded(
                'prices',
                fn () => $this->prices->map(fn ($p) => [
                    'id' => $p->id,
                    'grade' => $p->grade,
                    'lang' => $p->lang,
                    'monthlyPrice' => $p->monthly_price,
                    'discount' => $p->discount,
                ])->values()
            ),
        ];
    }
}
