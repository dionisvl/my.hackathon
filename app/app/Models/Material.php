<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['title', 'content'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_materials')
            ->withPivot('viewed_at')
            ->withTimestamps();
    }
}
