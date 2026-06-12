<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfilePage extends Component
{
    public function render()
    {
        return view('livewire.profile.profile-page', [
            'user' => Auth::user(),
        ]);
    }
}
