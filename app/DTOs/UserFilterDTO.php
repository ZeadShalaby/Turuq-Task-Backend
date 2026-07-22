<?php

namespace App\DTOs;

use App\Http\Requests\UserIndexRequest;

class UserFilterDTO
{
    public function __construct(
        public readonly ?int $age,
        public readonly ?string $search,
        public readonly string $sortBy,
        public readonly string $sortDirection,
        public readonly int $perPage,
    ) {
    }

    public static function fromRequest(UserIndexRequest $request): self
    {
        return new self(
            age: $request->has('age')? $request->integer('age'): null,
            search: $request->string('search')->toString() ?: null,
            sortBy: $request->input('sort_by', 'created_at'),
            sortDirection: $request->input('sort_direction', 'desc'),
            perPage: $request->integer('per_page', 10),
        );
    }
}