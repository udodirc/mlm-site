<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class MenuQueryBuilder extends BaseQueryBuilder
{
    protected function applyCustomFilters(array $filters): void
    {
        if (!empty($filters['parent_id'])) {
            $this->where('parent_id', $filters['parent_id']);
        }

        if (!empty($filters['url'])) {
            $this->where('url', $filters['url']);
        }
    }
}
