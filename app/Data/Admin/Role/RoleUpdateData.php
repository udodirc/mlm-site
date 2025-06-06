<?php

namespace App\Data\Admin\Role;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class RoleUpdateData extends Data
{
    public string $name;
    public function __construct(
        string $name,
    ) {
        $this->name = $name;
    }

    public static function rules(...$args): array
    {
        return [
            'name' => [
                new Required(),
                new Unique(
                    table: 'roles',
                    column: 'name',
                    where: fn (Builder $q): Builder => $q->where('name', '!=', $args[0]->payload['name'])
                ),
                new StringType(),
                new Max(100)
            ]
        ];
    }
}
