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
        ];
    }

    protected function toUpdateArray(Data $data): array
    {
        /** @var ContentUpdateData $data */
        return [
            'menu_id' => $data->menu_id,
            'content' => $data->content,
        ];
    }
}
