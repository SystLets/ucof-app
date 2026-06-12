<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class LoginLogoutRoutingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        User::query()->delete();
    }

    public function test_login_then_logout_redirects_correctly(): void
    {
        $user = UserFactory::create(['email' => 'route@example.test', 'password' => 'ValidPassword123']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'ValidPassword123',
        ])->assertRedirect('/dashboard');

        $this->post('/logout')->assertRedirect('/login');
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
