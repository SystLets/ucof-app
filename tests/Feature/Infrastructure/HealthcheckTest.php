<?php

declare(strict_types=1);

namespace Tests\Feature\Infrastructure;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HealthcheckTest extends TestCase
{
    public function test_health_endpoint_reports_healthy_when_mongodb_pings(): void
    {
        DB::shouldReceive('connection')
            ->with('mongodb')
            ->andReturn(new class {
                public function getMongoDB(): object
                {
                    return new class {
                        public function command(array $command): void
                        {
                        }
                    };
                }
            });

        $this->getJson('/health')
            ->assertOk()
            ->assertJson([
                'status' => 'healthy',
                'checks' => [
                    'application' => 'healthy',
                    'mongodb' => 'healthy',
                ],
            ]);
    }
}
