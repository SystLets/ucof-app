<?php

declare(strict_types=1);

namespace Tests\Unit\Auth;

use App\Rules\PasswordPolicyRule;
use App\Services\Auth\PasswordResetTokenService;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class AuthFoundationRulesTest extends TestCase
{
    public function test_password_policy_rejects_weak_passwords(): void
    {
        $validator = Validator::make(
            ['password' => 'short'],
            ['password' => ['required', new PasswordPolicyRule()]],
        );

        $this->assertTrue($validator->fails());
    }

    public function test_password_policy_accepts_strong_passwords(): void
    {
        $validator = Validator::make(
            ['password' => 'ValidPassword123'],
            ['password' => ['required', new PasswordPolicyRule()]],
        );

        $this->assertFalse($validator->fails());
    }

    public function test_unknown_reset_token_cannot_be_consumed(): void
    {
        $service = app(PasswordResetTokenService::class);

        $this->assertNull($service->consume('unknown-token'));
    }
}
