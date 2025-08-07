<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Authentication
 * @authenticated
 *
 * Endpoints for registering, logging in, logging out and fetching the authenticated user.
 */
class AuthController extends Controller
{
    /**
    * Register a new user and issue a Sanctum token.
    *
    * @bodyParam Request \App\Http\Requests\Auth\RegisterRequest
    * @responseFile 201 docs/responses/auth/register-success.json
    * @responseFile 400 docs/responses/auth/register-error.json
    */
    public function register(RegisterRequest $request, UserRepository $userRepository)
    {
        $user = $userRepository->create($request->validated());

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
        ], Response::HTTP_CREATED);
    }

    /**
     * Login and receive a Sanctum token.
     *
     * @unauthenticated
     * @bodyParam Request \App\Http\Requests\Auth\LoginRequest
     * @responseFile 200 docs/responses/auth/login-success.json
     * @responseFile 401 docs/responses/auth/login-error.json
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        /** @var \App\Models\User $user */
        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Logout (revoke the current token).
     *
     * @authenticated
     * @responseFile 200 docs/responses/auth/logout-success.json
     */
    public function logout(Request $request)
    {
        // Delete only the token that was used to authenticate this request:
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ], 200);
    }
}
