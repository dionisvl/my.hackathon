<?php

namespace App\Broadcasting;

use App\Models\MoonshineUser;

class ChatChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param MoonshineUser $user
     * @return array|bool
     */
    public function join(MoonshineUser $user): bool|array
    {
        // Проверяем, что аутентифицированный пользователь идентичен тому, кто присоединяется к каналу
        return $user->id === auth()->id();
        return true;
    }
}
