<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterInstitutionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'org' => ['required', 'string', 'max:255'],
            'kind' => ['required', Rule::in(['maktab', 'bogcha', 'markaz'])],
            'name' => ['required', 'string', 'max:255'], // mas'ul shaxs
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }
}
