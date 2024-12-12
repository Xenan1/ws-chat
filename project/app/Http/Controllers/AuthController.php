<?php

namespace App\Http\Controllers;

use App\DTO\CredentialsDTO;
use App\DTO\UserDTO;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $credentials = new CredentialsDTO($data['login'], $data['password']);

        return $this->getLoginResponse($credentials);
    }

    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $userData = UserDTO::fromRequest($request);
        User::query()->create($userData->toArray());

        return $this->getLoginResponse($userData->getCredentials());
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function getLoginResponse(CredentialsDTO $credentials): JsonResponse
    {
        $token = auth()->attempt($credentials->toArray());

        return !$token
            ? response()->json(['error' => 'Unauthorized'], 401)
            : $this->respondWithToken($token);
    }
}
