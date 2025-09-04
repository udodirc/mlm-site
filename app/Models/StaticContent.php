<?php

namespace App\Models;

use App\QueryBuilders\StaticContentQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $name
 * @property string $content
 * @property bool $status
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 */
class StaticContent extends Model
{
    use HasFactory;

    protected $table = 'static_content';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'content',
        'status'
    ];

    public function newEloquentBuilder($query): StaticContentQueryBuilder
    {
        return new StaticContentQueryBuilder($query);
    }
}
