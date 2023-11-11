<?php

namespace App\Mail;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CourseDeadlineEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Course $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function build(): CourseDeadlineEmail
    {
        return $this->view('moonshine.emails.courseDeadline')
            ->subject('Уведомление о сроке выполнения курса онбординга')
            ->with([
                'courseTitle' => $this->course->title,
                'deadline' => $this->course->deadline_at,
            ]);
    }
}
