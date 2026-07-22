<?php

namespace App\Services;

use App\DTOs\UserMessageDTO;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthService
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    public function login(array $credentials): UserMessageDTO
    {
        $user = $this->userRepository->findByEmail($credentials['email']);
        throw_unless(
            Hash::check($credentials['password'], $user->password),
            UnauthorizedHttpException::class,
            __('auth.failed')
        );
        return new UserMessageDTO(token: JWTAuth::fromUser($user), user: $user);
    }

    public function register(array $credentials): UserMessageDTO
    {
        return new UserMessageDTO(token: JWTAuth::fromUser($this->userRepository->create($credentials)), user: $this->userRepository->findByEmail($credentials['email']));
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function me(): User
    {
        return JWTAuth::parseToken()->authenticate();
    }

    public function refresh(): UserMessageDTO
    {
        $user = $this->me();
        $newToken = JWTAuth::refresh(JWTAuth::getToken());
        return new UserMessageDTO( token: $newToken,user: $user);
    }
}