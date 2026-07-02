<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterParentRequest;
use App\Http\Resources\AuthUserResource;
use App\Models\District;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterParentController extends Controller
{
    public function __invoke(RegisterParentRequest $request): JsonResponse
    {
        $data = $request->validated();

        $district = District::firstOrCreate(['name' => $data['district']]);

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $this->syntheticEmail($data['phone']),
            'role' => User::ROLE_PARENT,
            'age' => $data['age'],
            'district_id' => $district->id,
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['user' => new AuthUserResource($user->load('district'))], 201);
    }

    /** email hali majburiy (§background: doctrine/dbal talab qilinmasligi uchun) — telefon asosida sintetik email */
    protected function syntheticEmail(string $phone): string
    {
        return Str::of($phone)->replace('+', '')->append('@maktabgid.test')->value();
    }
}
