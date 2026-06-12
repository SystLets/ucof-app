<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use App\Rules\PasswordPolicyRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfilePasswordChangeService
{
    public function __construct(
        private readonly SessionInvalidationService $sessions,
    ) {
    }

    public function change(User $user, string $currentPassword, string $newPassword, string $newPasswordConfirmation): bool
    {
        Validator::make(
            [
                'current_password' => $currentPassword,
                'password' => $newPassword,
                'password_confirmation' => $newPasswordConfirmation,
            ],
            [
                'current_password' => ['required'],
                'password' => ['required', 'confirmed', new PasswordPolicyRule()],
            ],
            [],
            [
                'password' => 'password',
            ],
        )->validate();

        if (! Hash::check($currentPassword, (string) $user->password)) {
            return false;
        }

        $user->forceFill(['password' => Hash::make($newPassword)])->save();
        $this->sessions->invalidateOtherSessions($user, $newPassword);

        return true;
    }
}
