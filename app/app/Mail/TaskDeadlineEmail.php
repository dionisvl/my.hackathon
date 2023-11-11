<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskDeadlineEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function build(): TaskDeadlineEmail
    {
        return $this->view('moonshine.emails.taskDeadline')
            ->subject('Уведомление о сроке выполнения задачи')
            ->with([
                'taskTitle' => $this->task->title,
                'deadline' => $this->task->deadline,
            ]);
    }
}
