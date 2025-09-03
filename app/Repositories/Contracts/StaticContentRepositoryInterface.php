<?php

namespace App\Repositories\Contracts;

use App\Models\StaticContent;

interface StaticContentRepositoryInterface extends BaseRepositoryInterface
{
    public function contentByName(string $name): ?StaticContent;
}
