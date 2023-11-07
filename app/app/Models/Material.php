<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Учебные материалы
 */
class Material extends Model
{
    protected $fillable = ['title', 'content'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_materials')
            ->withPivot('viewed_at')
            ->withTimestamps();
    }
}
