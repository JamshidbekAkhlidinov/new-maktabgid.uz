<?php

namespace App\Http\Requests\Institution;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // controller darajasida InstitutionPolicy::update tekshiriladi
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', Rule::in(['maktab', 'bogcha', 'markaz', 'mutaxassis'])],
            'lang' => ['sometimes', 'nullable', 'string', 'max:100'],
            'about' => ['sometimes', 'nullable', 'string'],
            'district' => ['sometimes', 'nullable', 'string', 'max:255'],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'monthly_price' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'grades' => ['sometimes', 'nullable', 'string', 'max:100'],
            'work_hours' => ['sometimes', 'nullable', 'string', 'max:100'],
            'works_saturday' => ['sometimes', 'boolean'],
            'specializations' => ['sometimes', 'array'],
            'specializations.*' => ['string'],

            // Muassasa haqida sahifasi bo'limlari — backend.md frontend-ulash bosqichi
            'facilities' => ['sometimes', 'array'],
            'facilities.*' => ['string'],
            'teachers_text' => ['sometimes', 'nullable', 'string', 'max:4000'],
            'programs_text' => ['sometimes', 'nullable', 'string', 'max:4000'],
            'lessons_text' => ['sometimes', 'nullable', 'string', 'max:4000'],
            'videos_text' => ['sometimes', 'nullable', 'string', 'max:4000'],
            'admission_steps_text' => ['sometimes', 'nullable', 'string', 'max:4000'],
            'stat_class_size' => ['sometimes', 'nullable', 'string', 'max:50'],
            'stat_experience_years' => ['sometimes', 'nullable', 'string', 'max:50'],
            'stat_admission_rate' => ['sometimes', 'nullable', 'string', 'max:50'],
            'stat_first_grade_seats' => ['sometimes', 'nullable', 'string', 'max:50'],
        ];
    }
}
