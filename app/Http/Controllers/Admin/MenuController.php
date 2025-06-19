<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Menu\MenuCreateData;
use App\Data\Admin\Menu\MenuUpdateData;
use App\Http\Controllers\BaseController;
use App\Models\Menu;
use App\Resource\MenuResource;
use App\Services\MenuService;

/**
 * @extends BaseController<MenuService, Menu, MenuResource, MenuCreateData, MenuUpdateData>
 */
class MenuController extends BaseController
{
    public function __construct(MenuService $service)
    {
        parent::__construct(
            $service,
            MenuResource::class,
            Menu::class,
            MenuCreateData::class,
            MenuUpdateData::class
        );
    }
}
