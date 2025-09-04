<?php

namespace App\Data\Admin\Menu;

use Illuminate\Database\Query\Builder;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class MenuUpdateData extends Data
{
    public ?int $parent_id;
    public string $name;
    public ?string $url;
    public bool|Optional|null $status;

    public function __construct(
        ?int $parent_id,
        string $name,
        ?string $url,
        ?bool $status,
    ){
        $this->parent_id = $parent_id;
        $this->name = $name;
        $this->url = $url;
        $this->status = $status;
    }

    public static function rules(...$args): array
    {
        return [
            'name' => [
                new Unique(
                    table: 'menu',
                    column: 'name',
                    where: fn (Builder $q): Builder => $q->where('name', '!=', $args[0]->payload['name'])
                ),
                new Required(),
                new StringType(),
                new Max(100)
            ],
            'url' => [
                new Unique(
                    table: 'menu',
                    column: 'url',
                    where: fn (Builder $q): Builder => $q->where('url', '!=', $args[0]->payload['url'])
                ),
                new Nullable(),
                new StringType(),
                new Max(50)
            ],
            'parent_id' => [
                new Nullable(),
                new IntegerType(),
                new Exists(
                    table: 'menu',
                    column: 'id'
                )
            ],
            'status' => [
                new Nullable(),
                new BooleanType(),
            ],
        ];
    }
}
