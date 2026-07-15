<?php

namespace App\Http\Requests\Career;

use Illuminate\Foundation\Http\FormRequest;

class VacancyStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Haqiqiy autentifikatsiya route darajasida 'auth' middleware bilan ta'minlanadi.
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'org' => ['required', 'string', 'max:255'],
            'salary_range' => ['nullable', 'string', 'max:100'],
            'employment_type' => ['required', 'string', 'in:full,part,hourly'],
        ];
    }
}
