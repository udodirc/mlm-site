<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MenuRepository extends AbstractRepository implements MenuRepositoryInterface
{
    public function __construct(Menu $menu)
    {
        parent::__construct($menu);
    }

    /**
     * @param array $filters
     * @return Collection
     */
    public function all(array $filters = []): Collection
    {
        return $this->model
            ->newQuery()
            ->filter($filters)  // кастомный метод из UserQueryBuilder
            ->get();
    }
}
