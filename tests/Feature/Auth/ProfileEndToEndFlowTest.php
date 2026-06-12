<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Auth;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class ProfileEndToEndFlowTest extends TestCase
{
    public function test_full_lifecycle_login_profile_change_and_logout(): void
    {
        $user = UserFactory::create([
            'email' => 'e2e@example.test',
            'password' => 'ValidPassword123',
            'name' => 'End To End',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'ValidPassword123',
        ])->assertRedirect('/dashboard');

        $this->get('/profile')
            ->assertOk()
            ->assertSee('End To End')
            ->assertSee('e2e@example.test');

        $this->post('/profile/password', [
            'current_password' => 'ValidPassword123',
            'password' => 'NewValidPassword123',
            'password_confirmation' => 'NewValidPassword123',
        ])->assertRedirect('/profile');

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'NewValidPassword123',
        ])->assertRedirect('/dashboard');

        Auth::logout();
    }
}
