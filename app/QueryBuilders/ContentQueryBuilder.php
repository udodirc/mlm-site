<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class ContentQueryBuilder extends BaseQueryBuilder
{
    protected function applyCustomFilters(array $filters): void
    {
        if (!empty($filters['menu_id'])) {
            $this->where('menu_id', $filters['menu_id']);
        }
    }
}
