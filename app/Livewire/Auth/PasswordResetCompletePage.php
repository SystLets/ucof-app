<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Livewire\Component;

class PasswordResetCompletePage extends Component
{
    public string $token;

    public function mount(string $token): void
    {
        $this->token = $token;
    }

    public function render()
    {
        return view('livewire.auth.password-reset-complete-page');
    }
}
