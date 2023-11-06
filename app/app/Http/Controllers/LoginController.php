<?php

declare(strict_types=1);


namespace App\Http\Controllers;

use App\Models\MoonshineUserRole;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use MoonShine\Models\MoonshineUser;

class LoginController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    public function handleProviderCallback()
    {
        // Здесь логика по созданию/поиску пользователя в базе данных и аутентификации пользователя в приложении.
        try {
            // Пытаемся получить пользователя от ВКонтакте
            $vkUser = Socialite::driver('vkontakte')->user();

            // Найти существующего пользователя или создать нового
            $user = MoonshineUser::where('vk_id', $vkUser->id)->first();

            if (!$user) {
                // Создаем нового пользователя, если не найден
                $user = MoonshineUser::create([
                    'name' => $vkUser->name,
                    'email' => $vkUser->email ?? $vkUser->name . '@unknown.email', // ВКонтакте может не предоставить email
                    'vk_id' => $vkUser->id,
                    // 'avatar' => $vkUser->avatar
                    'password' => bcrypt(Str::random(25)), // Генерируем случайный пароль
                    'moonshine_user_role_id' => MoonshineUserRole::WORKER_ROLE_ID, // Все кто входит из ВК будут обычными сотрудниками
                ]);
            }

            // Аутентифицируем пользователя
            Auth::login($user, true);

            // Перенаправляем пользователя куда нужно
            return redirect()->to('/home');

        } catch (Exception $e) {
            // В случае ошибки, перенаправляем пользователя обратно на страницу логина
            return redirect()->route('login', ['error' => $e->getMessage()]);
        }
    }
}
