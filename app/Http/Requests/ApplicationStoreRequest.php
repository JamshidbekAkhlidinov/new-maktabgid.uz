<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Ekskursiya yoki joylashtirish arizasi — mehmon (guest) ham yubora oladi. */
class ApplicationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution_id' => ['required', 'integer', 'exists:institutions,id'],
            'type' => ['required', Rule::in(['excursion', 'enrollment'])],
            'child_name' => ['required', 'string', 'max:255'],
            'child_birth_date' => ['nullable', 'date'],
            'child_age' => ['nullable', 'integer', 'min:0', 'max:20'],
            'current_grade' => ['nullable', 'string', 'max:50'],
            'target_grade' => ['nullable', 'string', 'max:50'],
            'previous_school' => ['nullable', 'string', 'max:255'],
            'parent_name' => ['required', 'string', 'max:255'],
            'parent_phone' => ['required', 'string', 'max:20'],
            'preferred_start' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
