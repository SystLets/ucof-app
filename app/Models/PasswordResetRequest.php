<?php

declare(strict_types=1);

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PasswordResetRequest extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'password_reset_requests';

    protected $fillable = [
        'user_account_id',
        'token_hash',
        'issued_at',
        'expires_at',
        'used_at',
        'request_ip',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }
}
