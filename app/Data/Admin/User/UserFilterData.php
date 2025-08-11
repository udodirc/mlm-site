<?php

namespace App\Data\Admin\User;

use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UserFilterData extends Data
{
    public string|Optional|null $name;
    public string|Optional|null $email;
    public string|Optional|null $role;

    public function __construct(
        string|Optional|null $name = null,
        string|Optional|null $email = null,
        string|Optional|null $role = null,
    ) {
        $this->name = $name ?? new Optional();
        $this->email = $email ?? new Optional();
        $this->role = $role ?? new Optional();
    }

    public static function rules(): array
    {
        return [
            'name' => [
                new StringType(),
                new Max(100),
                new Nullable(),
            ],
            'email' => [
                new StringType(),
                new Max(100),
                new Nullable(),
            ],
            'role' => [
                new StringType(),
                new Max(100),
                new Nullable(),
            ],
        ];
    }
}
