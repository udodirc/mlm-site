<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Ordered
{
    public static bool $ordered = true;

    protected static function bootOrdered(): void
    {
        static::addGlobalScope('ordered', function (Builder $builder) {
            $builder->orderBy('order');
        });
    }
}
