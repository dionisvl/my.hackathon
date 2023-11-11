<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssignedEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The task instance.
     */
    public Task $task;

    /**
     * Create a new message instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        $taskId = $this->task->id;
        $taskUrl = url("/admin/resource/task-resource/detail-page?resourceItem=$taskId");
        return $this->view('moonshine.emails.taskAssigned')
            ->with([
                'taskTitle' => $this->task->title,
                'taskDescription' => $this->task->description ?? '',
                'taskDeadline' => $this->task->deadline,
                'taskUrl' => $taskUrl,
            ]);
    }
}
