<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Content\ContentCreateData;
use App\Data\Admin\Content\ContentUpdateData;
use App\Http\Controllers\BaseController;
use App\Models\Content;
use App\Resource\ContentResource;
use App\Services\ContentService;

class ContentController extends BaseController
{
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
