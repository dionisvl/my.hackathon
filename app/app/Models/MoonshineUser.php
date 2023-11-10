<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Notifications\Notifiable;
use MoonShine\Permissions\Traits\HasMoonShinePermissions;
use MoonShine\Traits\Models\HasMoonShineSocialite;

class MoonshineUser extends \MoonShine\Models\MoonshineUser
{
    use HasMoonShineSocialite;
    use HasFactory;
    use Notifiable;
    use HasMoonShinePermissions;

    protected $fillable = [
        'email',
        'moonshine_user_role_id',
        'password',
        'name',
        'avatar',
    ];

    protected $with = ['moonshineUserRole'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(MoonshineUserRole::class, 'moonshine_user_role_id');
    }

    // связь пользователя с информацией о датах всех его изученных материалов
    public function materials(): HasMany
    {
        return $this->hasMany(UserMaterial::class, 'user_id');
    }

    // связь пользователя со всеми его изученными материалами
//    public function materials(): BelongsToMany
//    {
//        return $this->belongsToMany(Material::class, 'user_materials', 'user_id', 'material_id')
//            ->withPivot('viewed_at')
//            ->wherePivotNotNull('viewed_at');
//    }

    // связь пользователя с информацией о всех его пройденных тестах
    public function tests(): HasMany
    {
        return $this->hasMany(UserTest::class, 'user_id');
    }

    // Отношение многие ко многим для получения курсов пользователя
    public function coursesByUserId()
    {
        return $this->belongsToMany(Course::class, 'course_users', 'user_id', 'course_id');
    }

    public function courses(): HasManyThrough
    {
        return $this->hasManyThrough(
            Course::class,
            CourseRole::class,
            'role_id', // Внешний ключ в таблице course_roles соответствующий role_id
            'id', // Внешний ключ в таблице courses
            'moonshine_user_role_id', // Локальный ключ в таблице moonshine_users
            'course_id' // Локальный ключ в таблице course_roles
        );
    }
}
