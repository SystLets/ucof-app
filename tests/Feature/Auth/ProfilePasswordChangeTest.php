<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Auth;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class ProfilePasswordChangeTest extends TestCase
{
    public function test_password_change_rejects_invalid_current_password(): void
    {
        $user = UserFactory::create(['password' => 'ValidPassword123']);

        $this->actingAs($user)
            ->from('/profile')
            ->post('/profile/password', [
                'current_password' => 'WrongPassword123',
                'password' => 'NewValidPassword123',
                'password_confirmation' => 'NewValidPassword123',
            ])
            ->assertRedirect('/profile')
            ->assertSessionHasErrors('current_password');
    }

    public function test_password_change_accepts_valid_current_password(): void
    {
        $user = UserFactory::create(['email' => 'change@example.test', 'password' => 'ValidPassword123']);

        $this->actingAs($user)
            ->from('/profile')
            ->post('/profile/password', [
                'current_password' => 'ValidPassword123',
                'password' => 'NewValidPassword123',
                'password_confirmation' => 'NewValidPassword123',
            ])
            ->assertRedirect('/profile')
            ->assertSessionHas('status');

        Auth::logout();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'NewValidPassword123',
        ]);
        $this->assertAuthenticated();
    }
}
