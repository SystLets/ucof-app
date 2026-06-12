<?php

declare(strict_types=1);

namespace Tests\Unit\Provisioning;

use App\Rules\PasswordPolicyRule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ProvisionUserValidationTest extends TestCase
{
    public function test_provision_user_command_is_registered(): void
    {
        $commands = Artisan::all();

        $this->assertArrayHasKey('ucof:provision-user', $commands);
    }

    public function test_password_policy_rule_is_available_in_command_context(): void
    {
        $validator = Validator::make(
            ['password' => 'weak-pass'],
            ['password' => ['required', new PasswordPolicyRule()]],
        );

        $this->assertTrue($validator->fails());
        $this->assertStringContainsString('at least 12 characters', $validator->errors()->first('password'));
    }
}
