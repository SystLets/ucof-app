<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Tests\Factories\UserFactory;
use Tests\TestCase;

class LandingAccessTest extends TestCase
{
    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = UserFactory::create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('You are signed in');
    }
}
