<?php

namespace App\Data\Admin\Menu;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class MenuFilterData extends Data
{
    public string|Optional|null $name;

    public string|Optional|null $created_from;

    public string|Optional|null $created_to;

    public function __construct(
        string|Optional|null $name = null,
        string|Optional|null $created_from = null,
        string|Optional|null $created_to = null,
    ) {
        $this->name = $name ?? new Optional();
        $this->created_from = $created_from ?? new Optional();
        $this->created_to = $created_to ?? new Optional();
    }

    public static function rules(): array
    {
        return [
            'name' => [
                new StringType(),
                new Max(100),
                new Nullable(),
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
