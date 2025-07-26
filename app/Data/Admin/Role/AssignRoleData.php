<?php

namespace App\Data\Admin\Role;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class AssignRoleData extends Data
{
    public int $id;
    public string $role;
    public function __construct(
        int $id,
        string $role,
    ) {
        $this->id = $id;
        $this->role = $role;
    }

    public static function rules(...$args): array
    {
        return [
            'id' => [
                new Required(),
                new IntegerType()
            ],
            'role' => [
                new Required(),
                new StringType(),
                new Max(100)
            ]
        ];
    }
}
