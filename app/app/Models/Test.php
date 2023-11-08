<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description',];

    public function questions(): HasMany
    {
        return $this->hasMany(TestQuestions::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(MoonshineUser::class, 'user_tests')
            ->withPivot('completed_at')
            ->withTimestamps();
    }
}
