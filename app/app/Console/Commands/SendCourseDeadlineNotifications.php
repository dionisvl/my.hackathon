<?php

namespace App\Console\Commands;

use App\Mail\CourseDeadlineEmail;
use App\Models\Course;
use App\Models\EmailLogs;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Отправляем уведомления всем пользователям
 * у которых по курсу онбординга пришел дедлайн Сегодня
 */
class SendCourseDeadlineNotifications extends Command
{
    protected $signature = 'send:deadline-course-notifications';
    protected $description = 'Send notifications for courses with approaching deadlines';

    public function handle(): void
    {
        $courses = Course::where('deadline_at', '>=', Carbon::now())
            ->where('deadline_at', '<=', Carbon::now()->addDay())
            ->with('users')
            ->get();

        foreach ($courses as $course) {
            foreach ($course->users as $user) {
                $email = new CourseDeadlineEmail($course);
                Mail::to($user->email)->send($email);
                Log::info('Course Deadline notification sent to ' . $user->email);
                EmailLogs::logEmail($user->id, $user->email, $email->subject);
            }
        }
    }
}
