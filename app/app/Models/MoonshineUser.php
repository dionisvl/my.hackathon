<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Notifications\Notifiable;
use MoonShine\Permissions\Traits\HasMoonShinePermissions;
use MoonShine\Traits\Models\HasMoonShineSocialite;

/**
 * @property int $id
 * @property string $email
 * @property string $name
 * @property bool $is_course_completed
 */
class MoonshineUser extends \MoonShine\Models\MoonshineUser
{
    use HasMoonShineSocialite;
    use HasFactory;
    use Notifiable;
    use HasMoonShinePermissions;

    protected $appends = ['is_course_completed'];

    /**
     * Аксессор к виртуальному полю is_course_completed
     * @return bool
     */
    public function getIsCourseCompletedAttribute(): bool
    {
        return $this->isCourseCompleted();
    }

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

    // Онбординг план именно для этого пользователя вычисляемый через его должность
    public function plan(): HasOneThrough
    {
        return $this->hasOneThrough(
            OnboardingPlan::class,
            MoonshineUserRole::class,
            'id',// Внешний ключ MoonshineUserRole на MoonshineUser
            'role_id', // Внешний ключ OnboardingPlan на MoonshineUserRole
            'moonshine_user_role_id', // Локальный ключ на MoonshineUser
            'id' // Локальный ключ на MoonshineUserRole
        );
    }

    /** Возвращает ответ: прошел ли сотрудник курс онбординга? */
    public function isCourseCompleted(): bool
    {
        $course = $this->courses()->first();
        $user = $this;
        if ($course === null || $course->materials === null) {
            return false;
        }
        $allMaterialsViewed = $course->materials->every(function ($material) use ($user) {
            return $user->materials->where('material_id', $material->id)->whereNotNull('viewed_at');
        });
        $allTestsPassed = $course->tests->every(function ($test) use ($user) {
            $userTest = $user->tests->where('test_id', $test->id)->first();
            return $userTest && $userTest->result >= UserTest::PASS_THRESHOLD;
        });

        return $allMaterialsViewed && $allTestsPassed;
    }

    public static function getCountStat(): array
    {
        $users = self::all();
        $countFinished = 0;
        $countProcess = 0;

        foreach ($users as $user) {
            $course = $user->courses()->first();
            if ($course === null || $course->materials === null) {
                $countProcess++;
                continue;
            }
            $allMaterialsViewed = $course->materials->every(function ($material) use ($user) {
                return $user->materials->where('material_id', $material->id)->whereNotNull('viewed_at');
            });
            $allTestsPassed = $course->tests->every(function ($test) use ($user) {
                $userTest = $user->tests->where('test_id', $test->id)->first();
                return $userTest && $userTest->result >= UserTest::PASS_THRESHOLD;
            });

            if ($allMaterialsViewed && $allTestsPassed) {
                $countFinished++;
            } else {
                $countProcess++;
            }
        }

        return [$countProcess, $countFinished];

    }
}
