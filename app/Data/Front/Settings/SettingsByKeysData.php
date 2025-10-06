<?php

namespace App\Data\Front\Settings;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class SettingsByKeysData extends Data
{
    public array $keys = [];
    public function __construct(
        array $keys
    ) {
        $this->keys = $keys;
    }

    public static function rules(...$args): array
    {
        return [
            'keys' => [
                new Required(),
                new ArrayType(),
            ],
        ];
    }
}
