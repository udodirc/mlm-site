<?php

namespace App\Data\Admin\StaticContent;

use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class StaticContentFilterData extends Data
{
    public string|Optional|null $name;

    public bool|Optional|null $status;

    public string|Optional|null $created_from;

    public string|Optional|null $created_to;

    public function __construct(
        string|Optional|null $name = null,
        bool|Optional|null $status = null,
        string|Optional|null $created_from = null,
        string|Optional|null $created_to = null,
    ) {
        $this->name = $name ?? new Optional();
        $this->status = $status ?? new Optional();
        $this->created_from = $created_from ?? new Optional();
        $this->created_to = $created_to ?? new Optional();
    }

    public static function rules(): array
    {
        return [
            'name' => [
                new StringType(),
                new Nullable(),
            ],
            'status' => [
                new Nullable(),
                new BooleanType(),
            ],
            'created_from' => [
                new StringType(),
                new Nullable(),
            ],
            'created_to' => [
                new StringType(),
                new Nullable(),
            ],
        ];
    }
}
