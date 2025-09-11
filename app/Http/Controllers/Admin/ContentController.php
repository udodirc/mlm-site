<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Content\ContentCreateData;
use App\Data\Admin\Content\ContentFilterData;
use App\Data\Admin\Content\ContentUpdateData;
use App\Enums\PaginationEnum;
use App\Http\Controllers\BaseController;
use App\Models\Content;
use App\Resource\ContentResource;
use App\Services\ContentService;

/**
 * @extends BaseController<ContentService, Content, ContentResource, ContentCreateData, ContentUpdateData>
 */
class ContentController extends BaseController
{
    protected ?string $filterDataClass = ContentFilterData::class;
    protected string $perPageConfigKey = PaginationEnum::Content->value;

    public function __construct(ContentService $service)
    {
        parent::__construct(
            $service,
            ContentResource::class,
            Content::class,
            ContentCreateData::class,
            ContentUpdateData::class
        );
    }
}
