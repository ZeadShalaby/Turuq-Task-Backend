<?php

namespace App\Http\Controllers\Api;

use App\DTOs\UserFilterDTO;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserIndexRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    use ApiResponse;

    public function __construct(
        protected UserService $userService
    ) {
    }

    /**
     * ?Display all users
     */
    public function index(UserIndexRequest $request)
    {
        $users = $this->userService->getUsers(UserFilterDTO::fromRequest($request));
        return $this->success([
            'users' => UserResource::collection($users),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ], __('messages.retrieved_successfully'));
    }

    /**
     * ?Store user
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return $this->success(['user' => new UserResource($user),], __('messages.created_successfully'));
    }

    /**
     * ?Show user
     */
    public function show(string $id)
    {
        return $this->success(new UserResource($this->userService->findOrFail($id)), __('messages.retrieved_successfully'));
    }

    /**
     * ?Update user
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = $this->userService->findOrFail($id);
        $this->userService->updateUser($user, $request->validated());
        return $this->success(new UserResource($user->fresh()), __('messages.updated_successfully'));
    }

    /**
     * ?Delete user
     */
    public function destroy(string $id)
    {
        $user = $this->userService->findOrFail($id);
        $this->userService->deleteUser($user);
        return $this->success([], __('messages.deleted_successfully'));
    }
}