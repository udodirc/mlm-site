<?php

namespace App\Data\Admin\Content;

use Illuminate\Database\Query\Builder;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class ContentUpdateData extends Data
{
    public ?int $menu_id;
    public string $content;

    public function __construct(
        ?int $menu_id,
        string $content,
    ){
        $this->menu_id = $menu_id;
        $this->content = $content;
    }

    public static function rules(...$args): array
    {
        return [
            'menu_id' => [
                new Unique(
                    table: 'content',
                    column: 'menu_id',
                    where: fn (Builder $q): Builder => $q->where('menu_id', '!=', $args[0]->payload['menu_id'])
                ),
                new Exists(
                    table: 'menu',
                    column: 'id'
                ),
                new Required(),
                new IntegerType()
            ],
            'content' => [
                new StringType(),
            ]
        ];
    }
}
