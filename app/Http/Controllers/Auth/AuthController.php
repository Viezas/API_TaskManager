<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();
        if(Hash::check($validated['password'], $user['password'])) {
            $auth_token = $user->createToken(Str::random(50));
            return response()->json([
                "token" => $auth_token->plainTextToken
            ]);
        }

        return response()->json([
            'error' => Config::get('error.login')
        ], 401);
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        $auth_token = $user->createToken(Str::random(50));
        return response()->json([
            "token" => $auth_token->plainTextToken
        ]);
    }
}
