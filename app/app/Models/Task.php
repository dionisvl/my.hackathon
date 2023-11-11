<?php

namespace App\Models;

use App\Mail\TaskAssignedEmail;
use App\Mail\TaskCompletedEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * @property int $id
 * @property int $creator_id
 * @property int|null $assignee_id
 * @property string $status
 * @property string $title
 * @property string|null $description
 * @property string|null $result
 * @property Carbon|null $deadline
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User $creator
 * @property User|null $assignee
 */
class Task extends Model
{
    use HasFactory;

    public const STATUS_NOT_STARTED = 'not_started';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'creator_id',
        'assignee_id',
        'status',
        'title',
        'description',
        'result',
        'deadline',
    ];

    protected array $dates = [
        'deadline',
    ];

    /**
     * Когда меняются статусы задач тогда мы отправляем письма заинтересованным лицам.
     */
    protected static function booted(): void
    {
        static::created(static function ($task) {
            if ($task->assignee_id && $assignee = $task->assignee) {
                Mail::to($assignee->email)->send(new TaskAssignedEmail($task));
            }
        });

        static::updated(static function ($task) {
            // Если задача была назначена, отправляем уведомление
            if ($task->wasChanged('assignee_id') && $task->assignee_id && $assignee = $task->assignee) {
                Mail::to($assignee->email)->send(new TaskAssignedEmail($task));
            }

            // Если задача была выполнена, отправляем уведомление менеджеру
            if ($task->wasChanged('status') && $task->status === Task::STATUS_COMPLETED) {
                $creator = $task->creator;
                $assignee = $task->assignee;
                if ($creator && $assignee) {
                    /** @var MoonshineUser $creator */
                    $managerEmail = $creator->email;
                    Mail::to($managerEmail)->send(new TaskCompletedEmail($task, $assignee));
                } else {
                    Log::warning('creator or assignee of task not found');
                }

            }
        });
    }

    /**
     * Получение пользователя, создавшего задачу
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(MoonshineUser::class, 'creator_id');
    }

    /**
     * Получение пользователя, на которого назначена задача.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(MoonshineUser::class, 'assignee_id');
    }

    /**
     * Получение локализованного названия статуса.
     */
    public function getLocalizedStatusAttribute(string $status = null): string
    {
        $statusNames = [
            self::STATUS_NOT_STARTED => 'не взята в работу',
            self::STATUS_IN_PROGRESS => 'в работе',
            self::STATUS_COMPLETED => 'выполнена',
        ];

        if ($status) {
            return $statusNames[$status] ?? 'неизвестный статус';
        }
        return $statusNames[$this->status] ?? 'неизвестный статус';
    }
}
