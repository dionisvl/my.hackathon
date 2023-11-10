<?php

namespace App\Http\Controllers;

use App\Models\MoonshineUser;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class UserController extends Controller
{
    public function progress(int $userId): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = MoonshineUser::query()->findOrFail($userId);
        $course = $user->courses->first();
        if (empty($course)) {
            echo 'Ошибка: пользователю не назначен курс обучения. Назначьте курс в разделе "Учебные курсы"';
            die();
        }

        return view('moonshine.user.progress', compact('course', 'user'));
    }
}
