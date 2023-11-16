<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\MessageFormRequest;
use App\Models\Message;
use App\Models\MoonshineUser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use JsonException;

class ChatController extends Controller
{
    public function index(): Factory|View|Application
    {
        //auth()->loginUsingId(random_int(1,4));
//        if (auth()->user() === null){
//            throw new RuntimeException('Пользователь не авторизован');
//        }
        return view('chat.chat');
    }

    /**
     * @throws JsonException
     */
    public function send(MessageFormRequest $request)
    {
        $user = $request->user();
        $messageText = $request->validated()['message'];
        $message = $user->messages()->create(['message' => $messageText]);
        broadcast(new MessageSent($request->user(), $message));


        $trigger = "Help";
        $triggerPos = strpos($messageText, $trigger);

        if ($triggerPos !== false) {
            /** @var MoonshineUser $userAi */
            $userAi = MoonshineUser::query()->where('email', 'god@god.ru')->firstOrFail();

            // Получаем ответ от AI
            $aiResponse = getAi($message);

            // Создаем новое сообщение с ответом AI

            /** @var Message $userAi */
            $aiMessage = $userAi->messages()->create(['message' => $aiResponse]);

            // Broadcast AI message
            broadcast(new MessageSent($userAi, $aiMessage));
        }

        return $message;
    }

    public function messages(): Collection|array
    {
        $messages = Message::query()
            ->with('user')
            ->get();
        return $messages;
    }
}
