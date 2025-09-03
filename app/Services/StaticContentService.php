<?php

namespace App\Services;

use App\Data\Admin\StaticContent\StaticContentCreateData;
use App\Data\Admin\StaticContent\StaticContentUpdateData;
use App\Models\StaticContent;
use App\Repositories\Contracts\StaticContentRepositoryInterface;
use Spatie\LaravelData\Data;

/**
 * @extends BaseService<StaticContentRepositoryInterface, StaticContentCreateData, StaticContentUpdateData, StaticContent>
 */
class StaticContentService extends BaseService
{
    public function __construct(StaticContentRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function toCreateArray(Data $data): array
    {
        /** @var StaticContentCreateData $data */
        return [
            'name' => $data->name,
            'content' => $data->content,
        ];
    }

    protected function toUpdateArray(Data $data): array
    {
        /** @var StaticContentUpdateData $data */
        return [
            'name' => $data->name,
            'content' => $data->content,
        ];
    }

    public function contentByName(string $name): ?StaticContent
    {
        return $this->repository->contentByName($name);
    }
}
