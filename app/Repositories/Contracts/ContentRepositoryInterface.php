<?php

namespace App\Repositories\Contracts;

use App\Models\Content;

interface ContentRepositoryInterface extends BaseRepositoryInterface
{
    public function contentByMenu(string $slug): ?Content;
}
