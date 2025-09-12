<?php

namespace App\Repositories\Contracts;

use App\Models\Project;

interface ProjectRepositoryInterface extends BaseRepositoryInterface
{
    public function projectByUrl(string $slug): ?Project;
}
