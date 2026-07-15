<?php

namespace App\Http\Requests\Forum;

use App\Support\MaktabgidData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ThreadStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Haqiqiy autentifikatsiya route darajasida 'auth' middleware bilan ta'minlanadi.
        return true;
    }

    public function rules(): array
    {
        // "Hammasi" — filtr uchun umumiy yorliq, real kategoriya emas.
        $categories = array_values(array_diff(MaktabgidData::forumCategories(), ['Hammasi']));

        return [
            'category' => ['required', 'string', Rule::in($categories)],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
        ];
    }
}
