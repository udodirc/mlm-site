<?php

namespace App\Models;

use App\QueryBuilders\MenuQueryBuilder;
use App\Traits\Ordered;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read int $id
 * @property int $parent_id
 * @property string $name
 * @property string $url
 * @property bool $status
 * @property-read int $order
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 */
class Menu extends Model
{
    /** @use HasFactory<\Database\Factories\MenuFactory> */
    use HasFactory, Ordered;

    protected $table = 'menu';

    public static bool $cache = true;
    public static ?int $ttl = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'url',
        'status',
        'order'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function content(): HasOne
    {
        return $this->hasOne(Content::class, 'menu_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function newEloquentBuilder($query): MenuQueryBuilder
    {
        return new MenuQueryBuilder($query);
    }
}
