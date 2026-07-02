<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterInstitutionRequest;
use App\Http\Resources\AuthUserResource;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterInstitutionController extends Controller
{
    public function __invoke(RegisterInstitutionRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => Str::of($data['phone'])->replace('+', '')->append('@maktabgid.test')->value(),
                'role' => User::ROLE_INSTITUTION,
                'password' => Hash::make($data['password']),
            ]);

            Institution::create([
                'owner_user_id' => $user->id,
                'name' => $data['org'],
                'type' => $data['kind'],
                'accepting' => true,
                'works_saturday' => false,
                'rating' => 0,
                'review_count' => 0,
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['user' => new AuthUserResource($user->load('institution'))], 201);
    }
}
