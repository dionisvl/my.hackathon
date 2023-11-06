<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use MoonShine\Forms\LoginForm;
use MoonShine\Http\Controllers\MoonShineController;

class AuthenticateController extends MoonShineController
{
    public function login(): View|RedirectResponse
    {
        if ($this->auth()->check()) {
            return to_route('moonshine.index');
        }

        $form = config('moonshine.forms.login', LoginForm::class);

        return view('moonshine::auth.login', [
            'form' => new $form(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function authenticate(LoginFormRequest $request): RedirectResponse
    {
        $request->authenticate();
        return redirect()->intended(route('moonshine.index'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('moonshine.login');
    }
}
