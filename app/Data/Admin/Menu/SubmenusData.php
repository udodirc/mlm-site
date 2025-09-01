<?php

namespace App\Data\Admin\Menu;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;

class SubmenusData
{
    public ?int $id;

    public function __construct(
        ?int $id,
    )
    {
        $this->id = $id;
    }

    public static function rules(...$args): array
    {
        return [
           'id' => [
               new Required(),
                new IntegerType(),
                new Exists(
                    table: 'menu',
                    column: 'id'
                )
            ]
        ];
    }
}
