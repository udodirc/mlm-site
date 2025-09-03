<?php

namespace App\Repositories;

use App\Data\Admin\StaticContent\StaticContentByNamesData;
use App\Models\StaticContent;
use App\Repositories\Contracts\StaticContentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StaticContentRepository extends AbstractRepository implements StaticContentRepositoryInterface
{
    public function __construct(StaticContent $content)
    {
        parent::__construct($content);
    }

    public function contentByName(string $name): ?StaticContent
    {
        return $this->model->where('name', $name)->first() ?? null;
    }

    public function getContentByNames(StaticContentByNamesData $names): ?Collection
    {
        return $this->model
            ->whereIn('name', $names->names)
            ->get(['name', 'content']);
    }
}
