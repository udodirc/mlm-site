<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $key
 * @property string $value
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 */
class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = ['key', 'value'];
}
