<?php

namespace App\Data\Admin\Contacts;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class ContactSendData extends Data
{
    public string $email;
    public ?string $phone;

    public string $message;

    public function __construct(
        string $email,
        string $phone,
        string $message
    )
    {
        $this->email = $email;
        $this->phone = $phone;
        $this->message = $message;
    }

    public static function rules(...$args): array
    {
        return [
            'email' => [
                new Required(),
                new StringType(),
                new Max(100)
            ],
            'phone' => [
                new Nullable(),
                new StringType(),
                new Max(100)
            ],
            'message' => [
                new Required(),
                new StringType(),
            ],
        ];
    }
}
