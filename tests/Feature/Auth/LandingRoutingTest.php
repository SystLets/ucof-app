<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Tests\Factories\UserFactory;
use Tests\TestCase;

class LandingRoutingTest extends TestCase
{
    public function test_successful_login_redirects_to_dashboard_route(): void
    {
        $user = UserFactory::create(['email' => 'landing@example.test', 'password' => 'ValidPassword123']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'ValidPassword123',
        ])->assertRedirect('/dashboard');
    }
}
