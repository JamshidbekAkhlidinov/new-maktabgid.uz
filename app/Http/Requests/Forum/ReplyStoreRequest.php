<?php

namespace App\Http\Requests\Forum;

use Illuminate\Foundation\Http\FormRequest;

class ReplyStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Haqiqiy autentifikatsiya route darajasida 'auth' middleware bilan ta'minlanadi.
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:5000'],
        ];
    }
}
