<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MoonshineUserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MoonShine\Models\MoonshineUser;

class UserController extends Controller
{
    public function store(Request $request): Response
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:moonshine_users,email',
            'password' => 'required|min:6',
        ]);

        $user = MoonshineUser::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'moonshine_user_role_id' => MoonshineUserRole::WORKER_ROLE_ID,
        ]);

        return response($user, Response::HTTP_CREATED);
    }
}
