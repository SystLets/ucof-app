<?php

declare(strict_types=1);

namespace Tests\Feature\Infrastructure;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Throwable;

class ReadinessAndFailureTest extends TestCase
{
    public function test_health_endpoint_reports_degraded_when_mongodb_is_unavailable(): void
    {
        DB::shouldReceive('connection')
            ->with('mongodb')
            ->andThrow(new class('mongodb unavailable') extends \RuntimeException implements Throwable {
            });

        $this->getJson('/health')
            ->assertStatus(503)
            ->assertJson([
                'status' => 'degraded',
                'checks' => [
                    'application' => 'healthy',
                    'mongodb' => 'unhealthy',
                ],
            ]);
    }
}
