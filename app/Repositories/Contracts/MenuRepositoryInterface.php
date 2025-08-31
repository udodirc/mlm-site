<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface MenuRepositoryInterface extends BaseRepositoryInterface
{
    public function subMenus(int $id): Collection;
}
