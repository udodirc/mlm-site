<?php

namespace App\Data\Admin\Menu;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class MenuCreateData extends Data
{
    public ?int $parent_id;
    public string $name;
    public ?string $url;

    public function __construct(
        ?int $parent_id,
        string $name,
        ?string $url,
    ){
        $this->parent_id = $parent_id;
        $this->name = $name;
        $this->url = $url;
    }

    public static function rules(...$args): array
    {
        return [
            'name' => [
                new Unique(
                    table: 'menu',
                    column: 'name',
                ),
                new Required(),
                new StringType(),
                new Max(100),
            ],
            'url' => [
                new Unique(
                    table: 'menu',
                    column: 'url',
                ),
                new Nullable(),
                new StringType(),
                new Max(50),
            ],
            'parent_id' => [
                new Nullable(),
                new IntegerType(),
                new Exists(
                    table: 'menu',
                    column: 'id'
                )
            ]
        ];
    }
}
