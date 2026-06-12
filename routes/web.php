<?php

declare(strict_types=1);

use App\Http\Controllers\HealthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\ProfilePasswordController;
use App\Http\Controllers\DashboardController;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\PasswordResetCompletePage;
use App\Livewire\Auth\PasswordResetRequestPage;
use App\Livewire\HomePage;
use App\Livewire\Profile\ProfilePage;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class);
Route::get('/', HomePage::class);

Route::middleware('guest')->group(function (): void {
	Route::get('/login', LoginPage::class)->name('login');
	Route::post('/login', [LoginController::class, 'store'])->name('login.store');

	Route::get('/password/reset', PasswordResetRequestPage::class)->name('password.request');
	Route::post('/password/reset/request', [PasswordResetController::class, 'request'])
		->name('password.email');

	Route::get('/password/reset/{token}', PasswordResetCompletePage::class)->name('password.reset');
	Route::post('/password/reset/complete', [PasswordResetController::class, 'complete'])
		->name('password.update');
});

Route::middleware('auth')->group(function (): void {
	Route::get('/dashboard', DashboardController::class)->name('dashboard');
	Route::post('/logout', LogoutController::class)->name('logout');

	Route::get('/profile', ProfilePage::class)->name('profile.show');
	Route::post('/profile/password', ProfilePasswordController::class)->name('profile.password.update');
});
