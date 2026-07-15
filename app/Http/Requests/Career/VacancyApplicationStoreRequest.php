<?php

namespace App\Http\Requests\Career;

use Illuminate\Foundation\Http\FormRequest;

class VacancyApplicationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Mehmon ham yubora oladi (Application modeli bilan bir xil qoida).
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
