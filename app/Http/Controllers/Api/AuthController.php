<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthController
 *
 * Provides API authentication endpoints using Laravel Sanctum,
 * including registration, login, logout, and fetching the authenticated user.
 *
 * @author  Leon. M. Saia
 * @since   2025-08-12
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre'   => ['required','string','min:3'],
            'apellido' => ['required','string','min:3'],
            'email'    => ['required','email','unique:users,email'],
            'password' => ['required','min:8'],
            'rol'      => ['required','in:Admin,User'],
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json([
            'message' => 'registered',
            'user'    => $user,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $cred = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $cred['email'])->first();

        if (! $user || ! Hash::check($cred['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['message' => 'logged_out']);
    }
}
