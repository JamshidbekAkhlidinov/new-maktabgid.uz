<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'age' => ['required', 'integer', 'min:18', 'max:90'],
            'district' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }
}
