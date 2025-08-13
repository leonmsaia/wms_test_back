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
 * @author  Leon. M. Saia <leonmsaia@gmail.com>
 * @since   2025-08-12
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller
{
    /**
     * Registers a new user and stores it in the database.
     *
     * @param  Request  $request  The HTTP request containing user data.
     * @return JsonResponse       JSON response with the created user.
     */
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

    /**
     * Authenticates a user and returns a Sanctum API token.
     *
     * @param  Request  $request  The HTTP request containing login credentials.
     * @return JsonResponse       JSON response with the access token.
     */
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

    /**
     * Returns the currently authenticated user.
     *
     * @param  Request  $request  The current HTTP request.
     * @return JsonResponse       JSON response with user data.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    /**
     * Logs out the authenticated user by revoking the current token.
     *
     * @param  Request  $request  The current HTTP request.
     * @return JsonResponse       JSON response with a logout confirmation message.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['message' => 'logged_out']);
    }
}
