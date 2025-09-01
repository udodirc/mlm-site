<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $name
 * @property int $key
 * @property string $value
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 */
class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = ['id', 'name', 'key', 'value'];
}
