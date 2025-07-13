<?php

namespace App\Models;

use Carbon\Carbon;
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
    protected $table = 'content';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'menu_id',
        'content',
        'content'
    ];
}
