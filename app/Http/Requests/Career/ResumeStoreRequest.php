<?php

namespace App\Http\Requests\Career;

use Illuminate\Foundation\Http\FormRequest;

class ResumeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Haqiqiy autentifikatsiya route darajasida 'auth' middleware bilan ta'minlanadi.
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'role_title' => ['required', 'string', 'max:255'],
            'experience' => ['required', 'string', 'max:100'],
            'salary_expectation' => ['nullable', 'string', 'max:100'],
            'specialization_key' => ['nullable', 'string', 'exists:specializations,key'],
        ];
    }
}
