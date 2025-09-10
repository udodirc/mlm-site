<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Project\ProjectCreateData;
use App\Data\Admin\Project\ProjectFilterData;
use App\Data\Admin\Project\ProjectUpdateData;
use App\Enums\PaginationEnum;
use App\Http\Controllers\BaseController;
use App\Models\Project;
use App\Resource\ProjectResource;
use App\Services\ProjectService;

class ProjectController extends BaseController
{
    protected ?string $filterDataClass = ProjectFilterData::class;
    protected string $perPageConfigKey = PaginationEnum::Project->value;

    public function __construct(ProjectService $service)
    {
        parent::__construct(
            $service,
            ProjectResource::class,
            Project::class,
            ProjectCreateData::class,
            ProjectUpdateData::class
        );
    }
}
