<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Tests\Factories\UserFactory;
use Tests\TestCase;

class ProfileViewTest extends TestCase
{
    public function test_profile_displays_authenticated_user_data(): void
    {
        $user = UserFactory::create([
            'name' => 'Profile Owner',
            'email' => 'profile@example.test',
        ]);

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk()
            ->assertSee('Profile Owner')
            ->assertSee('profile@example.test');
    }
}
