<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthController
{
    public function __invoke(): JsonResponse
    {
        $databaseHealthy = true;

        try {
            DB::connection('mongodb')->getMongoDB()->command(['ping' => 1]);
        } catch (Throwable) {
            $databaseHealthy = false;
        }

        $statusCode = $databaseHealthy ? 200 : 503;

        return response()->json([
            'status' => $databaseHealthy ? 'healthy' : 'degraded',
            'checks' => [
                'application' => 'healthy',
                'mongodb' => $databaseHealthy ? 'healthy' : 'unhealthy',
            ],
        ], $statusCode);
    }
}
