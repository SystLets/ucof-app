<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Providers\RouteServiceProvider;
use App\Services\Auth\LoginService;
use App\Support\AuthMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginController
{
    public function store(Request $request, LoginService $login): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $success = $login->attempt(
            $request,
            $validated['email'],
            $validated['password'],
            (bool) ($validated['remember'] ?? false),
        );

        if (! $success) {
            return back()
                ->withErrors(['email' => AuthMessage::invalidCredentials()])
                ->onlyInput('email');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
