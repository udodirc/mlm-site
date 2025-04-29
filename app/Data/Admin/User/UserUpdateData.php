<?php

namespace App\Data\Admin\User;

use Illuminate\Database\Query\Builder;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\References\RouteParameterReference;

class UserUpdateData extends Data
{
    public string $email;
    public string $name;
    public ?string $password;
    public string $role;

    public function __construct(
        string $email,
        string $name,
        ?string $password,
        string $role
    ) {
        $this->password = $password;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
    }

    public static function rules(...$args): array
    {
        return [
            'name' => [
                new Unique(
                    table: 'users',
                    column: 'name',
                    where: fn (Builder $q): Builder => $q->where('name', '!=', $args[0]->payload['name'])
                ),
                new Required(),
                new StringType(),
                new Max(100)
            ],
            'email' => [
                new Unique(
                    table: 'users',
                    column: 'email',
                    where: fn (Builder $q): Builder => $q->where('email', '!=', $args[0]->payload['email'])
                ),
                new Required(),
                new StringType(),
                new Max(100)
            ],
            'password' => [
                new Nullable(),
                new Unique(
                    table: 'users',
                    column: 'email',
                ),
                new Required(),
                new StringType(),
                new Max(100),
                new Min(8),
            ],
            'role' => [
                new Required(),
                new StringType(),
                new Max(100)
            ],
        ];
    }
}
