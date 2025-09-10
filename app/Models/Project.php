<?php

namespace App\Models;

use App\QueryBuilders\ProjectQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $name
 * @property string $content
 * @property bool $status
 * @property string $title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $og_title
 * @property string $og_description
 * @property string $og_image
 * @property string $og_url
 * @property string $og_type
 * @property string $canonical_url
 * @property string $robots
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 */
class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    public static bool $cache = true;
    public static ?int $ttl = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'content',
        'status',
        'title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'og_url',
        'og_type',
        'canonical_url',
        'robots'
    ];

    public function newEloquentBuilder($query): ProjectQueryBuilder
    {
        return new ProjectQueryBuilder($query);
    }
}
