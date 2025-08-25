<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;

class MenuRepository extends AbstractRepository implements MenuRepositoryInterface
{
    public function __construct(Menu $menu)
    {
        parent::__construct($menu);
    }
}
