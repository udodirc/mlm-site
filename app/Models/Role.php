<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use App\QueryBuilders\RoleQueryBuilder;
use Illuminate\Database\Eloquent\Builder;

class Role extends SpatieRole
{
    public function newEloquentBuilder($query): RoleQueryBuilder
    {
        return new RoleQueryBuilder($query);
    }
}

