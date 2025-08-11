<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param array $filters
     * @return Collection
     */
    public function all(array $filters = []): Collection;
}
