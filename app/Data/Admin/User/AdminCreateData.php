<?php

namespace App\Data\Admin\User;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class AdminCreateData extends Data
{
    public string $email;
    public string $name;
    public string $password;
    public Carbon|Optional|null $emailVerifiedAt;

    public function __construct(
        string $email,
        string $name,
        string $password,
        Carbon|Optional|null $emailVerifiedAt = new Optional()
    ) {
        $this->emailVerifiedAt = $emailVerifiedAt;
        $this->password = $password;
        $this->name = $name;
        $this->email = $email;
    }

    public static function rules(...$args): array
    {
        return [
            'name' => [
                new Unique(
                    table: 'users',
                    column: 'name',
                ),
                new Required(),
                new StringType(),
                new Max(100)
            ],
            'email' => [
                new Unique(
                    table: 'users',
                    column: 'email',
                ),
                new Required(),
                new StringType(),
                new Max(100)
            ],
            'password' => [
                new Unique(
                    table: 'users',
                    column: 'email',
                ),
                new Required(),
                new StringType(),
                new Max(100),
                new Min(8),
            ],
        ];
    }
}
