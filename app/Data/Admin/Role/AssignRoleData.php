<?php

namespace App\Data\Admin\Role;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class AssignRoleData extends Data
{
    public string $role;
    public function __construct(
        string $role,
    ) {
        $this->role = $role;
    }

    public static function rules(...$args): array
    {
        return [
            'role' => [
                new Required(),
                new StringType(),
                new Max(100)
            ]
        ];
    }
}
