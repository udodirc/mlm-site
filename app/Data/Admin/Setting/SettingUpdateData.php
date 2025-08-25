<?php

namespace App\Data\Admin\Setting;

use Illuminate\Database\Query\Builder;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class SettingUpdateData extends Data
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
                    where: fn (Builder $q): Builder => $q->where('key', '!=', $args[0]->payload['key'])
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
