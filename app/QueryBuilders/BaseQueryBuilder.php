<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

abstract class BaseQueryBuilder extends Builder
{
    public function filter(array $filters = []): static
    {
        if (!empty($filters['name'])) {
            $this->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['status'])) {
            $this->where('status', filter_var($filters['status'], FILTER_VALIDATE_BOOLEAN));
        }

        if (!empty($filters['created_from']) && !empty($filters['created_to'])) {
            $createdFrom = Carbon::createFromFormat('Y-m-d', $filters['created_from'])->startOfDay();
            $createdTo = Carbon::createFromFormat('Y-m-d', $filters['created_to'])->endOfDay();
            $this->whereBetween('created_at', [$createdFrom, $createdTo]);
        } else {
            if (!empty($filters['created_from'])) {
                $createdFrom = Carbon::createFromFormat('Y-m-d', $filters['created_from'])->startOfDay();
                $this->where('created_at', '>=', $createdFrom);
            }

            if (!empty($filters['created_to'])) {
                $createdTo = Carbon::createFromFormat('Y-m-d', $filters['created_to'])->endOfDay();
                $this->where('created_at', '<=', $createdTo);
            }
        }

        // Вызов кастомных фильтров из дочерних классов (если нужно)
        $this->applyCustomFilters($filters);

        return $this;
    }

    protected function applyCustomFilters(array $filters): void
    {

    }
}
