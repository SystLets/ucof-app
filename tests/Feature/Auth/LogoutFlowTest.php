<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class LogoutFlowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        User::query()->delete();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = UserFactory::create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/login');

        $this->assertGuest();
    }
}
