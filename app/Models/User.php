<?php

declare(strict_types=1);

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $connection = 'mongodb';

    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'failed_signin_attempts',
        'last_signin_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'last_signin_at' => 'datetime',
            'failed_signin_attempts' => 'integer',
        ];
    }
}
