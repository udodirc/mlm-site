<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param array $filters
     * @return LengthAwarePaginator|Collection
     */
    public function all(array $filters = []): LengthAwarePaginator|Collection;
}
