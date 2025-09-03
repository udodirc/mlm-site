<?php

namespace App\Data\Admin\StaticContent;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
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
