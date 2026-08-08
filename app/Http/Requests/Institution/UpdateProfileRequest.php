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
            'grades' => ['sometimes', 'nullable', 'string', 'max:100'],
            // Ish vaqti — endi har bir hafta kuni uchun alohida {on,hours} (2026-08-08).
            // 'work_hours'/'works_saturday' shundan avtomatik hisoblanadi (ProfileController::update()).
            'work_schedule' => ['sometimes', 'array'],
            'work_schedule.*.on' => ['sometimes', 'boolean'],
            'work_schedule.*.hours' => ['sometimes', 'nullable', 'string', 'max:50'],
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

            // "Narxlar" — sinf/guruh + o'quv tili bo'yicha alohida narx-chegirma (2026-07-15).
            // institutions.monthly_price endi shulardan avtomatik hisoblanadi (ProfileController::update()).
            'prices' => ['sometimes', 'array'],
            'prices.*.grade' => ['required', 'string', 'max:100'],
            'prices.*.lang' => ['sometimes', 'nullable', 'string', 'max:50'],
            'prices.*.price' => ['required', 'integer', 'min:0'],
            'prices.*.discount' => ['sometimes', 'nullable', 'string', 'max:50'],
        ];
    }
}
