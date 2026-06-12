<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class UserFactory
{
    /**
     * @param  array{name?: string, email?: string, password?: string, status?: string}  $overrides
     */
    public static function create(array $overrides = []): User
    {
        $plainPassword = $overrides['password'] ?? 'ValidPassword123';

        $user = new User();
        $user->fill([
            'name' => $overrides['name'] ?? 'Test User',
            'email' => $overrides['email'] ?? 'user+'.uniqid().'@example.test',
            'password' => Hash::make($plainPassword),
            'status' => $overrides['status'] ?? 'active',
            'failed_signin_attempts' => 0,
        ]);
        $user->save();

        // Keep plain password available for tests where needed.
        $user->setAttribute('plain_password', $plainPassword);

        return $user;
    }
}
