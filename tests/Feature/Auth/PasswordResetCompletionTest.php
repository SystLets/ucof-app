<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Services\Auth\PasswordResetService;
use Illuminate\Support\Facades\Auth;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class PasswordResetCompletionTest extends TestCase
{
    public function test_valid_reset_token_updates_password(): void
    {
        $user = UserFactory::create(['email' => 'reset@example.test', 'password' => 'ValidPassword123']);

        /** @var PasswordResetService $service */
        $service = app(PasswordResetService::class);
        $token = $service->request((string) $user->email, '127.0.0.1');

        $this->post('/password/reset/complete', [
            'token' => $token,
            'password' => 'NewValidPassword123',
            'password_confirmation' => 'NewValidPassword123',
        ])->assertRedirect('/login');

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'ValidPassword123',
        ]);
        $this->assertGuest();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'NewValidPassword123',
        ]);
        $this->assertAuthenticated();

        Auth::logout();
    }
}
