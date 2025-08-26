<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class UserQueryBuilder extends BaseQueryBuilder
{
    protected function applyCustomFilters(array $filters): void
    {
        if (!empty($filters['email'])) {
            $this->where('email', $filters['email']);
        }

        if (!empty($filters['role'])) {
            $this->whereHas('roles', function (Builder $q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }
    }
}
