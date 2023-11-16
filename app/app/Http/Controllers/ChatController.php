<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\MessageFormRequest;
use App\Models\Message;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

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

    public function send(MessageFormRequest $request)
    {
        $user = $request->user();
        $message = $user
            ->messages()
            ->create($request->validated());

        broadcast(new MessageSent($request->user(), $message));

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
