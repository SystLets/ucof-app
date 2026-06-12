<?php

declare(strict_types=1);

namespace App\Support;

final class AuthMessage
{
    public static function invalidCredentials(): string
    {
        return 'We could not sign you in with those credentials.';
    }

    public static function resetRequestAccepted(): string
    {
        return 'If an account exists for that email, reset instructions have been generated.';
    }
}
