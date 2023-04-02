<?php

declare(strict_types=1);

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Resources\StatusResource;
use App\Http\Resources\User\UserResource;
use App\Services\UserCreateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(UserCreateRequest $request, UserCreateService $service): JsonResponse
    {
        $validatedUser = $request->validated();
        $user = $service->create($validatedUser);
        Auth::login($user);

        return UserResource::make($user)->response()->setStatusCode(201);
    }

    public function login(UserLoginRequest $request): Response|JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $accessToken = $user->tokens()->first();
            if (!$accessToken) {
                $accessToken = $user->createToken('accessToken')->plainTextToken;
            }
            return response()->json(['access_token' => $accessToken->token], 200);
        }

        return response()->json(StatusResource::make(false), 422);
    }

    public function logout(): Response|JsonResponse
    {
        Auth::logout();
        return response()->json([], 204);
    }
}
