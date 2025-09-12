<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectRepository extends AbstractRepository implements ProjectRepositoryInterface
{
    public function __construct(Project $project)
    {
        parent::__construct($project);
    }

    public function projectByUrl(string $slug): ?Project
    {
        return $this->model
            ->where('url', $slug)
            ->where('status', true)
            ->first() ?? null;
    }
}
