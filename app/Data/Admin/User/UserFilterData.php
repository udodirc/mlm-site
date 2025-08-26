<?php

namespace App\Data\Admin\User;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UserFilterData extends Data
{
    public string|Optional|null $name;
    public string|Optional|null $email;
    public string|Optional|null $role;

    public string|Optional|null $created_from;

   public string|Optional|null $created_to;

    public function __construct(
        string|Optional|null $name = null,
        string|Optional|null $email = null,
        string|Optional|null $role = null,
        string|Optional|null $created_from = null,
        string|Optional|null $created_to = null,
    ) {
        $this->name = $name ?? new Optional();
        $this->email = $email ?? new Optional();
        $this->role = $role ?? new Optional();
        $this->created_from = $created_from ?? new Optional();
        $this->created_to = $created_to ?? new Optional();
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
            'created_from' => [
                new StringType(),
                new Nullable(),
            ],
            'created_to' => [
                new StringType(),
                new Nullable(),
            ],
        ];
    }
}
