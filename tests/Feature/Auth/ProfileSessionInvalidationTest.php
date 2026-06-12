<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Tests\Factories\UserFactory;
use Tests\TestCase;

class ProfileSessionInvalidationTest extends TestCase
{
    public function test_password_change_keeps_current_session_active(): void
    {
        $user = UserFactory::create(['password' => 'ValidPassword123']);

        $this->actingAs($user)
            ->post('/profile/password', [
                'current_password' => 'ValidPassword123',
                'password' => 'NewValidPassword123',
                'password_confirmation' => 'NewValidPassword123',
            ])
            ->assertRedirect('/profile');

        $this->get('/profile')->assertOk();
    }
}
