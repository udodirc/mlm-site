<?php

namespace App\Repositories\Contracts;

use App\Data\Admin\StaticContent\StaticContentByNamesData;
use App\Models\StaticContent;
use Illuminate\Database\Eloquent\Collection;

interface StaticContentRepositoryInterface extends BaseRepositoryInterface
{
    public function contentByName(string $name): ?StaticContent;

    public function getContentByNames(StaticContentByNamesData $names): ?Collection;
}
