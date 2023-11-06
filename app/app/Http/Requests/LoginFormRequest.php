<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use MoonShine\MoonShineAuth;

class LoginFormRequest extends \MoonShine\Http\Requests\LoginFormRequest
{
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = [
            config('moonshine.auth.fields.username', 'email') => $this->get(
                'username'
            ),
            config('moonshine.auth.fields.password', 'password') => $this->get(
                'password'
            ),
        ];

        if (!MoonShineAuth::guard()->attempt(
            $credentials,
            $this->boolean('remember')
        )) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => __('moonshine::auth.failed'),
            ]);
        }

        session()->regenerate();

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
}
