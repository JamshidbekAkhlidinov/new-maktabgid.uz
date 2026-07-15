<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Ota-ona kabineti — "Farzandlarim" o'z-o'ziga xizmat CRUD'i
 * (parent/children.blade.php, dashboard.blade.php). AchievementController
 * bilan bir xil andoza: egalik tekshiruvi to'g'ridan-to'g'ri, alohida
 * Policy shart emas (parent_user_id joriy foydalanuvchiga tengmi).
 */
class ParentChildController extends Controller
{
    private const GENDERS = [Child::GENDER_BOY, Child::GENDER_GIRL];

    public function store(Request $request): JsonResponse
    {
        $child = $request->user()->children()->create($this->validated($request));

        return response()->json(['child' => $child], 201);
    }

    public function update(Request $request, Child $child): JsonResponse
    {
        abort_unless($child->parent_user_id === $request->user()->id, 403);

        $child->update($this->validated($request));

        return response()->json(['child' => $child]);
    }

    public function destroy(Request $request, Child $child): JsonResponse
    {
        abort_unless($child->parent_user_id === $request->user()->id, 403);

        $child->delete();

        return response()->json(['ok' => true]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'last_name' => ['sometimes', 'nullable', 'string', 'max:100'],
            'age' => ['required', 'integer', 'min:0', 'max:20'],
            'gender' => ['required', Rule::in(self::GENDERS)],
            'interests' => ['sometimes', 'array'],
            'interests.*' => ['string', 'max:60'],
        ]);
    }
}
