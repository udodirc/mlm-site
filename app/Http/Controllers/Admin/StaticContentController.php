<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\StaticContent\StaticContentCreateData;
use App\Data\Admin\StaticContent\StaticContentFilterData;
use App\Data\Admin\StaticContent\StaticContentUpdateData;
use App\Enums\PaginationEnum;
use App\Http\Controllers\BaseController;
use App\Models\StaticContent;
use App\Resource\StaticContentResource;
use App\Services\StaticContentService;

class StaticContentController extends BaseController
{
    protected ?string $filterDataClass = StaticContentFilterData::class;
    protected string $perPageConfigKey = PaginationEnum::StaticContent->value;

    public function __construct(StaticContentService $service)
    {
        parent::__construct(
            $service,
            StaticContentResource::class,
            StaticContent::class,
            StaticContentCreateData::class,
            StaticContentUpdateData::class
        );
    }
}
