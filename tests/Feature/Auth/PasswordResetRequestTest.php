<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Tests\Factories\UserFactory;
use Tests\TestCase;

class PasswordResetRequestTest extends TestCase
{
    public function test_reset_request_returns_generic_status_for_existing_email(): void
    {
        UserFactory::create(['email' => 'existing@example.test']);

        $this->from('/password/reset')
            ->post('/password/reset/request', ['email' => 'existing@example.test'])
            ->assertRedirect('/password/reset')
            ->assertSessionHas('status');
    }

    public function test_reset_request_returns_generic_status_for_unknown_email(): void
    {
        $this->from('/password/reset')
            ->post('/password/reset/request', ['email' => 'unknown@example.test'])
            ->assertRedirect('/password/reset')
            ->assertSessionHas('status');
    }
}
