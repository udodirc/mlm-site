<?php

namespace App\Models;

use App\QueryBuilders\ContentQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $menu_id
 * @property string $content
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 */
class Content extends Model
{
    use HasFactory;

    protected $table = 'content';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'menu_id',
        'content',
        'content'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function newEloquentBuilder($query): ContentQueryBuilder
    {
        return new ContentQueryBuilder($query);
    }
}
