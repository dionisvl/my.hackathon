<?php

namespace App\Console\Commands;

use App\Mail\TaskDeadlineEmail;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Отправляем уведомления всем пользователям
 * у которых по задачам пришел дедлайн Сегодня, а задачка не завершена
 */
class SendTaskDeadlineNotifications extends Command
{
    protected $signature = 'send:deadline-task-notifications';
    protected $description = 'Send notifications for tasks with approaching deadlines';

    public function handle(): void
    {
        $tasks = Task::where('deadline', '>=', Carbon::now())
            ->where('deadline', '<=', Carbon::now()->addDay())
            ->where('status', '<>', Task::STATUS_COMPLETED)
            ->with('assignee')
            ->get();

        foreach ($tasks as $task) {
            if ($task->assignee) {
                Mail::to($task->assignee->email)->send(new TaskDeadlineEmail($task));
                Log::info('Deadline notification sent to ' . $task->assignee->email);
            }
        }
    }
}
