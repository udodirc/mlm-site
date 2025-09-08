<?php

namespace App\Services;

use App\Data\Admin\Content\ContentCreateData;
use App\Data\Admin\Content\ContentUpdateData;
use App\Models\Content;
use App\Repositories\Contracts\ContentRepositoryInterface;
use Spatie\LaravelData\Data;

/**
 * @extends BaseService<ContentRepositoryInterface, ContentCreateData, ContentUpdateData, Content>
 */
class ContentService extends BaseService
{
    public function __construct(ContentRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function toCreateArray(Data $data): array
    {
        /** @var ContentCreateData $data */
        return [
            'menu_id' => $data->menu_id,
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
        /** @var ContentUpdateData $data */
        return [
            'menu_id' => $data->menu_id,
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

    public function contentByMenu(string $slug): ?Content
    {
        return $this->repository->contentByMenu($slug);
    }
}
