<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\PasswordResetRequest;
use App\Models\User;
use App\Rules\PasswordPolicyRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordResetService
{
    public function __construct(
        private readonly PasswordResetTokenService $tokens,
    ) {
    }

    public function request(string $email, ?string $requestIp = null): ?string
    {
        $user = User::query()->where('email', $email)->first();

        if (! $user instanceof User) {
            return null;
        }

        return $this->tokens->issue($user, $requestIp);
    }

    public function complete(string $token, string $password, string $passwordConfirmation): bool
    {
        Validator::make(
            [
                'password' => $password,
                'password_confirmation' => $passwordConfirmation,
            ],
            [
                'password' => ['required', 'confirmed', new PasswordPolicyRule()],
            ],
            [],
            [
                'password' => 'password',
            ],
        )->validate();

        $resetRequest = $this->tokens->consume($token);

        if (! $resetRequest instanceof PasswordResetRequest) {
            return false;
        }

        $user = User::query()->find($resetRequest->user_account_id);

        if (! $user instanceof User) {
            return false;
        }

        $user->forceFill(['password' => Hash::make($password)])->save();

        return true;
    }
}
