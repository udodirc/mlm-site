<?php

namespace App\Data\Admin\StaticContent;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class StaticContentCreateData extends Data
{
    public string $name;
    public string $content;

    public function __construct(
        string $name,
        string $content,
    ){
        $this->name = $name;
        $this->content = $content;
    }

    public static function rules(...$args): array
    {
        return [
            'name' => [
                new Unique(
                    table: 'static_content',
                    column: 'name',
                ),
                new Required(),
                new StringType(),
            ],
            'content' => [
                new Required(),
                new StringType(),
            ]
        ];
    }
}
