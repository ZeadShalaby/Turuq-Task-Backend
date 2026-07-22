<?php

namespace App\Repositories;

use App\DTOs\UserFilterDTO;
use App\Filters\User\UserQueryFilter;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    public function __construct(protected User $user)
    {
    }

    public function create(array $data): User
    {
        return $this->user->create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->user->where('email', $email)->first();
    }

    public function findById(string $id): ?User
    {
        return $this->user->find($id);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }

    public function paginate(UserFilterDTO $dto)
    {
        return UserQueryFilter::apply($this->user->query(), $dto)->paginate($dto->perPage);
    }
}
