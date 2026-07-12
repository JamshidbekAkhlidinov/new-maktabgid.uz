<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterTeacherRequest;
use App\Http\Resources\AuthUserResource;
use App\Models\District;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Ustoz (o'qituvchi) sifatida ro'yxatdan o'tish — RegisterParentController bilan bir xil
 * andozada (User jadvalida "teacher" uchun alohida ustun kerak emas, mavjud
 * name/phone/age/district_id yetarli — bo'ish maydonlari, masalan fan/tajriba,
 * keyinchalik "Rezyumelarim" bo'limida to'ldiriladi, ro'yxatdan o'tishda emas).
 */
class RegisterTeacherController extends Controller
{
    public function __invoke(RegisterTeacherRequest $request): JsonResponse
    {
        $data = $request->validated();

        $district = District::firstOrCreate(['name' => $data['district']]);

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $this->syntheticEmail($data['phone']),
            'role' => User::ROLE_TEACHER,
            'age' => $data['age'],
            'district_id' => $district->id,
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['user' => new AuthUserResource($user->load('district'))], 201);
    }

    protected function syntheticEmail(string $phone): string
    {
        return Str::of($phone)->replace('+', '')->append('@maktabgid.test')->value();
    }
}
