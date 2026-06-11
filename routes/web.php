<?php

declare(strict_types=1);

use App\Http\Controllers\HealthController;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class);
Route::get('/', HomePage::class);
