<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\PasswordResetRequest;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

class PasswordResetTokenService
{
    public function issue(User $user, ?string $requestIp = null): string
    {
        $token = Str::random(64);
        $now = CarbonImmutable::now();

        PasswordResetRequest::query()->create([
            'user_account_id' => (string) $user->getKey(),
            'token_hash' => hash('sha256', $token),
            'issued_at' => $now,
            'expires_at' => $now->addMinutes(30),
            'request_ip' => $requestIp,
        ]);

        return $token;
    }

    public function consume(string $token): ?PasswordResetRequest
    {
        $request = PasswordResetRequest::query()
            ->where('token_hash', hash('sha256', $token))
            ->first();

        if (! $request instanceof PasswordResetRequest) {
            return null;
        }

        if ($request->used_at !== null || now()->greaterThan($request->expires_at)) {
            return null;
        }

        $request->forceFill(['used_at' => now()])->save();

        return $request;
    }
}
