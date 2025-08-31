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

    public function subMenus(int $id): Collection
    {
        return $this->model
            ->where('parent_id', $id)->get(['id', 'name']);
    }
}
