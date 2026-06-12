<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class LoginFlowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        User::query()->delete();
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = UserFactory::create(['email' => 'valid@example.test', 'password' => 'ValidPassword123']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'ValidPassword123',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    public function test_invalid_credentials_return_safe_error_message(): void
    {
        UserFactory::create(['email' => 'invalid@example.test', 'password' => 'ValidPassword123']);

        $response = $this->from('/login')->post('/login', [
            'email' => 'invalid@example.test',
            'password' => 'WrongPassword123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
