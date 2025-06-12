<?php

namespace App\Data\Admin\Role;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class RoleAssignPermissionsData extends Data
{
    public string $role;
    public array $permissions = [];
    public function __construct(
        string $role,
        array $permissions
    ) {
        $this->role = $role;
        $this->permissions = $permissions;
    }

    public static function rules(...$args): array
    {
        return [
            'role' => [
                new Required(),
                new StringType(),
                new Max(100)
            ],
            'permissions' => [
                new Required(),
                new ArrayType(),
            ],
        ];
    }
}
