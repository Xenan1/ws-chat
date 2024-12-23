<?php

namespace App\Http\Controllers;

use App\DTO\CredentialsDTO;
use App\DTO\TokenDTO;
use App\DTO\UserDTO;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Logging\AuthLogger;
use App\Logging\Enum\LogLevels;
use App\Services\RegisterService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        protected AuthLogger $logger,
    ) {}

    /**
     * @unauthenticated
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = new CredentialsDTO($request->getLogin(), $request->getPassword());

        return $this->getLoginResponse($credentials);
    }

    public function me(): UserResource
    {
        return new UserResource(auth()->user());
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(): TokenResource
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * @unauthenticated
     */
    public function register(RegisterUserRequest $request, RegisterService $service): JsonResponse
    {
        $userData = UserDTO::fromRequest($request);
        $service->registerUser($userData);

        return $this->getLoginResponse($userData->getCredentials());
    }

    protected function respondWithToken($token): TokenResource
    {
        $token = new TokenDTO($token, 'bearer', auth()->factory()->getTTL() * 60);

        return new TokenResource($token);
    }

    protected function getLoginResponse(CredentialsDTO $credentials): JsonResponse
    {
        $token = auth()->attempt($credentials->toArray());

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $this->logger->log(LogLevels::Info, 'User logged in', ['user_login' => $credentials->login]);

        return response()->json($this->respondWithToken($token));
    }
}
