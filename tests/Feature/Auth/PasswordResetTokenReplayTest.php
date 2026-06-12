<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Services\Auth\PasswordResetService;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class PasswordResetTokenReplayTest extends TestCase
{
    public function test_reset_token_cannot_be_reused(): void
    {
        $user = UserFactory::create(['email' => 'replay@example.test']);

        /** @var PasswordResetService $service */
        $service = app(PasswordResetService::class);
        $token = $service->request((string) $user->email, '127.0.0.1');

        $this->post('/password/reset/complete', [
            'token' => $token,
            'password' => 'NewValidPassword123',
            'password_confirmation' => 'NewValidPassword123',
        ])->assertRedirect('/login');

        $this->from('/password/reset/'.$token)
            ->post('/password/reset/complete', [
                'token' => $token,
                'password' => 'AnotherValidPassword123',
                'password_confirmation' => 'AnotherValidPassword123',
            ])
            ->assertRedirect('/password/reset/'.$token)
            ->assertSessionHasErrors('token');
    }
}
