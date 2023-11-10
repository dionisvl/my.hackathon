<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property DateTime $viewed_at
 * @property int $user_id
 * @property int $material_id
 */
class UserMaterial extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'material_id', 'viewed_at'];
}
