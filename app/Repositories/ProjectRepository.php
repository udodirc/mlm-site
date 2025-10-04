<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectRepository extends AbstractRepository implements ProjectRepositoryInterface
{
    public function __construct(Project $project)
    {
        parent::__construct($project);
    }

    public function allWithStatus(
        bool $paginate = true,
        array $filters = [],
        string $paginationKey = ''
    ): LengthAwarePaginator|Collection {
        $filters['status'] = true;

        return parent::all($paginate, $filters, 'per_page_project_in_front');
    }

    public function projectByUrl(string $slug): ?Project
    {
        return $this->model
            ->where('url', $slug)
            ->where('status', true)
            ->first() ?? null;
    }
}
