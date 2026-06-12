<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordPolicyRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || strlen($value) < 12) {
            $fail('Password must be at least 12 characters long.');

            return;
        }

        if (! preg_match('/[A-Z]/', $value)) {
            $fail('Password must include at least one uppercase letter.');

            return;
        }

        if (! preg_match('/[a-z]/', $value)) {
            $fail('Password must include at least one lowercase letter.');

            return;
        }

        if (! preg_match('/[0-9]/', $value)) {
            $fail('Password must include at least one number.');

            return;
        }
    }
}
