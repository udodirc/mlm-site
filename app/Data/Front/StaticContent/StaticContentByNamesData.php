<?php

namespace App\Data\Front\StaticContent;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class StaticContentByNamesData extends Data
{
    public array $names = [];
    public function __construct(
        array $names
    ) {
        $this->names = $names;
    }

    public static function rules(...$args): array
    {
        return [
            'names' => [
                new Required(),
                new ArrayType(),
            ],
        ];
    }
}
