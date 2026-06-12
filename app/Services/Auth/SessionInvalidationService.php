<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionInvalidationService
{
    public function logoutCurrent(Request $request): void
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function invalidateOtherSessions(User $user, string $currentPassword): void
    {
        Auth::login($user);

        try {
            Auth::logoutOtherDevices($currentPassword);
        } catch (\Throwable) {
            // Keep current-session continuity even when other-session invalidation is unavailable.
        }
    }
}
