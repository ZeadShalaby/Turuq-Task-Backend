<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

class AuthController extends BaseController
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());
        return $this->success(['token' => $data->token, 'user' => new UserResource($data->user),], __('messages.login_successfully'));
    }

    public function register(RegisterRequest $request)
    {
        $data = $this->authService->register($request->validated());
        return $this->success(['token' => $data->token, 'user' => new UserResource($data->user),], __('messages.register_successfully'));
    }

    public function me()
    {
        return $this->success(new UserResource($this->authService->me()), __('messages.retrieved_successfully'));
    }

    public function logout()
    {
        $this->authService->logout();
        return $this->success([], __('messages.logout_successfully'));
    }

    public function refresh()
    {
        $data = $this->authService->refresh();
        return $this->success(['token' => $data->token, 'user' => new UserResource($data->user),], __('messages.login_successfully'));
    }
}