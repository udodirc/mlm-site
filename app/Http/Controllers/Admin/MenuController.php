<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Menu\MenuCreateData;
use App\Data\Admin\Menu\MenuFilterData;
use App\Data\Admin\Menu\MenuUpdateData;
use App\Http\Controllers\BaseController;
use App\Models\Menu;
use App\Resource\MenuResource;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $filters = MenuFilterData::from($request);
        $filtersArray = $filters->toArray();

        return ($this->resourceClass)::collection(
            $this->service->all($filtersArray)
        );
    }
}
