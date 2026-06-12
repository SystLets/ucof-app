<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Services\Auth\SessionInvalidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogoutController
{
    public function __invoke(Request $request, SessionInvalidationService $sessions): RedirectResponse
    {
        $sessions->logoutCurrent($request);

        return redirect()->route('login');
    }
}
