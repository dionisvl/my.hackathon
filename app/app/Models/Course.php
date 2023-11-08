<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Учебный курс
 */
class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_at',
        'deadline_at',
        'end_at',
    ];

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'course_materials');
    }

    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(Test::class, 'course_tests');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(MoonshineUser::class, 'course_tests');
    }
}
