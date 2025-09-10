<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Menu\MenuChangeOrderData;
use App\Data\Admin\Menu\MenuCreateData;
use App\Data\Admin\Menu\MenuFilterData;
use App\Data\Admin\Menu\MenuUpdateData;
use App\Enums\PaginationEnum;
use App\Http\Controllers\BaseController;
use App\Models\Menu;
use App\Resource\MenuResource;
use App\Resource\MenuTreeResource;
use App\Resource\SubMenuResource;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @extends BaseController<MenuService, Menu, MenuResource, MenuCreateData, MenuUpdateData>
 */
class MenuController extends BaseController
{
    protected ?string $filterDataClass = MenuFilterData::class;
    protected string $perPageConfigKey = PaginationEnum::Menu->value;

    protected bool $paginate = true;

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

    public function subMenus(int $id): AnonymousResourceCollection|JsonResponse
    {
        return (SubMenuResource::class)::collection(
            $this->service->subMenus($id)
        );
    }

    public function parentMenus(): AnonymousResourceCollection|JsonResponse
    {
        return (SubMenuResource::class)::collection(
            $this->service->parentMenus()
        );
    }

    public function orderUp(Menu $menu): bool
    {
        return $this->service->orderUp($menu);
    }

    public function orderDown(Menu $menu): bool
    {
        return $this->service->orderDown($menu);
    }
}
