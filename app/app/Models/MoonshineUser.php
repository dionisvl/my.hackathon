<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use MoonShine\Traits\Models\HasMoonShineSocialite;

class MoonshineUser extends \MoonShine\Models\MoonshineUser
{
    use HasMoonShineSocialite;
    use HasFactory;
    use Notifiable;

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

    // Отношение многие ко многим для получения курсов пользователя
    public function coursesByUserId()
    {
        return $this->belongsToMany(Course::class, 'course_users', 'user_id', 'course_id');
    }

    public function courses()
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
