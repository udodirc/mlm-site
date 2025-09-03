<?php

namespace App\Repositories;

use App\Models\StaticContent;
use App\Repositories\Contracts\StaticContentRepositoryInterface;

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
}
