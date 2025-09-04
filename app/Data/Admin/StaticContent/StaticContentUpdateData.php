<?php

namespace App\Data\Admin\StaticContent;

use Illuminate\Database\Query\Builder;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class StaticContentUpdateData extends Data
{
    public string $name;
    public string $content;
    public bool|Optional|null $status;

    public function __construct(
        string $name,
        string $content,
        bool|Optional|null $status = null
    ){
        $this->name = $name;
        $this->content = $content;
        $this->status = $status;
    }

    public static function rules(...$args): array
    {
        return [
            'name' => [
                new Unique(
                    table: 'static_content',
                    column: 'name',
                    where: fn (Builder $q): Builder => $q->where('name', '!=', $args[0]->payload['name'])
                ),
                new Required(),
                new StringType(),
            ],
            'content' => [
                new Required(),
                new StringType(),
            ],
            'status' => [
                new Nullable(),
                new BooleanType(),
            ],
        ];
    }
}
