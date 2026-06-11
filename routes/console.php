<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

Artisan::command('ucof:hello', function (): void {
    $this->comment('UCOF foundation is ready.');
})->purpose('Print a foundation status message');
