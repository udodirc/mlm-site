<?php

namespace App\Services;

use App\Data\Admin\Project\ProjectCreateData;
use App\Data\Admin\Project\ProjectUpdateData;
use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Spatie\LaravelData\Data;

/**
 * @extends BaseService<ProjectRepositoryInterface, ProjectCreateData, ProjectUpdateData, Project>
 */
class ProjectService extends BaseService
{
    public function __construct(ProjectRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function toCreateArray(Data $data): array
    {
        /** @var ProjectCreateData $data */
        return [
            'name' => $data->name,
            'content' => $data->content,
            'title' => $data->title,
            'meta_description' => $data->meta_description,
            'meta_keywords' => $data->meta_keywords,
            'og_title' => $data->og_title,
            'og_description' => $data->og_description,
            'og_image' => $data->og_image,
            'og_type' => $data->og_type,
            'og_url' => $data->og_url,
            'canonical_url' => $data->canonical_url,
            'robots' => $data->robots
        ];
    }

    protected function toUpdateArray(Data $data): array
    {
        /** @var ProjectUpdateData $data */
        return [
            'name' => $data->name,
            'content' => $data->content,
            'status' => $data->status,
            'title' => $data->title,
            'meta_description' => $data->meta_description,
            'meta_keywords' => $data->meta_keywords,
            'og_title' => $data->og_title,
            'og_description' => $data->og_description,
            'og_image' => $data->og_image,
            'og_type' => $data->og_type,
            'og_url' => $data->og_url,
            'canonical_url' => $data->canonical_url,
            'robots' => $data->robots
        ];
    }
}
