<?php

namespace App\Filters\User;

use App\DTOs\UserFilterDTO;
use Illuminate\Database\Eloquent\Builder;

class UserQueryFilter
{
    public function __construct(
        protected Builder $query,
        protected UserFilterDTO $filters
    ) {
    }

    public static function apply(Builder $query, UserFilterDTO $filters): Builder
    {
        return (new self($query, $filters))->handle();
    }

    protected function handle(): Builder
    {
        return $this
            ->age()
            ->search()
            ->sort()
            ->query;
    }

    // ?todo fillter by age
    protected function age(): self
    {
        if ($this->filters->age !== null) {
            $this->query->where('age', $this->filters->age);
        }

        return $this;
    }

    // ?todo search
    protected function search(): self
    {
        if (!empty($this->filters->search)) {

            $search = trim($this->filters->search);

            $this->query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $this;
    }

    // ?todo sorting
    protected function sort(): self
    {
        $allowed = [
            'name',
            'email',
            'age',
            'created_at',
        ];

        $sortBy = $this->filters->sortBy;

        if (!in_array($sortBy, $allowed)) {
            $sortBy = 'created_at';
        }

        $direction = strtolower($this->filters->sortDirection);

        $direction = $direction === 'asc'
            ? 'asc'
            : 'desc';

        $this->query->orderBy($sortBy, $direction);

        return $this;
    }
}