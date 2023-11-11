<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\MoonshineUser;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskCompletedEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The task instance.
     */
    public Task $task;
    private MoonshineUser $assignee;

    /**
     * Create a new message instance.
     */
    public function __construct(Task $task, MoonshineUser $assignee)
    {
        $this->task = $task;
        $this->assignee = $assignee;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->view('moonshine.emails.taskCompleted')
            ->with([
                'taskTitle' => $this->task->title,
                'taskDescription' => $this->task->description ?? '',
                'taskDeadline' => $this->task->deadline,
                'taskResult' => $this->task->result ?? '',
                'taskAssigneeName' => $this->assignee->name,
            ]);
    }
}
