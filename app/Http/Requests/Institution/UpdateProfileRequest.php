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
        ];
    }
}
