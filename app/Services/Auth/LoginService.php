<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public function attempt(Request $request, string $email, string $password, bool $remember = false): bool
    {
        if (! Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            return false;
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();
        $user->forceFill([
            'last_signin_at' => now(),
            'failed_signin_attempts' => 0,
        ])->save();

        return true;
    }
}
