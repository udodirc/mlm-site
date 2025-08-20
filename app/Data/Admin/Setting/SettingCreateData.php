<?php

namespace App\Data\Admin\Setting;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class SettingCreateData extends Data
{
    public string $key;
    public string $value;

    public function __construct(
        string $key,
        string $value,
    ){
        $this->key = $key;
        $this->value = $value;
    }

    public static function rules(...$args): array
    {
        return [
            'key' => [
                new Unique(
                    table: 'settings',
                    column: 'key',
                ),
                new Required(),
                new StringType(),
                new Max(100),
            ],
            'value' => [
                new Required(),
                new StringType(),
                new Max(100),
            ]
        ];
    }
}
