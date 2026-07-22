<?php

namespace App\DTOs;
use App\Models\User;

class UserMessageDTO
{
    public function __construct(
        public User $user,
        public string $token,
    ) {
    }
}