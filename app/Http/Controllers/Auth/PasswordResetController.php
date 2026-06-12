<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Services\Auth\PasswordResetService;
use App\Support\AuthMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PasswordResetController
{
    public function request(Request $request, PasswordResetService $reset): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $reset->request($validated['email'], $request->ip());

        return back()->with('status', AuthMessage::resetRequestAccepted());
    }

    public function complete(Request $request, PasswordResetService $reset): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'password' => ['required', 'confirmed'],
        ]);

        if (! $reset->complete($validated['token'], $validated['password'], $request->string('password_confirmation')->toString())) {
            return back()->withErrors(['token' => 'This reset link is invalid or has expired.']);
        }

        return redirect()->route('login')->with('status', 'Your password has been updated.');
    }
}
