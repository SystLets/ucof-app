<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Services\Auth\ProfilePasswordChangeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfilePasswordController
{
    public function __invoke(Request $request, ProfilePasswordChangeService $profiles): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed'],
        ]);

        /** @var User $user */
        $user = $request->user();

        if (! $profiles->change(
            $user,
            $validated['current_password'],
            $validated['password'],
            $request->string('password_confirmation')->toString(),
        )) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        return back()->with('status', 'Password changed successfully.');
    }
}
