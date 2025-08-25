<?php

namespace App\Models;

use App\QueryBuilders\MenuQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $parent_id
 * @property string $name
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 */
class Menu extends Model
{
    /** @use HasFactory<\Database\Factories\MenuFactory> */
    use HasFactory;

    protected $table = 'menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'parent_id',
        'name'
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function newEloquentBuilder($query): MenuQueryBuilder
    {
        return new MenuQueryBuilder($query);
    }
}
