<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Resource\MenuTreeResource;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MenuController extends Controller
{
    private MenuService $service;

    public function __construct(MenuService $service)
    {
        $this->service = $service;
    }
    public function treeMenus(): AnonymousResourceCollection|JsonResponse
    {
        return (MenuTreeResource::class)::collection(
            $this->service->treeMenus()
        );
    }
}
