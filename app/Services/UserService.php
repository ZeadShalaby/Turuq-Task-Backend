<?php

namespace App\Services;

use App\DTOs\UserFilterDTO;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }
    public function getUsers(UserFilterDTO $dto)
    {
        return $this->userRepository->paginate($dto);
    }


    public function getUser(string $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    public function updateUser(User $user, array $data): bool
    {
        isset($data['password']) ? $data['password'] = Hash::make($data['password']) : null;
        return $this->userRepository->update($user, $data);
    }

    public function deleteUser(User $user): bool
    {
        return $this->userRepository->delete($user);
    }

    public function findOrFail(string $id): User
    {
        $user = $this->userRepository->findById($id);
        $user ?? throw new ModelNotFoundException();
        return $user;
    }
}